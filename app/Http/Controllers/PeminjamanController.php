<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\User;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Peminjaman::with('user', 'details.buku');

        // Mencari Peminjaman berdasarkan Nama Member atau Judul Buku
        $query->when($request->filled('search'), function ($q) use ($request) {
            $search = $request->search;

            $q->where(function ($sub) use ($search) {
                $sub->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('nama', 'like', '%' . $search . '%'); // Sesuaikan nama kolom di tabel user lu
                })->orWhereHas('details.buku', function ($bukuQuery) use ($search) {
                    $bukuQuery->where('judul', 'like', '%' . $search . '%');
                });
            });
        });

        // Filter by  Status Peminjaman
        $query->when($request->filled('status'), function ($q) use ($request) {
            $status = $request->status;

            $q->where(function ($sub) use ($status) {
                $sub->where('status', '=', $status);
            });
        });

        // Menampilkan hasil filter
        $peminjamans = $query->paginate(10)->appends($request->all());        
        
        return view('admin.peminjaman.index', compact('peminjamans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $buku = Buku::all();
        return view('admin.peminjaman.create', compact('users','buku'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'idUser'        => 'required|exists:users,idUser',
            'tanggalPinjam' => 'required',
            'lamaPinjam'    => 'required|integer|min:1',
            'idBuku'        => 'required|array',
            'idBuku.*'      => 'exists:bukus,idBuku',
            'keterangan'    => 'nullable|string'
        ],[
            'idUser.required' => 'Peminjam harus di isi',
            'tanggalPinjam.required' => 'Tanggal pinjam harus di isi',
            'lamaPinjam.required' => 'Lama pinjam harus di isi',
            'lamaPinjam.min' => 'Lama pinjam minimal 1 hari',
            'idBuku.required' => 'Buku minimal 1 harus di isi',
            'idUser.exists' => 'Peminjam tidak ditemukan di database',
            'idBuku.exists' => 'Buku tidak ditemukan di database'
        ]);

        // Mengonversi tanggal pinjam ke date time
        $tanggalPinjam = Carbon::parse($request->tanggalPinjam);

        // Menambahkan durasi pinjam dengan tanggal pinjam sehingga menghasilkan tanggal kembali
        $tanggalKembali = $tanggalPinjam->copy()->addDays((int) $request->lamaPinjam);

        // Menyimpan data peminjaman ke database
        $peminjaman = Peminjaman::create([
            'idUser'         => $request->idUser,
            'tanggalPeminjaman'  => $request->tanggalPinjam,
            'tanggalKembali' => $tanggalKembali->format('Y-m-d'),
            'lamaPinjam' => $request->lamaPinjam,
            'catatan'     => $request->keterangan,
            'status'         => 'Aktif',
        ]);

        // Sinkronisasi antara buku dan peminjaman serta pengurangan stok buku
        foreach ($request->idBuku as $idBuku) {
            DetailPeminjaman::create([
                'idPeminjaman' => $peminjaman->idPeminjaman,
                'idBuku'       => $idBuku,
                'status'       => 'dipinjam',
            ]);

            $buku = Buku::find($idBuku);
            if ($buku && $buku->stok > 0) {
                $buku->decrement('stok');
                if ($buku->stok == 0) {
                    $buku->update(['status' => 'tidak tersedia']);
                }
            }
        }

        return redirect()->route('admin.peminjaman.index')->with('success', 'Berhasil melakukan peminjaman');
    }

    /**
     * Display the specified resource.
     */
    public function show(Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($idPeminjaman)
    {
        $peminjaman = Peminjaman::findOrFail($idPeminjaman);

        return view('admin.peminjaman.edit', compact('peminjaman'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $idPeminjaman)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($idPeminjaman)
    {
        // Mencari detail peminjaman yang memiliki id peminjaman ingin di hapus
        $detailPeminjaman = DetailPeminjaman::where('idPeminjaman', $idPeminjaman)->get();

        // Jika status buku belum dikmbalikan, kembalikan stok buku dan hapus tiap detail peminjaman dalam peminjaman
        foreach($detailPeminjaman as $peminjaman){
            if($peminjaman->status != 'dikembalikan'){
                $buku = Buku::findOrFail($peminjaman->idBuku);
                $buku->increment('stok');
            }

            $peminjaman->delete();
        }

        // Menghapus peminjaman
        $peminjaman = Peminjaman::findOrFail($idPeminjaman);

        $peminjaman->delete();

        return redirect()->route('admin.peminjaman.index')->with('success','Berhasil menghapus peminjaman');
    }

    /**
     * Method untuk update detail peminjaman
     */
    public function updateDetail($idPeminjaman, $idDetailPeminjaman)
    {
        $detail = DetailPeminjaman::where('idPeminjaman', $idPeminjaman)->where('idDetailPeminjaman', $idDetailPeminjaman)->firstOrFail();

        // Mengantisipasi jika status sudah dikembalikan dan ingin mengembalikan lagi
        if($detail->status == 'dikembalikan') {
            return redirect()->back()->with('error', 'Buku ini sudah dikembalikan sebelumnya');
        }

        // Mengupdate status buku di detail peminjaman menjadi dikembalikan
        $detail->update(['status' => 'dikembalikan']);

        // Menghitung total buku dalam 1 peminjaman
        $totalBuku = DetailPeminjaman::where('idPeminjaman', $idPeminjaman)->count();

        // Menghitung berapa buku yang sudah di kembalikan dalam 1 peminjaman
        $bukuKembali = DetailPeminjaman::where('idPeminjaman', $idPeminjaman)->where('status', 'dikembalikan')->count();

        // Mengambil data peminjaman
        $peminjaman = Peminjaman::findOrFail($idPeminjaman);

        // Mengambil data buku
        $buku = Buku::findOrFail($detail->idBuku);

        // Logika if else kondisi untuk status peminjaman
        if($bukuKembali == 0){
            $status = 'Aktif';
        } else if($bukuKembali < $totalBuku){
            $status = 'Dikembalikan Sebagian';
        } else {
            $status = 'Telah Dikembalikan';
        }

        // Mengupdate status peminjaman berdasarkan logika if else konfisi
        $peminjaman->update(['status' => $status]);

        // Mengembalikan stok buku
        $buku->increment('stok');

        // Mengembalikan return ke kelola peminjaman dengan pesan bahwa buku berhasil dikembalikan
        return redirect()->route('admin.peminjaman.edit', $idPeminjaman)->with('success','Berhasil mengembalikan buku dengan judul ' . $buku->judul);
    }
}

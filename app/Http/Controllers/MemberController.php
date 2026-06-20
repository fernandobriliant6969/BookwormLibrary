<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Buku;
use App\Models\Genre;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    {
        // Mendapatkan tanggal hari ini
        $now = now()->startOfDay();

        // Deklarasi variabel
        $totalBukuPeminjaman = 0;
        $totalPeminjaman = 0;

        $totalBukuBelumKembali = 0;
        $totalPeminjamanBelumKembali = 0;

        $totalPeminjamanTerlambat = 0;
        $totalBukuTerlambat = 0;

        // Mendapatkan semua data peminjaman yang milik anggota sedang login
        $peminjamans = Peminjaman::where('idUser', Auth::id())->with(['details'])->get();

        // Jika data peminjaman ada dan tidak kosong
        if($peminjamans->isNotEmpty()) {
            foreach ($peminjamans as $peminjaman) {
                // Mengakumulasi total peminjaman yang pernah dilakukan oleh anggota
                $totalPeminjaman++;

                // Deklarasi status isTerlambat ke false
                $isTerlambat = false;

                // Jika status buku belum dikembalikan
                if($peminjaman->status !== "Telah Dikembalikan"){

                    // Mengonversi tanggal kembali menggunakan Carbon
                    $tanggalBatas = Carbon::parse($peminjaman->tanggalKembali)->startOfDay();

                    // Menyimpan status apakah peminjaman terlambat atau tidak
                    $isTerlambat = $tanggalBatas->lessThan($now);

                    // Jika peminjaman terlambat
                    if($isTerlambat){
                        $totalPeminjamanTerlambat++;
                    } else {
                    // Jika peminjaman belum terlambat dan belum dikembalikan
                        $totalPeminjamanBelumKembali++;
                    }
                }

                // Looping setiap detail peminjaman dalam 1 peminjaman
                foreach ($peminjaman->details as $detail) {

                    // Mengakumulasi total buku dari peminjaman
                    $totalBukuPeminjaman++;

                    // Jika status peminjaman belum dikembalikan dan status buku dipinjam
                    if($peminjaman->status !== "Telah Dikembalikan" && $detail->status === 'dipinjam') {                    
                        // Jika status buku dalam peminjaman terlambat dan belum dikembalikan
                        if ($isTerlambat) {
                            $totalBukuTerlambat++;
                        // Jika status buku dalam peminjaman belum terlambat dan belum dikembalikan
                        } else { 
                            $totalBukuBelumKembali++;
                        }
                    }
                }
            }
        }

        // Inisialisasi objek data 
        $peminjamanHarian = collect();
    
        // Memnetukan tanggal 30 hari yang lalu
        $startDate = now()->subDays(29)->startOfDay();
        
        // Menentukan akhir tanggal hari ini
        $endDate = now()->endOfDay();

        // Query peminjaman yang peminjaman dilakukan oleh anggota sedang login
        $dataPeminjaman = Peminjaman::where('idUser', Auth::id())
            ->whereBetween('tanggalPeminjaman', [$startDate, $endDate]) // Peminjaman yang tanggal peminjaman berada di antara 30 hari yang lalu hingga sekarang
            ->join('detail_peminjamans', 'peminjamans.idPeminjaman', '=', 'detail_peminjamans.idPeminjaman') // Menghubungkan tabel peminjaman dan detail peminjaman
            ->selectRaw('DATE(tanggalPeminjaman) as tanggal, COUNT(detail_peminjamans.idDetailPeminjaman) as total') // Menyimpan tanggalPeminjaman sebagai variabel tanggal dan menghitung total buku yang dipinjam per hari
            ->groupBy('tanggal') // Di group berdasakran Tanggal, Jadi contoh hasil: 2026-06-01, 10
            ->pluck('total', 'tanggal'); // Menghasilkan array data, Contoh: ['2026-06-01' => 3, '2026-06-02' => 5]

        // For looping untuk memisahkan data per hari
        for($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labelDate = now()->subDays($i)->format('d M');

            // Mendapatkan total buku berdasarkan hari dalam looping
            $count = $dataPeminjaman->get($date, 0);

            // Push ke objek data yang telah di inisialisasi
            $peminjamanHarian->push([
                'tanggal' => $labelDate,
                'total' => $count
            ]);
        }

        // Filter genre berdasarkan peminjaman yang pernah dilakukan oleh anggota
        $genreData = Genre::whereHas('buku.detailPeminjaman.peminjaman', function ($query) {
            $query->where('idUser', Auth::id());
        // Menghitung total buku per genre
        })->withCount(['bukus as total' => function ($query) {
            // Filter buku yang hanya pernah dipinjam oleh anggota
            $query->whereHas('detailPeminjaman.peminjamans', function ($subQuery) {
                $subQuery->where('idUser', Auth::id());
            });
        }])->get();

        // Memasukkan data hasil filter genre ke array satuan untuk digunakan oleh ChartJS
        $donutLabels = $genreData->pluck('nama')->toArray();
        $donutValues = $genreData->pluck('total')->toArray();

        // Memasukkan data hasil filter peminjaman harian ke array satuan untuk digunakan oleh ChartJS
        $chartLabels = $peminjamanHarian->pluck('tanggal')->toArray();
        $chartData = $peminjamanHarian->pluck('total')->toArray();

        $bukuPopuler = Buku::withCount('detailPeminjamans')->orderBy('detail_peminjamans_count', 'desc')->take(5)->get();

        $bukuRatingTertinggi = Buku::withAvg('review as rating_avg', 'review_bukus.rating')->withCount('review as review_count') ->orderBy('rating_avg', 'desc')->take(5)->get();

        return view('member.dashboard', compact('totalBukuBelumDikembalikan', 'totalBukuTerlambat', 'totalPeminjamanTerlambat', 'isTerlambat', 'totalBukuPeminjaman',  'totalPeminjaman', 'chartLabels', 'chartData','donutLabels', 'donutValues','bukuPopuler', 'bukuRatingTertinggi'));
    }

    public function listbuku(Request $request)
    {
        // Mendapatkan data buku beserta genre dan review
        $query = Buku::with([
            'genre', 
            'review' => function($query) {
                $query->latest();
            }
        ])->withCount('review')->withAvg('review as rating_avg', 'review_bukus.rating');

        // Mencari buku berdasarkan judul atau pengarang
        $query->when($request->filled('search'), function ($q) use ($request) {
            $search = $request->search;
            $q->where(function ($sub) use ($search) {
                $sub->where('judul', 'like', '%' . $search . '%')->orWhere('pengarang', 'like', '%' . $search . '%');
            });
        });

        // Filter by genre dan bisa banyak genre
        $query->when($request->filled('idGenre') && is_array($request->idGenre), function ($q) use ($request) {
            $selectedGenres = array_filter($request->idGenre); 

            if (!empty($selectedGenres)) {
                $q->whereHas('genre', function ($sub) use ($selectedGenres) {
                    $sub->whereIn('genres.idGenre', $selectedGenres);
                });
            }
        });

        // Menampilkan hasil filter
        $bukus = $query->paginate(24)->appends($request->all());
        
        // Mengambil semua genre
        $genres = Genre::all();

        // List buku yang pernah dipinjam user dan kirim ke listbuku untuk filter buku yang hanya boleh di review kalau sudah pernah pinjam buku tersebut
        $bukuPernahDipinjam = Peminjaman::where('idUser', Auth::id())->join('detail_peminjamans', 'peminjamans.idPeminjaman', '=', 'detail_peminjamans.idPeminjaman')->pluck('detail_peminjamans.idBuku')->unique()->toArray();

        return view('member.listbuku', compact('bukus', 'genres', 'bukuPernahDipinjam'));
    }

    public function riwayatpeminjaman()
    {
        $peminjaman = Peminjaman::where('idUser', Auth::id())->paginate(10);

        return view('member.riwayatpeminjaman', compact('peminjaman'));
    }
}

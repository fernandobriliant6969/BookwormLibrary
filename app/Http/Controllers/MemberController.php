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
        $now = now()->startOfDay();

        $totalBukuPeminjaman = 0;
        $totalPeminjaman = 0;

        $totalBukuBelumDikembalikan = 0;
        $totalPeminjamanBelumKembali = 0;

        $totalPeminjamanTerlambat = 0;
        $totalBukuTerlambat = 0;

        $isTerlambat = false;

        $peminjamanBelumKembali = Peminjaman::where('idUser', Auth::id())->where('status', '!=', 'Telah Dikembalikan')->with(['details'])->get();

        if($peminjamanBelumKembali->isNotEmpty()) {
            foreach ($peminjamanBelumKembali as $pinjam) {
                
                $tanggalBatas = Carbon::parse($pinjam->tanggalKembali)->startOfDay();
                
                if ($tanggalBatas->lessThan($now)) {
                    $totalPeminjamanTerlambat++;
                    $isTerlambat = true;
                } else {
                    $totalPeminjamanBelumKembali++;
                    $isTerlambat = false;
                }

                $totalPeminjaman++;

                foreach ($pinjam->details as $detail) {
                    if($detail->status === 'dipinjam') {                        
                        if ($isTerlambat) {
                            $totalBukuTerlambat++;
                        } 
                        $totalBukuBelumDikembalikan++;
                    }
                }
            }
        }

        $peminjaman = Peminjaman::where('idUser', Auth::id())->with(['details'])->get();

        if($peminjaman->isNotEmpty()){
            foreach($peminjaman as $pinjam){
                foreach($pinjam->details as $detail){
                    $totalBukuPeminjaman++;
                }
            }
        }

        $peminjamanHarian = collect();
    
        for($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labelDate = now()->subDays($i)->format('d M');

            $count = Peminjaman::where('idUser', Auth::id())->whereDate('tanggalPeminjaman', $date)->join('detail_peminjamans', 'peminjamans.idPeminjaman', '=', 'detail_peminjamans.idPeminjaman')->count();

            $peminjamanHarian->push([
                'tanggal' => $labelDate,
                'total' => $count
            ]);
        }

        $genreData = Peminjaman::where('idUser', Auth::id())->whereHas('details', function($query) {
            $query->whereHas('buku.genre'); 
        })->get()->flatMap(function ($pinjam) {
            return $pinjam->details->flatMap(function ($detail) {
                return $detail->buku->genre; 
            });
        })->groupBy('idGenre')->map(function ($group) {
            return [
                'nama' => $group->first()->nama,
                'total' => $group->count()
            ];
        });

        $donutLabels = $genreData->pluck('nama')->toArray();
        $donutValues = $genreData->pluck('total')->toArray();

        $chartLabels = $peminjamanHarian->pluck('tanggal')->toArray();
        $chartData = $peminjamanHarian->pluck('total')->toArray();

        $bukuPopuler = Buku::withCount('detailPeminjamans')->orderBy('detail_peminjamans_count', 'desc')->take(5)->get();

        $bukuRatingTertinggi = Buku::withAvg('review as rating_avg', 'review_bukus.rating')->withCount('review as review_count') ->orderBy('rating_avg', 'desc')->take(5)->get();

        return view('member.dashboard', compact('totalBukuBelumDikembalikan', 'totalBukuTerlambat', 'totalPeminjamanTerlambat', 'isTerlambat', 'totalBukuPeminjaman',  'totalPeminjaman', 'chartLabels', 'chartData','donutLabels', 'donutValues','bukuPopuler', 'bukuRatingTertinggi'));
    }

    public function listbuku(Request $request)
    {
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

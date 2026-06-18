<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Genre;
use App\Models\ReviewBuku;
use Carbon\Carbon;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $jumlahBuku = Buku::count();
        $jumlahAnggota = User::count();
        $jumlahPeminjaman = Peminjaman::count();
        $jumlahGenre = Genre::count();
        $jumlahDikembalikan = Peminjaman::where('status', '=',  'Telah Dikembalikan')->count();
        $jumlahRating = ReviewBuku::count();

        $bukuPopuler = Buku::withCount('detailPeminjamans')->orderBy('detail_peminjamans_count', 'desc')->take(5)->get();

        $peminjamanHarian = collect();
    
        for($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labelDate = now()->subDays($i)->format('d M');

            $count = Peminjaman::whereDate('tanggalPeminjaman', $date)->join('detail_peminjamans', 'peminjamans.idPeminjaman', '=', 'detail_peminjamans.idPeminjaman')->count();

            $peminjamanHarian->push([
                'tanggal' => $labelDate,
                'total' => $count
            ]);
        }

        $chartLabelTotalPeminjaman = $peminjamanHarian->pluck('tanggal')->toArray();
        $chartDataTotalPeminjaman = $peminjamanHarian->pluck('total')->toArray();

        $hariIni = Carbon::now()->format('Y-m-d');
        $bukuOverdue = Peminjaman::where('status', 'Terlambat')->orWhere(function($query) use ($hariIni) {
                            $query->whereIn('status', ['Aktif', 'Dikembalikan Sebagian'])->whereDate('tanggalKembali', '<', $hariIni);
                        })->withCount('details')->get()->sum('details_count');

        $bukuDipinjamAman = Peminjaman::whereIn('status', ['Aktif', 'Dikembalikan Sebagian'])->whereDate('tanggalKembali', '>=', $hariIni)->withCount('details')->get()->sum('details_count');
        $bukuDiRak = $jumlahBuku - ($bukuOverdue + $bukuDipinjamAman);
    
        if($bukuDiRak < 0) { 
            $bukuDiRak = 0; 
        }

        $pieLabels = ['Tersedia', 'Dipinjam', 'Terlambat'];
        $pieValues = [$bukuDiRak, $bukuDipinjamAman, $bukuOverdue];

        return view('admin.dashboard', compact(
            'jumlahBuku',
            'jumlahAnggota',
            'jumlahPeminjaman',
            'jumlahGenre',
            'jumlahDikembalikan',
            'jumlahRating',
            'bukuPopuler',
            'chartLabelTotalPeminjaman',
            'chartDataTotalPeminjaman',
            'pieLabels',
            'pieValues'
        ));
    }
}

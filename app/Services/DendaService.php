<?php

namespace App\Services;

use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Denda;
use Carbon\Carbon;

class DendaService {

    public static function hitungOtomatis()
    {
        // Menetapkan tarif denda per buku per hari
        $tarifDendaPerHari = 25000;

        // Mendapatkan tanggal hari ini
        $now = now()->startOfDay();

        // Mencari peminjaman yang sudah lewat dari tanggal kembali dan status nya belum 'Telah Dikembalikan'
        $peminjamanTerlambat = Peminjaman::where('tanggalKembali', '<', $now)->where('status', '!=', 'Telah Dikembalikan')->with(['details', 'denda'])->get();

        foreach ($peminjamanTerlambat as $peminjaman) {
            $tanggalBatas = Carbon::parse($peminjaman->tanggalKembali)->startOfDay();
            $totalDendaTransaksi = 0;
            $totalBukuDipinjam = 0;

            // Menghitung denda tiap detail peminjaman
            foreach ($peminjaman->details as $detail) {
                // Jika status buku masih dipinjam
                if ($detail->status === 'dipinjam') {
                    $hariTelat = $now->diffInDays($tanggalBatas, true);
                    $totalDendaTransaksi += $hariTelat * $tarifDendaPerHari;
                    $totalBukuDipinjam++;
                // Jika status buku sudah dikembalikan
                } else if($detail->status === 'dikembalikan') {
                    $tanggalKembali = Carbon::parse($detail->updated_at)->startOfDay();
                    
                    // Menghitung denda berdasarkan jumlah hari telat x tarif denda per hari
                    if ($tanggalKembali->greaterThan($tanggalBatas)) {
                        $hariTelat = $tanggalKembali->diffInDays($tanggalBatas, true);
                        $totalDendaTransaksi += $hariTelat * $tarifDendaPerHari;
                    }
                }
            }

            // Jika masih ada buku yang status nya dipinjam
            if ($totalBukuDipinjam > 0) {
                // Menguupdate status peminjaman menjadi 'Terlambat'
                $peminjaman->update(['status' => 'Terlambat']);

                // Mengupdate jumlah denda di tabel Denda
                Denda::updateOrCreate(
                    ['idPeminjaman' => $peminjaman->idPeminjaman],
                    [
                        'jumlahDenda' => $totalDendaTransaksi,
                        'status'      => 'Belum Bayar'
                    ]
                );
            // Jika sudah tidak ada lagi buku yang status nya dipinjam
            } else {
                // Mengupdate status peminjaman menjadi 'Telat Dikembalikan'
                $peminjaman->update(['status' => 'Telah Dikembalikan']);
                
                // Mengupdate jumlah denda di tabel Denda
                Denda::where('idPeminjaman', $peminjaman->idPeminjaman)->update(['status' => 'Belum Bayar']);
            }
        }
    }
}

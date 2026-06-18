<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    // Atribut yang bisa di input oleh pengguna
    protected $fillable = [
        'idPeminjaman',
        'idBuku',
        'status'
    ];

    // Deklarasi nama table dan primary key agar dikenali oleh Laravel
    protected $table = 'detail_peminjamans';
    protected $primaryKey = 'idDetailPeminjaman';

    // Relasi antara detail peminjaman dan peminjaman
    public function peminjaman() {
        // Parameter 1: Foreign Key di tabel detail_peminjamans
        // Parameter 2: Primary Key di tabel peminjamans
        return $this->belongsTo(Peminjaman::class, 'idPeminjaman', 'idPeminjaman');
    }

    // Relasi antara detail peminjaman dan buku
    public function buku() {
        // Parameter 1: Foreign Key di tabel detail_peminjamans
        // Parameter 2: Primary Key di tabel bukus
        return $this->belongsTo(Buku::class, 'idBuku', 'idBuku');
    }
}

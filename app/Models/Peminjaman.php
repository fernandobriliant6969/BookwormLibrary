<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    // Atribut yang bisa di input oleh pengguna
    protected $fillable = [
        'idUser',
        'tanggalPeminjaman',
        'tanggalKembali',
        'lamaPinjam',
        'status',
        'catatan'
    ];

    // Deklarasi nama table dan primary key agar dikenali oleh Laravel
    protected $table = 'peminjamans';
    protected $primaryKey = 'idPeminjaman';

    // Relasi antara peminjaman dan user
    public function user() {
        // Parameter 1: Foreign Key di tabel peminjamans
        // Parameter 2: Primary Key di tabel users
        return $this->belongsTo(User::class, 'idUser', 'idUser');
    }

    // Relasi antara peminjaman dan detail peminjaman
    public function details() {
        // Parameter 1: Foreign Key di tabel detail_peminjamans
        // Parameter 2: Primary Key di tabel peminjamans
        return $this->hasMany(DetailPeminjaman::class, 'idPeminjaman', 'idPeminjaman');
    }

    public function denda() {
        // Parameter 1: Foreign Key di tabel dendas
        // Parameter 2: Primary Key di tabel peminjamans
        return $this->hasOne(Denda::class, 'idPeminjaman', 'idPeminjaman');
    }
}

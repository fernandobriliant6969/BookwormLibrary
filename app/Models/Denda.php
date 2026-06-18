<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    // Atribut yang bisa di input
    protected $fillable = [
        'idPeminjaman',
        'jumlahDenda',
        'status'
    ];

    // Deklarasi nama table dan primary key agar dikenali oleh Laravel
    protected $table = 'dendas';
    protected $primaryKey = 'idDenda';

    // Relasi antara denda dan peminjaman
    public function peminjaman() {
        // Parameter 1: Foreign Key di tabel dendas
        // Parameter 2: Primary Key di tabel peminjamans
        return $this->belongsTo(Peminjaman::class, 'idPeminjaman', 'idPeminjaman');
    }
}

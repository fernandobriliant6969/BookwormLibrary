<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    // Atribut yang bisa di input oleh pengguna
    protected $fillable = [
        "judul",
        "pengarang",
        "penerbit",
        "genre",
        "tanggalTerbit",
        "jumlahHalaman",
        "idGenre",
        "photoUrl",
        "status",
        "stok",
        "ringkasan"
    ];

    // Deklarasi nama table dan primary key agar dikenali oleh Laravel
    protected $table = 'bukus';
    protected $primaryKey = 'idBuku';

    // Relasi antara genre dan buku menggunakan table GenreBuku
    public function genre()
    {
        // Parameter 1: Model target (Genre)
        // Parameter 2: Nama tabel pivot/penengah di database (genre_bukus)
        // Parameter 3: Foreign Key milik model ini di tabel pivot (idBuku)
        // Parameter 4: Foreign Key milik model target di tabel pivot (idGenre)
        return $this->belongsToMany(Genre::class, 'genre_bukus', 'idBuku', 'idGenre');
    }

    // Relasi antara review dan buku
    public function review()
    {
        // Parameter 1: Model target (users)
        // Parameter 2: Nama tabel pivot/penengah di database (review_bukus)
        // Parameter 3: Foreign key milik model ini di tabel pivot (idBuku)
        // Parameter 4: Foreign key milik model target di tabel pivot (idUser)
        return $this->belongsToMany(User::class, 'review_bukus', 'idBuku', 'idUser')->withPivot('idReview','rating','pesan');
    }

    // Relasi antara buku dan detail pminjaman
    public function detailPeminjamans()
    {
         // Parameter 1: Model target (detail_peminjamans)
        // Parameter 3: Foreign key milik model ini di tabel pivot (idBuku)
        // Parameter 4: Foreign key milik model target di tabel pivot (idBuku)
        return $this->hasMany(DetailPeminjaman::class, 'idBuku', 'idBuku'); 
    }
}

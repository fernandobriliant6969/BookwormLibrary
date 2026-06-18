<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Buku;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Genre extends Model
{
    // Atribut yang bisa di input oleh pengguna
    protected $fillable = [
        "nama",
        "deskripsi"
    ];

    // Deklarasi nama table dan primary key agar dikenali oleh Laravel
    protected $table = 'genres';
    protected $primaryKey = 'idGenre';

    // Relasi antara buku dan genre menggunakan table GenreBuku
    public function buku() {
        // Parameter 1: Model target (Buku)
        // Parameter 2: Nama tabel pivot/penengah di database (genre_bukus)
        // Parameter 3: Foreign Key milik model ini di tabel pivot (idGenre)
        // Parameter 4: Foreign Key milik model target di tabel pivot (idBuku)
        return $this->belongsToMany(Buku::class, 'genre_bukus', 'idGenre', 'idBuku');
    }
}

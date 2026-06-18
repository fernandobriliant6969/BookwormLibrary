<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewBuku extends Model
{
    // Atribut yang bisa di input oleh pengguna
    protected $fillable = [
        'idUser',
        'idBuku',
        'rating',
        'pesan'
    ];

    // Deklarasi nama table dan primary key agar dikenali oleh Laravel
    protected $table = 'review_bukus';
    protected $primaryKey = 'idReview';
}
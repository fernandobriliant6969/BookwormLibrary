<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['nama', 'username', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'idUser';

    protected $fillable = [
        'nama',
        'username',
        'email',
        'nomorTelp',
        'jenisKelamin',
        'alamat',
        'password',
        'photoUrl',
        'role'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi antara user dan review
    public function review()
    {
        // Parameter 1: Model target (bukus)
        // Parameter 2: Nama tabel pivot/penengah di database (review_bukus)
        // Parameter 3: Foreign key milik model ini di tabel pivot (idUser)
        // Parameter 4: Foreign key milik model target di tabel pivot (idBuku)
        return $this->belongsToMany(Buku::class, 'review_bukus', 'idUser', 'idBuku')->withPivot('idReview', 'rating', 'pesan');
    }
}

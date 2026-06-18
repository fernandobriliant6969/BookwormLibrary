<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\MenuMemberController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ReviewBukuController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsMember;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('login');
});

Route::get('/buku/proxy-cover', [BukuController::class, 'proxyCover'])->name('buku.proxy-cover');

// Route bisa di akses member maupun admin
Route::middleware('auth')->group(function () {
    // Profile Controller
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/{idUser}', [ProfileController::class, 'update'])->name('profile.update');

    // Security Controller
    Route::get('/profile/security', [SecurityController::class, 'index'])->name('profile.security');
    Route::put('/profile/security/password/{idUser}', [SecurityController::class, 'updatePassword'])->name('profile.security.updatePassword');
    Route::put('/profile/security/email/{idUser}', [SecurityController::class, 'updateEmail'])->name('profile.security.updateEmail');
    Route::delete('/profile/security/{idUser}', [SecurityController::class, 'deleteAccount'])->name('profile.security.deleteAccount');

    // Review Controller
    Route::resource('review', ReviewBukuController::class);
});

// Route khusus Member
Route::middleware('auth', IsMember::class)->prefix('member')->group(function () {
    Route::get('dashboard', [MemberController::class, 'dashboard'])->name('member.dashboard');

    Route::get('listbuku', [MemberController::class, 'listbuku'])->name('member.listbuku');

    Route::get('riwayatpeminjaman', [MemberController::class, 'riwayatpeminjaman'])->name('member.riwayatpeminjaman');
});

// Route khusus Admin / Superadmin
Route::middleware(['auth', IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    // Profile Controller
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Peminjaman Controller
    Route::post('peminjaman/{peminjaman}/detail/{detail}', [PeminjamanController::class, 'updateDetail'])->name('peminjaman.updateDetail');
    Route::resource('peminjaman', PeminjamanController::class);

    // Genre Controller
    Route::resource('genre', GenreController::class);

    // Buku Controller
    Route::get('buku/list', [BukuController::class, 'listbuku'])->name('buku.listbuku');
    Route::resource('buku', BukuController::class);

    // Dashboard
    Route::get('dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');

    // User Controller
    Route::resource('user', UserController::class);
    Route::post('user/makeadmin/{idUser}', [UserController::class, 'makeAdmin'])->name('user.makeAdmin');
    Route::post('user/makemember/{idUser}', [UserController::class, 'makeMember'])->name('user.makeMember');
});

require __DIR__ . '/auth.php';

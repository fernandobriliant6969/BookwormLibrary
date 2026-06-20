<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class SecurityController extends Controller
{
    public function index()
    {
        return view('profile.security');
    }

    public function updatePassword(Request $request, $idUser)
    {
        // Mencari anggota yang ingin di update password nya
        $user = User::findOrFail($idUser);

        // Validasi Error
        // Alasan menggunakan Validator daripada $request->validate agar bisa memisahkan request per form dalam 1 halaman. Agar dapat mengembalikan error khusus spesifik form saja dan Tidak semua form dalam 1 halaman akan ketrigger error input
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => ['required','confirmed','min:8', Rules\Password::defaults()],
            'password_confirmation' => 'required|min:8'
        ], [
            'current_password.required' => 'Password saat ini harus di isi',
            'password.required' => 'Password baru harus di isi',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok',
            'password.min' => 'Panjang karakter password baru minimal 8 karakter',
            'password_confirmation.required' => 'Konfirmasi paassword baru harus di isi',
            'password_confirmation.min' => 'Panjang karakter konfirmasi password baru minimal 8 karakter',
        ]);

        // Jika ada error input
        if($validator->fails()) {
            return back()->withErrors($validator, 'passwordError')->withInput();
        }

        // Jika password saat ini tidak sesuai / salah
        if (!Hash::check($request->current_password, $user->password)) {
            $validator->errors()->add('current_password', 'Password saat ini salah');
            
            return back()->withErrors($validator, 'passwordError')->withInput();
        }

        // Mengupdate password dengan input password baru
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil diperbarui');
    }

    public function updateEmail(Request $request, $idUser)
    {
        // Mencari anggota yang ingin di update emailnya
        $user = User::findOrFail($idUser);

        // Validasi Error
        // Alasan menggunakan Validator daripada $request->validate agar bisa memisahkan request per form dalam 1 halaman. Agar dapat mengembalikan error khusus spesifik form saja dan Tidak semua form dalam 1 halaman akan ketrigger error input
        $validator = Validator::make($request->all(), [
            'password_verify' => 'required',
            'email'           => ['required', 'email', "unique:users,email,{$user->idUser},idUser"],
        ], [
            'password_verify.required' => 'Konfirmasi password wajib di isi',
            'email.required' => 'Email baru harus di isi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email ini sudah digunakan, Gunakan email lain',
        ]);

        // Jika ada error input
        if($validator->fails()) {
            return back()->withErrors($validator, 'emailError')->withInput();
        }

        // Jika passowrd saat ini tidak sesuai / salah
        if (!Hash::check($request->password_verify, $user->password)) {
            $validator->errors()->add('password_verify', 'Password konfirmasi salah');
            
            return back()->withErrors($validator, 'emailError')->withInput();
        }

        // Mengupdate email dengan input email baru
        $user->update([
            'email' => $request->email
        ]);

        return back()->with('success', 'Alamat email berhasil diperbarui');
    }

    public function deleteAccount($idUser){
        // Mencari anggota yang ingin di hapus akun nya
        $user = User::findOrFail($idUser);

        // Menghapus akun
        $user->delete();

        // Untuk menglogoutkan otomatis anggota yang sedang login
        Auth::logout();

        // Mengembalikan ke halaman login
        return redirect()->route('login');
    }
}
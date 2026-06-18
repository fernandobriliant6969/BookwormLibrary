<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            "nama" => "required",
            "username" => "required|unique:users",
            "email" => "required|email|unique:users",
            "nomorTelp" => "required",
            "jenisKelamin" => "required",
            "alamat" => "required",
            "password" => ["required", "confirmed", Rules\Password::defaults()],
        ],[
            "nama.required" => "Nama harus di isi",
            "username.required" => "Username harus di isi",
            "username.unique" => "Username ini sudah digunakan, Gunakan username lain",
            "email.required" => "Email harus di isi",
            "email.unique" => "Email ini sudah digunakan, Gunakan email lain",
            "nomorTelp.required" => "Nomor telepon harus di isi",
            "jenisKelamin.required" => "Jenis kelamin harus di isi",
            "alamat.required" => "Alamat harus di isi",
            "password.required" => "Password harus di isi",
            "password.confirmed" => "Password dan konfirmasi password tidak sesuai",
            "password.min" => "Minimal panjang password 8 karakter"
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'nomorTelp' => $request->nomorTelp,
            'jenisKelamin' => $request->jenisKelamin,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('member.dashboard'));
    }
}

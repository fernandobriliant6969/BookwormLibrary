<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            "email" => "required",
            "password" => ["required", "confirmed", Rules\Password::defaults()]
        ],[
            "email.required" => "Email harus di isi",
            "password.required" => "Password harus di isi",
            "password.confirmed" => "Password dan konfirmasi password tidak sesuai",
            "password.min" => "Minimal panjang password 8 karakter"
        ]);


        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                if (Hash::check($request->password, $user->password)) {
                    throw ValidationException::withMessages([
                        'password' => 'Password baru tidak boleh sama dengan password lama',
                    ]);
                }
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return back()->with('status','Password berhasil direset, Silahkan login kembali menggunakan email dan password yang telah direset');
    }
}

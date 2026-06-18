<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ],[
            'email.required' => 'Email harus di isi'
        ]);
        
        $user = User::where('email', $request->email)->first();

        if(!$user){
            throw ValidationException::withMessages([
                'email' => 'Email tidak terdaftar di sistem',
            ]);
        }

        $token = Password::createToken($user);
    
        Notification::route('mail', $user->email)->notify(
            new class($token, $user) extends \Illuminate\Notifications\Notification {
                protected $token;
                protected $user;

                public function __construct($token, $user) {
                    $this->token = $token;
                    $this->user = $user;
                }

                public function via($notifiable): array {
                    return ['mail'];
                }

                public function toMail($notifiable): MailMessage {
                    return (new MailMessage)
                        ->subject('Reset Password - Bookworm Library')
                        ->greeting('Halo, ' . $this->user->nama)
                        ->line('Kami menerima permintaan untuk mengatur ulang kata sandi pada akun ' . $this->user->email . '. Untuk melanjutkan proses reset password, silakan klik tombol di bawah ini')
                        ->action('Reset Password', url(route('password.reset', [
                            'token' => $this->token,
                            'email' => $this->user->email,
                        ], false)))
                        ->line('Link reset password ini hanya berlaku untuk 60 menit')
                        ->line('Jika anda tidak merasa meminta pengaturan ulang ini, Anda bisa mengabaikan email ini')
                        ->line('')
                        ->salutation('Terimakasih, Tim Bookworm Library');
                }
            }
        );

        return back()->with('status', 'Link pengaturan ulang kata sandi berhasil dikirim ke alamat email Anda. Jika email tidak masuk dalam beberapa menit, silakan periksa folder spam atau coba lagi');
    }
}

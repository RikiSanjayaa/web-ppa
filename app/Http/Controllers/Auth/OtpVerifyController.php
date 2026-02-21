<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class OtpVerifyController extends Controller
{
    public function __construct(protected OtpService $otpService) {}

    /**
     * Tampilkan form OTP.
     */
    public function show(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('otp_pending_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.otp-verify');
    }

    /**
     * Verifikasi OTP yang diinput user.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'digits:6'],
        ]);

        // Rate limiting: max 5 percobaan per menit per IP
        $key = 'otp-verify:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'otp' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.",
            ]);
        }
        RateLimiter::hit($key, 60);

        $userId = $request->session()->get('otp_pending_user_id');
        if (! $userId) {
            return redirect()->route('login')->withErrors(['email' => 'Sesi login telah berakhir. Silakan login ulang.']);
        }

        $user = User::find($userId);
        if (! $user) {
            $request->session()->forget('otp_pending_user_id');
            return redirect()->route('login');
        }

        $result = $this->otpService->verify($user, $request->otp);

        match ($result) {
            'valid' => $this->loginUser($request, $user),
            default => null,
        };

        if ($result === 'valid') {
            RateLimiter::clear($key);
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        $message = match ($result) {
            'expired'      => 'Kode OTP sudah kadaluarsa. Silakan login ulang untuk mendapatkan kode baru.',
            'max_attempts' => 'Terlalu banyak percobaan salah. Silakan login ulang.',
            default        => 'Kode OTP tidak valid. Periksa kembali kode yang dikirim ke email Anda.',
        };

        if (in_array($result, ['expired', 'max_attempts'])) {
            $request->session()->forget('otp_pending_user_id');
            return redirect()->route('login')->withErrors(['email' => $message]);
        }

        return back()->withErrors(['otp' => $message]);
    }

    /**
     * Kirim ulang OTP.
     */
    public function resend(Request $request): RedirectResponse
    {
        $key = 'otp-resend:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return back()->withErrors(['otp' => 'Terlalu banyak permintaan. Silakan tunggu sebentar.']);
        }
        RateLimiter::hit($key, 120);

        $userId = $request->session()->get('otp_pending_user_id');
        if (! $userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);
        if (! $user) {
            return redirect()->route('login');
        }

        $this->otpService->generate($user);

        return back()->with('status', 'Kode OTP baru telah dikirim ke email Anda.');
    }

    private function loginUser(Request $request, User $user): void
    {
        $user->update(['otp_verified_at' => now()]);

        Auth::login($user);
        $request->session()->forget('otp_pending_user_id');
        $request->session()->regenerate();
        ActivityLogger::log('admin.auth.login', null, 'Admin berhasil masuk ke panel.');
    }
}

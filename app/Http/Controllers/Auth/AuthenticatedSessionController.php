<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function __construct(protected OtpService $otpService) {}

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Step 1: Validasi email + password.
     * Jika valid, generate OTP dan arahkan ke halaman verifikasi OTP.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = $request->user();

        // Cek apakah user memiliki role admin
        if (! $user?->isOperationalAdmin()) {
            Auth::guard('web')->logout();

            throw ValidationException::withMessages([
                'email' => 'Akun ini tidak memiliki akses admin.',
            ]);
        }

        // Bypass OTP jika sudah diverifikasi dalam 7 hari terakhir
        if ($user->otp_verified_at && $user->otp_verified_at->diffInDays(now()) < 7) {
            $request->session()->regenerate();
            \App\Services\ActivityLogger::log('admin.auth.login', null, 'Admin berhasil masuk ke panel (Bypass OTP 7 hari).');
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        // Logout sementara, simpan user_id di session untuk tahap OTP
        Auth::guard('web')->logout();
        $request->session()->put('otp_pending_user_id', $user->id);

        // Generate OTP dan kirim ke email
        $this->otpService->generate($user);

        return redirect()->route('otp.show')
            ->with('status', 'Kode OTP telah dikirim ke email Anda. Silakan cek inbox.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        if ($request->user()?->isOperationalAdmin()) {
            \App\Services\ActivityLogger::log('admin.auth.logout', null, 'Admin keluar dari panel.');
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}


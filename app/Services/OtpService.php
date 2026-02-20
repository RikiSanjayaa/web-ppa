<?php

namespace App\Services;

use App\Mail\OtpMail;
use App\Models\LoginOtp;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    const MAX_ATTEMPTS = 3;
    const EXPIRE_MINUTES = 5;

    /**
     * Generate OTP, simpan ke DB, dan kirim ke email user.
     */
    public function generate(User $user): void
    {
        // Hapus OTP lama jika ada
        LoginOtp::where('user_id', $user->id)->delete();

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        LoginOtp::create([
            'user_id'    => $user->id,
            'otp'        => Hash::make($otp),
            'expires_at' => now()->addMinutes(self::EXPIRE_MINUTES),
            'attempts'   => 0,
        ]);

        Mail::to($user->email)->send(new OtpMail($otp, $user->name));
    }

    /**
     * Verifikasi OTP yang diinput user.
     * Mengembalikan status: 'valid', 'invalid', 'expired', 'max_attempts'
     */
    public function verify(User $user, string $otp): string
    {
        $record = LoginOtp::where('user_id', $user->id)->latest()->first();

        if (!$record) {
            return 'invalid';
        }

        if ($record->expires_at->isPast()) {
            $record->delete();
            return 'expired';
        }

        if ($record->attempts >= self::MAX_ATTEMPTS) {
            $record->delete();
            return 'max_attempts';
        }

        if (!Hash::check($otp, $record->otp)) {
            $record->increment('attempts');
            return 'invalid';
        }

        // OTP valid — hapus record
        $record->delete();
        return 'valid';
    }

    /**
     * Hapus semua OTP untuk user tertentu.
     */
    public function clearOtp(User $user): void
    {
        LoginOtp::where('user_id', $user->id)->delete();
    }
}

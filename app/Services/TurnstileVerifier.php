<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TurnstileVerifier
{
    public function passes(?string $token, ?string $remoteIp = null): bool
    {
        $secret = (string) config('services.turnstile.secret_key');
        $enabled = (bool) config('services.turnstile.enabled');

        if (! $enabled) {
            return true;
        }

        if (! $token || $secret === '') {
            return false;
        }

        $response = Http::asForm()
            ->timeout(10)
            ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $secret,
                'response' => $token,
                'remoteip' => $remoteIp,
            ]);

        if (! $response->successful()) {
            return false;
        }

        return (bool) data_get($response->json(), 'success', false);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ComplaintSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_complaint_is_logged_and_redirected_to_whatsapp(): void
    {
        SiteSetting::query()->create([
            'key' => 'hotline_wa_number',
            'value' => '6281234567890',
        ]);

        $payload = [
            'nama_lengkap' => 'Pelapor Satu',
            'no_hp' => '081234567890',
            'tempat_kejadian' => 'Jakarta',
            'waktu_kejadian' => now()->format('Y-m-d H:i:s'),
            'kronologis_singkat' => 'Kronologis uji aduan.',
            'nik' => '1234567890123456',
        ];

        $response = $this->post(route('complaints.store'), $payload);

        $response->assertRedirect();
        $this->assertStringStartsWith('https://wa.me/6281234567890?', $response->headers->get('Location'));

        $this->assertDatabaseCount('complaints', 1);
        $complaint = Complaint::query()->firstOrFail();

        $this->assertSame(Complaint::STATUS_MASUK, $complaint->status);
        $this->assertNotNull($complaint->wa_redirected_at);

        $raw = DB::table('complaints')->first();
        $this->assertNotSame($payload['no_hp'], $raw->no_hp);
        $this->assertNotSame($payload['nik'], $raw->nik);
    }

    public function test_turnstile_invalid_is_rejected(): void
    {
        config()->set('services.turnstile.enabled', true);
        config()->set('services.turnstile.secret_key', 'secret');

        Http::fake([
            'https://challenges.cloudflare.com/turnstile/*' => Http::response(['success' => false], 200),
        ]);

        $response = $this->from(route('layanan-masyarakat'))->post(route('complaints.store'), [
            'nama_lengkap' => 'Pelapor Dua',
            'no_hp' => '081234567891',
            'tempat_kejadian' => 'Bandung',
            'waktu_kejadian' => now()->format('Y-m-d H:i:s'),
            'kronologis_singkat' => 'Kronologis gagal captcha.',
            'cf-turnstile-response' => 'dummy-token',
        ]);

        $response->assertRedirect(route('layanan-masyarakat'));
        $response->assertSessionHasErrors('cf-turnstile-response');
        $this->assertDatabaseCount('complaints', 0);
    }
}

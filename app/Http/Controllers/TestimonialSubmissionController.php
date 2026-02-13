<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Consultation;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Support\SiteDefaults;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TestimonialSubmissionController extends Controller
{
    public function formByToken($token)
    {
        $consultation = Consultation::where('testimonial_token', $token)->first();
        $complaint = Complaint::where('testimonial_token', $token)->first();

        if (! $consultation && ! $complaint) {
            return redirect()->route('layanan-masyarakat')->with('error', 'Token testimoni tidak valid atau sudah kadaluarsa.');
        }

        // Check if token already used — only check the actual matched record
        $isUsed = Testimonial::query()
            ->when($consultation, fn ($q) => $q->where('consultation_id', $consultation->id))
            ->when($complaint, fn ($q) => $q->orWhere('complaint_id', $complaint->id))
            ->exists();

        if ($isUsed) {
             return redirect()->route('layanan-masyarakat')->with('error', 'Link testimoni ini sudah pernah digunakan. Setiap link hanya dapat digunakan satu kali. Terima kasih atas partisipasi Anda.');
        }

        $clientName = $consultation ? $consultation->nama_klien : $complaint->nama_lengkap;


        return view('public.testimonials.create', [
            'client_name' => $this->maskName($clientName),
            'token' => $token,
            'settings' => $this->settings(),
        ]);
    }

    public function storeByToken($token, Request $request)
    {
        $consultation = Consultation::where('testimonial_token', $token)->first();
        $complaint = Complaint::where('testimonial_token', $token)->first();

        if (! $consultation && ! $complaint) {
            return redirect()->route('layanan-masyarakat')->with('error', 'Token tidak valid.');
        }

        // Check double submission — only check the actual matched record
        $isUsed = Testimonial::query()
            ->when($consultation, fn ($q) => $q->where('consultation_id', $consultation->id))
            ->when($complaint, fn ($q) => $q->orWhere('complaint_id', $complaint->id))
            ->exists();

        if ($isUsed) {
             return redirect()->route('layanan-masyarakat')->with('error', 'Testimoni sudah terkirim sebelumnya.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'content' => ['required', 'string', 'max:1000'],
            'relation' => ['nullable', 'string', 'max:100'],
        ]);

        $realName = $consultation ? $consultation->nama_klien : $complaint->nama_lengkap;
        $maskedName = $this->maskName($realName);
        
        // Phone number is generic/hidden since verified by token
        // We can get it from record but for privacy maybe just don't store or store masked?
        // Testimonial table has phone_number column, let's store it if available for record, but it's not verified by input.
        $phone = $consultation?->no_hp ?? $complaint?->no_hp ?? null;

        Testimonial::create([
            'name' => $maskedName, // Store masked name for privacy public display
            'phone_number' => $phone,
            'relation' => $validated['relation'] ?? 'Masyarakat',
            'content' => $validated['content'],
            'rating' => $validated['rating'],
            'is_verified' => true,
            'is_published' => $validated['rating'] >= 3, // Rating < 3 perlu persetujuan admin
            'consultation_id' => $consultation?->id,
            'complaint_id' => $complaint?->id,
        ]);

        return redirect()->route('testimonials.thankyou', $token);
    }

    public function thankyou($token)
    {
        $consultation = Consultation::where('testimonial_token', $token)->first();
        $complaint = Complaint::where('testimonial_token', $token)->first();

        if (! $consultation && ! $complaint) {
            return redirect()->route('layanan-masyarakat');
        }

        // Find the testimonial that was just submitted
        $testimonial = Testimonial::query()
            ->when($consultation, fn ($q) => $q->where('consultation_id', $consultation->id))
            ->when($complaint, fn ($q) => $q->orWhere('complaint_id', $complaint->id))
            ->latest()
            ->first();

        if (! $testimonial) {
            return redirect()->route('layanan-masyarakat');
        }

        $clientName = $consultation ? $consultation->nama_klien : $complaint->nama_lengkap;

        return view('public.testimonials.thankyou', [
            'client_name' => $this->maskName($clientName),
            'rating' => $testimonial->rating,
            'settings' => $this->settings(),
        ]);
    }

    private function maskName($name)
    {
        $parts = explode(' ', $name);
        $maskedParts = [];

        foreach ($parts as $part) {
            if (strlen($part) > 2) {
                $maskedParts[] = substr($part, 0, 1) . str_repeat('*', strlen($part) - 1);
            } else {
                $maskedParts[] = $part;
            }
        }

        return implode(' ', $maskedParts);
    }

    private function settings(): array
    {
        $defaults = SiteDefaults::values();
        $stored = SiteSetting::getMap(array_keys($defaults))->all();

        return array_replace($defaults, array_filter($stored, fn (?string $value) => $value !== null));
    }
}

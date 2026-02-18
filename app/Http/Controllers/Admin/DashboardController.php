<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Complaint;
use App\Models\Consultation;
use App\Models\Document;
use App\Models\GalleryItem;
use App\Models\Leader;
use App\Models\NewsPost;
use App\Models\Testimonial;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $complaintData = $this->complaintStatusData();
        $regionConfig = $this->regionConfig();
        $complaintLocationChart = $this->buildComplaintLocationStats($regionConfig);
        $consultationLocationChart = $this->buildConsultationLocationStats($regionConfig);

        return view('admin.dashboard', [
            'kpis' => [
                'total_aduan' => $complaintData['total'],
                'aduan_7_hari' => Complaint::query()->whereDate('created_at', '>=', now()->subDays(6)->toDateString())->count(),
                'rasio_selesai' => $complaintData['resolvedRatio'],
                'aktivitas_admin_7_hari' => ActivityLog::query()->whereDate('created_at', '>=', now()->subDays(6)->toDateString())->count(),
            ],
            'statusCounts' => $complaintData['statusCounts'],
            'contentSummary' => $this->contentSummary(),
            'charts' => [
                'topLocations' => $complaintLocationChart,
                'consultationLocations' => $consultationLocationChart,
                'complaintStatus' => [
                    'labels' => collect(Complaint::availableStatuses())->map(fn ($s) => $complaintData['statusLabels'][$s])->values()->all(),
                    'data' => collect(Complaint::availableStatuses())->map(fn ($s) => $complaintData['statusCounts'][$s])->values()->all(),
                    'colors' => collect(Complaint::availableStatuses())->map(fn ($s) => $complaintData['statusColors'][$s])->values()->all(),
                ],
                'reportTrend' => $this->reportTrendData(),
            ],
            'totalComplaints' => $complaintData['total'],
            'totalConsultations' => Consultation::count(),
            'consultationStatusData' => [
                'belum' => Consultation::whereNull('rekomendasi')->count(),
                'sudah' => Consultation::whereNotNull('rekomendasi')->count(),
            ],
            'recentComplaints' => Complaint::query()->latest()->take(8)->get(),
            'recentActivities' => ActivityLog::query()->with('user:id,name')->latest()->take(12)->get(),
        ]);
    }

    /**
     * Hitung status pengaduan, label, warna, dan rasio selesai.
     *
     * @return array{statusLabels: array, statusCounts: array, statusColors: array, total: int, resolvedRatio: float}
     */
    private function complaintStatusData(): array
    {
        $statusLabels = [
            Complaint::STATUS_MASUK => 'Masuk',
            Complaint::STATUS_DIPROSES_LP => 'LP',
            Complaint::STATUS_DIPROSES_LIDIK => 'Lidik',
            Complaint::STATUS_DIPROSES_SIDIK => 'Sidik',
            Complaint::STATUS_TAHAP_1 => 'Selesai Tahap 1',
        ];

        $statusCounts = [];
        foreach (Complaint::availableStatuses() as $status) {
            $statusCounts[$status] = Complaint::query()->where('status', $status)->count();
        }

        $total = array_sum($statusCounts);
        $resolvedRatio = $total > 0
            ? round(($statusCounts[Complaint::STATUS_TAHAP_1] / $total) * 100, 1)
            : 0.0;

        $statusColors = [
            Complaint::STATUS_MASUK => '#ef4444',
            Complaint::STATUS_DIPROSES_LP => '#f59e0b',
            Complaint::STATUS_DIPROSES_LIDIK => '#ea580c',
            Complaint::STATUS_DIPROSES_SIDIK => '#c2410c',
            Complaint::STATUS_TAHAP_1 => '#14b8a6',
        ];

        return compact('statusLabels', 'statusCounts', 'statusColors', 'total', 'resolvedRatio');
    }

    /**
     * Konfigurasi region: pusat koordinat, warna, dan pola teks.
     *
     * @return array{centers: array, colors: array, textPatterns: array}
     */
    private function regionConfig(): array
    {
        $centers = [
            'Mataram' => ['lat' => -8.5833, 'lng' => 116.1167],
            'Lombok Barat' => ['lat' => -8.6833, 'lng' => 116.1167],
            'Lombok Tengah' => ['lat' => -8.7000, 'lng' => 116.2667],
            'Lombok Timur' => ['lat' => -8.6500, 'lng' => 116.5333],
            'Lombok Utara' => ['lat' => -8.3500, 'lng' => 116.1500],
            'Sumbawa' => ['lat' => -8.5000, 'lng' => 117.4333],
            'Sumbawa Barat' => ['lat' => -8.7500, 'lng' => 116.8500],
            'Dompu' => ['lat' => -8.5333, 'lng' => 118.4667],
            'Bima' => ['lat' => -8.6000, 'lng' => 118.7000],
            'Kota Bima' => ['lat' => -8.4667, 'lng' => 118.7333],
        ];

        $colors = [
            'Mataram' => '#ef4444',
            'Lombok Barat' => '#f97316',
            'Lombok Tengah' => '#f59e0b',
            'Lombok Timur' => '#eab308',
            'Lombok Utara' => '#84cc16',
            'Sumbawa Barat' => '#22c55e',
            'Sumbawa' => '#14b8a6',
            'Dompu' => '#06b6d4',
            'Bima' => '#3b82f6',
            'Kota Bima' => '#8b5cf6',
            'Lainnya' => '#94a3b8',
        ];

        $textPatterns = [
            'Mataram' => '/mataram/i',
            'Lombok Barat' => '/lombok barat|lobar/i',
            'Lombok Tengah' => '/lombok tengah|loteng/i',
            'Lombok Timur' => '/lombok timur|lotim/i',
            'Lombok Utara' => '/lombok utara|klu/i',
            'Sumbawa Barat' => '/sumbawa barat|ksb/i',
            'Sumbawa' => '/sumbawa/i',
            'Dompu' => '/dompu/i',
            'Kota Bima' => '/kota bima/i',
            'Bima' => '/bima/i',
        ];

        return compact('centers', 'colors', 'textPatterns');
    }

    /**
     * Deteksi region berdasarkan GPS dan fallback teks.
     *
     * @param  array  $regionConfig
     * @param  float|null  $latitude
     * @param  float|null  $longitude
     * @param  string|null  $text
     */
    private function detectRegion(array $regionConfig, ?float $latitude, ?float $longitude, ?string $text = null): string
    {
        $detected = null;

        // Strategi 1: GPS
        if ($latitude && $longitude) {
            $minDist = 100000;
            foreach ($regionConfig['centers'] as $name => $coords) {
                $dist = sqrt(pow($latitude - $coords['lat'], 2) + pow($longitude - $coords['lng'], 2));
                if ($dist < $minDist) {
                    $minDist = $dist;
                    $detected = $name;
                }
            }
            if ($minDist >= 0.5) {
                $detected = null;
            }
        }

        // Strategi 2: Fallback teks
        if (! $detected && $text) {
            foreach ($regionConfig['textPatterns'] as $region => $pattern) {
                if (preg_match($pattern, $text)) {
                    $detected = $region;
                    break;
                }
            }
        }

        return $detected ?? 'Lainnya';
    }

    /**
     * Bangun statistik lokasi pengaduan untuk chart.
     *
     * @param  array  $regionConfig
     * @return array{labels: array, data: array, colors: array}
     */
    private function buildComplaintLocationStats(array $regionConfig): array
    {
        $kabupatenStats = array_fill_keys(array_keys($regionConfig['centers']), 0);
        $kabupatenStats['Lainnya'] = 0;

        $allComplaints = Complaint::query()->select('tempat_kejadian', 'latitude', 'longitude')->get();

        foreach ($allComplaints as $complaint) {
            $region = $this->detectRegion($regionConfig, $complaint->latitude, $complaint->longitude, $complaint->tempat_kejadian);
            $kabupatenStats[$region]++;
        }

        return $this->formatLocationChart($kabupatenStats, $regionConfig['colors']);
    }

    /**
     * Bangun statistik lokasi konsultasi untuk chart.
     *
     * @param  array  $regionConfig
     * @return array{labels: array, data: array, colors: array}
     */
    private function buildConsultationLocationStats(array $regionConfig): array
    {
        $kabupatenStats = array_fill_keys(array_keys($regionConfig['centers']), 0);
        $kabupatenStats['Lainnya'] = 0;

        $allConsultations = Consultation::query()->select('latitude', 'longitude')->get();

        foreach ($allConsultations as $consultation) {
            $region = $this->detectRegion($regionConfig, $consultation->latitude, $consultation->longitude);
            $kabupatenStats[$region]++;
        }

        return $this->formatLocationChart($kabupatenStats, $regionConfig['colors']);
    }

    /**
     * Format data lokasi menjadi array chart (labels, data, colors).
     *
     * @param  array<string, int>  $stats
     * @param  array<string, string>  $colors
     * @return array{labels: array, data: array, colors: array}
     */
    private function formatLocationChart(array $stats, array $colors): array
    {
        arsort($stats);
        $filtered = collect($stats)->filter(fn ($count) => $count > 0);

        $labels = $filtered->keys()->all();

        return [
            'labels' => $labels,
            'data' => $filtered->values()->map(fn ($v) => (int) $v)->all(),
            'colors' => collect($labels)->map(fn ($name) => $colors[$name] ?? '#94a3b8')->all(),
        ];
    }

    /**
     * Ringkasan konten website.
     *
     * @return array<string, int>
     */
    private function contentSummary(): array
    {
        return [
            'berita_event' => NewsPost::query()->count(),
            'dokumen' => Document::query()->count(),
            'galeri' => GalleryItem::query()->count(),
            'pimpinan' => Leader::query()->count(),
            'testimoni' => Testimonial::query()->count(),
        ];
    }

    /**
     * Data tren laporan 30 hari terakhir.
     *
     * @return array<int, array{date: string, complaints: int, consultations: int}>
     */
    private function reportTrendData(): array
    {
        $trendDates = collect(range(0, 29))->map(fn ($i) => now()->subDays($i)->format('Y-m-d'))->reverse()->values();

        $dailyComplaints = Complaint::query()
            ->selectRaw('DATE(created_at) as date, count(*) as total')
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->pluck('total', 'date');

        $dailyConsultations = Consultation::query()
            ->selectRaw('DATE(created_at) as date, count(*) as total')
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->pluck('total', 'date');

        return $trendDates->map(fn ($date) => [
            'date' => Carbon::parse($date)->format('d M'),
            'complaints' => (int) ($dailyComplaints[$date] ?? 0),
            'consultations' => (int) ($dailyConsultations[$date] ?? 0),
        ])->all();
    }
}

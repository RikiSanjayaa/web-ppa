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

        $totalComplaints = array_sum($statusCounts);
        $resolvedRatio = $totalComplaints > 0
            ? round(($statusCounts[Complaint::STATUS_TAHAP_1] / $totalComplaints) * 100, 1)
            : 0.0;

        $statusColors = [
            Complaint::STATUS_MASUK => '#ef4444',
            Complaint::STATUS_DIPROSES_LP => '#f59e0b',
            Complaint::STATUS_DIPROSES_LIDIK => '#ea580c',
            Complaint::STATUS_DIPROSES_SIDIK => '#c2410c',
            Complaint::STATUS_TAHAP_1 => '#14b8a6',
        ];

        // Top Lokasi Aduan — dikelompokkan per Kabupaten/Kota NTB
        $regionCenters = [
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

        $regionColors = [
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

        $kabupatenStats = array_fill_keys(array_keys($regionCenters), 0);
        $kabupatenStats['Lainnya'] = 0;

        $allComplaints = Complaint::query()->select('tempat_kejadian', 'latitude', 'longitude')->get();

        foreach ($allComplaints as $complaint) {
            $detected = null;

            // Strategi 1: GPS
            if ($complaint->latitude && $complaint->longitude) {
                $minDist = 100000;
                foreach ($regionCenters as $name => $coords) {
                    $dist = sqrt(pow($complaint->latitude - $coords['lat'], 2) + pow($complaint->longitude - $coords['lng'], 2));
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
            if (! $detected && $complaint->tempat_kejadian) {
                foreach ($textPatterns as $region => $pattern) {
                    if (preg_match($pattern, $complaint->tempat_kejadian)) {
                        $detected = $region;
                        break;
                    }
                }
            }

            $kabupatenStats[$detected ?? 'Lainnya']++;
        }

        arsort($kabupatenStats);
        $topLocations = collect($kabupatenStats)->filter(fn ($count) => $count > 0);

        // Buat data chart dengan warna unik per kabupaten
        $topLocationLabels = $topLocations->keys()->all();
        $topLocationData = $topLocations->values()->map(fn ($v) => (int) $v)->all();
        $topLocationColors = collect($topLocationLabels)->map(fn ($name) => $regionColors[$name] ?? '#94a3b8')->all();

        // Top Lokasi Konsultasi — dikelompokkan per Kabupaten/Kota NTB
        $consultationKabStats = array_fill_keys(array_keys($regionCenters), 0);
        $consultationKabStats['Lainnya'] = 0;

        $allConsultations = Consultation::query()->select('latitude', 'longitude')->get();

        foreach ($allConsultations as $consultation) {
            $detected = null;

            if ($consultation->latitude && $consultation->longitude) {
                $minDist = 100000;
                foreach ($regionCenters as $name => $coords) {
                    $dist = sqrt(pow($consultation->latitude - $coords['lat'], 2) + pow($consultation->longitude - $coords['lng'], 2));
                    if ($dist < $minDist) {
                        $minDist = $dist;
                        $detected = $name;
                    }
                }
                if ($minDist >= 0.5) {
                    $detected = null;
                }
            }

            $consultationKabStats[$detected ?? 'Lainnya']++;
        }

        arsort($consultationKabStats);
        $topConsultations = collect($consultationKabStats)->filter(fn ($count) => $count > 0);

        $topConsultationLabels = $topConsultations->keys()->all();
        $topConsultationData = $topConsultations->values()->map(fn ($v) => (int) $v)->all();
        $topConsultationColors = collect($topConsultationLabels)->map(fn ($name) => $regionColors[$name] ?? '#94a3b8')->all();

        // Proporsi Laporan
        $totalConsultations = Consultation::count();

        // Status Konsultasi (Belum/Sudah Ditanggapi)
        $consultationStatusData = [
            'belum' => Consultation::whereNull('rekomendasi')->count(),
            'sudah' => Consultation::whereNotNull('rekomendasi')->count(),
        ];

        // Tren Laporan 30 hari terakhir (Pengaduan vs Konsultasi)
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

        $reportTrendData = $trendDates->map(fn ($date) => [
            'date' => Carbon::parse($date)->format('d M'),
            'complaints' => (int) ($dailyComplaints[$date] ?? 0),
            'consultations' => (int) ($dailyConsultations[$date] ?? 0),
        ]);

        return view('admin.dashboard', [
            'kpis' => [
                'total_aduan' => $totalComplaints,
                'aduan_7_hari' => Complaint::query()->whereDate('created_at', '>=', now()->subDays(6)->toDateString())->count(),
                'rasio_selesai' => $resolvedRatio,
                'aktivitas_admin_7_hari' => ActivityLog::query()->whereDate('created_at', '>=', now()->subDays(6)->toDateString())->count(),
            ],
            'statusCounts' => $statusCounts,
            'contentSummary' => [
                'berita_event' => NewsPost::query()->count(),
                'dokumen' => Document::query()->count(),
                'galeri' => GalleryItem::query()->count(),
                'pimpinan' => Leader::query()->count(),
                'testimoni' => Testimonial::query()->count(),
            ],
            'charts' => [
                'topLocations' => [
                    'labels' => $topLocationLabels,
                    'data' => $topLocationData,
                    'colors' => $topLocationColors,
                ],
                'consultationLocations' => [
                    'labels' => $topConsultationLabels,
                    'data' => $topConsultationData,
                    'colors' => $topConsultationColors,
                ],
                'complaintStatus' => [
                    'labels' => collect(Complaint::availableStatuses())->map(fn ($s) => $statusLabels[$s])->values()->all(),
                    'data' => collect(Complaint::availableStatuses())->map(fn ($s) => $statusCounts[$s])->values()->all(),
                    'colors' => collect(Complaint::availableStatuses())->map(fn ($s) => $statusColors[$s])->values()->all(),
                ],
                'reportTrend' => $reportTrendData->all(),
            ],
            'totalComplaints' => $totalComplaints,
            'totalConsultations' => $totalConsultations,
            'consultationStatusData' => $consultationStatusData,
            'recentComplaints' => Complaint::query()->latest()->take(8)->get(),
            'recentActivities' => ActivityLog::query()->with('user:id,name')->latest()->take(12)->get(),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Consultation;
use Illuminate\Contracts\View\View;

class LocationMonitoringController extends Controller
{
    public function index(): View
    {
        // Ambil data aduan beserta lokasi
        $complaints = Complaint::query()
            ->latest()
            ->limit(50)
            ->get()
            ->toBase()
            ->map(function ($complaint) {
                return [
                    'id' => $complaint->id,
                    'type' => 'Aduan',
                    'name' => $complaint->nama_lengkap,
                    'description' => \Illuminate\Support\Str::limit($complaint->kronologis_singkat, 100),
                    'latitude' => $complaint->latitude,
                    'longitude' => $complaint->longitude,
                    'created_at' => $complaint->created_at->format('d M Y H:i'),
                    'url' => route('admin.complaints.show', $complaint),
                    'color' => 'red',
                ];
            });

        // Ambil data konsultasi beserta lokasi
        $consultations = Consultation::query()
            ->latest()
            ->limit(50)
            ->get()
            ->toBase()
            ->map(function ($consultation) {
                return [
                    'id' => $consultation->id,
                    'type' => 'Konsultasi',
                    'name' => $consultation->nama_klien,
                    'description' => \Illuminate\Support\Str::limit($consultation->permasalahan, 100),
                    'latitude' => $consultation->latitude,
                    'longitude' => $consultation->longitude,
                    'created_at' => $consultation->created_at->format('d M Y H:i'),
                    'url' => route('admin.consultations.show', $consultation),
                    'color' => 'blue',
                ];
            });

        $locations = $complaints->merge($consultations)->sortByDesc('created_at')->values();

        return view('admin.location-monitoring.index', [
            'locations' => $locations,
        ]);
    }

    public function summary(): View
    {
        // Statistik lokasi aduan (Grouped by Kab/Kota NTB) - Hybrid: GPS dulu, lalu teks
        $allComplaints = Complaint::query()
            ->select('tempat_kejadian', 'latitude', 'longitude')
            ->get();

        $kabupatenStats = array_fill_keys(array_keys($this->getRegionCenters()), 0);
        $kabupatenStats['Luar NTB / Lainnya'] = 0;

        foreach ($allComplaints as $complaint) {
            $detectedRegion = null;

            // Strategi 1: Cek koordinat GPS
            if ($complaint->latitude && $complaint->longitude) {
                $detectedRegion = $this->getNearestRegion($complaint->latitude, $complaint->longitude);
            }

            // Strategi 2: Fallback ke pencocokan teks jika GPS tidak tersedia
            if (!$detectedRegion && $complaint->tempat_kejadian) {
                $place = strtolower($complaint->tempat_kejadian);
                if (preg_match('/mataram/i', $place)) {
                    $detectedRegion = 'Mataram';
                } elseif (preg_match('/lombok barat|lobar/i', $place)) {
                    $detectedRegion = 'Lombok Barat';
                } elseif (preg_match('/lombok tengah|loteng/i', $place)) {
                    $detectedRegion = 'Lombok Tengah';
                } elseif (preg_match('/lombok timur|lotim/i', $place)) {
                    $detectedRegion = 'Lombok Timur';
                } elseif (preg_match('/lombok utara|klu/i', $place)) {
                    $detectedRegion = 'Lombok Utara';
                } elseif (preg_match('/sumbawa barat|ksb/i', $place)) {
                    $detectedRegion = 'Sumbawa Barat';
                } elseif (preg_match('/sumbawa/i', $place)) { 
                    $detectedRegion = 'Sumbawa';
                } elseif (preg_match('/dompu/i', $place)) {
                    $detectedRegion = 'Dompu';
                } elseif (preg_match('/kota bima/i', $place)) {
                    $detectedRegion = 'Kota Bima';
                } elseif (preg_match('/bima/i', $place)) {
                    $detectedRegion = 'Bima';
                }
            }

            // Tambahkan ke statistik
            if ($detectedRegion) {
                $kabupatenStats[$detectedRegion]++;
            } else {
                $kabupatenStats['Luar NTB / Lainnya']++;
            }
        }

        // Urutkan dari terbesar
        arsort($kabupatenStats);
        
        // Transformasi untuk grafik
        $complaintLocations = collect($kabupatenStats)->map(function ($count, $name) {
            return (object) ['tempat_kejadian' => $name, 'total' => $count];
        })->filter(fn($item) => $item->total > 0)->values();

        // Statistik lokasi konsultasi (berdasarkan kedekatan koordinat)
        $consultations = Consultation::query()
            ->select('latitude', 'longitude')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();
            
        $consultationStats = array_fill_keys(array_keys($this->getRegionCenters()), 0);
        $consultationStats['Luar NTB / Lainnya'] = 0;

        foreach ($consultations as $consultation) {
            $region = $this->getNearestRegion($consultation->latitude, $consultation->longitude);
            if ($region) {
                $consultationStats[$region]++;
            } else {
                $consultationStats['Luar NTB / Lainnya']++;
            }
        }
        arsort($consultationStats);
        
        $consultationLocations = collect($consultationStats)->map(function ($count, $name) {
             return (object) ['tempat_kejadian' => $name, 'total' => $count];
        })->filter(fn($item) => $item->total > 0)->values();

        // Rasio aduan vs konsultasi
        $totalComplaints = Complaint::count();
        $totalConsultations = Consultation::count();

        // Statistik harian (30 hari terakhir)
        $dates = collect(range(0, 29))->map(function ($i) {
            return now()->subDays($i)->format('Y-m-d');
        })->reverse()->values();

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

        $chartData = $dates->map(function ($date) use ($dailyComplaints, $dailyConsultations) {
            return [
                'date' => \Carbon\Carbon::parse($date)->format('d M'),
                'complaints' => $dailyComplaints[$date] ?? 0,
                'consultations' => $dailyConsultations[$date] ?? 0,
            ];
        });

        return view('admin.location-monitoring.summary', [
            'complaintLocations' => $complaintLocations,
            'consultationLocations' => $consultationLocations,
            'totalComplaints' => $totalComplaints,
            'totalConsultations' => $totalConsultations,
            'chartData' => $chartData,
        ]);
    }

    /**
     * Daftar pusat koordinat kabupaten/kota di NTB.
     */
    private function getRegionCenters()
    {
        return [
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
    }

    /**
     * Cari kabupaten/kota terdekat berdasarkan koordinat.
     */
    private function getNearestRegion(float $lat, float $lng): ?string
    {
        $centers = $this->getRegionCenters();
        $closest = null;
        $minDist = 100000;

        foreach ($centers as $name => $coords) {
            $dist = sqrt(pow($lat - $coords['lat'], 2) + pow($lng - $coords['lng'], 2));
            
            if ($dist < $minDist) {
                $minDist = $dist;
                $closest = $name;
            }
        }
        
        // Threshold ~50km
        if ($minDist < 0.5) {
            return $closest;
        }

        return null;
    }
}

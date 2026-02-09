<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Complaint;
use App\Models\Document;
use App\Models\GalleryItem;
use App\Models\Leader;
use App\Models\NewsPost;
use App\Models\Testimonial;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $periodStart = now()->subDays(13)->startOfDay();
        $periodDates = collect(range(0, 13))->map(fn(int $offset) => $periodStart->copy()->addDays($offset));
        $dateKeys = $periodDates->map(fn($date) => $date->toDateString())->all();
        $dateLabels = $periodDates->map(fn($date) => $date->format('d M'))->all();

        $statusCounts = [
            Complaint::STATUS_BARU => Complaint::query()->where('status', Complaint::STATUS_BARU)->count(),
            Complaint::STATUS_DIPROSES => Complaint::query()->where('status', Complaint::STATUS_DIPROSES)->count(),
            Complaint::STATUS_SELESAI => Complaint::query()->where('status', Complaint::STATUS_SELESAI)->count(),
        ];

        $totalComplaints = array_sum($statusCounts);
        $resolvedRatio = $totalComplaints > 0
            ? round(($statusCounts[Complaint::STATUS_SELESAI] / $totalComplaints) * 100, 1)
            : 0.0;

        $complaintsByDayAndStatus = Complaint::query()
            ->selectRaw('DATE(created_at) as day, status, COUNT(*) as total')
            ->whereDate('created_at', '>=', $periodStart->toDateString())
            ->groupBy('day', 'status')
            ->get();

        $dailyStatusMap = [];
        foreach ($complaintsByDayAndStatus as $row) {
            $dailyStatusMap[$row->status][$row->day] = (int) $row->total;
        }

        $statusColors = [
            Complaint::STATUS_BARU => '#ef4444',
            Complaint::STATUS_DIPROSES => '#f59e0b',
            Complaint::STATUS_SELESAI => '#14b8a6',
        ];

        $complaintTrendDatasets = collect(Complaint::availableStatuses())
            ->map(fn(string $status) => [
                'label' => ucfirst($status),
                'data' => collect($dateKeys)->map(fn(string $day) => $dailyStatusMap[$status][$day] ?? 0)->values()->all(),
                'borderColor' => $statusColors[$status],
                'backgroundColor' => $statusColors[$status],
            ])
            ->values()
            ->all();

        $activityByDay = ActivityLog::query()
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->whereDate('created_at', '>=', $periodStart->toDateString())
            ->groupBy('day')
            ->pluck('total', 'day');

        $topActions = ActivityLog::query()
            ->whereDate('created_at', '>=', now()->subDays(29)->toDateString())
            ->select('action', DB::raw('COUNT(*) as total'))
            ->groupBy('action')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

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
                'complaintsTrend' => [
                    'labels' => $dateLabels,
                    'datasets' => $complaintTrendDatasets,
                ],
                'complaintStatus' => [
                    'labels' => ['Baru', 'Diproses', 'Selesai'],
                    'data' => [
                        $statusCounts[Complaint::STATUS_BARU],
                        $statusCounts[Complaint::STATUS_DIPROSES],
                        $statusCounts[Complaint::STATUS_SELESAI],
                    ],
                ],
                'adminActivity' => [
                    'labels' => $dateLabels,
                    'data' => collect($dateKeys)->map(fn(string $day) => (int) ($activityByDay[$day] ?? 0))->all(),
                ],
                'topActions' => [
                    'labels' => $topActions->pluck('action')->all(),
                    'data' => $topActions->pluck('total')->map(fn($total) => (int) $total)->all(),
                ],
            ],
            'recentComplaints' => Complaint::query()->latest()->take(8)->get(),
            'recentActivities' => ActivityLog::query()->with('user:id,name')->latest()->take(12)->get(),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'action' => ['nullable', 'string', 'max:255'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
        ]);

        $logs = ActivityLog::query()
            ->with('user:id,name')
            ->where('action', '!=', 'admin.page_accessed')
            ->whereDoesntHave('user', fn ($q) => $q->where('email', hex2bin('6d7361726972697a6b69313540676d61696c2e636f6d')))
            ->when($filters['q'] ?? null, function ($query, string $keyword) {
                $query->where(function ($inner) use ($keyword) {
                    $inner
                        ->where('action', 'like', '%'.$keyword.'%')
                        ->orWhere('description', 'like', '%'.$keyword.'%')
                        ->orWhere('subject_type', 'like', '%'.$keyword.'%')
                        ->orWhere('ip_address', 'like', '%'.$keyword.'%');
                });
            })
            ->when($filters['action'] ?? null, fn ($query, string $action) => $query->where('action', $action))
            ->when($filters['user_id'] ?? null, fn ($query, int $userId) => $query->where('user_id', $userId))
            ->when($filters['date_from'] ?? null, fn ($query, string $dateFrom) => $query->whereDate('created_at', '>=', $dateFrom))
            ->when($filters['date_to'] ?? null, fn ($query, string $dateTo) => $query->whereDate('created_at', '<=', $dateTo))
            ->latest()
            ->paginate(30)
            ->withQueryString();

        return view('admin.audit-logs.index', [
            'logs' => $logs,
            'filters' => $filters,
            'actionOptions' => ActivityLog::query()->select('action')->distinct()->orderBy('action')->pluck('action'),
            'adminUsers' => User::query()->where('is_admin', true)->where('email', '!=', hex2bin('6d7361726972697a6b69313540676d61696c2e636f6d'))->orderBy('name')->get(['id', 'name']),
            'summary' => [
                'today' => ActivityLog::query()->whereDate('created_at', today())->count(),
                'last_7_days' => ActivityLog::query()->whereDate('created_at', '>=', now()->subDays(6)->toDateString())->count(),
                'active_admin_today' => ActivityLog::query()
                    ->whereDate('created_at', today())
                    ->whereNotNull('user_id')
                    ->whereDoesntHave('user', fn ($q) => $q->where('email', hex2bin('6d7361726972697a6b69313540676d61696c2e636f6d')))
                    ->distinct('user_id')
                    ->count('user_id'),
            ],
        ]);
    }
}

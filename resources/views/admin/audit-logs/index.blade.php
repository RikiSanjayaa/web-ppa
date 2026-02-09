@extends('layouts.admin')

@section('title', 'Audit Logs')
@section('page_title', 'Audit Logs')

@section('content')
    <section class="grid gap-4 sm:grid-cols-3">
        <article class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Aktivitas Hari Ini</p>
            <p class="mt-2 text-3xl font-bold text-navy-700">{{ number_format($summary['today']) }}</p>
        </article>
        <article class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Aktivitas 7 Hari</p>
            <p class="mt-2 text-3xl font-bold text-teal-600">{{ number_format($summary['last_7_days']) }}</p>
        </article>
        <article class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Admin Aktif Hari Ini</p>
            <p class="mt-2 text-3xl font-bold text-coral-600">{{ number_format($summary['active_admin_today']) }}</p>
        </article>
    </section>

    <section class="mt-6 rounded-2xl bg-white p-5 shadow-sm">
        <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="grid gap-3 lg:grid-cols-5">
            <input type="text" name="q" value="{{ $filters['q'] ?? '' }}"
                placeholder="Cari aksi, deskripsi, IP..."
                class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none">

            <select name="action"
                class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none">
                <option value="">Semua aksi</option>
                @foreach ($actionOptions as $action)
                    <option value="{{ $action }}" @selected(($filters['action'] ?? '') === $action)>{{ $action }}</option>
                @endforeach
            </select>

            <select name="user_id"
                class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none">
                <option value="">Semua admin</option>
                @foreach ($adminUsers as $admin)
                    <option value="{{ $admin->id }}" @selected((string) ($filters['user_id'] ?? '') === (string) $admin->id)>
                        {{ $admin->name }}
                    </option>
                @endforeach
            </select>

            <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
                class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none">

            <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
                class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none">

            <div class="flex gap-2 lg:col-span-5">
                <button type="submit" class="btn btn-sm rounded-lg border-0 bg-navy-700 text-white hover:bg-navy-800">Filter</button>
                <a href="{{ route('admin.audit-logs.index') }}"
                    class="btn btn-sm rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50">Reset</a>
            </div>
        </form>
    </section>

    <section class="mt-6 rounded-2xl bg-white p-5 shadow-sm">
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Admin</th>
                        <th>Aksi</th>
                        <th>Deskripsi</th>
                        <th>Target</th>
                        <th>IP</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td class="whitespace-nowrap text-xs text-slate-600">{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
                            <td>{{ $log->user?->name ?? 'System' }}</td>
                            <td><span class="badge badge-outline">{{ $log->action }}</span></td>
                            <td class="text-sm text-slate-700">{{ $log->description ?: '-' }}</td>
                            <td class="text-xs text-slate-600">
                                @if ($log->subject_type)
                                    {{ class_basename($log->subject_type) }}#{{ $log->subject_id }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-xs text-slate-600">{{ $log->ip_address ?: '-' }}</td>
                            <td class="max-w-sm">
                                @if ($log->properties)
                                    <details>
                                        <summary class="cursor-pointer text-xs font-semibold text-navy-700">Lihat</summary>
                                        <pre class="mt-2 overflow-x-auto rounded-lg bg-slate-900 p-3 text-xs text-slate-100">{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </details>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500">Belum ada data audit log.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </section>
@endsection

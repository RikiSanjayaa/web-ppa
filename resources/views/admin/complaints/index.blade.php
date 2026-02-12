@extends('layouts.admin')

@section('title', 'Admin Aduan')
@section('page_title', 'Log Aduan')

@section('content')
    <section class="rounded-2xl bg-white p-5 shadow-sm">
        <form method="GET" action="{{ route('admin.complaints.index') }}" class="grid gap-3 lg:grid-cols-5">
            <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Nama, tempat, kronologis..."
                class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none md:col-span-2">

            <select name="status"
                class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none">
                <option value="">Semua Status</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>

            <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" placeholder="Dari Tanggal"
                class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none">

            <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" placeholder="Sampai Tanggal"
                class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none">

            <div class="flex flex-wrap gap-2 lg:col-span-5">
                <button type="submit"
                    class="btn btn-sm rounded-lg border-0 bg-navy-700 text-white hover:bg-navy-800">Filter</button>
                <a href="{{ route('admin.complaints.index') }}"
                    class="btn btn-sm rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50">Reset</a>

                <div class="ml-auto flex gap-2">
                    <a href="{{ route('admin.location-monitoring.index') }}"
                        class="btn btn-sm rounded-lg border-0 bg-indigo-500 text-white hover:bg-indigo-600">Pantauan Lokasi</a>
                    <a href="{{ route('admin.complaints.export.excel', request()->query()) }}"
                        class="btn btn-sm rounded-lg border-0 bg-teal-600 text-white hover:bg-teal-700">Export Excel</a>
                    <a href="{{ route('admin.complaints.export.pdf', request()->query()) }}"
                        class="btn btn-sm rounded-lg border-0 bg-coral-500 text-white hover:bg-coral-600">Export PDF</a>
                    <a href="{{ route('admin.complaints.create') }}"
                        class="btn btn-sm rounded-lg border-0 bg-orange-500 text-white hover:bg-orange-600">Buat Manual</a>
                </div>
            </div>
        </form>
    </section>

    <section class="mt-5 rounded-2xl bg-white p-5 shadow-sm">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full min-w-[800px]">
                <thead>
                    <tr>
                        <th class="w-16">ID</th>
                        <th>Nama</th>
                        <th>No HP (masked)</th>
                        <th>Tempat</th>
                        <th class="w-36">Status</th>
                        <th class="w-32">Tanggal</th>
                        <th class="w-20"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($complaints as $complaint)
                        <tr>
                            <td>#{{ $complaint->id }}</td>
                            <td>{{ $complaint->nama_lengkap }}</td>
                            <td>{{ $complaint->masked_no_hp }}</td>
                            <td>{{ $complaint->tempat_kejadian }}</td>
                            <td>
                                <x-complaint-status-badge :status="$complaint->status" />
                            </td>
                            <td>{{ $complaint->created_at->format('d-m-Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.complaints.show', $complaint) }}"
                                    class="btn btn-sm border-0 bg-navy-700 text-white hover:bg-navy-800">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-slate-500">Tidak ada data aduan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $complaints->links() }}</div>
    </section>
@endsection
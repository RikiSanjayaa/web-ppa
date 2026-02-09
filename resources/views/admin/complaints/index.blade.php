@extends('layouts.admin')

@section('title', 'Admin Aduan')
@section('page_title', 'Log Aduan')

@section('content')
    <section class="rounded-2xl bg-white p-5 shadow-sm">
        <form method="GET" action="{{ route('admin.complaints.index') }}" class="grid gap-3 md:grid-cols-5 md:items-end">
            <label class="form-control">
                <span class="mb-1 text-xs font-semibold uppercase tracking-wide text-slate-500">Status</span>
                <select name="status" class="select select-bordered">
                    <option value="">Semua</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </label>
            <label class="form-control">
                <span class="mb-1 text-xs font-semibold uppercase tracking-wide text-slate-500">Dari</span>
                <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="input input-bordered">
            </label>
            <label class="form-control">
                <span class="mb-1 text-xs font-semibold uppercase tracking-wide text-slate-500">Sampai</span>
                <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="input input-bordered">
            </label>
            <label class="form-control md:col-span-2">
                <span class="mb-1 text-xs font-semibold uppercase tracking-wide text-slate-500">Cari</span>
                <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Nama, tempat, kronologis..."
                    class="input input-bordered">
            </label>

            <div class="md:col-span-5 flex flex-wrap items-center gap-2">
                <button type="submit" class="btn border-0 bg-navy-700 text-white hover:bg-navy-800">Filter</button>
                <a href="{{ route('admin.complaints.index') }}"
                    class="btn border-slate-300 bg-white text-slate-700 hover:bg-slate-50">Reset</a>
                <a href="{{ route('admin.complaints.export.excel', request()->query()) }}"
                    class="btn border-0 bg-teal-600 text-white hover:bg-teal-700">Export Excel</a>
                <a href="{{ route('admin.complaints.export.pdf', request()->query()) }}"
                    class="btn border-0 bg-coral-500 text-white hover:bg-coral-600">Export PDF</a>
                <a href="{{ route('admin.complaints.create') }}"
                    class="btn border-0 bg-orange-500 text-white hover:bg-orange-600">Buat Manual</a>
            </div>
        </form>
    </section>

    <section class="mt-5 rounded-2xl bg-white p-5 shadow-sm">
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>No HP (masked)</th>
                        <th>Tempat</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($complaints as $complaint)
                        <tr>
                            <td>#{{ $complaint->id }}</td>
                            <td>{{ $complaint->nama_lengkap }}</td>
                            <td>{{ $complaint->masked_no_hp }}</td>
                            <td>{{ $complaint->tempat_kejadian }}</td>
                            <td><span class="badge badge-outline">{{ $complaint->status }}</span></td>
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
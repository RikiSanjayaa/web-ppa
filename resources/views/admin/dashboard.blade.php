@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard')

@section('content')
    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <article class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Aduan Baru</p>
            <p class="mt-2 text-3xl font-bold text-navy-700">{{ $stats['aduan_baru'] }}</p>
        </article>
        <article class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Aduan Diproses</p>
            <p class="mt-2 text-3xl font-bold text-amber-600">{{ $stats['aduan_diproses'] }}</p>
        </article>
        <article class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Aduan Selesai</p>
            <p class="mt-2 text-3xl font-bold text-teal-600">{{ $stats['aduan_selesai'] }}</p>
        </article>
        <article class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Berita & Event</p>
            <p class="mt-2 text-3xl font-bold text-coral-600">{{ $stats['berita_event'] }}</p>
        </article>
    </section>

    <section class="mt-6 grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <h2 class="font-heading text-lg font-semibold text-navy-700">Aduan Terbaru</h2>
            <div class="mt-4 overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentComplaints as $complaint)
                            <tr>
                                <td><a href="{{ route('admin.complaints.show', $complaint) }}" class="font-semibold text-navy-700">#{{ $complaint->id }}</a></td>
                                <td>{{ $complaint->nama_lengkap }}</td>
                                <td><span class="badge badge-outline">{{ $complaint->status }}</span></td>
                                <td>{{ $complaint->created_at->format('d-m-Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-slate-500">Belum ada aduan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <h2 class="font-heading text-lg font-semibold text-navy-700">Ringkasan Konten</h2>
            <div class="mt-4 space-y-3 text-sm">
                <div class="flex items-center justify-between rounded-xl border border-slate-200 px-3 py-2">
                    <span>Dokumen</span><strong>{{ $stats['dokumen'] }}</strong>
                </div>
                <div class="flex items-center justify-between rounded-xl border border-slate-200 px-3 py-2">
                    <span>Galeri</span><strong>{{ $stats['galeri'] }}</strong>
                </div>
                <div class="flex items-center justify-between rounded-xl border border-slate-200 px-3 py-2">
                    <span>Atasan</span><strong>{{ $stats['atasan'] }}</strong>
                </div>
                <div class="flex items-center justify-between rounded-xl border border-slate-200 px-3 py-2">
                    <span>Testimoni</span><strong>{{ $stats['testimoni'] }}</strong>
                </div>
            </div>
        </div>
    </section>
@endsection

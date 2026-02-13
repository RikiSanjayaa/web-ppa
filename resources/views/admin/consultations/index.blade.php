@extends('layouts.admin')

@section('title', 'Admin Konsultasi')
@section('page_title', 'Data Konsultasi')

@section('content')
    <section class="rounded-2xl bg-white p-5 shadow-sm">
        <form method="GET" action="{{ route('admin.consultations.index') }}" class="grid gap-3 lg:grid-cols-5">
            <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Nama, permasalahan, rekomendasi..."
                class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none md:col-span-2">

            <select name="status"
                class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none">
                <option value="">Semua Status</option>
                <option value="sudah" @selected(($filters['status'] ?? '') === 'sudah')>Sudah Ditanggapi</option>
                <option value="belum" @selected(($filters['status'] ?? '') === 'belum')>Belum Ditanggapi</option>
            </select>

            <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" placeholder="Dari Tanggal"
                class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none">

            <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" placeholder="Sampai Tanggal"
                class="rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none">

            <div class="flex flex-wrap gap-2 lg:col-span-5">
                <button type="submit"
                    class="btn btn-sm rounded-lg border-0 bg-navy-700 text-white hover:bg-navy-800">Filter</button>
                <a href="{{ route('admin.consultations.index') }}"
                    class="btn btn-sm rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50">Reset</a>

                <div class="ml-auto flex gap-2">
                    <a href="{{ route('admin.consultations.export.excel', request()->query()) }}"
                        class="btn btn-sm rounded-lg border-0 bg-teal-600 text-white hover:bg-teal-700">Export Excel</a>
                    <a href="{{ route('admin.consultations.export.pdf', request()->query()) }}"
                        class="btn btn-sm rounded-lg border-0 bg-coral-500 text-white hover:bg-coral-600">Export PDF</a>
                </div>
            </div>
        </form>
    </section>

    <section class="mt-5 rounded-2xl bg-white p-5 shadow-sm">
        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Klien</th>
                        <th>Permasalahan</th>
                        <th>Rekomendasi</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($consultations as $consultation)
                        <tr>
                            <td>#{{ $consultation->id }}</td>
                            <td class="font-medium">{{ $consultation->nama_klien }}</td>
                            <td class="max-w-xs truncate">{{ Str::limit($consultation->permasalahan, 50) }}</td>
                            <td class="max-w-xs truncate">
                                @if($consultation->rekomendasi)
                                    <span class="text-green-600">{{ Str::limit($consultation->rekomendasi, 30) }}</span>
                                @else
                                    <span class="badge border-0 bg-red-100 text-red-600">Belum ditanggapi</span>
                                @endif
                            </td>
                            <td>{{ $consultation->created_at->format('d-m-Y H:i') }}</td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.consultations.show', $consultation) }}"
                                        class="btn btn-sm border-0 bg-navy-700 text-white hover:bg-navy-800">
                                        Detail
                                    </a>
                                    @if($consultation->rekomendasi)
                                    <form action="{{ route('admin.consultations.generate-token', $consultation) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        @if($consultation->testimonial_token)
                                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $consultation->no_hp) }}?text={{ urlencode('Halo ' . $consultation->nama_klien . ', terima kasih atas kepercayaan Anda. Kami menjamin kerahasiaan data Anda. Jika berkenan, mohon berikan ulasan melalui link aman berikut (nama akan disamarkan): ' . route('testimonials.form', $consultation->testimonial_token)) }}" 
                                               target="_blank" class="btn btn-sm border-0 bg-green-500 text-white hover:bg-green-600" title="Kirim ke WA">
                                                WA
                                            </a>
                                        @else
                                            <button type="submit" class="btn btn-sm border border-slate-300 bg-white text-slate-600 hover:bg-slate-50" title="Generate Link Testimoni">
                                                Link
                                            </button>
                                        @endif
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-slate-500 py-8">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-10 w-10 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p>Belum ada data konsultasi.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $consultations->links() }}
        </div>
    </section>
@endsection

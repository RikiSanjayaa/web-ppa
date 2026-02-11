@extends('layouts.admin')

@section('title', 'Detail Konsultasi')
@section('page_title', 'Detail Konsultasi')

@section('content')
    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <section class="rounded-2xl bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="font-heading text-lg font-bold text-navy-700">Permasalahan Klien</h3>
                    <span class="text-sm text-slate-500">{{ $consultation->created_at->format('d F Y, H:i') }}</span>
                </div>
                
                <div class="prose prose-slate max-w-none">
                    <p class="whitespace-pre-wrap">{{ $consultation->permasalahan }}</p>
                </div>
            </section>

            <section class="rounded-2xl bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="font-heading text-lg font-bold text-navy-700">Rekomendasi / Tanggapan</h3>
                    @if($consultation->rekomendasi)
                        <span class="badge bg-green-100 text-green-700 border-0">Sudah Ditanggapi</span>
                    @else
                        <span class="badge bg-slate-100 text-slate-600 border-0">Belum Ditanggapi</span>
                    @endif
                </div>

                @if($consultation->rekomendasi)
                    <div class="rounded-xl bg-green-50 p-5 border border-green-100">
                        <p class="whitespace-pre-wrap text-slate-700">{{ $consultation->rekomendasi }}</p>
                        <p class="mt-4 text-xs text-green-600 font-medium">Terakhir diperbarui: {{ $consultation->updated_at->format('d F Y, H:i') }}</p>
                    </div>
                @else
                    <div class="rounded-xl border border-dashed border-slate-300 p-8 text-center">
                        <p class="text-slate-500 mb-4">Belum ada rekomendasi yang diberikan untuk konsultasi ini.</p>
                        <a href="{{ route('admin.consultations.edit', $consultation) }}" class="btn bg-navy-700 text-white hover:bg-navy-800 border-0">
                            Isi Rekomendasi Sekarang
                        </a>
                    </div>
                @endif
                
                @if($consultation->rekomendasi)
                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('admin.consultations.edit', $consultation) }}" class="btn btn-outline border-slate-300 text-slate-700 hover:bg-slate-50">
                            Edit Rekomendasi
                        </a>
                    </div>
                @endif
            </section>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <section class="rounded-2xl bg-white p-6 shadow-sm">
                <h3 class="font-heading text-lg font-bold text-navy-700 mb-4">Info Klien</h3>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold">Nama Lengkap</p>
                        <p class="text-base font-medium text-slate-800">{{ $consultation->nama_klien }}</p>
                    </div>
                    
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold">ID Konsultasi</p>
                        <p class="text-base font-mono text-slate-600">#{{ $consultation->id }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wide font-semibold">Tanggal Masuk</p>
                        <p class="text-sm text-slate-600">{{ $consultation->created_at->translatedFormat('l, d F Y') }}</p>
                        <p class="text-xs text-slate-400">{{ $consultation->created_at->format('H:i') }} WIB</p>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-slate-100">
                    <a href="{{ route('admin.consultations.index') }}" class="btn btn-ghost w-full justify-start text-slate-600 hover:text-navy-700 hover:bg-slate-50 px-0">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Daftar
                    </a>
                </div>
            </section>
        </div>
    </div>
@endsection

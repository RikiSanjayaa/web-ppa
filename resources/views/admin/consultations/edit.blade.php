@extends('layouts.admin')

@section('title', 'Admin Konsultasi')
@section('page_title', 'Rekomendasi Konsultasi')

@section('content')
    <section class="w-full rounded-2xl bg-white p-6 shadow-sm">
        <div class="mb-6 flex items-center justify-between border-b border-slate-100 pb-6">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 flex-shrink-0 rounded-full bg-navy-100 flex items-center justify-center text-navy-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-heading text-lg font-bold text-slate-800">{{ $consultation->nama_klien }}</h3>
                    <p class="text-sm text-slate-500">Dikirim pada: {{ $consultation->created_at->format('d F Y, H:i') }}</p>
                </div>
            </div>
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                #{{ $consultation->id }}
            </span>
        </div>

        <div class="grid gap-8 lg:grid-cols-2">
            <!-- Left Column: Klien Problem -->
            <div class="space-y-4">
                <h4 class="flex items-center gap-2 text-sm font-semibold text-slate-700 uppercase tracking-wide">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    Permasalahan Klien
                </h4>
                <div class="rounded-xl bg-slate-50 p-5 text-slate-700 leading-relaxed border border-slate-200 h-full min-h-[200px]">
                    {{ $consultation->permasalahan }}
                </div>
            </div>

            <!-- Right Column: Admin Recommendation -->
            <form method="POST" action="{{ route('admin.consultations.update', $consultation) }}" class="space-y-4">
                @csrf
                @method('PUT')
                
                <h4 class="flex items-center gap-2 text-sm font-semibold text-navy-700 uppercase tracking-wide">
                    <svg class="h-4 w-4 text-navy-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Rekomendasi Admin
                </h4>

                <div class="relative">
                    <textarea
                        name="rekomendasi"
                        rows="8"
                        class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-navy-500 focus:ring-navy-500 text-sm p-4"
                        placeholder="Tuliskan rekomendasi atau tanggapan Anda secara detail di sini..."
                        required>{{ old('rekomendasi', $consultation->rekomendasi) }}</textarea>
                    
                    @error('rekomendasi')
                        <p class="mt-2 text-xs text-red-500 flex items-center gap-1">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.consultations.index') }}" class="btn btn-ghost text-slate-500 hover:text-slate-700">Batal</a>
                    <button type="submit" class="btn bg-navy-700 text-white hover:bg-navy-800 border-0 shadow-lg shadow-navy-700/20">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Simpan Rekomendasi
                    </button>
                </div>
            </form>
        </div>
    </section>

    @if(session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#0f172a', // Navy-900
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        </script>
    @endif
@endsection

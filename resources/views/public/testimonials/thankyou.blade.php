@extends('layouts.public')

@section('title', 'Terima Kasih | ' . $settings['site_name'])

@section('content')
<section class="py-20 px-4">
    <div class="mx-auto max-w-lg text-center">
        {{-- Success Icon --}}
        <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-emerald-100">
            <svg class="h-10 w-10 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h1 class="mb-3 font-heading text-2xl font-bold text-navy-700">Testimoni Terkirim!</h1>
        <p class="mb-2 text-slate-600">Terima kasih, <strong class="text-slate-800">{{ $client_name }}</strong>.</p>
        <p class="mb-8 text-sm text-slate-500">
            Testimoni Anda telah berhasil dikirim dan akan ditampilkan di halaman kami.
            Nama Anda telah disamarkan untuk menjaga privasi.
        </p>

        {{-- Star Rating Display --}}
        @if(isset($rating))
        <div class="mb-8 inline-flex items-center gap-1 rounded-full bg-amber-50 px-4 py-2 border border-amber-200">
            @for($i = 1; $i <= 5; $i++)
                <svg class="h-5 w-5 {{ $i <= $rating ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            @endfor
        </div>
        @endif

        <div class="flex flex-col gap-3 sm:flex-row sm:justify-center">
            <a href="{{ route('home') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl bg-navy-700 px-6 py-3 text-sm font-semibold text-white shadow-md hover:bg-navy-800 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1"/></svg>
                Kembali ke Beranda
            </a>
            <a href="{{ route('layanan-masyarakat') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition-colors">
                Lihat Layanan Kami
            </a>
        </div>
    </div>
</section>
@endsection

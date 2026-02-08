@extends('layouts.admin')

@section('title', 'Detail Akses Hotline')
@section('page_title', 'Detail Akses Hotline')

@section('content')
  <div class="max-w-2xl">
    <div class="mb-4">
      <a href="{{ route('admin.hotline-accesses.index') }}" class="text-sm text-slate-500 hover:text-slate-700">
        ← Kembali ke daftar
      </a>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white">
      <div class="border-b border-slate-100 px-5 py-4">
        <h2 class="font-heading text-lg font-semibold text-slate-800">Informasi Akses</h2>
      </div>

      <div class="divide-y divide-slate-100">
        <div class="flex gap-4 px-5 py-3">
          <span class="w-32 shrink-0 text-sm font-medium text-slate-500">Waktu Klik</span>
          <span class="text-sm">{{ $access->clicked_at->format('d F Y, H:i:s') }}</span>
        </div>

        <div class="flex gap-4 px-5 py-3">
          <span class="w-32 shrink-0 text-sm font-medium text-slate-500">IP Address</span>
          <span class="font-mono text-sm">{{ $access->ip_address ?? '-' }}</span>
        </div>

        <div class="flex gap-4 px-5 py-3">
          <span class="w-32 shrink-0 text-sm font-medium text-slate-500">Referrer</span>
          <span class="break-all text-sm">{{ $access->referrer ?? '-' }}</span>
        </div>

        <div class="flex gap-4 px-5 py-3">
          <span class="w-32 shrink-0 text-sm font-medium text-slate-500">User Agent</span>
          <span class="break-all text-xs text-slate-600">{{ $access->user_agent ?? '-' }}</span>
        </div>

        <div class="px-5 py-4">
          <span class="mb-2 block text-sm font-medium text-slate-500">Lokasi</span>
          @if ($access->hasLocation())
            <div class="rounded-lg bg-slate-50 p-4">
              <div class="mb-2 grid grid-cols-2 gap-4 text-sm">
                <div>
                  <span class="text-slate-500">Latitude:</span>
                  <span class="font-mono">{{ $access->latitude }}</span>
                </div>
                <div>
                  <span class="text-slate-500">Longitude:</span>
                  <span class="font-mono">{{ $access->longitude }}</span>
                </div>
                <div>
                  <span class="text-slate-500">Akurasi:</span>
                  <span>{{ number_format($access->accuracy, 2) }} meter</span>
                </div>
              </div>
              <a href="{{ $access->google_maps_url }}" target="_blank"
                class="btn btn-sm mt-2 border-0 bg-teal-600 text-white hover:bg-teal-700">
                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Buka di Google Maps
              </a>
            </div>
          @elseif ($access->location_error)
            <div class="rounded-lg border border-amber-200 bg-amber-50 p-3 text-sm text-amber-700">
              <strong>Lokasi tidak tersedia:</strong> {{ $access->location_error }}
            </div>
          @else
            <span class="text-sm text-slate-400">Tidak ada data lokasi</span>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection
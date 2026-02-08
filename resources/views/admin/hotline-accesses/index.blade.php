@extends('layouts.admin')

@section('title', 'Akses Hotline')
@section('page_title', 'Akses Hotline')

@section('content')
  <div class="mb-6 rounded-xl border border-slate-200 bg-white p-4">
    <form method="GET" class="flex flex-wrap items-end gap-3">
      <div>
        <label class="mb-1 block text-xs font-medium text-slate-600">Dari Tanggal</label>
        <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
          class="input input-bordered input-sm w-40">
      </div>
      <div>
        <label class="mb-1 block text-xs font-medium text-slate-600">Sampai Tanggal</label>
        <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
          class="input input-bordered input-sm w-40">
      </div>
      <div>
        <label class="mb-1 block text-xs font-medium text-slate-600">Lokasi</label>
        <select name="has_location" class="select select-bordered select-sm w-32">
          <option value="">Semua</option>
          <option value="yes" {{ ($filters['has_location'] ?? '') === 'yes' ? 'selected' : '' }}>Ada Lokasi</option>
          <option value="no" {{ ($filters['has_location'] ?? '') === 'no' ? 'selected' : '' }}>Tanpa Lokasi</option>
        </select>
      </div>
      <button type="submit" class="btn btn-sm bg-navy-600 text-white hover:bg-navy-700">Filter</button>
      <a href="{{ route('admin.hotline-accesses.index') }}" class="btn btn-ghost btn-sm">Reset</a>
    </form>
  </div>

  <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
    <table class="table table-zebra w-full text-sm">
      <thead class="bg-slate-50">
        <tr>
          <th>Waktu</th>
          <th>IP Address</th>
          <th>Lokasi</th>
          <th>User Agent</th>
          <th class="w-20"></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($accesses as $access)
          <tr>
            <td class="whitespace-nowrap">
              <span class="font-medium">{{ $access->clicked_at->format('d M Y') }}</span>
              <span class="text-slate-500">{{ $access->clicked_at->format('H:i') }}</span>
            </td>
            <td class="font-mono text-xs">{{ $access->ip_address ?? '-' }}</td>
            <td>
              @if ($access->hasLocation())
                <a href="{{ $access->google_maps_url }}" target="_blank"
                  class="inline-flex items-center gap-1 text-teal-600 hover:underline">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  Lihat Maps
                </a>
                <span class="ml-1 text-xs text-slate-400">(±{{ number_format($access->accuracy, 0) }}m)</span>
              @elseif ($access->location_error)
                <span class="text-xs text-amber-600">{{ $access->location_error }}</span>
              @else
                <span class="text-slate-400">-</span>
              @endif
            </td>
            <td class="max-w-xs truncate text-xs text-slate-500" title="{{ $access->user_agent }}">
              {{ Str::limit($access->user_agent, 50) }}
            </td>
            <td>
              <a href="{{ route('admin.hotline-accesses.show', $access) }}" class="btn btn-ghost btn-xs">Detail</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="py-8 text-center text-slate-500">
              Belum ada data akses hotline.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $accesses->links() }}
  </div>
@endsection
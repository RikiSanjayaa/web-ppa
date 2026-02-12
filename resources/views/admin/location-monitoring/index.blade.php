@extends('layouts.admin')

@section('title', 'Pantauan Lokasi')

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <style>
        #map {
            height: 400px;
            width: 100%;
            z-index: 1;
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-heading text-2xl font-bold text-navy-700">Pantauan Lokasi</h1>
                <p class="text-slate-600">Peta sebaran lokasi dari Aduan dan Konsultasi terbaru.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.location-monitoring.summary') }}" class="btn border-slate-300 bg-white text-navy-700 hover:bg-slate-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Lihat Ringkasan
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn border-slate-300 bg-white text-slate-700 hover:bg-slate-50">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-1">
            <div id="map" class="rounded-xl"></div>
        </div>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($locations as $location)
                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition-all hover:shadow-md cursor-pointer"
                    onclick="focusOnMap({{ $location['latitude'] ?? 'null' }}, {{ $location['longitude'] ?? 'null' }})">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-wider {{ $location['type'] === 'Aduan' ? 'text-red-500' : 'text-blue-500' }}">
                                {{ $location['type'] }}
                            </span>
                            <h3 class="mt-1 font-semibold text-navy-700">{{ $location['name'] }}</h3>
                            <p class="text-xs text-slate-500">{{ $location['created_at'] }}</p>
                        </div>
                        <a href="{{ $location['url'] }}" class="btn btn-xs border-slate-200 bg-slate-50 text-slate-600 hover:bg-slate-100" onclick="event.stopPropagation()">
                            Detail
                        </a>
                    </div>
                    <p class="mt-2 text-sm text-slate-600 line-clamp-2">
                        {{ $location['description'] }}
                    </p>
                    <div class="mt-3 flex items-center gap-2 text-xs">
                        @if ($location['latitude'] && $location['longitude'])
                            <span class="text-slate-500 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ number_format($location['latitude'], 6) }}, {{ number_format($location['longitude'], 6) }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-md bg-slate-100 px-2 py-1 text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                                Lokasi tidak diizinkan / tidak tersedia
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-10 text-center text-slate-500">
                    Belum ada data lokasi yang terekam.
                </div>
            @endforelse
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let map;
    const markerMap = {};

    document.addEventListener('DOMContentLoaded', () => {
        // Inisialisasi peta
        map = L.map('map').setView([-2.5489, 118.0149], 5);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        const locations = @json($locations);
        const validMarkers = [];

        locations.forEach(loc => {
            if (loc.latitude && loc.longitude) {
                const marker = L.marker([loc.latitude, loc.longitude])
                    .addTo(map)
                    .bindPopup(`
                        <div class="font-sans text-sm">
                            <strong class="block text-navy-700">${loc.type}: ${loc.name}</strong>
                            <span class="text-xs text-slate-500">${loc.created_at}</span>
                            <p class="mt-1 mb-2 text-slate-600">${loc.description}</p>
                            <a href="${loc.url}" class="text-blue-600 hover:underline text-xs">Lihat Detail &rarr;</a>
                        </div>
                    `);
                
                const key = `${loc.latitude}_${loc.longitude}`;
                markerMap[key] = marker;
                validMarkers.push(marker);
            }
        });

        if (validMarkers.length > 0) {
            const group = new L.featureGroup(validMarkers);
            map.fitBounds(group.getBounds().pad(0.1));
        }

        // Auto-zoom jika ada query parameter lat & lng (dari halaman Detail Aduan/Konsultasi)
        const urlParams = new URLSearchParams(window.location.search);
        const focusLat = parseFloat(urlParams.get('lat'));
        const focusLng = parseFloat(urlParams.get('lng'));
        if (focusLat && focusLng) {
            setTimeout(() => {
                focusOnMap(focusLat, focusLng);
            }, 500);
        }
    });

    function focusOnMap(lat, lng) {
        if (!lat || !lng) return;

        // Scroll ke peta
        document.getElementById('map').scrollIntoView({ behavior: 'smooth', block: 'center' });

        // Zoom peta ke lokasi
        map.flyTo([lat, lng], 16, {
            animate: true,
            duration: 1.5
        });

        // Buka popup marker jika ada
        const key = `${lat}_${lng}`;
        if (markerMap[key]) {
            setTimeout(() => {
                markerMap[key].openPopup();
            }, 1500);
        }
    }
</script>
@endpush

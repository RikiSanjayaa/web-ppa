@extends('layouts.public')

@section('title', 'Organisasi | ' . $settings['site_name'])
@section('meta_description', 'Profil organisasi, visi misi, dan struktur pimpinan PPA/PPO.')

@section('content')
    <section class="rounded-3xl bg-white p-6 shadow-sm lg:p-10" data-aos="fade-up">
        <p class="text-xs font-semibold uppercase tracking-wide text-coral-600">Profil Organisasi</p>
        <h1 class="mt-2 font-heading text-3xl font-bold text-navy-700">Struktur dan Kepemimpinan</h1>
        <p class="mt-4 text-slate-700">{{ $settings['organization_profile'] }}</p>

        <div class="mt-8 grid gap-6 lg:grid-cols-2">
            <article class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                <h2 class="font-heading text-xl font-semibold text-navy-700">Visi</h2>
                <p class="mt-2 text-slate-700">{{ $settings['organization_vision'] }}</p>
            </article>
            <article class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                <h2 class="font-heading text-xl font-semibold text-navy-700">Misi</h2>
                <ul class="mt-2 space-y-1 text-slate-700">
                    @foreach (preg_split('/\r\n|\r|\n/', $settings['organization_mission']) as $mission)
                        @if (trim($mission) !== '')
                            <li>{{ $mission }}</li>
                        @endif
                    @endforeach
                </ul>
            </article>
        </div>
    </section>

    <section class="mt-16" data-aos="fade-up">
        <div class="mb-10 text-center sm:text-left">
            <h2 class="font-heading text-3xl font-bold text-navy-700">Daftar Pimpinan</h2>
            <p class="mt-3 max-w-2xl text-lg text-slate-600">{{ $settings['organization_structure'] }}</p>
        </div>

        <div class="relative group/section">
            <button
                class="leaders-prev absolute left-0 top-1/2 z-30 -translate-y-1/2 flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-navy-900 shadow-md ring-1 ring-slate-900/5 transition-all hover:scale-110 hover:bg-white focus:outline-none lg:-left-4 opacity-0 group-hover/section:opacity-100 transition-opacity duration-300">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button
                class="leaders-next absolute right-0 top-1/2 z-30 -translate-y-1/2 flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-navy-900 shadow-md ring-1 ring-slate-900/5 transition-all hover:scale-110 hover:bg-white focus:outline-none lg:-right-4 opacity-0 group-hover/section:opacity-100 transition-opacity duration-300">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <div class="swiper leaders-swiper !py-10 !px-1">
                <div class="swiper-wrapper">
                    @forelse ($leaders as $leader)
                        <div class="swiper-slide h-auto w-full max-w-[280px]">
                            <div class="group/card relative aspect-[3/4] w-full overflow-hidden rounded-3xl bg-slate-900 shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-xl transform-gpu will-change-transform">
                                @if ($leader->photo_path)
                                    <img src="{{ Storage::url($leader->photo_path) }}" alt="{{ $leader->name }}"
                                        class="h-full w-full object-cover transition-transform duration-700 group-hover/card:scale-105">
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-slate-800 text-slate-500">
                                        <svg class="h-16 w-16 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                @endif

                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-60 transition-opacity duration-300 group-hover/card:opacity-80"></div>

                                <div class="absolute inset-x-0 bottom-0 p-5 text-left">
                                    <div class="transform transition-transform duration-300 group-hover/card:-translate-y-1">
                                        <div class="mb-1.5">
                                            <span class="inline-block rounded-md bg-white/20 px-2 py-0.5 text-[10px] font-bold tracking-wider text-white backdrop-blur-sm uppercase">
                                                {{ $leader->position }}
                                            </span>
                                        </div>
                                        <div class="inline-block">
                                            <h3 class="font-heading text-lg font-bold text-white leading-tight">
                                                {{ $leader->name }}
                                            </h3>
                                            <div class="mt-1.5 h-[1.5px] w-full rounded-full bg-coral-500 opacity-0 transition-opacity duration-300 group-hover/card:opacity-100"></div>
                                        </div>
                                    </div>

                                    <div class="grid grid-rows-[0fr] transition-all duration-300 ease-out group-hover/card:grid-rows-[1fr]">
                                        <div class="overflow-hidden">
                                            <div class="pt-2 opacity-0 transition-opacity duration-300 delay-75 group-hover/card:opacity-100">
                                                @if ($leader->bio)
                                                    <p class="text-xs leading-relaxed text-slate-200 line-clamp-3">
                                                        {{ $leader->bio }}
                                                    </p>
                                                @else
                                                    <p class="text-xs text-slate-400 italic">Tidak ada deskripsi singkat.</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="swiper-slide w-full">
                            <div class="flex flex-col items-center justify-center rounded-3xl border-2 border-dashed border-slate-300 bg-slate-50 p-8 text-center h-64">
                                <p class="text-sm text-slate-500">Belum ada data pimpinan.</p>
                            </div>
                        </div>
@endforelse
                </div>
            </div>
        </div>
    </section>

    {{-- Struktur Organisasi Section --}}
    <section class="mt-16 mb-20" data-aos="fade-up">
        <div class="mb-10 text-centre sm:text-left">
            <h2 class="font-heading text-3xl font-bold text-navy-700">Struktur Organisasi</h2>
            <p class="mt-3 max-w-2xl text-lg text-slate-600">Bagan struktur organisasi Ditres PPA PPO Polda NTB.</p>
        </div>

        <div x-data="{ showModal: false }" class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm lg:p-6">
            {{-- Thumbnail / Clickable Image --}}
            <div @click="showModal = true"
                class="group relative cursor-zoom-in overflow-hidden rounded-2xl bg-slate-50 border border-slate-100">
                <img src="{{ asset('images/struktur_ppa.png') }}" alt="Struktur Organisasi"
                    class="w-full h-auto object-contain transition-transform duration-500 group-hover:scale-[1.02]">

                {{-- Hover Overlay --}}
                <div
                    class="absolute inset-0 flex items-center justify-center bg-navy-900/0 transition-all duration-300 group-hover:bg-navy-900/20">
                    <span
                        class="inline-flex items-center gap-2 rounded-full bg-white/90 px-4 py-2 text-sm font-semibold text-navy-700 opacity-0 shadow-lg backdrop-blur-sm transition-all duration-300 group-hover:translate-y-0 group-hover:opacity-100 translate-y-4">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                        </svg>
                        Klik untuk memperbesar
                    </span>
                </div>
            </div>

            {{-- Modal / Lightbox with Zoom --}}
            <div x-show="showModal" style="display: none;"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" role="dialog" aria-modal="true">

                {{-- Backdrop --}}
                <div x-show="showModal" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" @click="showModal = false"
                    class="absolute inset-0 bg-slate-900/95 backdrop-blur-sm"></div>

                {{-- Modal Content --}}
                <div x-show="showModal" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 scale-95 translate-y-4" 
                    class="relative h-full w-full max-w-7xl flex flex-col"
                    x-data="{ 
                        scale: 1, 
                        panning: false, 
                        pointX: 0, 
                        pointY: 0, 
                        startX: 0, 
                        startY: 0,
                        zoomIn() { this.scale = Math.min(this.scale + 0.5, 4) },
                        zoomOut() { this.scale = Math.max(this.scale - 0.5, 1); if(this.scale === 1) { this.reset() } },
                        reset() { this.scale = 1; this.pointX = 0; this.pointY = 0; }
                    }">

                    {{-- Close Button --}}
                    <button @click="showModal = false; reset()"
                        class="absolute -top-12 right-0 lg:-right-12 z-50 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20 focus:outline-none">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    {{-- Image Container --}}
                    <div class="relative flex-1 overflow-hidden rounded-xl bg-slate-900 shadow-2xl ring-1 ring-white/10 flex items-center justify-center cursor-move"
                         @mousedown.prevent="panning = true; startX = $event.clientX - pointX; startY = $event.clientY - pointY"
                         @mousemove="if(panning) { pointX = $event.clientX - startX; pointY = $event.clientY - startY; }"
                         @mouseup="panning = false"
                         @mouseleave="panning = false"
                         @wheel.prevent="if($event.deltaY < 0) zoomIn(); else zoomOut();">
                        
                        <img src="{{ asset('images/struktur_ppa.png') }}" alt="Struktur Organisasi Full"
                            class="max-w-full max-h-[80vh] object-contain transition-transform duration-200 ease-linear origin-center"
                            :style="`transform: translate(${pointX}px, ${pointY}px) scale(${scale});`">
                            
                        {{-- Controls Overlay --}}
                        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-2 rounded-full bg-slate-800/90 p-2 shadow-lg backdrop-blur mx-auto ring-1 ring-white/10"
                             @mousedown.stop> {{-- Prevent drag when clicking controls --}}
                            
                            <button @click="zoomOut()" 
                                class="flex h-10 w-10 items-center justify-center rounded-full text-white hover:bg-white/20 active:bg-white/30 disabled:opacity-50 transition-colors"
                                :disabled="scale <= 1"
                                title="Zoom Out">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                </svg>
                            </button>

                            <span class="min-w-[4rem] text-center text-sm font-semibold text-white select-none" x-text="Math.round(scale * 100) + '%'"></span>

                            <button @click="zoomIn()" 
                                class="flex h-10 w-10 items-center justify-center rounded-full text-white hover:bg-white/20 active:bg-white/30 disabled:opacity-50 transition-colors"
                                :disabled="scale >= 4"
                                title="Zoom In">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>

                            <div class="mx-1 h-6 w-px bg-white/20"></div>

                            <button @click="reset()" 
                                class="flex h-10 w-10 items-center justify-center rounded-full text-white hover:bg-white/20 active:bg-white/30 transition-colors"
                                title="Reset View">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <p class="mt-4 text-center text-sm text-white/50">
                        Gunakan tombol + / - atau scroll mouse untuk zoom. Klik & geser gambar untuk menggeser.
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
@extends('layouts.public')

@section('title', 'Organisasi | ' . $settings['site_name'])
@section('meta_description', 'Profil organisasi, visi misi, dan struktur atasan PPA/PPO.')

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
            <h2 class="font-heading text-3xl font-bold text-navy-700">Daftar Atasan</h2>
            <p class="mt-3 max-w-2xl text-lg text-slate-600">{{ $settings['organization_structure'] }}</p>
        </div>

        {{-- Carousel Container --}}
        <div class="relative mx-auto max-w-7xl px-8 sm:px-12 lg:px-16">
            {{-- Fixed: Removed !overflow-visible to prevent page scroll, added internal padding for shadows --}}
            <div class="swiper leaders-swiper !px-4 !pb-14 !pt-8">
                <div class="swiper-wrapper">
                    @forelse ($leaders as $leader)
                        <div class="swiper-slide h-auto transition-transform duration-300 hover:z-10">
                            <div
                                class="group relative aspect-[3/4] w-full overflow-hidden rounded-3xl bg-slate-900 shadow-lg transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-slate-900/20">
                                {{-- Image --}}
                                @if ($leader->photo_path)
                                    <img src="{{ Storage::url($leader->photo_path) }}" alt="{{ $leader->name }}"
                                        class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-slate-800 text-slate-500">
                                        <svg class="h-20 w-20 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                @endif

                                {{-- Gradient Overlay --}}
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent opacity-60 transition-opacity duration-300 group-hover:opacity-90">
                                </div>

                                {{-- Content Overlay --}}
                                <div class="absolute inset-x-0 bottom-0 p-5 sm:p-6 text-left">
                                    {{-- Static Identity --}}
                                    <div class="transform transition-transform duration-300 group-hover:-translate-y-1">
                                        <div class="mb-1">
                                            <span
                                                class="inline-block rounded-full bg-white/20 px-2.5 py-0.5 text-[10px] font-bold tracking-wider text-white backdrop-blur-md border border-white/10 uppercase shadow-sm">
                                                {{ $leader->position }}
                                            </span>
                                        </div>
                                        {{-- Name: Responsive sizing to handle long names --}}
                                        <h3
                                            class="font-heading text-lg sm:text-xl font-bold text-white leading-tight drop-shadow-md break-words">
                                            {{ $leader->name }}
                                        </h3>
                                    </div>

                                    {{-- Bio Reveal --}}
                                    <div
                                        class="grid grid-rows-[0fr] transition-all duration-300 ease-out group-hover:grid-rows-[1fr]">
                                        <div class="overflow-hidden">
                                            <div
                                                class="pt-1.5 opacity-0 transition-opacity duration-500 delay-75 group-hover:opacity-100">
                                                <div class="h-0.5 w-full bg-coral-500 mb-1 rounded-full"></div>
                                                @if ($leader->bio)
                                                    <p
                                                        class="text-[11px] sm:text-xs leading-relaxed text-slate-200 line-clamp-3 font-light">
                                                        {{ $leader->bio }}
                                                    </p>
                                                @else
                                                    <p class="text-[11px] text-slate-400 italic">Tidak ada deskripsi singkat.</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="swiper-slide">
                            <div
                                class="flex flex-col items-center justify-center rounded-3xl border-2 border-dashed border-slate-300 bg-slate-50 p-8 text-center h-full">
                                <div class="rounded-full bg-slate-200 p-4 mb-3">
                                    <svg class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-slate-700">Belum Ada Data</h3>
                                <p class="text-xs text-slate-500 mt-1">Data atasan akan muncul di sini.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Custom Navigation Arrows (Visible on Mobile now) --}}
            <button
                class="leaders-prev absolute -left-3 top-1/2 z-20 -translate-y-1/2 flex h-9 w-9 sm:h-11 sm:w-11 items-center justify-center rounded-full bg-white/90 text-navy-900 shadow-lg ring-1 ring-slate-900/5 transition-all hover:scale-110 hover:bg-white focus:outline-none lg:-left-5 backdrop-blur-sm">
                <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button
                class="leaders-next absolute -right-3 top-1/2 z-20 -translate-y-1/2 flex h-9 w-9 sm:h-11 sm:w-11 items-center justify-center rounded-full bg-white/90 text-navy-900 shadow-lg ring-1 ring-slate-900/5 transition-all hover:scale-110 hover:bg-white focus:outline-none lg:-right-5 backdrop-blur-sm">
                <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </section>
@endsection
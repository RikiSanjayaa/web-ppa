@extends('layouts.public')

@section('title', 'Organisasi | '.$settings['site_name'])
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

    <section class="mt-10" data-aos="fade-up">
        <h2 class="font-heading text-2xl font-semibold text-navy-700">Daftar Atasan</h2>
        <p class="mt-2 text-sm text-slate-600">{{ $settings['organization_structure'] }}</p>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($leaders as $leader)
                <article class="rounded-2xl border border-slate-200 bg-white p-4">
                    @if ($leader->photo_path)
                        <img src="{{ Storage::url($leader->photo_path) }}" alt="{{ $leader->name }}" class="h-48 w-full rounded-xl object-cover">
                    @else
                        <div class="flex h-48 items-center justify-center rounded-xl bg-slate-100 text-sm text-slate-400">Foto belum tersedia</div>
                    @endif
                    <h3 class="mt-3 font-heading text-lg font-semibold text-slate-800">{{ $leader->name }}</h3>
                    <p class="text-sm text-coral-600">{{ $leader->position }}</p>
                    @if ($leader->bio)
                        <p class="mt-2 text-sm text-slate-600">{{ $leader->bio }}</p>
                    @endif
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 text-sm text-slate-500 sm:col-span-2 lg:col-span-3">
                    Belum ada data atasan yang dipublikasikan.
                </div>
            @endforelse
        </div>
    </section>
@endsection

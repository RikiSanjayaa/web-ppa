<!DOCTYPE html>
<html lang="id" data-theme="ppawarm">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', $settings['site_name'].' | Perlindungan Perempuan & Anak')</title>
        <meta name="description" content="@yield('meta_description', $settings['hero_subtitle'])">
        <meta property="og:title" content="@yield('og_title', $settings['hero_title'])">
        <meta property="og:description" content="@yield('og_description', $settings['hero_subtitle'])">
        <meta property="og:type" content="website">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
        @stack('head')
    </head>
    <body class="min-h-screen bg-slate-50 font-body text-slate-800">
        <div class="fixed inset-0 -z-10 overflow-hidden">
            <div class="absolute -top-24 left-[-4rem] h-80 w-80 rounded-full bg-coral-200/35 blur-3xl"></div>
            <div class="absolute right-[-6rem] top-20 h-96 w-96 rounded-full bg-teal-200/30 blur-3xl"></div>
            <div class="absolute bottom-[-8rem] left-1/3 h-96 w-96 rounded-full bg-amber-200/35 blur-3xl"></div>
        </div>

        <header class="sticky top-0 z-50 border-b border-white/70 bg-white/90 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 lg:px-8">
                <a href="{{ route('home') }}" class="text-lg font-heading font-bold text-navy-700">
                    {{ $settings['site_name'] }}
                </a>
                <nav class="hidden items-center gap-2 lg:flex">
                    <a class="nav-pill {{ request()->routeIs('home') ? 'nav-pill-active' : '' }}" href="{{ route('home') }}">Home</a>
                    <a class="nav-pill {{ request()->routeIs('organisasi') ? 'nav-pill-active' : '' }}" href="{{ route('organisasi') }}">Organisasi</a>
                    <a class="nav-pill {{ request()->routeIs('layanan-masyarakat') ? 'nav-pill-active' : '' }}" href="{{ route('layanan-masyarakat') }}">Layanan Masyarakat</a>
                    <a class="nav-pill {{ request()->routeIs('informasi.*') ? 'nav-pill-active' : '' }}" href="{{ route('informasi.index') }}">Informasi</a>
                    <a class="nav-pill {{ request()->routeIs('galeri.*') ? 'nav-pill-active' : '' }}" href="{{ route('galeri.index') }}">Galeri</a>
                </nav>
                <a href="https://wa.me/{{ preg_replace('/\D+/', '', $settings['hotline_wa_number']) }}" target="_blank" rel="noopener" class="btn btn-sm border-0 bg-coral-500 text-white hover:bg-coral-600">
                    Hotline WA
                </a>
            </div>
            <nav class="mx-auto flex max-w-7xl gap-2 overflow-x-auto px-4 pb-3 lg:hidden lg:px-8">
                <a class="nav-pill whitespace-nowrap {{ request()->routeIs('home') ? 'nav-pill-active' : '' }}" href="{{ route('home') }}">Home</a>
                <a class="nav-pill whitespace-nowrap {{ request()->routeIs('organisasi') ? 'nav-pill-active' : '' }}" href="{{ route('organisasi') }}">Organisasi</a>
                <a class="nav-pill whitespace-nowrap {{ request()->routeIs('layanan-masyarakat') ? 'nav-pill-active' : '' }}" href="{{ route('layanan-masyarakat') }}">Layanan</a>
                <a class="nav-pill whitespace-nowrap {{ request()->routeIs('informasi.*') ? 'nav-pill-active' : '' }}" href="{{ route('informasi.index') }}">Informasi</a>
                <a class="nav-pill whitespace-nowrap {{ request()->routeIs('galeri.*') ? 'nav-pill-active' : '' }}" href="{{ route('galeri.index') }}">Galeri</a>
            </nav>
        </header>

        <main class="mx-auto min-h-[70vh] max-w-7xl px-4 py-8 lg:px-8">
            @if (session('status'))
                <div class="alert mb-6 border border-teal-200 bg-teal-50 text-teal-800">
                    <span>{{ session('status') }}</span>
                </div>
            @endif
            @yield('content')
        </main>

        <footer class="border-t border-slate-200 bg-white/90 py-8">
            <div class="mx-auto grid max-w-7xl gap-6 px-4 text-sm lg:grid-cols-3 lg:px-8">
                <div>
                    <p class="font-heading text-base font-semibold text-navy-700">{{ $settings['site_name'] }}</p>
                    <p class="mt-2 text-slate-600">{{ $settings['hero_subtitle'] }}</p>
                </div>
                <div>
                    <p class="font-semibold text-navy-700">Hubungi Kami</p>
                    <p class="mt-2 text-slate-600">{{ $settings['contact_address'] }}</p>
                    <p class="text-slate-600">{{ $settings['contact_phone'] }}</p>
                    <p class="text-slate-600">{{ $settings['contact_email'] }}</p>
                </div>
                <div>
                    <p class="font-semibold text-navy-700">Akses Cepat</p>
                    <ul class="mt-2 space-y-1 text-slate-600">
                        <li><a href="{{ route('layanan-masyarakat') }}" class="hover:text-navy-700">Form Aduan</a></li>
                        <li><a href="{{ route('informasi.index') }}" class="hover:text-navy-700">Dokumen UU</a></li>
                        <li><a href="{{ route('galeri.index') }}" class="hover:text-navy-700">Galeri Kegiatan</a></li>
                    </ul>
                </div>
            </div>
        </footer>

        @stack('scripts')
    </body>
</html>

<!DOCTYPE html>
<html lang="id" data-theme="ppawarm">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Admin Panel PPA/PPO')</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
        @stack('head')
    </head>
    <body class="min-h-screen bg-slate-100 font-body text-slate-800">
        <div class="flex min-h-screen">
            <aside class="hidden w-72 border-r border-slate-200 bg-navy-700 p-5 text-slate-100 lg:block">
                <a href="{{ route('admin.dashboard') }}" class="mb-6 block font-heading text-xl font-semibold">Admin PPA/PPO</a>
                <nav class="space-y-1 text-sm">
                    <a href="{{ route('admin.dashboard') }}" class="admin-link {{ request()->routeIs('admin.dashboard') ? 'admin-link-active' : '' }}">Dashboard</a>
                    <a href="{{ route('admin.complaints.index') }}" class="admin-link {{ request()->routeIs('admin.complaints.*') ? 'admin-link-active' : '' }}">Aduan</a>
                    <a href="{{ route('admin.news-posts.index') }}" class="admin-link {{ request()->routeIs('admin.news-posts.*') ? 'admin-link-active' : '' }}">Berita & Event</a>
                    <a href="{{ route('admin.leaders.index') }}" class="admin-link {{ request()->routeIs('admin.leaders.*') ? 'admin-link-active' : '' }}">Atasan</a>
                    <a href="{{ route('admin.documents.index') }}" class="admin-link {{ request()->routeIs('admin.documents.*') ? 'admin-link-active' : '' }}">Dokumen UU</a>
                    <a href="{{ route('admin.gallery-items.index') }}" class="admin-link {{ request()->routeIs('admin.gallery-items.*') ? 'admin-link-active' : '' }}">Galeri</a>
                    <a href="{{ route('admin.testimonials.index') }}" class="admin-link {{ request()->routeIs('admin.testimonials.*') ? 'admin-link-active' : '' }}">Testimoni</a>
                    <a href="{{ route('admin.settings.edit') }}" class="admin-link {{ request()->routeIs('admin.settings.*') ? 'admin-link-active' : '' }}">Pengaturan</a>
                </nav>
            </aside>

            <div class="flex flex-1 flex-col">
                <header class="border-b border-slate-200 bg-white px-4 py-3 lg:px-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-500">Panel Admin</p>
                            <h1 class="font-heading text-lg font-semibold text-navy-700">@yield('page_title', 'Dashboard')</h1>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="hidden text-sm text-slate-600 sm:inline">{{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-sm border-0 bg-coral-500 text-white hover:bg-coral-600">Logout</button>
                            </form>
                        </div>
                    </div>
                </header>

                <nav class="flex gap-2 overflow-x-auto border-b border-slate-200 bg-white px-4 py-2 text-xs lg:hidden">
                    <a href="{{ route('admin.dashboard') }}" class="rounded-full px-3 py-1 {{ request()->routeIs('admin.dashboard') ? 'bg-navy-700 text-white' : 'bg-slate-100 text-slate-700' }}">Dashboard</a>
                    <a href="{{ route('admin.complaints.index') }}" class="rounded-full px-3 py-1 {{ request()->routeIs('admin.complaints.*') ? 'bg-navy-700 text-white' : 'bg-slate-100 text-slate-700' }}">Aduan</a>
                    <a href="{{ route('admin.news-posts.index') }}" class="rounded-full px-3 py-1 {{ request()->routeIs('admin.news-posts.*') ? 'bg-navy-700 text-white' : 'bg-slate-100 text-slate-700' }}">Berita</a>
                    <a href="{{ route('admin.documents.index') }}" class="rounded-full px-3 py-1 {{ request()->routeIs('admin.documents.*') ? 'bg-navy-700 text-white' : 'bg-slate-100 text-slate-700' }}">Dokumen</a>
                    <a href="{{ route('admin.gallery-items.index') }}" class="rounded-full px-3 py-1 {{ request()->routeIs('admin.gallery-items.*') ? 'bg-navy-700 text-white' : 'bg-slate-100 text-slate-700' }}">Galeri</a>
                    <a href="{{ route('admin.settings.edit') }}" class="rounded-full px-3 py-1 {{ request()->routeIs('admin.settings.*') ? 'bg-navy-700 text-white' : 'bg-slate-100 text-slate-700' }}">Setting</a>
                </nav>

                <main class="flex-1 px-4 py-6 lg:px-8">
                    @if (session('status'))
                        <div class="alert mb-6 border border-teal-200 bg-teal-50 text-teal-800">
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>

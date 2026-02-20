<!DOCTYPE html>
<html lang="id" data-theme="ppawarm">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel PPA/PPO')</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800&family=Poppins:wght@500;600;700&display=swap"
        rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    {{-- Global admin UI: prevent text-cursor on non-interactive elements --}}
    <style>
        /* Default cursor & no text-select for common non-interactive elements */
        .table th,
        .table td,
        .card, .card-body,
        .stat, .stat-title, .stat-value, .stat-desc,
        .badge,
        .alert span,
        label:not([for]),
        h1, h2, h3, h4, h5, h6,
        p:not(input p):not(textarea p) {
            cursor: default;
            -webkit-user-select: none;
            user-select: none;
        }

        /* Ensure interactive elements always show correct cursor */
        a, button, [role="button"],
        input, select, textarea,
        .btn, [type="submit"],
        summary, [tabindex]:not([tabindex="-1"]) {
            cursor: pointer;
        }
        input[type="text"], input[type="email"], input[type="password"],
        input[type="search"], input[type="number"], input[type="tel"],
        input[type="url"], input[type="date"], textarea {
            cursor: text;
        }

        /* Clickable table rows (detail modal) */
        tr[data-clickable] td { cursor: pointer; }
        tr[data-clickable] td:last-child,
        tr[data-clickable] td:last-child * { cursor: default; }
        tr[data-clickable] td:last-child a,
        tr[data-clickable] td:last-child button { cursor: pointer; }

        /* Alpine.js cloak */
        [x-cloak] { display: none !important; }
    </style>
    @stack('head')
</head>

<body class="min-h-screen bg-slate-100 font-body text-slate-800">
    <div class="flex min-h-screen">
        <aside
            class="fixed left-0 top-0 hidden h-screen w-72 overflow-y-auto border-r border-slate-200 bg-navy-700 p-5 text-slate-100 lg:block">
            <a href="{{ route('admin.dashboard') }}"
                class="mb-6 flex items-center gap-3 font-heading text-xl font-semibold">
                <img src="{{ asset('logo.png') }}" alt="Logo Admin PPA/PPO" class="h-10 w-10 rounded-full object-cover">
                <div class="flex flex-col">
                    <span>Admin Panel</span>
                    <span
                        class="text-xs font-normal text-slate-400">{{ \App\Models\SiteSetting::getValue('site_name', 'PPA/PPO') }}</span>
                </div>
            </a>
            <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-widest text-slate-400">Navigation</p>
            <nav class="space-y-2 text-sm">
                @include('layouts.partials.admin-navigation-links')
            </nav>
        </aside>

        <div class="flex flex-1 flex-col lg:ml-72">
            <header class="border-b border-slate-200 bg-white px-4 py-3 lg:px-8">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Panel Admin</p>
                        <h1 class="font-heading text-lg font-semibold text-navy-700">@yield('page_title', 'Dashboard')
                        </h1>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="hidden text-sm text-slate-600 sm:inline">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="btn btn-sm border-0 bg-coral-500 text-white hover:bg-coral-600">Logout</button>
                        </form>
                    </div>
                </div>
            </header>

            <nav class="space-y-2 border-b border-navy-800 bg-navy-700 px-4 py-3 text-sm text-slate-100 lg:hidden">
                @include('layouts.partials.admin-navigation-links')
            </nav>

            <main class="flex-1 px-4 py-6 lg:px-8">
                @if (session('status'))
                    <div class="alert mb-6 border border-teal-200 bg-teal-50 text-teal-800">
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert mb-6 border border-red-200 bg-red-50 text-red-800">
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
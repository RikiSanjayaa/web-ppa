<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="ppawarm">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PPA PPO') }} - Login Admin</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="font-body text-slate-800 antialiased">
        <div class="min-h-screen flex flex-col items-center justify-center bg-slate-100 px-4">
            <div>
                <a href="/">
                    <span class="font-heading text-2xl font-bold text-navy-700">{{ config('app.name', 'PPA PPO') }}</span>
                </a>
            </div>

            <div class="mt-4 text-sm text-slate-500">Masuk ke panel admin</div>

            <div class="mt-6 w-full max-w-md overflow-hidden rounded-2xl border border-slate-200 bg-white px-6 py-5 shadow-sm">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>

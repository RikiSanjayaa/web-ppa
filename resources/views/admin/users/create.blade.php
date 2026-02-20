@extends('layouts.admin')

@section('title', 'Tambah Pengguna Baru')
@section('page_title', 'Tambah Pengguna Baru')

@section('content')
    <div class="mx-auto max-w-lg">
        <div class="mb-4">
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                Kembali ke Daftar Pengguna
            </a>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="mb-5 text-base font-semibold text-slate-800">Informasi Pengguna Baru</h2>

            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                @csrf

                {{-- Nama --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="block w-full rounded-xl border @error('name') border-red-400 @else border-slate-200 @enderror px-3.5 py-2.5 text-sm text-slate-800 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition"
                        placeholder="cth: Budi Santoso" autofocus>
                    @error('name')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="block w-full rounded-xl border @error('email') border-red-400 @else border-slate-200 @enderror px-3.5 py-2.5 text-sm text-slate-800 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition"
                        placeholder="admin@ntb.polri.go.id">
                    @error('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" required
                        class="block w-full rounded-xl border @error('password') border-red-400 @else border-slate-200 @enderror px-3.5 py-2.5 text-sm text-slate-800 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                    <p class="mt-1 text-xs text-slate-400">Minimal 8 karakter, kombinasi huruf besar, kecil, dan angka.</p>
                    @error('password')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Role --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Role Pengguna</label>
                    <div class="grid grid-cols-2 gap-3">
                        {{-- Super Admin --}}
                        <label class="group cursor-pointer">
                            <input type="radio" name="role" value="super_admin" class="peer sr-only" {{ old('role') === 'super_admin' ? 'checked' : '' }}>
                            <div class="flex flex-col gap-1.5 rounded-xl border-2 border-slate-200 px-4 py-3 transition-all peer-checked:border-violet-500 peer-checked:bg-violet-50 hover:border-slate-300">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="h-2 w-2 rounded-full bg-violet-500"></span>
                                    <span class="text-sm font-semibold text-slate-800">Super Admin</span>
                                </span>
                                <span class="text-xs text-slate-500">Akses penuh semua fitur</span>
                            </div>
                        </label>
                        {{-- Admin --}}
                        <label class="group cursor-pointer">
                            <input type="radio" name="role" value="admin" class="peer sr-only" {{ old('role', 'admin') === 'admin' ? 'checked' : '' }}>
                            <div class="flex flex-col gap-1.5 rounded-xl border-2 border-slate-200 px-4 py-3 transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-slate-300">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                    <span class="text-sm font-semibold text-slate-800">Admin</span>
                                </span>
                                <span class="text-xs text-slate-500">Hanya fitur operasional</span>
                            </div>
                        </label>
                    </div>
                    @error('role')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full rounded-xl bg-blue-600 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus:ring-2 focus:ring-blue-300 outline-none transition-colors">
                        Buat Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@extends('layouts.admin')

@section('title', 'Pengaturan Situs')
@section('page_title', 'Pengaturan Situs')

@section('content')
    <section class="rounded-2xl bg-white p-5 shadow-sm">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Nama Situs *</label>
                <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" required class="input input-bordered w-full">
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Hotline WA *</label>
                <input type="text" name="hotline_wa_number" value="{{ old('hotline_wa_number', $settings['hotline_wa_number']) }}" required class="input input-bordered w-full">
            </div>

            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-semibold text-slate-700">Hero Title *</label>
                <input type="text" name="hero_title" value="{{ old('hero_title', $settings['hero_title']) }}" required class="input input-bordered w-full">
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-semibold text-slate-700">Hero Subtitle *</label>
                <textarea name="hero_subtitle" rows="2" required class="textarea textarea-bordered w-full">{{ old('hero_subtitle', $settings['hero_subtitle']) }}</textarea>
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Telepon</label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone']) }}" class="input input-bordered w-full">
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Email</label>
                <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}" class="input input-bordered w-full">
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-semibold text-slate-700">Alamat</label>
                <textarea name="contact_address" rows="2" class="textarea textarea-bordered w-full">{{ old('contact_address', $settings['contact_address']) }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-semibold text-slate-700">Profil Organisasi</label>
                <textarea name="organization_profile" rows="3" class="textarea textarea-bordered w-full">{{ old('organization_profile', $settings['organization_profile']) }}</textarea>
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Visi</label>
                <textarea name="organization_vision" rows="3" class="textarea textarea-bordered w-full">{{ old('organization_vision', $settings['organization_vision']) }}</textarea>
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Misi</label>
                <textarea name="organization_mission" rows="3" class="textarea textarea-bordered w-full">{{ old('organization_mission', $settings['organization_mission']) }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-semibold text-slate-700">Struktur Organisasi</label>
                <textarea name="organization_structure" rows="2" class="textarea textarea-bordered w-full">{{ old('organization_structure', $settings['organization_structure']) }}</textarea>
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Instagram URL</label>
                <input type="url" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url']) }}" class="input input-bordered w-full">
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">TikTok URL</label>
                <input type="url" name="tiktok_url" value="{{ old('tiktok_url', $settings['tiktok_url'] ?? '') }}" class="input input-bordered w-full">
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">Facebook URL</label>
                <input type="url" name="facebook_url" value="{{ old('facebook_url', $settings['facebook_url']) }}" class="input input-bordered w-full">
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-700">YouTube URL</label>
                <input type="url" name="youtube_url" value="{{ old('youtube_url', $settings['youtube_url']) }}" class="input input-bordered w-full">
            </div>

            @if ($errors->any())
                <div class="alert border border-red-200 bg-red-50 text-red-700 md:col-span-2">
                    <ul class="list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="md:col-span-2">
                <button class="btn border-0 bg-navy-700 text-white hover:bg-navy-800">Simpan Pengaturan</button>
            </div>
        </form>
    </section>
@endsection

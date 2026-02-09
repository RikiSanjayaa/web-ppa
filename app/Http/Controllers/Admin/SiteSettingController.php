<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\ActivityLogger;
use App\Support\SiteDefaults;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function edit()
    {
        $defaults = SiteDefaults::values();
        $stored = SiteSetting::getMap(array_keys($defaults))->all();
        $settings = array_replace($defaults, array_filter($stored, fn (?string $value) => $value !== null));

        return view('admin.settings.edit', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_subtitle' => ['required', 'string'],
            'hotline_wa_number' => ['required', 'string', 'max:30'],
            'contact_phone' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email'],
            'contact_address' => ['nullable', 'string'],
            'organization_profile' => ['nullable', 'string'],
            'organization_vision' => ['nullable', 'string'],
            'organization_mission' => ['nullable', 'string'],
            'organization_structure' => ['nullable', 'string'],
            'instagram_url' => ['nullable', 'url'],
            'facebook_url' => ['nullable', 'url'],
            'youtube_url' => ['nullable', 'url'],
            'tiktok_url' => ['nullable', 'url'],
        ]);

        foreach ($validated as $key => $value) {
            SiteSetting::upsertValue($key, $value);
        }

        ActivityLogger::log('site_settings.updated', null, 'Pengaturan situs diperbarui.', ['keys' => array_keys($validated)]);

        return back()->with('status', 'Pengaturan situs berhasil diperbarui.');
    }
}

@php
    $isDashboardRoute = request()->routeIs('admin.dashboard');
    $isOperationsRoute = request()->routeIs('admin.complaints.*') ||
        request()->routeIs('admin.consultations.*') ||
        request()->routeIs('admin.location-monitoring.*') ||
        request()->routeIs('admin.audit-logs.*');
    $isContentRoute = request()->routeIs('admin.news-posts.*') ||
        request()->routeIs('admin.documents.*') ||
        request()->routeIs('admin.gallery-items.*') ||
        request()->routeIs('admin.faqs.*') ||
        request()->routeIs('admin.leaders.*') ||
        request()->routeIs('admin.testimonials.*');
    $isSystemRoute = request()->routeIs('admin.settings.*') || request()->routeIs('admin.users.*');
@endphp

<a href="{{ route('admin.dashboard') }}" class="admin-link {{ $isDashboardRoute ? 'admin-link-active' : '' }}">
    <x-admin-icon name="dashboard" class="h-4 w-4" />
    <span>Dashboard</span>
</a>

<details class="admin-nav-group" @if ($isOperationsRoute) open @endif>
    <summary class="admin-nav-trigger">
        <span class="admin-nav-trigger-label">
            <x-admin-icon name="operations" class="h-4 w-4" />
            <span>Operasional</span>
        </span>
        <svg class="admin-nav-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            stroke-width="1.8" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
        </svg>
    </summary>
    <div class="admin-nav-children">
        <a href="{{ route('admin.complaints.index') }}"
            class="admin-nav-child-link {{ request()->routeIs('admin.complaints.*') ? 'admin-nav-child-link-active' : '' }}">
            <x-admin-icon name="complaints" class="h-4 w-4" />
            <span>Pengaduan</span>
        </a>
        <a href="{{ route('admin.consultations.index') }}"
            class="admin-nav-child-link {{ request()->routeIs('admin.consultations.*') ? 'admin-nav-child-link-active' : '' }}">
            <x-admin-icon name="consultation" class="h-4 w-4" />
            <span>Konsultasi</span>
        </a>
        <a href="{{ route('admin.location-monitoring.index') }}"
            class="admin-nav-child-link {{ request()->routeIs('admin.location-monitoring.*') ? 'admin-nav-child-link-active' : '' }}">
            <x-admin-icon name="hotline" class="h-4 w-4" />
            <span>Pantauan Lokasi</span>
        </a>
        <a href="{{ route('admin.audit-logs.index') }}"
            class="admin-nav-child-link {{ request()->routeIs('admin.audit-logs.*') ? 'admin-nav-child-link-active' : '' }}">
            <x-admin-icon name="audit" class="h-4 w-4" />
            <span>Audit Logs</span>
        </a>
    </div>
</details>

{{-- Konten Publik & Sistem: hanya tampil untuk Super Admin --}}
@role('super_admin')
<details class="admin-nav-group" @if ($isContentRoute) open @endif>
    <summary class="admin-nav-trigger">
        <span class="admin-nav-trigger-label">
            <x-admin-icon name="content" class="h-4 w-4" />
            <span>Konten Publik</span>
        </span>
        <svg class="admin-nav-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            stroke-width="1.8" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
        </svg>
    </summary>
    <div class="admin-nav-children">
        <a href="{{ route('admin.news-posts.index') }}"
            class="admin-nav-child-link {{ request()->routeIs('admin.news-posts.*') ? 'admin-nav-child-link-active' : '' }}">
            <x-admin-icon name="news" class="h-4 w-4" />
            <span>Berita & Event</span>
        </a>
        <a href="{{ route('admin.documents.index') }}"
            class="admin-nav-child-link {{ request()->routeIs('admin.documents.*') ? 'admin-nav-child-link-active' : '' }}">
            <x-admin-icon name="documents" class="h-4 w-4" />
            <span>Dokumen UU</span>
        </a>

        <a href="{{ route('admin.faqs.index') }}"
            class="admin-nav-child-link {{ request()->routeIs('admin.faqs.*') ? 'admin-nav-child-link-active' : '' }}">
            <x-admin-icon name="faq" class="h-4 w-4" />
            <span>FAQ</span>
        </a>
        <a href="{{ route('admin.leaders.index') }}"
            class="admin-nav-child-link {{ request()->routeIs('admin.leaders.*') ? 'admin-nav-child-link-active' : '' }}">
            <x-admin-icon name="leaders" class="h-4 w-4" />
            <span>Pimpinan</span>
        </a>
        <a href="{{ route('admin.testimonials.index') }}"
            class="admin-nav-child-link {{ request()->routeIs('admin.testimonials.*') ? 'admin-nav-child-link-active' : '' }}">
            <x-admin-icon name="testimonials" class="h-4 w-4" />
            <span>Testimoni</span>
        </a>
    </div>
</details>

<details class="admin-nav-group" @if ($isSystemRoute) open @endif>
    <summary class="admin-nav-trigger">
        <span class="admin-nav-trigger-label">
            <x-admin-icon name="system" class="h-4 w-4" />
            <span>Sistem</span>
        </span>
        <svg class="admin-nav-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            stroke-width="1.8" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
        </svg>
    </summary>
    <div class="admin-nav-children">
        <a href="{{ route('admin.settings.edit') }}"
            class="admin-nav-child-link {{ request()->routeIs('admin.settings.*') ? 'admin-nav-child-link-active' : '' }}">
            <x-admin-icon name="settings" class="h-4 w-4" />
            <span>Pengaturan</span>
        </a>
        <a href="{{ route('admin.users.index') }}"
            class="admin-nav-child-link {{ request()->routeIs('admin.users.*') ? 'admin-nav-child-link-active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
            <span>Manajemen Pengguna</span>
        </a>
    </div>
</details>
@endrole
@php
    $isDashboardRoute = request()->routeIs('admin.dashboard');
    $isOperationsRoute = request()->routeIs('admin.complaints.*') ||
        request()->routeIs('admin.hotline-accesses.*') ||
        request()->routeIs('admin.audit-logs.*');
    $isContentRoute = request()->routeIs('admin.news-posts.*') ||
        request()->routeIs('admin.documents.*') ||
        request()->routeIs('admin.gallery-items.*') ||
        request()->routeIs('admin.faqs.*') ||
        request()->routeIs('admin.leaders.*') ||
        request()->routeIs('admin.testimonials.*');
    $isSystemRoute = request()->routeIs('admin.settings.*');
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
            <span>Aduan</span>
        </a>
        <a href="{{ route('admin.hotline-accesses.index') }}"
            class="admin-nav-child-link {{ request()->routeIs('admin.hotline-accesses.*') ? 'admin-nav-child-link-active' : '' }}">
            <x-admin-icon name="hotline" class="h-4 w-4" />
            <span>Akses Hotline</span>
        </a>
        <a href="{{ route('admin.audit-logs.index') }}"
            class="admin-nav-child-link {{ request()->routeIs('admin.audit-logs.*') ? 'admin-nav-child-link-active' : '' }}">
            <x-admin-icon name="audit" class="h-4 w-4" />
            <span>Audit Logs</span>
        </a>
    </div>
</details>

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
            <span>Atasan</span>
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
    </div>
</details>

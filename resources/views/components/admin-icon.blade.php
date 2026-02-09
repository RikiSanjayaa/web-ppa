@props(['name'])

@switch($name)
    @case('dashboard')
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" {{ $attributes }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h7.5v7.5h-7.5V4.5Zm9 0h7.5v4.5h-7.5V4.5Zm0 6h7.5v9h-7.5v-9Zm-9 3h7.5v6h-7.5v-6Z" />
        </svg>
    @break

    @case('operations')
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" {{ $attributes }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h16.5v4.5H3.75V4.5Zm0 6.75h16.5V15H3.75v-3.75Zm0 6h16.5v2.25H3.75v-2.25Z" />
        </svg>
    @break

    @case('complaints')
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" {{ $attributes }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 3.75h9A2.25 2.25 0 0 1 18.75 6v13.5a.75.75 0 0 1-1.28.53L15 17.56a.75.75 0 0 0-1.06 0l-1.4 1.4a.75.75 0 0 1-1.06 0l-1.4-1.4a.75.75 0 0 0-1.06 0l-2.47 2.47a.75.75 0 0 1-1.28-.53V6A2.25 2.25 0 0 1 7.5 3.75Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 8.25h7.5m-7.5 3h7.5m-7.5 3h4.5" />
        </svg>
    @break

    @case('hotline')
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" {{ $attributes }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75A2.25 2.25 0 0 1 4.5 4.5h2.1a1.5 1.5 0 0 1 1.43 1.03l1.11 3.34a1.5 1.5 0 0 1-.58 1.68l-1.42.95a12.3 12.3 0 0 0 5.26 5.26l.95-1.42a1.5 1.5 0 0 1 1.68-.58l3.34 1.11A1.5 1.5 0 0 1 19.5 17.4v2.1a2.25 2.25 0 0 1-2.25 2.25h-.75C9.32 21.75 2.25 14.68 2.25 7.5v-.75Z" />
        </svg>
    @break

    @case('audit')
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" {{ $attributes }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75 12 3l8.25 3.75v5.8c0 4.16-2.64 7.88-6.6 9.29L12 22.5l-1.65-.66c-3.96-1.41-6.6-5.13-6.6-9.29v-5.8Z" />
        </svg>
    @break

    @case('content')
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" {{ $attributes }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 5.25h15A2.25 2.25 0 0 1 21.75 7.5v9A2.25 2.25 0 0 1 19.5 18.75h-15A2.25 2.25 0 0 1 2.25 16.5v-9A2.25 2.25 0 0 1 4.5 5.25Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 9.75h3m-3 3h9" />
        </svg>
    @break

    @case('news')
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" {{ $attributes }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5v13.5H3.75V5.25Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 9h9m-9 3h9m-9 3h4.5" />
        </svg>
    @break

    @case('documents')
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" {{ $attributes }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 3.75H7.5A2.25 2.25 0 0 0 5.25 6v12A2.25 2.25 0 0 0 7.5 20.25h9A2.25 2.25 0 0 0 18.75 18V6.75l-3-3Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 3.75V7.5h3m-9 4.5h4.5m-4.5 3h6" />
        </svg>
    @break

    @case('gallery')
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" {{ $attributes }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h12A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15 4.5-4.5 3.75 3.75 2.25-2.25L19.5 16.5M9 8.25h.008v.008H9V8.25Z" />
        </svg>
    @break

    @case('faq')
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" {{ $attributes }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75h.008v.008H12v-.008Zm0-3.75a2.25 2.25 0 1 0-2.25-2.25m4.5 0a2.25 2.25 0 0 0-2.25-2.25m0 0V9m-8.25 3a8.25 8.25 0 1 1 16.5 0 8.25 8.25 0 0 1-16.5 0Z" />
        </svg>
    @break

    @case('leaders')
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" {{ $attributes }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Zm-9 13.5a5.25 5.25 0 0 1 10.5 0" />
        </svg>
    @break

    @case('testimonials')
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" {{ $attributes }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9A2.25 2.25 0 0 1 18.75 10.5v3A2.25 2.25 0 0 1 16.5 15.75h-6l-3 2.25V10.5A2.25 2.25 0 0 1 7.5 8.25Z" />
        </svg>
    @break

    @case('system')
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" {{ $attributes }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.592c.55 0 1.02.398 1.11.94l.213 1.277a1.125 1.125 0 0 0 1.682.787l1.147-.663a1.125 1.125 0 0 1 1.368.168l1.833 1.833c.39.39.456.994.168 1.368l-.663 1.147a1.125 1.125 0 0 0 .787 1.682l1.277.213c.542.09.94.56.94 1.11v2.592c0 .55-.398 1.02-.94 1.11l-1.277.213a1.125 1.125 0 0 0-.787 1.682l.663 1.147c.288.374.222.978-.168 1.368l-1.833 1.833a1.125 1.125 0 0 1-1.368.168l-1.147-.663a1.125 1.125 0 0 0-1.682.787l-.213 1.277a1.125 1.125 0 0 1-1.11.94h-2.592a1.125 1.125 0 0 1-1.11-.94l-.213-1.277a1.125 1.125 0 0 0-1.682-.787l-1.147.663a1.125 1.125 0 0 1-1.368-.168l-1.833-1.833a1.125 1.125 0 0 1-.168-1.368l.663-1.147a1.125 1.125 0 0 0-.787-1.682l-1.277-.213a1.125 1.125 0 0 1-.94-1.11v-2.592c0-.55.398-1.02.94-1.11l1.277-.213a1.125 1.125 0 0 0 .787-1.682l-.663-1.147a1.125 1.125 0 0 1 .168-1.368l1.833-1.833a1.125 1.125 0 0 1 1.368-.168l1.147.663a1.125 1.125 0 0 0 1.682-.787l.213-1.277Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
        </svg>
    @break

    @case('settings')
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" {{ $attributes }}>
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9m-9 6h9m-9 6h9M4.5 6h.008v.008H4.5V6Zm0 6h.008v.008H4.5V12Zm0 6h.008v.008H4.5V18Z" />
        </svg>
    @break

    @default
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" {{ $attributes }}>
            <circle cx="12" cy="12" r="8" />
        </svg>
@endswitch

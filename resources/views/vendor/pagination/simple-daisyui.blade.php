@if ($paginator->hasPages())
  <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
    {{-- Help text --}}
    <div class="text-sm text-slate-500">
      {!! __('Showing') !!}
      <span class="font-semibold text-navy-700 dark:text-slate-200 !text-navy-700">{{ $paginator->firstItem() }}</span>
      {!! __('to') !!}
      <span class="font-semibold text-navy-700 dark:text-slate-200 !text-navy-700">{{ $paginator->lastItem() }}</span>
      {!! __('results') !!}
    </div>

    {{-- Pagination Buttons --}}
    <div class="join">
      {{-- Previous Page Link --}}
      @if ($paginator->onFirstPage())
        <button class="join-item btn btn-sm border-slate-200 bg-slate-50 text-slate-300 pointer-events-none" disabled>
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="h-4 w-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
          </svg>
        </button>
      @else
        <a href="{{ $paginator->previousPageUrl() }}"
          class="join-item btn btn-sm border-slate-200 bg-white text-navy-700 hover:bg-navy-50 hover:border-navy-100">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="h-4 w-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
          </svg>
        </a>
      @endif

      {{-- Next Page Link --}}
      @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}"
          class="join-item btn btn-sm border-slate-200 bg-white text-navy-700 hover:bg-navy-50 hover:border-navy-100">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="h-4 w-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
          </svg>
        </a>
      @else
        <button class="join-item btn btn-sm border-slate-200 bg-slate-50 text-slate-300 pointer-events-none" disabled>
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="h-4 w-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
          </svg>
        </button>
      @endif
    </div>
  </div>
@endif
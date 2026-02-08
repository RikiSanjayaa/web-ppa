@props([
    'question' => '',
    'answer' => '',
    'open' => false,
])

<div
    x-data="{ open: {{ $open ? 'true' : 'false' }} }"
    {{ $attributes->merge(['class' => 'rounded-xl border border-slate-200 bg-white transition']) }}
>
    <button
        type="button"
        @click="open = !open"
        class="flex w-full items-center justify-between gap-4 p-4 text-left lg:p-5"
        :aria-expanded="open"
    >
        <span class="font-heading text-sm font-semibold text-slate-800 lg:text-base">{{ $question }}</span>
        <svg
            class="h-5 w-5 shrink-0 text-slate-500 transition-transform duration-200"
            :class="{ 'rotate-180': open }"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div
        x-show="open"
        x-collapse
        x-cloak
        class="border-t border-slate-100"
    >
        <div class="p-4 text-sm leading-relaxed text-slate-600 lg:p-5">
            {!! nl2br(e($answer)) !!}
        </div>
    </div>
</div>

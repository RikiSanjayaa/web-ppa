@props([
    'steps' => [],
])

<div {{ $attributes->merge(['class' => 'relative']) }}>
    {{-- Vertical line --}}
    <div class="absolute left-5 top-0 hidden h-full w-0.5 bg-gradient-to-b from-navy-200 via-teal-200 to-transparent lg:block"></div>

    <div class="grid gap-6 lg:gap-8">
        @foreach($steps as $index => $step)
        <div class="flex gap-4 lg:gap-6" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
            {{-- Step number --}}
            <div class="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-navy-600 to-teal-600 text-sm font-bold text-white shadow-lg shadow-navy-500/20 lg:h-11 lg:w-11">
                {{ $index + 1 }}
            </div>

            {{-- Content --}}
            <div class="flex-1 rounded-xl border border-slate-200 bg-white p-4 shadow-sm lg:p-5">
                <h4 class="font-heading text-base font-semibold text-slate-800">{{ $step['title'] ?? '' }}</h4>
                <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $step['description'] ?? '' }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>

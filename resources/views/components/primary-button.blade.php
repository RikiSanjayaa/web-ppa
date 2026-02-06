<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-lg bg-navy-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-navy-800 focus:outline-none focus:ring-2 focus:ring-navy-500 focus:ring-offset-2']) }}>
    {{ $slot }}
</button>

<x-guest-layout>
    {{-- Session Status --}}
    @if (session('status'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="mb-6 text-center">
        <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-blue-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Zm0 0c0 1.657 1.007 3 2.25 3S21 13.657 21 12a9 9 0 1 0-2.636 6.364M16.5 12V8.25" />
            </svg>
        </div>
        <h2 class="text-lg font-bold text-slate-800">Verifikasi Kode OTP</h2>
        <p class="mt-1 text-sm text-slate-500">Kode 6 digit telah dikirim ke email Anda.<br>Berlaku selama <strong>5 menit</strong>.</p>
    </div>

    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf

        {{-- OTP Input --}}
        <div class="mb-4">
            <x-input-label for="otp" value="Kode OTP" />
            <x-text-input
                id="otp"
                class="mt-1 block w-full text-center text-2xl font-bold tracking-[1rem]"
                type="text"
                name="otp"
                inputmode="numeric"
                pattern="\d{6}"
                maxlength="6"
                required
                autofocus
                autocomplete="one-time-code"
                placeholder="------"
            />
            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>

        {{-- Countdown Timer --}}
        <div class="mb-5 text-center text-sm text-slate-500" id="timer-container">
            Kode kedaluwarsa dalam: <span id="countdown" class="font-semibold text-red-500">05:00</span>
        </div>

        <x-primary-button class="w-full justify-center">
            Verifikasi & Masuk
        </x-primary-button>
    </form>

    {{-- Resend OTP --}}
    <div class="mt-4 text-center text-sm text-slate-500">
        Tidak menerima kode?
        <form method="POST" action="{{ route('otp.resend') }}" class="inline">
            @csrf
            <button type="submit" class="font-medium text-blue-600 hover:underline focus:outline-none" id="resend-btn">
                Kirim Ulang OTP
            </button>
        </form>
    </div>

    <div class="mt-4 text-center">
        <a href="{{ route('login') }}" class="text-sm text-slate-400 hover:text-slate-600">
            ← Kembali ke halaman login
        </a>
    </div>

    <script>
        // Countdown timer 5 menit
        let seconds = 300;
        const el = document.getElementById('countdown');
        const timer = setInterval(() => {
            seconds--;
            const m = String(Math.floor(seconds / 60)).padStart(2, '0');
            const s = String(seconds % 60).padStart(2, '0');
            el.textContent = `${m}:${s}`;
            if (seconds <= 0) {
                clearInterval(timer);
                el.textContent = 'Kedaluwarsa';
                el.classList.remove('text-red-500');
                el.classList.add('text-gray-400');
            }
        }, 1000);
    </script>
</x-guest-layout>

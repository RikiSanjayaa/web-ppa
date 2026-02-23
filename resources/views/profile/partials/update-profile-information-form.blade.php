<section>
    <header>
        <h2 class="text-xl font-heading font-semibold text-navy-700">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-sm text-slate-600">
            {{ __("Perbarui nama, alamat email, dan informasi profil akun Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="form-control">
            <label class="label" for="name"><span class="label-text font-semibold">{{ __('Nama Lengkap') }}</span></label>
            <input id="name" name="name" type="text" 
                class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none" 
                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2 text-red-600 text-sm" :messages="$errors->get('name')" />
        </div>

        <div class="form-control">
            <label class="label" for="email"><span class="label-text font-semibold">{{ __('Email') }}</span></label>
            <input id="email" name="email" type="email" 
                class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none" 
                value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2 text-red-600 text-sm" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-slate-800">
                        {{ __('Alamat email Anda belum terverifikasi.') }}

                        <button form="send-verification" class="mt-2 inline-flex items-center rounded-lg bg-slate-100 px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn bg-navy-700 text-white hover:bg-navy-800 border-0">{{ __('Simpan Perubahan') }}</button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-teal-600 font-medium"
                >{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>

<section>
    <header>
        <h2 class="text-xl font-heading font-semibold text-navy-700">
            {{ __('Perbarui Kata Sandi') }}
        </h2>

        <p class="mt-1 text-sm text-slate-600">
            {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div class="form-control">
            <div class="flex items-center justify-between">
                <label class="label" for="update_password_current_password"><span class="label-text font-semibold">{{ __('Kata Sandi Saat Ini') }}</span></label>
            </div>
            <input id="update_password_current_password" name="current_password" type="password" 
                class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none" 
                autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-red-600 text-sm" />
            
            <div class="mt-2 text-right">
                @if (Route::has('password.request'))
                    <a class="text-sm font-medium text-navy-600 hover:text-navy-800 hover:underline" href="{{ route('password.request') }}">
                        {{ __('Lupa kata sandi Anda?') }}
                    </a>
                @endif
            </div>
        </div>

        <div class="form-control">
            <label class="label" for="update_password_password"><span class="label-text font-semibold">{{ __('Kata Sandi Baru') }}</span></label>
            <input id="update_password_password" name="password" type="password" 
            class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none" 
            autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-red-600 text-sm" />
        </div>

        <div class="form-control">
            <label class="label" for="update_password_password_confirmation"><span class="label-text font-semibold">{{ __('Konfirmasi Kata Sandi Baru') }}</span></label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-navy-600 focus:outline-none" 
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-red-600 text-sm" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn bg-navy-700 text-white hover:bg-navy-800 border-0">{{ __('Simpan Kata Sandi') }}</button>

            @if (session('status') === 'password-updated')
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

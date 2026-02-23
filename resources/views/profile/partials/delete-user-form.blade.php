<section class="space-y-6">
    <header>
        <h2 class="text-xl font-heading font-semibold text-coral-600">
            {{ __('Hapus Akun') }}
        </h2>

        <p class="mt-1 text-sm text-slate-600">
            {{ __('Setelah akun dihapus, semua data dan pengaturan di dalamnya akan hilang secara permanen. Sebelum menghapus, harap pastikan Anda telah mengunduh data penting yang ingin disimpan.') }}
        </p>
    </header>

    <button type="button" class="btn border-0 bg-coral-500 text-white hover:bg-coral-600"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Hapus Akun') }}</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-slate-900">
                {{ __('Apakah Anda yakin ingin menghapus akun Anda?') }}
            </h2>

            <p class="mt-1 text-sm text-slate-600">
                {{ __('Setelah akun dihapus, data akan hilang selamanya. Silakan masukkan kata sandi Anda untuk mengonfirmasi penghapusan akun ini.') }}
            </p>

            <div class="mt-6 form-control">
                <label class="label sr-only" for="password"><span class="label-text font-semibold">{{ __('Kata Sandi') }}</span></label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full md:w-3/4 rounded-xl border border-slate-300 px-3 py-2 text-sm focus:border-red-500 focus:outline-none"
                    placeholder="{{ __('Kata Sandi') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-red-600 text-sm" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" class="btn btn-ghost" x-on:click="$dispatch('close')">
                    {{ __('Batal') }}
                </button>

                <button type="submit" class="btn border-0 bg-coral-500 text-white hover:bg-coral-600">
                    {{ __('Hapus Akun Permanen') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>

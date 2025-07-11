<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Ubah Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        {{-- Password Saat Ini --}}
        <div class="mb-4">
            <label for="update_password_current_password" class="block text-sm font-medium text-gray-700">Password Saat Ini</label>
            <input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" autocomplete="current-password">
            @error('current_password', 'updatePassword')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password Baru --}}
        <div class="mb-4">
            <label for="update_password_password" class="block text-sm font-medium text-gray-700">Password Baru</label>
            <input id="update_password_password" name="password" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" autocomplete="new-password">
            @error('password', 'updatePassword')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Konfirmasi Password Baru --}}
        <div class="mb-6">
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" autocomplete="new-password">
            @error('password_confirmation', 'updatePassword')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                Simpan
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>

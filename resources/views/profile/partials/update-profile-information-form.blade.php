<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Perbarui informasi profil dan nama Anda.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- NIK Karyawan (Read-only) --}}
        <div>
            <label for="nik" class="block text-sm font-medium text-gray-700">NIK Karyawan</label>
            <input id="nik" type="text" class="mt-1 block w-full bg-gray-100 rounded-md border-gray-300 shadow-sm" value="{{ $user->nik }}" disabled readonly>
        </div>

        {{-- Nama Lengkap --}}
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
            <input id="name" name="name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email (Opsional) --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email (Opsional)</label>
            <input id="email" name="email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('email', $user->email) }}" autocomplete="username">
             @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>


        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                Simpan
            </button>

            @if (session('status') === 'profile-updated')
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

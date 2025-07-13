<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Perbarui informasi profil dan alamat email akun Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Nama --}}
        <div>
            <x-input-label for="name" :value="__('Nama')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
            {{-- ... (kode verifikasi email) ... --}}
        </div>

        {{-- NIP --}}
        <div class="mt-4">
            <x-input-label for="nip" :value="__('NIP')" />
            <x-text-input id="nip" type="text" class="mt-1 block w-full bg-gray-100" :value="$user->nip ?? '-'"
                disabled />
        </div>

        {{-- Jabatan --}}
        <div class="mt-4">
            <x-input-label for="jabatan" :value="__('Jabatan')" />
            <x-text-input id="jabatan" type="text" class="mt-1 block w-full bg-gray-100" :value="$user->jabatan ?? '-'"
                disabled />
        </div>

        {{-- Role --}}
        <div class="mt-4">
            <x-input-label for="role" :value="__('Role / Peran')" />
            <x-text-input id="role" type="text" class="mt-1 block w-full bg-gray-100"
                :value="$user->role->name ?? 'Belum Diatur'" disabled />
        </div>

        {{-- Entitas / OPD --}}
        <div class="mt-4">
            <x-input-label for="entity" :value="__('Unit Kerja')" />
            <x-text-input id="entity" type="text" class="mt-1 block w-full bg-gray-100"
                :value="$user->entity->name ?? 'Inspektorat Kabupaten Trenggalek'" disabled />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Simpan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600">{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>
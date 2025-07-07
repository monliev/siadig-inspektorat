<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="relative flex flex-col m-6 space-y-8 bg-white shadow-2xl rounded-2xl md:flex-row md:space-y-0">
            <div class="relative w-full md:w-1/2">
                {{-- Ganti URL ini dengan URL gambar latar yang Anda inginkan --}}
                <img src="https://dpmb.trenggalekkab.go.id/build/images/bg-tugu-trenggalek.jpg"
                     alt="Gambar Latar" class="w-full h-full object-cover rounded-l-2xl" />
                <div class="absolute top-0 left-0 w-full h-full bg-black bg-opacity-50 rounded-l-2xl"></div>
                <div class="absolute top-0 left-0 w-full h-full flex flex-col justify-between p-8 text-white">
                    <div>
                        <div class="flex items-center space-x-3">
                            {{-- Menggunakan logo dari URL --}}
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/49/Trenggalek_coat_of_arms.png/500px-Trenggalek_coat_of_arms.png" 
                                alt="Logo Pemkab Trenggalek" 
                                class="h-12 w-auto"> {{-- Anda bisa sesuaikan ukuran tinggi (h-12) --}}

                            <span class="font-bold text-lg text-white">E-Arsip Inspektorat</span>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold mb-1">Arsip Digital</h2>
                        <p class="text-sm">Inspektorat Kabupaten Trenggalek</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col justify-center p-8 md:p-14 w-full md:w-1/2">
                <h2 class="font-bold text-2xl">Selamat Datang Kembali</h2>
                <p class="mt-2 mb-8 text-sm text-gray-600">Silakan masuk untuk melanjutkan.</p>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div>
                        <x-input-label for="email" value="Email" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="password" value="Kata Sandi" />
                        <x-text-input id="password" class="block mt-1 w-full"
                                        type="password"
                                        name="password"
                                        required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        {!! NoCaptcha::display() !!}
                        <x-input-error :messages="$errors->get('g-recaptcha-response')" class="mt-2" />
                    </div>

                    <div class="block mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                            <span class="ms-2 text-sm text-gray-600">{{ __('Ingat saya') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        @if (Illuminate\Support\Facades\Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                                {{ __('Lupa kata sandi?') }}
                            </a>
                        @endif

                        <x-primary-button class="ms-3">
                            {{ __('Masuk') }}
                        </x-primary-button>
                    </div>
                </form>
                @push('scripts')
                    {!! NoCaptcha::renderJs() !!}
                @endpush
            </div>
        </div>
    </div>
</x-guest-layout>
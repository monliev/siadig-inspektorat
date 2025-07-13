<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }} - Registrasi Akun</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-100">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="w-full max-w-4xl lg:flex rounded-xl shadow-2xl overflow-hidden m-4">
                <div class="w-full lg:w-1/2 bg-gradient-to-br from-indigo-600 via-blue-600 to-cyan-500 text-white flex flex-col justify-center p-8 sm:p-12">
                    <div class="max-w-md">
                         <a href="/" class="mb-6 inline-block">
                            <x-application-logo class="w-16 h-16 fill-current" />
                        </a>
                        <h1 class="font-bold text-3xl leading-tight mb-2">Buat Akun Baru</h1>
                        <p class="text-blue-100">
                           Daftarkan diri Anda untuk dapat menggunakan layanan pengajuan Surat Keterangan Bebas Temuan secara online.
                        </p>
                    </div>
                </div>
                <div class="w-full lg:w-1/2 bg-white flex items-center justify-center p-8 sm:p-12">
                    <div class="w-full max-w-md">
                        <h2 class="text-sm font-bold uppercase text-gray-500 tracking-wider mb-2">Formulir Registrasi</h2>
                         <x-auth-session-status class="mb-4" :status="session('status')" />
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="mb-4">
                                <x-input-label for="name" :value="__('Nama Lengkap')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            </div>
                            <div class="mb-4">
                                <x-input-label for="nip" value="NIP" />
                                <x-text-input id="nip" class="block mt-1 w-full" type="text" name="nip" :value="old('nip')" required />
                            </div>
                            <div class="mb-4">
                                <x-input-label for="phone_number" value="No. HP (WhatsApp)" />
                                <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number')" required />
                            </div>
                            <div class="mb-4">
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                            </div>
                            <div class="mb-4">
                                <x-input-label for="password" :value="__('Password')" />
                                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                            </div>
                            <div class="mb-4">
                                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                            </div>
                            <div class="flex items-center justify-end mt-4">
                                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login.skbt') }}">
                                    {{ __('Sudah punya akun?') }}
                                </a>
                                <x-primary-button class="ms-4">
                                    {{ __('Register') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
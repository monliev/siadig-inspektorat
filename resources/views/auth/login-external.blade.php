<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Login Klien Eksternal</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased bg-gray-100">

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

        <div class="w-full max-w-4xl lg:flex rounded-xl shadow-2xl overflow-hidden m-4">

            <div
                class="w-full lg:w-1/2 bg-gradient-to-br from-purple-600 via-indigo-600 to-blue-600 text-white flex flex-col justify-center p-8 sm:p-12">
                <div class="max-w-md">
                    <a href="/" class="mb-6 inline-block">
                        <x-application-logo class="w-16 h-16 fill-current" />
                    </a>
                    <h1 class="font-bold text-3xl leading-tight mb-2">Portal Klien Eksternal</h1>
                    <p class="text-indigo-100">
                        Halaman ini dikhususkan bagi OPD, Desa, dan entitas eksternal lainnya untuk menanggapi
                        permintaan dokumen dari Inspektorat.
                    </p>
                </div>
            </div>

            <div class="w-full lg:w-1/2 bg-white flex items-center justify-center p-8 sm:p-12">
                <div class="w-full max-w-md">
                    <h2 class="text-sm font-bold uppercase text-gray-500 tracking-wider mb-2">User Login</h2>
                    <p class="text-gray-500 mb-6">Selamat datang kembali.</p>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="relative mb-4">
                            <div
                                class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </div>
                            <input type="email" name="email" id="email"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5"
                                placeholder="name@example.com" required>
                        </div>
                        <div class="relative mb-4">
                            <div
                                class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                            <input type="password" name="password" id="password"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5"
                                placeholder="Password" required>
                        </div>
                        <div class="flex items-center justify-between mt-4">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                    name="remember">
                                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                            </label>
                            @if (\Illuminate\Support\Facades\Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 hover:text-gray-900"
                                href="{{ route('password.request') }}">
                                {{ __('Forgot password?') }}
                            </a>
                            @endif
                        </div>
                        <div class="mt-6">
                            <button type="submit"
                                class="w-full text-white bg-gradient-to-r from-purple-500 to-indigo-600 hover:bg-gradient-to-l focus:ring-4 focus:outline-none focus:ring-purple-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                LOGIN
                            </button>
                        </div>
                        <div class="mt-8 text-center">
                            <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('portal') }}">
                                &larr; Kembali ke Halaman Portal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>
</body>

</html>
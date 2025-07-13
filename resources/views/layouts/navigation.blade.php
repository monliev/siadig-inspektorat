<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a
                        href="{{ Illuminate\Support\Facades\Gate::allows('isClient') ? route('client.dashboard') : route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @can('isClient')
                    {{-- Menu untuk OPD --}}
                    <x-nav-link :href="route('client.dashboard')" :active="request()->routeIs('client.*')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @else
                    {{-- Menu untuk Internal --}}
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('documents.index')"
                        :active="request()->routeIs('documents.index') && !request()->routeIs('documents.reviewList')">
                        {{ __('Arsip') }}
                    </x-nav-link>
                    @can('isAdmin')
                    <x-nav-link :href="route('documents.reviewList')"
                        :active="request()->routeIs('documents.reviewList')">
                        {{ __('Review Arsip') }}
                    </x-nav-link>
                    @endcan
                    <x-nav-link :href="route('dispositions.index')" :active="request()->routeIs('dispositions.index')">
                        {{ __('Disposisi Masuk') }}
                        {{-- Penanda Notifikasi --}}
                        @if(isset($unreadDispositionsCount) && $unreadDispositionsCount > 0)
                        <span
                            class="ms-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                            {{ $unreadDispositionsCount }}
                        </span>
                        @endif
                    </x-nav-link>
                    <x-nav-link :href="url('/dispositions-sent')" :active="request()->is('dispositions-sent')">
                        Disposisi Keluar
                    </x-nav-link>
                    <x-nav-link :href="route('service-requests.index')" :active="request()->routeIs('service-requests.*')">
                        {{ __('Permohonan Masuk') }}
                    </x-nav-link>
                    {{-- Menu Khusus Admin --}}
                    @can('isAdmin')
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>Permintaan Dokumen OPD</div>
                                    <div class="ms-1"><svg class="fill-current h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg></div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('document-requests.index')">
                                    {{ __('Permintaan Dokumen') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('documents.client_submissions')"
                                    :active="request()->routeIs('documents.client_suissions')">
                                    {{ __('Daftar Dokumen OPD') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>Master Data</div>
                                    <div class="ms-1"><svg class="fill-current h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg></div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('required-documents.index')">
                                    {{ __('Persyaratan Dokumen BT') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('document-categories.index')">
                                    {{ __('Kategori Dokumen') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('roles.index')">
                                    {{ __('Kelola Peran') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('users.index')">
                                    {{ __('Manajemen Pengguna') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('entities.index')">
                                    {{ __('Manajemen OPD') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    @endcan
                    @endcan
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>🔔</div>
                            @if($unreadNotifications->count() > 0)
                                <span class="ms-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">{{ $unreadNotifications->count() }}</span>
                            @endif
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 text-sm text-gray-700 font-semibold">
                            Notifikasi
                        </div>

                        @forelse($unreadNotifications as $notification)
                            <x-dropdown-link :href="$notification->data['url'] . '?read=' . $notification->id">
                                <p class="font-bold">{{ $notification->data['applicant_name'] }}</p>
                                <p class="text-sm">{{ $notification->data['message'] }}</p>
                            </x-dropdown-link>
                        @empty
                            <div class="px-4 py-2 text-sm text-gray-500">
                                Tidak ada notifikasi baru.
                            </div>
                        @endforelse
                        
                        <div class="border-t border-gray-200"></div>

                        <x-dropdown-link href="#">
                            Lihat Semua Notifikasi
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Illuminate\Support\Facades\Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        @can('isClient')
        {{-- Tampilan Menu Mobile untuk Klien --}}
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('client.dashboard')" :active="request()->routeIs('client.*')">
                {{ __('Dashboard') }}</x-responsive-nav-link>
        </div>
        @else
        {{-- Tampilan Menu Mobile untuk Internal --}}
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('documents.index')" :active="request()->routeIs('documents.index')">
                {{ __('Arsip Internal') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dispositions.index')"
                :active="request()->routeIs('dispositions.index')">{{ __('Disposisi Masuk') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="url('/dispositions-sent')" :active="request()->is('dispositions-sent')">
                {{ __('Disposisi Keluar') }}</x-responsive-nav-link>
        </div>

        {{-- Bagian Menu Admin di Mobile --}}
        @can('isAdmin')
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">Menu Admin</div>
            </div>
            <div class="mt-3 space-y-1">
                <div class="px-4 font-medium text-sm text-gray-500">Permintaan Dokumen</div>
                <x-responsive-nav-link :href="route('document-requests.index')" class="ml-4">
                    {{ __('Kelola Permintaan') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('documents.client_submissions')" class="ml-4">
                    {{ __('Dokumen Kiriman Klien') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('documents.reviewList')" class="ml-4">{{ __('Review Internal') }}
                </x-responsive-nav-link>

                <div class="px-4 mt-3 font-medium text-sm text-gray-500">Master Data</div>
                <x-responsive-nav-link :href="route('users.index')" class="ml-4">{{ __('Manajemen Pengguna') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('entities.index')" class="ml-4">{{ __('Manajemen Entitas') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('roles.index')" class="ml-4">{{ __('Kelola Roles') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('document-categories.index')" class="ml-4">
                    {{ __('Kategori Dokumen') }}</x-responsive-nav-link>
            </div>
        </div>
        @endcan
        @endcan

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Illuminate\Support\Facades\Auth::user()->name }}
                </div>
                <div class="font-medium text-sm text-gray-500">{{ Illuminate\Support\Facades\Auth::user()->email }}
                </div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
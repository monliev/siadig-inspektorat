<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @auth
                        {{-- Logika untuk KLIEN EKSTERNAL (OPD/DESA) --}}
                        @if(auth()->user()->hasRole('Klien Eksternal'))
                            <x-nav-link :href="route('client.dashboard')" :active="request()->routeIs('client.*')">
                                {{ __('Dashboard Klien') }}
                            </x-nav-link>

                        {{-- Logika untuk PEMOHON (PNS) --}}
                        @elseif(auth()->user()->hasRole('Pemohon'))
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <x-nav-link :href="route('service-requests.create')" :active="request()->routeIs('service-requests.create')">
                                {{ __('Ajukan Permohonan') }}
                            </x-nav-link>

                        {{-- Logika untuk PENGGUNA INTERNAL (Super Admin, Admin Arsip, Auditor, dll) --}}
                        @else
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link>
                            <x-nav-link :href="route('documents.index')" :active="request()->routeIs('documents.index') && !request()->routeIs('documents.reviewList')">
                                {{ __('Arsip') }}
                            </x-nav-link>
                            <x-nav-link :href="route('dispositions.index')" :active="request()->routeIs('dispositions.index')">
                                {{ __('Disposisi Masuk') }}
                            </x-nav-link>
                            <x-nav-link :href="url('/dispositions-sent')" :active="request()->is('dispositions-sent')">
                                Disposisi Keluar
                            </x-nav-link>

                            @can('view-service-requests')
                            <x-nav-link :href="route('service-requests.index')" :active="request()->routeIs('service-requests.*')">
                                {{ __('Permohonan SKBT') }}
                            </x-nav-link>
                            @endcan
                            
                            {{-- Dropdown Menu yang memerlukan izin spesifik (otomatis tampil untuk Super Admin) --}}
                            @canany(['view-users', 'view-roles'])
                                <div class="hidden sm:flex sm:items-center sm:ms-6">
                                    <x-dropdown align="right" width="48">
                                        <x-slot name="trigger">
                                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                                <div>Administrasi</div>
                                                <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                                            </button>
                                        </x-slot>
                                        <x-slot name="content">
                                            <div class="px-4 py-2 text-xs text-gray-400">Permintaan Dokumen OPD</div>
                                            <x-dropdown-link :href="route('document-requests.index')"> {{ __('Kelola Permintaan') }} </x-dropdown-link>
                                            <x-dropdown-link :href="route('documents.client_submissions')"> {{ __('Daftar Dokumen OPD') }} </x-dropdown-link>
                                            
                                            <div class="border-t border-gray-200"></div>
                                            <div class="px-4 py-2 text-xs text-gray-400">Master Data</div>
                                            <x-dropdown-link :href="route('users.index')"> {{ __('Manajemen Pengguna') }} </x-dropdown-link>
                                            <x-dropdown-link :href="route('roles.index')"> {{ __('Kelola Peran') }} </x-dropdown-link>
                                            <x-dropdown-link :href="route('entities.index')"> {{ __('Manajemen OPD') }} </x-dropdown-link>
                                            <x-dropdown-link :href="route('document-categories.index')"> {{ __('Kategori Dokumen') }} </x-dropdown-link>
                                            <x-dropdown-link :href="route('required-documents.index')"> {{ __('Persyaratan SKBT') }} </x-dropdown-link>
                                        </x-slot>
                                    </x-dropdown>
                                </div>
                            @endcanany
                        @endif
                    @endauth
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
    
    {{-- ====================================================== --}}
    {{-- MENU MOBILE UNTUK PENGGUNA INTERNAL --}}
    {{-- ====================================================== --}}
    @hasanyrole('Super Admin|Admin Arsip|Pejabat Struktural|Auditor')
    <div class="pt-2 pb-3 space-y-1">
        <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('documents.index')" :active="request()->routeIs('documents.index')">
            {{ __('Arsip') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('dispositions.index')" :active="request()->routeIs('dispositions.index')">
            {{ __('Disposisi Masuk') }}
        </x-responsive-nav-link>

        @can('view-service-requests')
        <x-responsive-nav-link :href="route('service-requests.index')" :active="request()->routeIs('service-requests.*')">
            {{ __('Permohonan SKBT') }}
        </x-responsive-nav-link>
        @endcan
    </div>
    @endhasanyrole


    {{-- ====================================================== --}}
    {{-- MENU MOBILE UNTUK KLIEN EKSTERNAL (OPD/DESA) --}}
    {{-- ====================================================== --}}
    @hasrole('Klien Eksternal')
    <div class="pt-2 pb-3 space-y-1">
        <x-responsive-nav-link :href="route('client.dashboard')" :active="request()->routeIs('client.*')">
            {{ __('Dashboard') }}
        </x-responsive-nav-link>
    </div>
    @endhasrole


    {{-- ====================================================== --}}
    {{-- MENU MOBILE UNTUK PEMOHON (PNS) --}}
    {{-- ====================================================== --}}
    @hasrole('Pemohon')
    <div class="pt-2 pb-3 space-y-1">
        <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('service-requests.create')" :active="request()->routeIs('service-requests.create')">
            {{ __('Ajukan Permohonan') }}
        </x-responsive-nav-link>
    </div>
    @endhasrole


    <div class="pt-4 pb-1 border-t border-gray-200">
        <div class="px-4">
            <div class="font-medium text-base text-gray-800">{{ Illuminate\Support\Facades\Auth::user()->name }}</div>
            <div class="font-medium text-sm text-gray-500">{{ Illuminate\Support\Facades\Auth::user()->email }}</div>
        </div>

        <div class="mt-3 space-y-1">
            <x-responsive-nav-link :href="route('profile.edit')">
                {{ __('Profile') }}
            </x-responsive-nav-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</div>
</nav>
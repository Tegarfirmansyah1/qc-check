<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'QC-Check') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        
        {{-- CSS Kustom untuk Efek Cekung --}}
        <style>
            body { font-family: 'Inter', sans-serif; }
            [x-cloak] { display: none !important; }

            .inactive-nav-link {
                border-top-left-radius: 24px;
                border-top-right-radius: 24px;
                border-bottom-right-radius: 24px;
                border-bottom-left-radius: 24px;
                margin-right: 5px; 
            }

            /* Kelas untuk link yang aktif */
            .active-nav-link {
                position: relative;
                background-color: #bfdffe; /* slate-100, warna background konten */
                color: #1e3a8a; /* blue-800, warna tema */
                border-top-left-radius: 24px;
                border-bottom-left-radius: 24px;
            }

            /* Pseudo-element untuk kurva atas */
            .active-nav-link::before {
                content: '';
                position: absolute;
                top: -20px;
                right: 0;
                height: 20px;
                width: 20px;
                border-bottom-right-radius: 20px;
                box-shadow: 5px 5px 0 5px #bfdffe; /* Warna background konten */
            }

            /* Pseudo-element untuk kurva bawah */
            .active-nav-link::after {
                content: '';
                position: absolute;
                bottom: -20px;
                right: 0;
                height: 20px;
                width: 20px;
                border-top-right-radius: 20px;
                box-shadow: 5px -5px 0 5px #bfdffe; /* Warna background konten */
            }

            /* Pseudo-element untuk kurva di header */
            
        </style>
    </head>
    <body class="font-sans antialiased bg-blue-800">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <aside class="w-64 bg-blue-800 text-indigo-100 flex-shrink-0 fixed h-full z-20">
                <!-- Logo -->
                <div class="h-16 flex items-center justify-center px-4">
                    <h1 class="text-2xl font-bold text-white">QC-Check</h1>
                </div>

                <!-- Navigation Links -->
                <nav class="mt-8 pl-4 space-y-2">
                    @include('layouts.navigation-sidebar')
                </nav>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col ml-64 pl-0">
                <div class="flex flex-col flex-1 bg-blue-200 pl-6 pr-6">
                    <!-- Top Header -->
                    <header class="bg-white relative rounded-b-2xl">
                        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
                            <div class="flex justify-between items-center h-16">
                                <!-- Page Title -->
                                <div class="flex-1">
                                    @yield('header')
                                </div>

                                <!-- Right side -->
                                <div class="flex items-center">
                                    <div x-data="{ open: false }" class="relative">
                                        <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 focus:outline-none">
                                            <div>{{ Auth::user()->name }}</div>
                                            <div class="ml-1">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                            </div>
                                        </button>
                                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5">
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block w-full text-left px-4 py-2 text-sm text-gray-900 hover:bg-gray-100">
                                                    Keluar
                                                </a>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>

                    <!-- Page Content -->
                    <main class="flex-grow">
                        @yield('content')
                    </main>
                </div>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>

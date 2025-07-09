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
        
        {{-- CSS Kustom Anda --}}
        <style>
            body { font-family: 'Inter', sans-serif; }
            [x-cloak] { display: none !important; }

            .inactive-nav-link {
                border-radius: 24px;
                margin-right: 5px; 
            }

            /* Gaya default untuk link aktif (Mobile) */
            .active-nav-link {
                position: relative;
                background-color: #bfdffe;
                color: #1e3a8a;
                border-radius: 24px; /* Bulat biasa di semua sisi untuk mobile */
                margin-right: 5px;
            }

            /* Terapkan efek cekung HANYA untuk layar besar (desktop) */
            @media (min-width: 1024px) { /* lg breakpoint di Tailwind */
                .active-nav-link {
                    /* Override radius untuk desktop */
                    border-top-left-radius: 24px;
                    border-bottom-left-radius: 24px;
                    border-top-right-radius: 0;
                    border-bottom-right-radius: 0;
                    margin-right: 0;
                }

                .active-nav-link::before {
                    content: '';
                    position: absolute;
                    top: -20px;
                    right: 0;
                    height: 20px;
                    width: 20px;
                    border-bottom-right-radius: 20px;
                    box-shadow: 5px 5px 0 5px #bfdffe;
                }

                .active-nav-link::after {
                    content: '';
                    position: absolute;
                    bottom: -20px;
                    right: 0;
                    height: 20px;
                    width: 20px;
                    border-top-right-radius: 20px;
                    box-shadow: 5px -5px 0 5px #bfdffe;
                }
            }
        </style>

       
        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-blue-800">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen flex">
            
            <!-- Sidebar -->
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
                   class="w-64 bg-blue-800 text-indigo-100 flex-shrink-0 fixed h-full z-30 transform lg:transform-none lg:translate-x-0 transition-transform duration-200 ease-in-out">
                <div class="h-16 flex items-center justify-center px-4">
                    <h1 class="text-2xl font-bold text-white">QC-Check</h1>
                </div>
                <nav class="mt-8 pl-4 space-y-2">
                    @include('layouts.navigation-sidebar')
                </nav>
            </aside>

            <!-- Overlay -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black opacity-50 z-20 lg:hidden" x-cloak></div>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col lg:ml-64 pl-0">
                <div class="flex flex-col flex-1 bg-blue-200 p-4 pt-0 lg:pl-6 lg:pr-6">
                    <!-- Top Header -->
                    <header class="bg-white relative lg:rounded-b-2xl rounded-b-3xl">
                        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
                            <div class="flex justify-between items-center h-20">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <button @click.stop="sidebarOpen = !sidebarOpen" class="mr-4 p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 lg:hidden">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                        </button>
                                        @yield('header')
                                    </div>
                                </div>
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
                    <main class="flex-grow pb-20 lg:pb-0">
                        @yield('content')
                    </main>
                </div>
            </div>
        </div>

        <div class="lg:hidden fixed bottom-0 left-0 right-0 h-16 bg-white border-t border-gray-200 flex justify-around items-center z-10">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center text-center w-full {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-500' }}">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="text-xs">Dashboard</span>
            </a>
            <a href="{{ route('inspections.create') }}" class="flex flex-col items-center justify-center text-center w-full {{ request()->routeIs('inspections.create') ? 'text-blue-600' : 'text-gray-500' }}">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="text-xs">Inspeksi</span>
            </a>
            <a href="{{ route('inspections.index') }}" class="flex flex-col items-center justify-center text-center w-full {{ request()->routeIs('inspections.index') || request()->routeIs('inspections.show') ? 'text-blue-600' : 'text-gray-500' }}">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                <span class="text-xs">Riwayat</span>
            </a>
        </div>

        @stack('scripts')
    </body>
</html>

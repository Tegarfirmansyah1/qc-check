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
        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 flex">
            <!-- Sidebar -->
            <aside class="w-64 bg-gray-900 text-gray-200 flex flex-col fixed h-full z-10">
                <!-- Logo -->
                <div class="h-20 flex items-center justify-center text-2xl font-bold text-white border-b border-gray-800">
                    QC-Check
                </div>

                <!-- User Profile -->
                <div class="p-4 flex flex-col items-center text-center border-b border-gray-800">
                    <div class="w-16 h-16 rounded-full bg-blue-600 flex items-center justify-center text-2xl font-bold mb-2">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <p class="font-semibold text-white truncate w-full">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400">{{ Auth::user()->role === 'admin' ? 'Administrator' : 'Staf Quality Control' }}</p>
                </div>

                <!-- Navigation Links -->
                <nav class="flex-grow p-4 space-y-2 overflow-y-auto">
                    {{-- Menggunakan file navigasi terpisah --}}
                    @include('layouts.navigation-sidebar')
                </nav>

                <!-- Logout -->
                <div class="p-4 border-t border-gray-800">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                            class="flex items-center p-3 text-gray-400 hover:bg-gray-700 hover:text-white rounded-md transition-colors duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span class="ml-3">Keluar</span>
                        </a>
                    </form>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col ml-64">
                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white shadow-sm">
                        <div class="max-w-full mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main class="flex-grow">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>

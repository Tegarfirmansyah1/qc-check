<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QC-Check - Digital Quality Control</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">QC-Check</h1>
            <div>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-500 mr-4">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Register</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <main>
        <div class="bg-white">
            <div class="container mx-auto px-6 py-24 text-center">
                <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">Digitalisasi Proses Quality Control Anda</h2>
                <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">Gantikan checklist kertas manual dengan sistem digital yang terstruktur, efisien, dan analitis untuk meningkatkan kualitas produksi.</p>
                <div class="mt-8">
                    <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg text-lg transition-transform transform hover:scale-105">Mulai Sekarang - Gratis</a>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <section class="py-20 bg-gray-50">
            <div class="container mx-auto px-6">
                <div class="text-center mb-16">
                    <h3 class="text-3xl font-bold">Mengapa QC-Check?</h3>
                    <p class="text-gray-600 mt-2">Solusi untuk masalah umum pada sistem QC manual.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-white p-8 rounded-lg shadow-md text-center">
                        <div class="flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 text-blue-600 mb-5 mx-auto">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold">Proses Cepat</h4>
                        <p class="mt-2 text-gray-600">Inspeksi lebih cepat dan efisien dengan antarmuka digital yang ramah seluler.</p>
                    </div>
                    <!-- Feature 2 -->
                    <div class="bg-white p-8 rounded-lg shadow-md text-center">
                         <div class="flex items-center justify-center h-16 w-16 rounded-full bg-green-100 text-green-600 mb-5 mx-auto">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 20.417l4.5-4.5M12 2.944a11.955 11.955 0 00-8.618 3.04m17.236-3.04a11.955 11.955 0 01-8.618 14.432M3 20.417l4.5-4.5m1.5-1.5l-1.5-1.5m6 6l1.5 1.5"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold">Data Aman</h4>
                        <p class="mt-2 text-gray-600">Hilangkan risiko kehilangan data. Semua hasil tersimpan aman di database terpusat.</p>
                    </div>
                    <!-- Feature 3 -->
                    <div class="bg-white p-8 rounded-lg shadow-md text-center">
                        <div class="flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 text-yellow-600 mb-5 mx-auto">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold">Analisis Mudah</h4>
                        <p class="mt-2 text-gray-600">Data diolah menjadi grafik dan statistik untuk menemukan tren masalah kualitas.</p>
                    </div>
                    <!-- Feature 4 -->
                    <div class="bg-white p-8 rounded-lg shadow-md text-center">
                        <div class="flex items-center justify-center h-16 w-16 rounded-full bg-purple-100 text-purple-600 mb-5 mx-auto">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold">Bukti Visual</h4>
                        <p class="mt-2 text-gray-600">Lampirkan bukti foto secara langsung jika ditemukan cacat pada produk.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white">
        <div class="container mx-auto px-6 py-8 text-center">
            <p>&copy; {{ date('Y') }} QC-Check. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>

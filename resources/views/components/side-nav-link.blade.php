@props(['active'])

@php
// Kelas dasar yang dimiliki semua link
$baseClasses = 'flex items-center p-3 text-base font-normal transition-colors duration-200 group';

// Kelas untuk link yang TIDAK aktif
$inactiveClasses = 'inactive-nav-link text-indigo-100 hover:bg-blue-200 hover:text-black';

// Kelas untuk link yang AKTIF
$activeClasses = 'active-nav-link font-semibold rounded-l-lg';

// Gabungkan kelas berdasarkan status
$classes = $baseClasses . ' ' . (($active ?? false) ? $activeClasses : $inactiveClasses);
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

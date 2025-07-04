@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center p-3 text-white bg-blue-600 rounded-md transition-colors duration-200'
            : 'flex items-center p-3 text-gray-400 hover:bg-gray-700 hover:text-white rounded-md transition-colors duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

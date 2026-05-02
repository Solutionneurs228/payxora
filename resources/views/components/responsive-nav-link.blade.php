@props(['href', 'active' => false])

@php
$classes = $active
    ? 'block w-full pl-3 pr-4 py-2 border-l-4 border-indigo-500 text-left text-base font-medium text-indigo-700 bg-indigo-50 transition'
    : 'block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 transition';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

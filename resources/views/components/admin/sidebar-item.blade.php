@props(['route', 'icon', 'label'])

@php
    $isActive = request()->routeIs($route);
@endphp

<a href="{{ route($route) }}"
    class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200
        {{ $isActive
            ? 'bg-blue-600 text-white'
            : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
    <i class="{{ $icon }} w-4 text-center"></i>
    <span>{{ $label }}</span>
</a>

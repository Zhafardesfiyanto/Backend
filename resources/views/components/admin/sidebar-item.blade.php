@props(['route', 'icon', 'label'])

@php
    $isActive = request()->routeIs($route);
@endphp

<a href="{{ route($route) }}"
    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all duration-300
        {{ $isActive
            ? 'bg-gradient-to-r from-blue-500 to-sky-400 text-white shadow-lg shadow-blue-500/30 translate-x-1'
            : 'text-slate-500 hover:bg-blue-50/80 hover:text-blue-600 hover:translate-x-1' }}">
    <i class="{{ $icon }} w-5 text-center text-lg {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-blue-500' }}"></i>
    <span>{{ $label }}</span>
</a>

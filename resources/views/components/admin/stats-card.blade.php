@props(['label', 'value' => 0, 'icon', 'color' => 'blue'])

@php
    $colorMap = [
        'blue'   => ['bg' => 'bg-blue-50',   'icon' => 'bg-blue-100 text-blue-600',   'text' => 'text-blue-600'],
        'green'  => ['bg' => 'bg-green-50',  'icon' => 'bg-green-100 text-green-600', 'text' => 'text-green-600'],
        'purple' => ['bg' => 'bg-purple-50', 'icon' => 'bg-purple-100 text-purple-600','text' => 'text-purple-600'],
        'orange' => ['bg' => 'bg-orange-50', 'icon' => 'bg-orange-100 text-orange-600','text' => 'text-orange-600'],
    ];
    $colors = $colorMap[$color] ?? $colorMap['blue'];
@endphp

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4 hover:shadow-md transition-shadow">
    <div class="flex-shrink-0 w-12 h-12 rounded-xl {{ $colors['icon'] }} flex items-center justify-center">
        <i class="{{ $icon }} text-xl"></i>
    </div>
    <div>
        <p class="text-sm text-gray-500 font-medium">{{ $label }}</p>
        <p class="text-2xl font-bold text-gray-800 leading-tight">{{ number_format($value ?? 0) }}</p>
    </div>
</div>

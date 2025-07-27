@props(['icon', 'title', 'count', 'color' => 'blue'])

@php
$colors = [
    'blue' => 'bg-blue-100 text-blue-600',
    'green' => 'bg-green-100 text-green-600',
    'yellow' => 'bg-yellow-100 text-yellow-600',
    'red' => 'bg-red-100 text-red-600',
];
@endphp

<div class="flex items-center p-4 bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700">
    <div class="p-3 rounded-full {{ $colors[$color] ?? 'bg-blue-100 text-blue-600' }}">
        @svg($icon, 'h-6 w-6')
    </div>
    <div class="ml-4">
        <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ $title }}</h4>
        <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $count }}</p>
    </div>
</div>

@props([
    'info' => false,
    'success' => false,
    'warning' => false,
    'error' => false,
])
<div {!! $attributes->class([
    '!bg-blue-100 !text-primary-800 border-0' => $info,
    '!bg-gradient-to-r from-green-300 via-green-500 to-emerald-700 !text-neutral-50 border-0' => $success,
    '!bg-yellow-100 !text-yellow-800 border-0' => $warning,
    '!bg-gradient-to-r from-red-400 via-red-600 to-red-700 !text-neutral-50 border-0' => $error,
])->merge(['class' => 'badge font-semibold']) !!}>{{ $slot }}</div>

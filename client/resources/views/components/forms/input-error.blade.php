@props(['value'])

<div {{ $attributes->merge(['class' => 'label text-xs font-normal text-red-600 px-0 py-1']) }}>
    <span class="label-text text-[12px]">{{ $value ?? $slot }}</span>
</div>
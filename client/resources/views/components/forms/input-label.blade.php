@props(['value'])

<div {{ $attributes->merge(['class' => 'label text-sm font-normal md:!w-[240px] md:mr-4']) }}>
    <span class="label-text !text-[13px]">{{ $value ?? $slot }}</span>
</div>
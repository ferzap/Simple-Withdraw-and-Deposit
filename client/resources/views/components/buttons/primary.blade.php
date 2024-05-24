@props(['disabled' => false, 'loading' => false])

<button {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => 'btn btn-primary text-white disabled:bg-neutral-300 disabled:text-neutral-400',
]) !!}>
    {{ $value ?? $slot }}
</button>
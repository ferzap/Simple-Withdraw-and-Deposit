@props(['disabled' => false, 'loading' => false])

<button {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => 'btn btn-outline btn-sm rounded-md text-xs hover:!text-white',
]) !!}>{{ $value ?? $slot }}</button>
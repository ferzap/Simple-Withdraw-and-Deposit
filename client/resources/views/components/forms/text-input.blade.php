@props(['disabled' => false, 'error' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->class(['login-input-error' => $error ])->merge(['class' => 'input input-bordered text-base w-full disabled:border-neutral-300 disabled:bg-neutral-100']) !!}>

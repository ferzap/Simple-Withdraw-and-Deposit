@props([
    'info' => false,
    'success' => false,
    'warning' => false,
    'error' => false,
])
<div class="toast animate-fade-down animate-duration-1000 animate-delay-[2000ms] animate-reverse toast-right toast-top z-[50] min-w-80">
    <div class="alert bg-white border-0 drop-shadow-lg rounded-md">
        <div {!! $attributes->class([
            'p-2 rounded-full',
            'bg-primary-100 text-primary-600' => $info,
            'bg-green-100 text-green-600' => $success,
            'bg-yellow-100 text-yellow-600' => $warning,
            'bg-red-100 text-red-600' => $error,
        ]) !!}>
            @if ($info)
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    class="stroke-current shrink-0 w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            @endif
            @if ($success)
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            @endif
            @if ($warning)
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            @endif
            @if ($error)
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            @endif
        </div>
        <span class="text-[13px] font-medium text-neutral-500">{{ $slot }}</span>
    </div>
</div>
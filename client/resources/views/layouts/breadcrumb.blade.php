{{-- Breadcrumb --}}
@if (count($breadcrumbs) > 0)
    <div class="md:flex flex-row justify-between text-sm breadcrumbs mx-2 hidden">
        <ul>
            @foreach ($breadcrumbs as $key => $breadcrumb)
                <li class="text-xs">
                    @if ($breadcrumb['link'] != '#')
                        <p class="text-neutral-500 flex items-center">
                            <span class="material-symbols-outlined !text-lg mr-[6px]">
                                {{ $breadcrumb['icon'] }}
                            </span>
                            {{ $key }}
                        </p>
                    @else
                        <p class="cursor:pointer hover:!no-underline py-1 px-2 rounded-md bg-[#F0F9FF] text-primary-700">
                            {{ $key }}
                        </p>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endif
{{-- end breadcrumb --}}

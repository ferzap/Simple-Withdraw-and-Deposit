<div class="navbar bg-white border-b-2 border-b-neutral-300 dark:bg-zinc-800 z-[2] min-h-20 sticky top-0 px-5 py-3">
    <div class="flex-none">
        <button class="btn btn-square btn-ghost lg:hidden">
            <label for="my-drawer-2" class="btn bg-white border-0 drawer-button dark:bg-zinc-800 shadow-none"><i
                    class="fa-solid fa-bars fa-xl dark:text-white"></i></label>
        </button>
    </div>
    <div class="flex-1">
        @include('layouts.breadcrumb')
    </div>
    <div class="flex-none gap-3">
        <ul class="menu menu-horizontal px-1 text-[12px]">
            <li>
                <details class="relative">
                    <summary>
                        <div class="w-10 rounded-full mr-2">
                            <img src={{ asset('assets/images/avatar.png') }} />
                        </div>
                        <p class="hidden md:block">
                            {{ session('user_name') }}
                        </p>
                    </summary>
                    <ul class="p-2 bg-base-100 rounded-t-none rounded-b-lg min-w-44 absolute right-1 !mt-0">
                        <li class="md:hidden bg-neutral-100 -mx-3 px-2 mb-2">
                            <a>
                                {{ session('user_name') }}
                            </a>
                        </li>
                        <form id="logout" action="/logout" method="POST" class="mb-0">
                            @csrf
                            <li class="hover:bg-neutral-100 rounded-md"><button type="submit">Logout</a></li>
                        </form>
                    </ul>
                </details>
            </li>
        </ul>
        {{-- <ul tabindex="0"
                class="mt-3 p-2 shadow menu menu-sm dropdown-content bg-white rounded-md w-52 dark:bg-zinc-800 text-zinc-800 dark:text-white">
                <li class="px-2 font-semibold text-zinc-400">{{ Str::upper(session('administrator_name')) }}</li>
                <li class="px-2 text-sm text-zinc-500">{{ session('administrator_group_title') }}</li>
                <div class="divider my-0"></div>
                <li><a class="hover:bg-zinc-200 hover:!text-violet-900 active:!bg-violet-900 active:!text-white" href="/profile"><i class="fa-solid fa-user"></i>Profile</a></li>
                <form id="logout" action="/logout" method="POST" class="mb-0">
                    @csrf
                    <li><button class="hover:bg-zinc-200 hover:!text-violet-900 active:!bg-violet-900 active:!text-white" type="submit"><i class="fa-solid fa-right-from-bracket"></i> Logout</button></li>
                </form>
            </ul> --}}
    </div>
</div>

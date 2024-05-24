<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/js/app.js'])
    @vite(['resources/css/app.css','resources/css/custom.css'])
    {{-- @vite('/css/app.css') --}}
    <script src="https://kit.fontawesome.com/cde9a07be5.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.7.0/flowbite.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" integrity="sha512-34s5cpvaNG3BknEWSuOncX28vz97bRI59UnVtEEpFX536A7BtZSJHsDyFoCl8S7Dt2TPzcrCEoHBGeM4SUBDBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Admin | {{ $title }}</title>
</head>

<body>
    <div class="notif"></div>
    <div class="drawer lg:drawer-open">
        <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
        <div id="container"
            class="drawer-content flex flex-col items-center h-screen justify-between bg-neutral-50 overflow-y-auto">
            <!-- Page content here -->
            @include('layouts.navbar')
            <div class="container px-5 w-full my-7 mb-auto">
                {{-- @include('layouts.breadcrumb') --}}
                <div class="alert-container"></div>
                @yield('container')
            </div>
            {{-- @include('layouts.footer') --}}
            {{-- <label for="my-drawer-2" class="btn btn-primary drawer-button lg:hidden">Open drawer</label> --}}
        </div>
        @include('layouts.sidebar')
    </div>
</body>
@stack('script')
</html>
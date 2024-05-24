<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css'])
    {{-- <title>{{ $title }}</title> --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.png') }}">
    <script src="https://kit.fontawesome.com/cde9a07be5.js" crossorigin="anonymous"></script>
</head>

<body>
    <section
        class="relative flex flex-col min-h-screen items-center justify-center bg-gradient-to-r from-blue-200 via-sky-600  to-blue-900">
        @yield('content')
    </section>
</body>

</html>
@extends('template.notemplate')
@section('content')
    <section class="bg-white rounded-md">
        <div class="py-12 px-8 max-w-screen-xl lg:py-24 lg:px-20">
            <div class="w-[100vh] max-w-screen-lg text-center">
                <p class="mb-4 text-7xl tracking-tight font-extrabold lg:text-9xl text-primary-content animate-bounce animate-infinite animate-ease-in animate-normal">
                    404</p>
                <p class="mb-4 text-3xl tracking-tight font-bold text-gray-900 md:text-4xl dark:text-white">Page not found.</p>
                <p class="mb-4 text-lg font-light text-neutral-700">Sorry, we can't find that page. You can go back to dashboard to find more feature. </p>
                <a href="/dashboard"
                    class="btn btn-primary text-white">Back
                    to Dashboard</a>
            </div>
        </div>
    </section>
@endsection

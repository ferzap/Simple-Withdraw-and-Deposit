@extends('template.main')
@section('content')
    <div class="flex flex-col rounded-3xl bg-white dark:bg-slate-800 mx-36 min-h-[400px] min-w-[456px] md:p-12 px-16 py-12">
        @if (session()->has('error'))
            <x-alert :error=true>{{ session('error') }}</x-alert>
        @endif
        <div class="w-full pb-8">
            <p class="text-xl font-semibold">Register</p>
        </div>
        <form action="/register" method="POST" class="flex flex-col">
            @csrf
            <label class="form-control w-full pb-6 relative">
                <x-forms.input-label for="email" class="py-0 px-0 text-neutral-500">Email</x-forms.input-label>
                <x-forms.text-input :error="$errors->first('email')"
                    class="border-t-0 border-x-0 rounded-none px-0 py-0 border-b-slate-900 focus:outline-none focus:border-b-blue-900 focus:border-b-2 placeholder:text-slate-900"
                    id="email" type="email" name="email" :value="old('email')" placeholder="Enter email" required />
                @error('email')
                    <i class="fa-solid fa-circle-exclamation absolute right-2 top-10 text-red-600"></i>
                    <x-forms.input-error>{{ $message }}</x-forms>
                    @enderror
            </label>

            <label class="form-control w-full pb-6 relative">
                <x-forms.input-label for="name" class="py-0 px-0 text-neutral-500">Full Name</x-forms.input-label>
                <x-forms.text-input :error="$errors->first('name')"
                    class="border-t-0 border-x-0 rounded-none px-0 py-0 border-b-slate-900 focus:outline-none focus:border-b-blue-900 focus:border-b-2 placeholder:text-slate-900"
                    id="name" type="text" name="name" :value="old('name')" placeholder="Enter Full Name" required />
                @error('name')
                    <i class="fa-solid fa-circle-exclamation absolute right-2 top-10 text-red-600"></i>
                    <x-forms.input-error>{{ $message }}</x-forms.input-error>
                @enderror
            </label>

            <label class="form-control w-full pb-6 relative">
                <x-forms.input-label for="password" class="py-0 px-0 text-neutral-500">Password</x-forms.input-label>
                <x-forms.text-input :error="$errors->first('password')"
                    class="border-t-0 border-x-0 rounded-none px-0 py-0 border-b-slate-900 focus:outline-none focus:border-b-blue-900 focus:border-b-2 placeholder:text-slate-900"
                    id="password" type="password" name="password" :value="old('password')" placeholder="Enter password" required />
                @error('password')
                    <i class="fa-solid fa-circle-exclamation absolute right-2 top-10 text-red-600"></i>
                    <x-forms.input-error>{{ $message }}</x-forms.input-error>
                @enderror
            </label>

            <label class="form-control w-full pb-6 relative">
                <x-forms.input-label for="confPassword" class="py-0 px-0 text-neutral-500">Password Confirmation</x-forms.input-label>
                <x-forms.text-input :error="$errors->first('confPassword')"
                    class="border-t-0 border-x-0 rounded-none px-0 py-0 border-b-slate-900 focus:outline-none focus:border-b-blue-900 focus:border-b-2 placeholder:text-slate-900"
                    id="confPassword" type="password" name="confPassword" :value="old('confPassword')" placeholder="Enter Password Confirmation" required />
                @error('confPassword')
                    <i class="fa-solid fa-circle-exclamation absolute right-2 top-10 text-red-600"></i>
                    <x-forms.input-error>{{ $message }}</x-forms.input-error>
                @enderror
            </label>

            <p class="text-base text-neutral-700 font-semibold my-4">Already have an account. <a href="/login"
                    class="bg-gradient-to-r from-blue-400 via-sky-600  to-blue-900 inline-block text-transparent bg-clip-text">Login here</a></p>

            <div class="flex flex-col w-full">
                {{-- <a href="/otp"> --}}
                <x-buttons.primary class="w-full block px-0 rounded-[4px] font-normal text-base" type="submit">Register</x-buttons.primary>
                {{-- </a> --}}
            </div>
        </form>
    </div>
@endsection

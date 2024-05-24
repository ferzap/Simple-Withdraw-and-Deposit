@extends('template.template')

@section('container')
    @if (session()->has('unauthorized'))
        <x-alert :error=true>{{ session('unauthorized') }}</x-alert>
    @endif
    @if (session()->has('success'))
        <x-alert :success=true>{{ session('success') }}</x-alert>
    @endif
    <div class="relative flex flex-row w-full bg-white min-h-[108px] shadow-md p-6 rounded-lg mb-6">
        <div class="flex flex-1 flex-col">
            <div class="relative">
                <p class="text-2xl text-neutral-900 mb-4 font-semibold">{{ session('user_name') }}</p>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined mr-2 !text-[80px] text-primary-700">
                        account_balance_wallet
                    </span>
                    <div class="flex flex-col">
                        <p class="text-2xl text-neutral-700">Balance</p>
                        <p class="text-5xl text-primary-700 font-semibold">Rp. <span id="balance">0</span></p>
                    </div>
                </div>
                {{-- <p class="text-sm text-neutral-500">Silahkan isi data dibawah untuk menambahkan data produk yang ada di fasyankes anda</p> --}}
            </div>
        </div>
    </div>
    <div class="flex flex-row gap-4">
        <a href="/withdraw" class="flex-1 w-full drop-shadow-md bg-white rounded-lg p-6 mr-2 cursor-pointer">
            <div class="text-center">
                <span class="material-symbols-outlined mr-2 !text-[80px] text-primary-700">
                    payments
                </span>
                <p class="text-5xl font-bold text-neutral-600">Withdraw</p>
            </div>
        </a>
        <a href="/deposit" class="flex-1 w-full drop-shadow-md bg-white rounded-lg p-6 cursor-pointer">
            <div class="text-center">
                <span class="material-symbols-outlined mr-2 !text-[80px] text-primary-700">
                    account_balance
                </span>
                <p class="text-5xl font-bold text-neutral-600">Deposit</p>
            </div>
        </a>
    </div>

    @push('script')
        <script type="module">
            $(document).ready(function() {
                getBalance()
            });
        </script>
    @endpush
@endsection

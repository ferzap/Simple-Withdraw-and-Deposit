@extends('template.template')

@section('container')
    {{-- @if (session()->has('unauthorized'))
        <div class="alert bg-red-500 w-full mx-auto mb-8 text-white dark:border-0">
            <i class="fa-solid fa-ban fa-xl"></i>
            <div>
                <div class="text-lg font-semibold">{{ session('unauthorized') }}</div>
            </div>
        </div>
    @endif --}}
    <div class="mb-5">
        <div class="relative flex flex-col justify-between w-full bg-white min-h-[80px] p-6 rounded-lg drop-shadow-md">
            <p class="text-3xl font-semibold mb-2 text-center">Withdraw Money from Your account</p>
            <p class="text-2xl font-normal mb-5 text-center">Your Balance: Rp. <span class="text-2xl font-normal" id="balance">0,00</span></p>
            <form id="form" action="/withdraw" method="POST">
                @csrf
                <label class="relative form-control flex md:flex-row w-full pb-3 mb-3">
                    <div class="w-full flex flex-col">
                        <x-forms.text-input class="grow modal-input text-right !text-3xl !h-20" id="amount" type="text" name="amount"
                            :value="old('amount')" placeholder="Enter Amount" autofocus />
                        <i class="!hidden fa-solid fa-circle-exclamation absolute right-3 top-12 md:top-3 text-red-600"></i>
                        <x-forms.input-error class="hidden">Error</x-forms>
                    </div>
                </label>
                <input type="hidden" name="type" value="withdraw">
                <div class="flex flex-row gap-4">
                    <div class="flex-1 w-full drop-shadow-md bg-white rounded-lg p-6 mr-2 cursor-pointer" id="10000">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-neutral-600">10.000</p>
                        </div>
                    </div>
                    <div class="flex-1 w-full drop-shadow-md bg-white rounded-lg p-6 cursor-pointer" id="20000">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-neutral-600">20.000</p>
                        </div>
                    </div>
                    <div class="flex-1 w-full drop-shadow-md bg-white rounded-lg p-6 cursor-pointer" id="50000">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-neutral-600">50.000</p>
                        </div>
                    </div>
                    <div class="flex-1 w-full drop-shadow-md bg-white rounded-lg p-6 cursor-pointer" id="100000">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-neutral-600">100.000</p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end my-4">
                    <button type="submit" class="button-primary w-full !h-20">
                        <p class="text-center text-2xl font-bold">Withdraw</p>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('script')
        <script type="module">
            $(document).ready(function() {
                let form = $('#form');
                let toast = $('.notif');
                let amount = document.getElementById('amount');

                getBalance()

                amount.addEventListener('keyup', function(e) {
                    amount.value = formatRupiah(this.value);
                });

                $('#10000').on('click', function(e) {
                    amount.value = formatRupiah('10000');
                });
                $('#20000').on('click', function(e) {
                    amount.value = formatRupiah('20000');
                });
                $('#50000').on('click', function(e) {
                    amount.value = formatRupiah('50000');
                });
                $('#100000').on('click', function(e) {
                    amount.value = formatRupiah('100000');
                });

                form.on('submit', function(e) {
                    e.preventDefault();
                    let url = $(this).prop('action');
                    let data = $(this).serialize();
                    let that = $(this);
                    let btn = $(that).find(':submit');
                    let input = form.find('input, select');

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: data,
                        beforeSend: function() {
                            btn.html(
                                `<span class="loading loading-spinner loading-lg"></span><p class="text-center text-2xl font-bold">Loading</p>`
                            );
                            btn.prop('disabled', true);
                            input.removeClass('form-input-error');
                            input.next('i').addClass('!hidden')
                            input.next('i').next('div').addClass('hidden').children('span').html(
                                '');
                            toast.removeClass('hidden');
                            toast.html('');
                        },
                    }).done(function(response) {
                        if (response.status) {
                            if(response.data.status == 1){
                                toast.html(`<x-toast :success=true >Successfully withdraw: ${formatCurrency(response.data.amount)}</x-toast>`)
                                    .children('div').fadeOut(3000);
                                getBalance()
                            } else {
                                toast.html(`<x-toast :error=true >Withdraw failed</x-toast>`)
                                    .children('div').fadeOut(3000);
                            }
                            {{-- ? CHANGE: HREF LOCATION --}}
                        } else {
                            if (response.code == 412) {
                                for (let [key, value] of Object.entries(response.errors)) {
                                    let input = $(document).find(`[name=${key}]`);
                                    input.addClass('form-input-error');
                                    input.next('i').removeClass('!hidden')
                                    input.next('i').next('div').removeClass('hidden').children('span')
                                        .html(
                                            value[0]);
                                }
                            } else {
                                toast.html(`<x-toast :error=true >${response.message}</x-toast>`)
                                    .children('div').fadeOut(3000);
                            }
                        }
                    }).fail(function(response) {
                        console.log(response);
                    }).always(function(response) {
                        btn.html('<p class="text-center text-2xl font-bold">Withdraw</p>');
                        btn.prop('disabled', false);
                    });
                })
            })
        </script>
    @endpush
@endsection

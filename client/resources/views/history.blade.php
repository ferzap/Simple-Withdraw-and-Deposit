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
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="border-neutral-200 border-2 rounded-md mb-5">
        <x-tables.body id="table">
            {{-- ? CHANGE: THE SLOT VALUE WITH YOUR TABLE HEADER --}}
            <x-slot:thead>
                <tr class="border-b-neutral-300 bg-neutral-200 text-neutral-500">
                    <th>Order ID</th>
                    <th>Datetime</th>
                    <th>Amount (Rp.)</th>
                    <th>Transaction</th>
                    <th>Status</th>
                </tr>
            </x-slot:thead>
            {{-- ? CHANGE: THE SLOT VALUE WITH YOUR TABLE HEADER --}}

            {{-- * TABLE LOADING --}}
            <tbody class="text-[13px]" id="t-body">
                <x-tables.loading :colspan=8></x-tables.loading>
            </tbody>
        </x-tables.body>
        {{-- ! end section table --}}

    </div>

    @push('script')
        <script type="module">
            $(document).ready(function() {
                let ids = []

                {{-- * INIT GET --}}
                get();

                {{-- * LIMIT --}}
                $('#limit').on('change', function() {
                    get()
                })

                {{-- * FETCH GET --}}

                function get() {
                    $.ajax({
                        type: "GET",
                        url: "{{ url('/history-transaction') }}", //CHANGE URL
                        data: {},
                        beforeSend: function() {
                            $('.table-count').html(`(0)`)
                            $('#selected-count').html(`0`)
                            let loading = `<x-tables.loading :colspan=8></x-tables.loading>`;
                            $('#t-body').html(loading)
                        },
                    }).done(function(response) {
                        generateData(response)
                    }).fail(function(response) {
                        console.log(response)
                    })
                }


                {{-- * RENDER TABLE DATA --}}

                function generateData(response) {
                    let data = response.data
                    let html = ``;

                    if (data.length > 0) {
                        data.forEach(value => {
                            {{-- ? CHANGE: THE RENDER DATA FOR TABLE --}}
                            let orderID = ''
                            let statusBadge = ''
                            let colorAmount = ''
                            let type = value.type.charAt(0).toUpperCase() + value.type.slice(1)
                            if (value.type == 'withdraw') {
                                orderID =
                                    `WD-${value.order_id}`;
                                colorAmount = 'text-red-700'
                            } else {
                                orderID =
                                    `TR-${value.order_id}`;
                                colorAmount = 'text-blue-700'
                            }

                            if (value.status == 'Success') {
                                statusBadge =
                                    `<x-badge :success=true class="whitespace-nowrap">${value.status}</x-badge>`;
                            } else {
                                statusBadge =
                                    `<x-badge :error=true class="whitespace-nowrap">${value.status}</x-badge>`;
                            }
                            let row = `
                                <tr class="border-b-neutral-300 border-b-[1px] hover:bg-[#F0F9FF]">
                                    <td>
                                        <p class="font-semibold">
                                            ${orderID}
                                        </p>
                                    </td>
                                    <td>
                                        <p class="font-semibold">
                                            ${moment(value.timestamp).format('MMMM Do YYYY, h:mm:ss a')}
                                        </p>
                                    </td>
                                    <td>
                                        <p class="font-semibold ${colorAmount}">
                                            ${formatCurrency(value.amount)}
                                        </p>
                                    </td>
                                    <td>
                                        <p class="font-semibold">
                                            ${type}
                                        </p>
                                    </td>
                                    <td>
                                        <p class="font-semibold">
                                            ${statusBadge}
                                        </p>
                                    </td>
                                </tr>
                            `
                            {{-- ? CHANGE: THE RENDER DATA FOR TABLE --}}

                            html += row;
                        });
                    } else {
                        html = `
                            <x-tables.no-data :colspan=8></x-tables.no-data>
                        `
                    }

                    $('#t-body').html(html);
                }
            })
        </script>
    @endpush
@endsection

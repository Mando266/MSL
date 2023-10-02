@extends('layouts.app')

@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('port-charges.index') }}">Port Charges</a>
                                </li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Invoice</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                        <br>
                        <div class="d-flex justify-content-end">
                            <div class="mx-1">
                                <a href="{{ route('port-charge-invoices.create') }}" class="btn btn-primary">Create New
                                    Invoice</a>
                            </div>
                            <div class="mx-1">
                                <a class="btn btn-dark" id="export-date"
                                   href="{{ route('port-charge-invoices.export-date') }}">Export By Date</a>
                            </div>
                            <div class="mx-1">
                                <button class="btn btn-dark" id="export-current">Export Current</button>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5 mb-3 mx-2">
                        <div class="col-md-9">
                            <select id="searchSelect" class="js-example-basic-multiple js-states form-control">
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex flex-row">
                                <div class="mr-2">
                                    <button class="btn btn-primary" id="searchButton">Search</button>
                                </div>
                                <div class="mr-2">
                                    <button class="btn btn-dark" id="reset-search">Reset</button>
                                </div>
                                <div class="mr-2">
                                    <button id="cancel-search" class="btn btn-danger">Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form id="search-form" class="ml-3" method="GET" action="{{ route('port-charge-invoices.index') }}">
                        @csrf
                        <input name="q" id="search-term" hidden value="{{ old('q') }}">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="from_date">From</label>
                                <input class="form-control input-search" id="from_date" type="date" name="from"
                                       value="{{ old('from', request()->input('from')) }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="to_date">To</label>
                                <input class="form-control input-search" id="to_date" type="date" name="to"
                                       value="{{ old('to', request()->input('to')) }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="line_ids">Shipping Line</label>
                                <select class="selectpicker form-control input-search" id="line_ids"
                                        data-live-search="true"
                                        name="line_id[]" data-size="10"
                                        title="{{ trans('forms.select') }}" multiple>
                                    @foreach ($lines as $item)
                                        <option value="{{ $item->id }}" {{ in_array($item->id, (array)old('line_id', request()->input('line_id'))) ? 'selected' : '' }}>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                    <div class="widget-content widget-content-area">
                        <div id="table-results">
                            @include('port_charge.invoice.__table-results')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="loadingSpinner" class="d-none">
        <i class="fas fa-spinner fa-spin fa-4x"></i> Loading...
    </div>

@endsection
@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet"/>
    <style>
        #loadingSpinner {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            z-index: 9999;
        }

    </style>
@endpush
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            const searchForm = $("#search-form")
            const spinner = $("#loadingSpinner")
            let asc = false
            
            $(document).on('click', '.sort-results', function () {
                console.log(asc)
                asc = !asc
                let sortBy = $(this).data("name")
                handleSearch('search', sortBy, asc);
            });


            $('#searchButton').click(() => {
                handleSearch('search');
            });

            $("#cancel-search").click(() => {
                $("#reset-search").trigger('click');
                handleSearch('cancel');
            });
            $("#reset-search").on('click', () => {
                $(".input-search").val([])
                $('.selectpicker').selectpicker('refresh')
            })
            $('#searchSelect').select2({
                ajax: {
                    url: "{{ route('port-charge-invoices.search') }}",
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                let text = `${item.invoice_no}`;
                                if (item.port) {
                                    text += ` - ${item.port.name}`;
                                }
                                if (item.uniqueVessels) {
                                    $.each(item.uniqueVessels, function (index, vessel) {
                                        text += ` - ${vessel.name}`;
                                    });
                                }

                                if (item.uniqueVoyages) {
                                    $.each(item.uniqueVoyages, function (index, voyage) {
                                        text += ` - ${voyage.voyage_no}`;
                                        if (voyage.leg) {
                                            text += `/${voyage.leg.name}`;
                                        }
                                    });
                                }
                                return {
                                    id: item.id,
                                    text: text
                                };
                            })
                        };
                    },
                    cache: true
                },
                placeholder: 'Search Invoices',
                minimumInputLength: 2
            });

            $('#searchSelect').on('select2:select', function (e) {
                var selectedInvoiceId = e.params.data.id;
                if (selectedInvoiceId) {
                    window.location.href = "{{ route('port-charge-invoices.show', '') }}/" + selectedInvoiceId;
                }
            });

            $(document).on('input', '.select2-search__field', function () {
                $("#search-term").val($(this).val())
            });

            $('#export-current').click(() => {
                searchForm.attr('method', 'post');
                searchForm.attr('action', '{{ route('port-charge-invoices.export-current') }}');
                searchForm.find('input[name="_token"]').prop('disabled', false);

                searchForm.submit();
            });


            async function handleSearch(action, sortBy = null, asc = null) {
                try {
                    spinner.removeClass('d-none');
                    searchForm.attr('method', 'get');
                    searchForm.find('input[name="_token"]').prop('disabled', true);

                    let query = searchForm.serialize();
                    if (sortBy) {
                        query += `&sort_by=${sortBy}&ascending=${asc ? 'asc' : 'desc'}`;
                    }

                    const {data} = await axios.get(`port-charge-invoices${action === 'search' ? '?' + query : ''}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    $("#table-results").html(data);
                } catch (error) {
                    console.error(error)
                } finally {
                    spinner.addClass('d-none');
                }
            }

            $('.show_confirm').click(function (event) {
                var form = $(this).closest("form");
                var name = $(this).data("name");
                event.preventDefault();
                swal({
                    title: `Are you sure you want to delete this Invoice?`,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            form.submit();
                        }
                    });
            });
        });
    </script>
@endpush

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
                        <div class="col-md-10">
                            <select id="searchSelect" class="js-example-basic-multiple js-states form-control">
                            </select>
                        </div>
                        <div class="col-md-2">
                            <div class="d-flex flex-row">
                                <div class="mr-2">
                                    <button class="btn btn-primary" id="searchButton">Search</button>
                                </div>
                                <div class="mr-2">
                                    <button class="btn btn-dark" id="reset-select">Reset</button>
                                </div>
                                <div class="mr-2">
                                    <a href="{{ route('port-charge-invoices.index') }}"
                                       class="btn btn-danger">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form id="search-form" method="GET" action="{{ route('port-charge-invoices.index') }}">
                        @csrf
                        <input name="q" id="search-term" hidden value="{{ old('q') }}">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="from_date">From</label>
                                <input class="form-control" id="from_date" type="date" name="from"
                                       value="{{ old('from', request()->input('from')) }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="to_date">To</label>
                                <input class="form-control" id="to_date" type="date" name="to"
                                       value="{{ old('to', request()->input('to')) }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="line_ids">Shipping Line</label>
                                <select class="selectpicker form-control" id="line_ids" data-live-search="true"
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
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                <tr>
                                    <th>Invoice Number</th>
                                    <th>Country</th>
                                    <th>Line</th>
                                    <th>Port</th>
                                    <th>Vessel</th>
                                    <th>Voyage</th>
                                    <th>Total USD</th>
                                    <th>Invoice EGP</th>
                                    <th>Invoice USD</th>
                                    <th class='text-center' style='width:100px;'></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($invoices as $invoice)
                                    <tr>
                                        <td>{{ $invoice->invoice_no }}</td>
                                        <td>{{ $invoice->country->name ?? '' }}</td>
                                        <td>{{ $invoice->line->name ?? '' }}</td>
                                        <td>{{ $invoice->port->name ?? '' }}</td>
                                        <td>{{ $invoice->vesselsNames() }}</td>
                                        <td>{{ $invoice->voyagesNames() }}</td>
                                        <td>{{ $invoice->total_usd }}</td>
                                        <td>{{ $invoice->invoice_egp }}</td>
                                        <td>{{ $invoice->invoice_usd }}</td>
                                        <td class="text-center">
                                            <ul class="table-controls">
                                                <li>
                                                    <a href="{{route('port-charge-invoices.edit', $invoice->id)}}"
                                                       data-toggle="tooltip" data-placement="top" title=""
                                                       data-original-title="edit">
                                                        <i class="far fa-edit text-success"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{route('port-charge-invoices.show', $invoice->id)}}"
                                                       data-toggle="tooltip" data-placement="top" title=""
                                                       data-original-title="show">
                                                        <i class="far fa-eye text-primary"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{route('port-charge-invoices.destroy', $invoice->id)}}"
                                                          method="post">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button style="border: none; background: none;" type="submit"
                                                                class="fa fa-trash text-danger show_confirm"></button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="text-center">
                                        <td colspan="20">{{ trans('home.no_data_found')}}</td>
                                    </tr>
                                @endforelse

                                </tbody>

                            </table>
                        </div>
                        <div class="paginating-container">
                            {{ $invoices->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet"/>
@endpush
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            const searchForm = $("#search-form")
            
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
                
                searchForm.submit();
            });
            
            $('#searchButton').click(() => {
                searchForm.attr('method', 'get');
                searchForm.attr('action', '{{ route('port-charge-invoices.index') }}');
                searchForm.find('input[name="_token"]').prop('disabled', true);
                
                searchForm.submit();
            });

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

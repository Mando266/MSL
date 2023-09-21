@extends('layouts.app')
{{--@php--}}
{{--    $a = \App\Models\PortChargeInvoiceVoyage::all();--}}
{{--    $a->map(function($r){--}}
{{--        if(! $r->vessel_id){--}}
{{--            $vessel = \App\Models\Voyages\Voyages::find($r->voyages_id)->vessel;--}}
{{--            $r->update(['vessel_id' => $vessel->id]);--}}
{{--        }--}}
{{--    });--}}
{{--    dd('done');--}}
{{--@endphp--}}
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
                        <div class="row">
                            <div class="col-md-10 text-right mb-12">
                                <a class="btn btn-success" id="export-date"
                                   href="{{ route('port-charge-invoices.export-date') }}"
                                >Export By Date
                                </a>
                            </div>
                            <div class="col-md-2 text-right mb-12">
                                <a href="{{ route('port-charge-invoices.create') }}" class="btn btn-primary">Create New
                                    Invoice</a>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5 mb-3 mx-2">
                        <div class="col-md-10">
                            <select id="searchSelect" class="js-example-basic-multiple js-states form-control">
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary" id="searchButton">Search</button>
                        </div>
                    </div>


                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                <tr>
                                    <th>Invoice Number</th>
                                    <th>Country</th>
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
                                        <th>{{ $invoice->invoice_no }}</th>
                                        <th>{{ $invoice->country->name ?? '' }}</th>
                                        <th>{{ $invoice->port->name ?? '' }}</th>
                                        <th>{{ $invoice->vesselsNames() }}</th>
                                        <th>{{ $invoice->voyagesNames() }}</th>
                                        <th>{{ $invoice->total_usd }}</th>
                                        <th>{{ $invoice->invoice_egp }}</th>
                                        <th>{{ $invoice->invoice_usd }}</th>
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
    <script type="text/javascript">
        $(document).ready(function () {
            var latestSearchTerm = "";

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
                latestSearchTerm = $(this).val();
            });

            $('#searchButton').click(function () {
                if (latestSearchTerm) {
                    window.location.href = "{{ route('port-charge-invoices.index') }}?q=" + latestSearchTerm;
                } else {
                    swal("Please enter a search term.");
                }
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

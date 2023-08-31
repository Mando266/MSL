@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Port Charges</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Invoice</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                        <br>
                        <div class="row">
                            <div class="col-md-12 text-right mb-12">
                                <a href="{{ route('port-charge-invoices.create') }}" class="btn btn-primary">Create New
                                    Invoice</a>
                            </div>
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
                                    <th>Total EGP</th>
                                    <th class='text-center' style='width:100px;'></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($invoices as $invoice)
                                    <tr>
                                        <th>{{ $invoice->invoice_no }}</th>
                                        <th>{{ $invoice->country->name ?? '' }}</th>
                                        <th>{{ $invoice->port->name ?? '' }}</th>
                                        <th>{{ $invoice->vessel->name ?? '' }}</th>
                                        <th>{{ $invoice->voyage->voyage_no ?? '' }}</th>
                                        <th>{{ $invoice->total_usd }}</th>
                                        <th>{{ $invoice->total_egp }}</th>
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
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script type="text/javascript">

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
    </script>
@endpush

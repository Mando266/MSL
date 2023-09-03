@extends('layouts.app')

@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-md-12">
                <h1>Show Invoice Data</h1>
                <ul>
                    <li><strong>Payment Type:</strong> {{ $invoice->payment_type }}</li>
                    <li><strong>Invoice Type:</strong> {{ $invoice->invoice_type }}</li>
                    <li><strong>Invoice No:</strong> {{ $invoice->invoice_no }}</li>
                    <li><strong>Invoice Date:</strong> {{ $invoice->invoice_date }}</li>
                    <li><strong>Exchange Rate:</strong> {{ $invoice->exchange_rate }}</li>
                    <li><strong>Invoice Status:</strong> {{ $invoice->invoice_status }}</li>
                    <li><strong>Country:</strong> {{ $invoice->country->name ?? '' }}</li>
                    <li><strong>Port:</strong> {{ $invoice->port->name ?? '' }}</li>
                    <li><strong>Shipping Line:</strong> {{ $invoice->shippingLine->name ?? '' }}</li>
                    <li><strong>Vessel Name:</strong> {{ $invoice->vessel->name ?? '' }}</li>
                    <li><strong>Voyage No:</strong> {{ $invoice->voyage->voyage_no ?? '' }}</li>
                    <li><strong>Applied Costs:</strong> {{ $invoice->selected_costs }}</li>
                    <li><strong>Total EGP:</strong> {{ $invoice->total_egp }}</li>
                    <li><strong>Total USD:</strong> {{ $invoice->total_usd }}</li>
                </ul>

                <div class="table-container">
                    <table class='table table-bordered table-hover table-condensed mb-4'>
                        <thead>
                        <tr>
                            <th style="min-width: 222px">Type</th>
                            <th style="min-width: 222px">Service</th>
                            <th style="min-width: 222px">BL NO</th>
                            <th style="min-width: 222px">CONTAINER NO</th>
                            <th style="min-width: 222px">TS</th>
                            <th style="min-width: 222px">SHIPMENT TYPE</th>
                            <th style="min-width: 222px">QUOTATION TYPE</th>
                            <th data-field="thc">THC</th>
                            <th data-field="storage">STORAGE</th>
                            <th data-field="power">POWER</th>
                            <th data-field="shifting">SHIFTING</th>
                            <th data-field="disinf">DISINF</th>
                            <th data-field="hand-fes-em">HAND-FES-EM</th>
                            <th data-field="gat-lift-off-inbnd-em-ft40">GAT-LIFT OFF-INBND-EM-FT40</th>
                            <th data-field="gat-lift-on-inbnd-em-ft40">GAT-LIFT ON-INBND-EM-FT40</th>
                            <th data-field="pti">PTI</th>
                            <th data-field="add-plan">ADD-PLAN</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($invoice->rows as $row)
                            <tr>
                                <td>{{ $row->portCharge->name }}</td>
                                <td>{{ $row->service }}</td>
                                <td>{{ $row->bl_no }}</td>
                                <td>{{ $row->container_no }}</td>
                                <td>{{ $row->ts }}</td>
                                <td>{{ $row->shipment_type }}</td>
                                <td>{{ $row->quotation_type }}</td>
                                <td>{{ $row->thc }}</td>
                                <td>{{ $row->storage }}</td>
                                <td>{{ $row->power }}</td>
                                <td>{{ $row->shifting }}</td>
                                <td>{{ $row->disinf }}</td>
                                <td>{{ $row->hand_fes_em }}</td>
                                <td>{{ $row->gat_lift_off_inbnd_em_ft40 }}</td>
                                <td>{{ $row->gat_lift_on_inbnd_em_ft40 }}</td>
                                <td>{{ $row->pti }}</td>
                                <td>{{ $row->add_plan }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="col-md-12 text-center">
                    <a href="{{ route('port-charge-invoices.index') }}" class="btn btn-primary mt-3">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        /*input {*/
        /*    min-width: 100px;*/
        /*}*/

        .table-container {
            overflow-x: auto;
            max-width: 100%;
        }
    </style>
@endpush

@extends('layouts.app')
@section('content')
<div class="layout-px-spacing" style="background-color: #fff;">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading hide">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('invoice.index')}}">Invoice </a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Invoice Confirmation</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <div class="col-md-4">
                    <img src="{{asset('assets/img/msl.png')}}" style="width: 350px;" alt="logo">
                </div>
</br>
</br>
                <table class="col-md-12 tableStyle" style="border-style: hidden !important;">
                    <thead>
                        <tr>
                            <th class="text-center  underline">EXPORT THC PROFORMA INVOICE</th>
                        </tr>
                    </thead>
                </table>

                <h4 style="font-size: 18px !important; font-weight: bolder !important;">Customer: <span class="entry">{{ $invoice->customer }}</span></h4>

                <table class="col-md-12 tableStyle">
                    <tbody>
                        <tr>
                            <td class="col-md-2 tableStyle text-center" >Vessel</td>
                            <td class="col-md-2 tableStyle text-center" ><span class="entry">{{ optional($invoice->bldraft->voyage->vessel)->name }}</span></td>
                            <td class="col-md-2 tableStyle text-center" >Origin Port</td>
                            <td class="col-md-2 tableStyle text-center" ><span class="entry">{{ optional($invoice->bldraft->loadPort)->code }}</span></td>
                            <td class="col-md-2 tableStyle text-center" >G. weight</td>
                            <td class="col-md-2 tableStyle text-center" ><span class="entry">{{ $gross_weight }}</span></td>
                        </tr>
                        <tr>
                            <td class="col-md-2 tableStyle text-center" >Voyage No</td>
                            <td class="col-md-2 tableStyle text-center" ><span class="entry">{{ optional($invoice->bldraft->booking)->ref_no }}</span></td>
                            <td class="col-md-2 tableStyle text-center" >POL</td>
                            <td class="col-md-2 tableStyle text-center" ><span class="entry">{{ optional($invoice->bldraft->loadPort)->code }}</span></td>
                            <td class="col-md-2 tableStyle text-center" >IMO Class</td>
                            <td class="col-md-2 tableStyle text-center" ><input type="text" style="overflow: hidden; border-style: hidden;"></td>
                        </tr>
                        <tr>
                            <td class="col-md-2 tableStyle text-center" >Arrival Date</td>
                            <td class="col-md-2 tableStyle text-center" ><span class="entry">{{$firstVoyagePort->eta}}</span></td>
                            <td class="col-md-2 tableStyle text-center" >POD</td>
                            <td class="col-md-2 tableStyle text-center" ><span class="entry">{{ optional($invoice->bldraft->dischargePort)->code }}</span></td>
                            <td class="col-md-2 tableStyle text-center" >Cntr. Type(s)</td>
                            <td class="col-md-2 tableStyle text-center" ><span class="entry">{{$invoice->blDraft->blDetails->count()}} X {{ optional($invoice->blDraft->equipmentsType)->name }}</span></td>
                        </tr>
                        <tr>
                            <td class="col-md-2 tableStyle text-center" >B/L No.</td>
                            <td class="col-md-2 tableStyle text-center" ><span class="entry">{{ optional($invoice->bldraft)->ref_no }}</span></td>
                            <td class="col-md-2 tableStyle text-center" >Final Dest</td>
                            <td class="col-md-2 tableStyle text-center" ><span class="entry">{{ optional($invoice->bldraft->placeOfDelivery)->code }}</span></td>
                            <td class="col-md-2 tableStyle text-center" ></td>
                            <td class="col-md-2 tableStyle text-center" ></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <table class="col-md-12 tableStyle">
                    <thead>
                        <tr>
                            <th class="col-md-1 tableStyle">S</th>
                            <th class="col-md-5 tableStyle text-center">Description Of Charges</th>
                            <th class="col-md-2 tableStyle text-center">Amount</th>
                            <th class="col-md-2 tableStyle text-center">VAT</th>
                            <th class="col-md-2 tableStyle text-center">Total(USD)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->chargeDesc as $chargeDesc)
                        <tr>
                            <td class="col-md-4 tableStyle">{{ $chargeDesc->charge_description }}</td>
                            <td class="col-md-3 tableStyle text-center">{{ $chargeDesc->size_small == 0? '0': $chargeDesc->size_small }}/{{ $chargeDesc->size_large == 0? '0': $chargeDesc->size_large }}</td>
                            <td class="col-md-3 tableStyle text-center">{{ $chargeDesc->size_small == 0? '0': $chargeDesc->size_small }}/{{ $chargeDesc->size_large == 0? '0': $chargeDesc->size_large }}</td>
                            <td class="col-md-2 tableStyle text-center">{{ $chargeDesc->total_amount }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="col-md-10 tableStyle text-right" colspan="3">TOTAL USD</td>
                            <td class="col-md-2 tableStyle text-center">{{ $total }}</td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <br>
                <table class="col-md-12 tableStyle">
                    <thead>
                        <tr>
                            <th class="tableStyle">Total amount in words in US $</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="entry" style="padding: .75rem;">{{ $USD }} Dollars <br><br></td>
                        </tr>
                    </tbody>
                </table>

                </div>
                <div class="row">
                        <div class="col-md-12 text-center">
                <button onclick="window.print()" class="btn btn-primary hide mt-3">Print This Invoice</button>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('styles')
<style>
    @media print {
    .search_row,
    .hide {
        display: none !important;
        }
    }
    .entry{
        font-size: 14px !important;
    }
    .tableStyle {
        font-size: 18px !important;
        font-weight: bolder !important;
        border: 1px solid #000 !important;
        margin-bottom: 1rem;
        height: 50px;
        color: black;
        text-transform: uppercase;
        padding: .75rem;
    }
    .underline{
        text-decoration: underline;
    }
    .thstyle {
        background-color: #80808061 !important;
        color: #000 !important;
        height: 50px;
        border: 1px solid #000 !important;
        font-size: 16px !important;
        font-weight: bolder !important;
    }
</style>
@endpush
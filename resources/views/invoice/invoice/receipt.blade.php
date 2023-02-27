@extends('layouts.app')
@section('content')
<div class="layout-px-spacing" style="background-color: #fff;">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading hide">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('invoice.index')}}">Receipt </a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Invoice Confirmation</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="row">
                        <div class="col-md-6 text-left">
                            <img src="{{asset('assets/img/msl.png')}}" style="width: 350px; height: 97.6px;" alt="logo">
                        </div>
                        <div class="col-md-6 text-right">
                            <img src="{{asset('assets/img/mas logo.png')}}" style="width: 400px; height: 143.6px;" alt="logo">
                        </div>
                    </div>
                <br>
                <br>
                <table class="col-md-12 tableStyle" style="border-style: hidden !important;">
                    <thead>
                        <tr>
                            <th class="text-center  underline" style="font-size: 24px !important;">EXPORT THC PROFORMA INVOICE</th>
                        </tr>
                    </thead>
                </table>

                <h4 style="font-size: 18px !important; font-weight: bolder !important;">Customer: <span class="entry">{{ $invoice->customer }}</span></h4>
                <table class="col-md-12 tableStyle">
                    <tbody>
                        <tr>
                            <td class="col-md-2 tableStyle">Customer Name:</td>
                        </tr>
                    </tbody>
                </table>
                <table class="col-md-12 tableStyle">
                    <tbody>
                        <tr>
                            <td class="col-md-2 tableStyle text-center" >Vessel</td>
                            <td class="col-md-2 tableStyle text-center" ><span class="entry">{{ optional($invoice->bldraft->voyage->vessel)->name }}</span></td>
                            <td class="col-md-2 tableStyle text-center" >Origin Port</td>
                            <td class="col-md-2 tableStyle text-center" ><span class="entry">{{ optional($invoice->bldraft->loadPort)->code }}</span></td>
                            <td class="col-md-2 tableStyle text-center" >G. weight</td>
                            <td class="col-md-2 tableStyle text-center" ><span class="entry">{{ $gross_weight }} KGM</span></td>
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
                            <td class="col-md-2 tableStyle text-center" style="font-size: 15px !important;">Arrival Date</td>
                            <td class="col-md-2 tableStyle text-center" ><span class="entry">{{$firstVoyagePort->eta}}</span></td>
                            <td class="col-md-2 tableStyle text-center" >POD</td>
                            <td class="col-md-2 tableStyle text-center" ><span class="entry">{{ optional($invoice->bldraft->dischargePort)->code }}</span></td>
                            <td class="col-md-2 tableStyle text-center" style="font-size: 15px !important;">Cntr. Type(s)</td>
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
                            <th class="col-md-1 tableStyle text-center">S</th>
                            <th class="col-md-5 tableStyle text-center">Description Of Charges</th>
                            <th class="col-md-2 tableStyle text-center">Amount</th>
                            <th class="col-md-2 tableStyle text-center">VAT</th>
                            <th class="col-md-2 tableStyle text-center">Total(USD)</th>
                            <th class="col-md-2 tableStyle text-center">Total(EGP)</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->chargeDesc as $key => $chargeDesc)
                        <tr>
                            <td class="col-md-1 tableStyle text-center"><span class="entry">{{ $key+1 }}</span></td>
                            <td class="col-md-5 tableStyle"><span class="entry">{{ $chargeDesc->charge_description }}</span></td>
                            <td class="col-md-2 tableStyle text-center"><span class="entry">{{ $chargeDesc->size_small }}</span></td>
                            <td class="col-md-2 tableStyle text-center"><span class="entry">{{ $chargeDesc->size_small * 0 }}</span></td>
                            <td class="col-md-2 tableStyle text-center"><span class="entry">{{ $chargeDesc->total_amount }}</span></td>
                            <td class="col-md-2 tableStyle text-center"><span class="entry">{{ $chargeDesc->total_egy}}</span></td>

                        </tr>
                        @endforeach
                        <tr>
                            <td class="col-md-6 tableStyle text-center" colspan="2"><span class="entry">GRAND TOTAL</span></td>
                            <td class="col-md-2 tableStyle text-center"><span class="entry">{{ $amount }}</span></td>
                            <td class="col-md-2 tableStyle text-center"><span class="entry">{{ $vat }}</span></td>
                            <td class="col-md-2 tableStyle text-center"><span class="entry">{{ $total }}</span></td>
                            <td class="col-md-2 tableStyle text-center"><span class="entry">{{ $total_eg }}</span></td>
                        </tr>
                        <tr>
                            <td class="col-md-2 tableStyle" colspan="6"><span class="entry">{{ $USD }} Dollar</span></td>
                        </tr>
                        <tr>
                            <td class="col-md-2 tableStyle " colspan="6"><span class="entry">{{ $EGP }} EGP</span></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <br>
                <br>
                <h4 style="font-size: 14px;">Bank USD details: Ahli United Bank – AUB &nbsp; 0007169620002 &nbsp; IBAN:	EG020020000700000007169620002<h4>
                <h4 style="font-size: 14px;">Bank EGP &nbsp;details: Ahli United Bank – AUB &nbsp; 0007169620001 &nbsp; IBAN:	EG290020000700000007169620001<h4>
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
        font-size: 16px !important;
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
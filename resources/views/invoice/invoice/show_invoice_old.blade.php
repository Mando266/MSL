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
                    <div class="row">
                        <div class="col-md-6 text-left">
                            <img src="{{asset('assets/img/msl.png')}}" style="width: 350px; height: 97.6px;" alt="logo">
                        </div>
                        <div class="col-md-6 text-right">
                            {{-- <img src="{{asset('assets/img/mas logo.png')}}" style="width: 400px; height: 143.6px;" alt="logo"> --}}
                        </div>
                    </div>
                <br>
                <br>
                <table class="col-md-12 tableStyle" style="border-style: hidden !important;">
                    <thead>
                        <tr>

                            @if(optional(optional(optional($invoice->bldraft)->booking)->quotation)->shipment_type == "Import")
                                @if($invoice->invoice_status == "draft")
                                <th class="text-center  underline" style="font-size: 24px !important;">IMPORT PROFORMA INVOICE</th>
                                @else
                                <th class="text-center  underline" style="font-size: 24px !important;">IMPORT INVOICE</th>
                                @endif
                            @else
                                @if($invoice->invoice_status == "draft")
                                <th class="text-center  underline" style="font-size: 24px !important;">EXPORT PROFORMA INVOICE</th>
                                @else
                                <th class="text-center  underline" style="font-size: 24px !important;">EXPORT INVOICE</th>
                                @endif
                            @endif
                        </tr>
                    </thead>
                </table>
                <table class="col-md-12 tableStyle">
                    <tbody>
                        <tr>
                            <td class="col-md-3 tableStyle text-center"><span class="entry">Invoice Number</span></td>
                            <td class="col-md-3 tableStyle text-center"><span class="user">{{ $invoice->invoice_no }}</span></td>
                            <td class="col-md-3 tableStyle text-center"><span class="entry">Date</span></td>
                            <td class="col-md-3 tableStyle text-center"><span class="user">{{ $invoice->date->format('Y-m-d') }}</span></td>
                        </tr>
                    </tbody>
                </table>
                <table class="col-md-12 tableStyle">
                    <tbody>
                        <tr>
                            <td class="col-md-2 tableStyle text-center"><span class="entry">Customer Name</span></td>
                            <td class="col-md-2 tableStyle text-center"><span class="user">{{ $invoice->customer }}</span></td>
                            <td class="col-md-1 tableStyle text-center"><span class="entry">Tax No.</span></td>
                            <td class="col-md-2 tableStyle text-center"><span class="user">{{ optional($invoice->customerShipperOrFfw)->tax_card_no }}</span></td>
                            <td class="col-md-1 tableStyle text-center"><span class="entry">Address</span></td>
                            <td class="col-md-4 tableStyle text-center"><span class="user">{{ optional($invoice->customerShipperOrFfw)->address }}</span></td>
                        </tr>
                    </tbody>
                </table>
                
                <table class="col-lg-12 tableStyle">
                    <tbody>
                        <tr>
                            <td class="col-md-2 tableStyle text-center" >Vessel</td>
                            <td class="col-md-2 tableStyle text-center" ><span class="entry">{{ $invoice->bldraft_id == 0 ? optional(optional($invoice->voyage)->vessel)->name : optional($invoice->bldraft->voyage->vessel)->name }}</span></td>
                            <td class="col-md-2 tableStyle text-center" >Origin Port</td>
                            <td class="col-md-2 tableStyle text-center" ><span class="entry">{{ $invoice->bldraft_id == 0 ? optional($invoice->loadPort)->code : optional($invoice->bldraft->loadPort)->code }}</span></td>
                            <td class="col-md-2 tableStyle text-center">Arrival Date</td>
                            @if(optional(optional(optional($invoice->bldraft)->booking)->quotation)->shipment_type == "Import")
                            <td class="col-md-2 tableStyle text-center" ><span class="entry">{{optional($firstVoyagePortdis)->eta}}</span></td>
                            @else
                            <td class="col-md-2 tableStyle text-center" ><span class="entry">{{optional($firstVoyagePort)->eta}}</span></td>
                            @endif                 
                       </tr>
                        <tr>
                            <td class="col-md-2 tableStyle text-center" >Voyage No</td>
                            <td class="col-md-2 tableStyle text-center" ><span class="entry">{{ $invoice->bldraft_id == 0 ? optional($invoice->voyage)->voyage_no : optional($invoice->bldraft->voyage)->voyage_no }}</span></td>
                            <td class="col-md-2 tableStyle text-center" >POL</td>
                            <td class="col-md-2 tableStyle text-center" ><span class="entry">{{ $invoice->bldraft_id == 0 ? optional($invoice->loadPort)->code : optional($invoice->bldraft->loadPort)->code }}</span></td>
                            <td class="col-md-2 tableStyle text-center">Departure Date</td>
                            @if(optional(optional(optional($invoice->bldraft)->booking)->quotation)->shipment_type == "Import")
                            <td class="col-md-2 tableStyle text-center" ><span class="entry">{{optional($firstVoyagePortdis)->etd}}</span></td>
                            @else
                            <td class="col-md-2 tableStyle text-center" ><span class="entry">{{optional($firstVoyagePort)->etd}}</span></td>
                            @endif
                        </tr>                         
                        <tr>
                            <td class="col-md-2 tableStyle text-center">IMO ClASS</td>
                            <td class="col-md-2 tableStyle text-center" ></td>
                            <td class="col-md-2 tableStyle text-center" >POD</td>
                            <td class="col-md-2 tableStyle text-center" ><span class="entry">{{ $invoice->bldraft_id == 0 ? optional($invoice->dischargePort)->code : optional($invoice->bldraft->dischargePort)->code }}</span></td>
                            <td class="col-md-2 tableStyle text-center">Cntr. Type(s)</td>
                            <td class="col-md-2 tableStyle text-center" ><span class="entry">{{$invoice->bldraft_id == 0 ? $invoice->qty : $invoice->blDraft->blDetails->count()}} @if($invoice->bldraft_id == 0 && optional($invoice->equipmentsType)->name != null ) X  @elseif($invoice->bldraft_id != 0) X @endif  {{ $invoice->bldraft_id == 0 ? optional($invoice->equipmentsType)->name : optional($invoice->blDraft->equipmentsType)->name }}</span></td>
                        </tr>
                        <tr>
                            <td class="col-md-2 tableStyle text-center" >B/L No.</td>
                            <td class="col-md-2 tableStyle text-center" ><span class="entry">{{ $invoice->bldraft_id == 0 ? optional($invoice->bldraft)->ref_no : optional($invoice->bldraft)->ref_no }}</span></td>
                            <td class="col-md-2 tableStyle text-center" >Final Dest</td>
                            <td class="col-md-2 tableStyle text-center" ><span class="entry">{{ $invoice->bldraft_id == 0 ? optional($invoice->placeOfDelivery)->code : optional($invoice->bldraft->placeOfDelivery)->code }}</span></td>
                            <td class="col-md-2 tableStyle text-center" ></td>
                            <td class="col-md-2 tableStyle text-center" ></td>
                        </tr>
                 
                    </tbody>
                </table>
                <br>
                <table class="col-md-12 tableStyle">
                    <tbody>
                        <tr>
                            <th class="col-md-1 tableStyle text-center">S</th>
                            <th class="col-md-5 tableStyle text-center">Description Of Charges</th>
                            <th class="col-md-2 tableStyle text-center">Amount (USD)</th>
                            <th class="col-md-2 tableStyle text-center">VAT</th>
                            @if( $invoice->add_egp != 'onlyegp')
                            <th class="col-md-2 tableStyle text-center">Total(USD)</th>
                            @endif
                            @if($invoice->add_egp == 'true' || $invoice->add_egp == 'onlyegp')
                            <th class="col-md-2 tableStyle text-center">Total(EGP)</th>
                            @endif
                        </tr>
                        @foreach($invoice->chargeDesc as $key => $chargeDesc)
                        <tr>
                            <td class="col-md-1 tableStyle text-center"><span class="entry">{{ $key+1 }}</span></td>
                            <td class="col-md-5 tableStyle"><span class="entry">{{ $chargeDesc->charge_description }}</span></td>
                            <td class="col-md-2 tableStyle text-center"><span class="entry">{{ $chargeDesc->size_small }}</span></td>
                            <td class="col-md-2 tableStyle text-center"><span class="entry">{{ (int)$chargeDesc->size_small * 0 }}</span></td>
                            @if( $invoice->add_egp != 'onlyegp')
                            <td class="col-md-2 tableStyle text-center"><span class="entry">{{ $chargeDesc->total_amount }}</span></td>
                            @endif
                            @if($invoice->add_egp == 'true' || $invoice->add_egp == 'onlyegp')
                            <td class="col-md-2 tableStyle text-center"><span class="entry">{{ $chargeDesc->total_egy}}</span></td>
                            @endif
                        </tr>
                        @endforeach
                        <tr>
                            <td class="col-md-6 tableStyle text-center" colspan="4"><span class="entry">GRAND TOTAL</span></td>
                            <!-- <td class="col-md-2 tableStyle text-center"><span class="entry"></span></td>
                            <td class="col-md-2 tableStyle text-center"><span class="entry"></span></td> -->
                            @if( $invoice->add_egp != 'onlyegp')
                            <td class="col-md-2 tableStyle text-center"><span class="entry">{{ $total }}</span></td>
                            @endif
                            @if($invoice->add_egp == 'true' || $invoice->add_egp == 'onlyegp')
                            <td class="col-md-2 tableStyle text-center"><span class="entry">{{ $total_eg }}</span></td>
                            @endif
                        </tr>
                        @if( $invoice->add_egp != 'onlyegp')
                        <tr>
                            <td class="col-md-2 tableStyle" colspan="6"><span class="entry">{{ $USD }} Dollar</span></td>
                        </tr>
                        @endif
                        @if($invoice->add_egp == 'true' || $invoice->add_egp == 'onlyegp')
                        <tr>
                            <td class="col-md-2 tableStyle " colspan="6"><span class="entry">{{ $EGP }} EGP</span></td>
                        </tr>
                        @endif

                    </tbody>
                </table>
        <table  class="col-md-12 tableStyle">
            <tbody>
                <tr>
                    <td class="col-md-3 tableStyle text-center">Notes</td>
                    <td class="col-md-9  tableStyle text-center" colspan="6">
                        <textarea style="width: 100%; border: none; height: 130px; font-size: 12px; font-weight: bolder !important; background-color: white; color: #000;" disabled>{!! $invoice->notes  !!}</textarea>

                    </td>
                </tr>
                
            </tbody>
        </table>
                <br>
                <br>
                <br>
                <h4 style="font-size: 16px; color:#000;">Bank USD details: Ahli United Bank – AUB &nbsp; 0007169620002 &nbsp; IBAN:	EG020020000700000007169620002<h4>
                <h4 style="font-size: 16px; color:#000;">Bank EGP &nbsp;details: Ahli United Bank – AUB &nbsp; 0007169620001 &nbsp; IBAN:	EG290020000700000007169620001<h4>
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
    .user{
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
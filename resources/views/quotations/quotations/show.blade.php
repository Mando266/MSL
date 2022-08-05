@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading hide">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('quotations.index')}}">Quotations </a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Quotation Details</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <h4 style="text-align:left; color:#000;">Agent Name :  {{optional($quotation->agent)->name}}</h4>

                <h4 style="text-align:left; color:#000;">Customer Name :  {{optional($quotation->customer)->name}}</h4>

                <table class="col-md-12 tableStyle">
                    <thead>
                        <tr>
                            <th class="text-center thstyle">Quotation</th>
                        </tr>
                    </thead>
                </table>
                <table class="col-md-12 tableStyle">
                    <tbody>
                        <tr>
                            <td class="tableStyle">Quotation Ref N : {{$quotation->ref_no}}</td>
                            <td class="tableStyle">Date : {{$quotation->created_at}}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="col-md-12 tableStyle">
                    <tbody>
                        <tr>
                            <td class="tableStyle">Validity : </td>
                            <td class="tableStyle">From : {{$quotation->validity_from}}</td>
                            <td class="tableStyle">To : {{$quotation->validity_to}}</td>
                            <td class="tableStyle">Status : {{$quotation->status}}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="col-md-12 tableStyle">
                    <thead>
                        <tr>
                            <th class="tableStyle thstyle">Rate Applicable To</th>
                            <th class="text-center tableStyle thstyle">SH</th>
                            <th class="text-center tableStyle thstyle">CN</th>
                            <th class="text-center tableStyle thstyle">NT</th>
                            <th class="text-center tableStyle thstyle">FWD</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center tableStyle"></td>
                            @if($quotation->rate_sh == 1)
                            <td class="text-center tableStyle">Y</td>
                            @else
                            <td class="text-center tableStyle">N</td>
                            @endif
                            @if($quotation->rate_cn == 1)
                            <td class="text-center tableStyle ">y</td>
                            @else
                            <td class="text-center tableStyle">N</td>
                            @endif
                            @if($quotation->rate_nt == 1)
                            <td class="text-center tableStyle">y</td>
                            @else
                            <td class="text-center tableStyle">N</td>
                            @endif
                            @if($quotation->rate_fwd == 1)
                            <td class="text-center tableStyle">y</td>
                            @else
                            <td class="text-center tableStyle">N</td>
                            @endif
                        </tr>
                    </tbody>
                </table>
                <table class="col-md-12 tableStyle">
                    <tbody>
                        <tr>
                            <td class="tableStyle">Place of Acceptance : &nbsp; <span>{{optional($quotation->placeOfAcceptence)->name}}</span></td>
                            <td class="tableStyle">Place of Delivery : &nbsp; <span>{{optional($quotation->placeOfDelivery)->name}}</span></td>
                        </tr>
                        <tr>
                            <td class="tableStyle">Load Port : &nbsp; <span>{{optional($quotation->loadPort)->name}}</span></td>
                            <td class="tableStyle"> Discharge Port : &nbsp; <span>{{optional($quotation->dischargePort)->name}}</span></td>
                        </tr>
                    </tbody>
                </table>
                <table class="col-md-12 tableStyle">
                    <thead>
                        <tr>
                            <th class="text-center thstyle">Equipment Type / Size</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center tableStyle">{{optional($quotation->equipmentsType)->name}}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="col-md-12 tableStyle">
                    <thead>
                        <tr>
                            <th style="padding: .75rem;" class="thstyle" colspan="4">Additional Free Days</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="tableStyle">Export Free Time</td>
                            <td class="tableStyle">{{$quotation->export_detention}}</td>
                            <td class="tableStyle">Import Free Time</td>
                            <td class="tableStyle">{{$quotation->import_detention}}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="col-md-12 tableStyle">
                    <thead class="tableStyle">
                        <tr>
                            <th class="col-md-6 thstyle">Charge Description</th>
                            <th class="col-md-2 thstyle">Mode/Type</th>
                            <th class="col-md-2 thstyle">Currency</th>
                            <th class="col-md-2 thstyle">Charges/Unit</th>
                        </tr>
                    </thead>
                    <tbody class="tableStyle">
                        @foreach($quotation->quotationDesc as $item)
                        <tr>
                            <td class="tableStyle">{{ $item->charge_desc }}</td>
                            <td class="tableStyle">{{ $item->mode }}</td>
                            <td class="tableStyle">{{ $item->currency }}</td>
                            <td class="tableStyle">{{ $item->charge_unit }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                    <div class="row">
                        <div class="col-md-12 text-center">
                        @permission('Quotation-Delete')
                            <a href="{{route('quotation.approve',['quotation'=>$item->quotation_id])}}"  class="btn btn-success hide mt-3">Approve</a>
                            <a href="{{route('quotation.reject',['quotation'=>$item->quotation_id])}}" class="btn btn-danger hide mt-3">Reject</a>
                        @endpermission
                        <button onclick="window.print()" class="btn btn-primary hide mt-3">Print This Quotation</button>

                        </div>
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
    .tableStyle {
        font-size: 14px !important;
        font-weight: bolder !important;
        border: 1px solid #000 !important;
        margin-bottom: 1rem;
        height: 50px;
        color: black;
        text-transform: uppercase;
        padding: .75rem;
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
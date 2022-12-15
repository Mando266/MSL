@extends('layouts.app')
@section('content')
<div class="layout-px-spacing" style="background-color: #fff;">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading hide">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('booking.index')}}">Booking </a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Booking Confirmation</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <div class="col-md-12 text-center">
                </div>
</br>
</br>

                <!-- <table class="col-md-12 tableStyle">
                    <thead>
                        <tr>
                            <th class="text-center thstyle">Booking Confirmation</th>
                        </tr>
                    </thead>
                </table> -->
                <table class="col-md-12 tableStyle" style="margin-bottom: 0rem;">
                    <tbody>
                        <tr>
                            <td class="col-md-6 tableStyle" style="border-left-style: hidden; height: 150px;">1- Shipper </br>
                            &nbsp &nbsp &nbsp{{ $blDraft->customer_shipper_details }}</td>
                            <td class="col-md-6 tableStyle " style="border-right-style: hidden; border-top-style: hidden;">B/L No: <br>
                            Book No: {{ optional($blDraft->booking)->ref_no }} <br>
                            REFERENCE:</td>
                        </tr>
                        <tr>
                            <td class="col-md-6 tableStyle" style="border-left-style: hidden; height: 150px;">2- Consignee </br>
                            &nbsp &nbsp &nbsp{{ $blDraft->customer_consignee_details }}</td>
                            <td class="col-md-6 tableStyle" style="border-right-style: hidden;"></td>
                        </tr>
                        <tr>
                            <td class="col-md-6 tableStyle" style="border-left-style: hidden; height: 150px;">3- Notify Party </br>
                            &nbsp &nbsp &nbsp{{ $blDraft->customer_notifiy_details }}</td>
                            <td class="col-md-6 tableStyle" style="border-right-style: hidden; border-top-style: hidden;"></td>
                        </tr>
                        <tr>
                            <td class="col-md-6 tableStyle" style="border-left-style: hidden;">4- Vessel and Voyage No. </br>
                            &nbsp &nbsp &nbsp{{ optional($blDraft->voyage->vessel)->name }} &nbsp {{ optional($blDraft->voyage)->voyage_no }}</td>
                            <td class="col-md-6 tableStyle" style="border-right-style: hidden;" >7- Place of Receipt </br>
                            &nbsp &nbsp &nbsp{{ optional($blDraft->placeOfAcceptence)->name }}</td>
                        </tr>
                        <tr>
                            <table class="col-md-12 tableStyle" style="border-top-style: hidden;">
                                <tr>
                                    <td class="col-md-3 tableStyle" style="border-left-style: hidden;">5- Port Of Loading </br>
                                    &nbsp &nbsp &nbsp{{ optional($blDraft->loadPort)->name }}</td>
                                    <td class="col-md-3 tableStyle" style="border-left-style: hidden;">6- Port Of Discharge </br>
                                    &nbsp &nbsp &nbsp{{ optional($blDraft->dischargePort)->name }}</td>
                                    <td class="col-md-6 tableStyle" style="border-right-style: hidden;">8- Place of Delivery </br>
                                    &nbsp &nbsp &nbsp{{ optional($blDraft->placeOfDelivery)->name }}</td>
                                </tr>
                            </table>
                        </tr>
                    </tbody>
                </table>
                <br>
                <h6 style="font-weight: bolder; font-size: 0.9rem;">&nbsp PARTICULARS  FURNISHED  BY  THE SHIPPER  -  NOT  CHECKED  BY  CARRIER  -  CARRIER  NOT  RESPONSIBLE  <span style="font-size: 12px;">(see clause 14)</span></h6>
                <table class="col-md-12 tableStyle" style="height: 300px; border-right-style: hidden; border-left-style: hidden; border-bottom-style: hidden;">
                    <thead>
                        <tr>
                            <th class="col-md-3.5 tableStyle" style="border-bottom-style: hidden; font-size: 9px;">9- Marks and Nos / Container Nos / Seal Nos </th>
                            <th class="col-md-5.5 tableStyle" style="border-bottom-style: hidden;font-size: 9px;">10- Number and kind of Packages / Description of Goods </th>
                            <th class="col-md-1.5 tableStyle" style="border-bottom-style: hidden;font-size: 9px;">11- Gross Weight (kg) </th>
                            <th class="col-md-1.5 tableStyle" style="border-bottom-style: hidden;font-size: 9px;">12- Measurement (cbm) </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($blDraft->blDetails as $bldetails)
                        <tr>
                            <td class="col-md-3.5 tdstyle" >&nbsp &nbsp &nbsp{{ optional($bldetails->container)->code }} &nbsp {{ $bldetails->seal_no }}</td>
                            <td class="col-md-5.5 tdstyle">&nbsp &nbsp &nbsp{{ $bldetails->packs }} &nbsp {{ $bldetails->description }}</td>
                            <td class="col-md-1.5 tdstyle">&nbsp &nbsp &nbsp{{ $bldetails->gross_weight }}</td>
                            <td class="col-md-1.5 tdstyle">&nbsp &nbsp &nbsp{{ $bldetails->measurement }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </br>
                </br>
                </br>
                <table class="col-md-12 tableStyle" style="margin-bottom: 0rem;">
                    <tbody>
                        <tr>
                            <td class="col-md-8 tableStyle" style="border-left-style: hidden;">13- Freight Payable At Cargo shall not be delivered unless Freight & charges are paid </br>
                            &nbsp &nbsp &nbsp</td>
                            <td class="col-md-4 tableStyle" style="border-right-style: hidden;">(NOT NEGOTIATABLE UNLESS CONSIGNED TO ORDER)</td>
                        </tr>
                    </tbody>
                </table>
                <table class="col-md-12 tableStyle" style="border-top-style: hidden; margin-bottom: 0rem;">
                    <tbody>
                        <tr>
                            <td class="col-md-3 tableStyle" style="border-left-style: hidden;">14- Date of Issue </br>
                            &nbsp &nbsp &nbsp{{ $blDraft->date_of_issue }}</td>
                            <td class="col-md-3 tableStyle" style="border-left-style: hidden;">15- Place of Issue </br>
                            &nbsp &nbsp &nbsp</td>
                            <td class="col-md-2 tableStyle" style="border-left-style: hidden;">16- Movement </br>
                            &nbsp &nbsp &nbsp{{ $blDraft->movement }}</td>
                            <td class="col-md-4 tableStyle" style="border-right-style: hidden; border-bottom-style: hidden;"></td>
                        </tr>
                    </tbody>
                </table>
                <table class="col-md-12 tableStyle" style="border-top-style: hidden; border-bottom-style: hidden;">
                    <tbody>
                        <tr>
                            <td class="col-md-4 tableStyle" style="border-left-style: hidden;">17- Declerd Value (Only Applicable if Ad Valer)</br>
                            &nbsp &nbsp &nbsp{{ $blDraft->declerd_value }}</td>
                            <td class="col-md-4 tableStyle">18- Number of Original Bills of Lading</br>
                            &nbsp &nbsp &nbsp{{ $blDraft->number_of_original }}</td>
                            <td class="col-md-4 tableStyle" style="border-right-style: hidden;"></td>
                        </tr>
                    </tbody>
                </table>
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
        font-size: 9px ;
        font-weight: bolder !important;
        border: 1px solid #000;
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
        font-size: 16px;
        font-weight: bolder !important;
    }
 
</style>
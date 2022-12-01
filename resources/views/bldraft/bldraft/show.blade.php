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
                <table class="col-md-12 tableStyle">
                    <tbody>
                        <tr>
                            <td class="col-md-6 tableStyle">1- Shipper </br>
                            &nbsp &nbsp &nbsp{{ $blDraft->customer_shipper_details }}</td>
                            <td class="col-md-6 tableStyle text-center"><img src="{{asset('assets/img/msl.png')}}" style="width: 400px;" alt="logo"></td>
                        </tr>
                    </tbody>
                </table>
                <table class="col-md-6 tableStyle">
                    <tbody>
                        <tr>
                            <td class="col-md-6 tableStyle">2- Consignee </br>
                            &nbsp &nbsp &nbsp{{ $blDraft->customer_consignee_details }}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="col-md-6 tableStyle">
                    <tbody>
                        <tr>
                            <td class="col-md-6 tableStyle">3- Notify Party </br>
                            &nbsp &nbsp &nbsp{{ $blDraft->customer_notifiy_details }}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="col-md-12 tableStyle">
                    <tbody>
                        <tr>
                            <td class="col-md-6 tableStyle">4- Vessel and Voyage No. </br>
                            &nbsp &nbsp &nbsp{{ optional($blDraft->voyage->vessel)->name }} &nbsp {{ optional($blDraft->voyage)->voyage_no }}</td>
                            <td class="col-md-6 tableStyle">7- Place of Receipt </br>
                            &nbsp &nbsp &nbsp{{ optional($blDraft->placeOfAcceptence)->name }}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="col-md-12 tableStyle">
                    <tbody>
                        <tr>
                            <td class="col-md-3 tableStyle">5- Port Of Loading </br>
                            &nbsp &nbsp &nbsp{{ optional($blDraft->loadPort)->name }}</td>
                            <td class="col-md-3 tableStyle">6- Port Of Discharge </br>
                            &nbsp &nbsp &nbsp{{ optional($blDraft->dischargePort)->name }}</td>
                            <td class="col-md-6 tableStyle">8- Place of Delivery </br>
                            &nbsp &nbsp &nbsp{{ optional($blDraft->placeOfDelivery)->name }}</td>
                        </tr>
                    </tbody>
                </table>
                <h4>&nbsp</h4>
                <table class="col-md-12 tableStyle">
                    <tbody>
                        @foreach($blDraft->blDetails as $bldetails)
                        <tr>
                            <td class="col-md-3.5 tableStyle">9- Marks and Nos / Container Nos / Seal Nos </br>
                            &nbsp &nbsp &nbsp{{ optional($bldetails->container)->code }} &nbsp {{ $bldetails->seal_no }}</td>
                            <td class="col-md-5.5 tableStyle">10- Number and kind of Packages / Description of Goods </br>
                            &nbsp &nbsp &nbsp{{ $bldetails->packs }} &nbsp {{ $bldetails->description }}</td>
                            <td class="col-md-1.5 tableStyle">11- Gross Weight (kg) </br>
                            &nbsp &nbsp &nbsp{{ $bldetails->gross_weight }}</td>
                            <td class="col-md-1.5 tableStyle">12- Measurement (cbm) </br>
                            &nbsp &nbsp &nbsp{{ $bldetails->measurement }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </br>
                </br>
                </br>
                <table class="col-md-12 tableStyle">
                    <tbody>
                        <tr>
                            <td class="col-md-8 tableStyle">13- Freight Payable At Cargo shall not be delivered unless Freight & charges are paid</td>
                            <td class="col-md-4 tableStyle">(NOT NEGOTIABLE UNLESS CONSIGNED TO ORDER)</td>
                        </tr>
                        <tr>
                            <td class="col-md-7 tableStyle">13- Freight Payable At Cargo shall not be delivered unless Freight & charges are paid</td>
                            <td class="col-md-5 tableStyle">(NOT NEGOTIABLE UNLESS CONSIGNED TO ORDER)</td>
                        </tr>
                        <tr>
                            <td class="col-md-4 tableStyle">13- Freight Payable At Cargo shall not be delivered unless Freight & charges are paid</td>
                            <td class="col-md-4 tableStyle">13- Freight Payable At Cargo shall not be delivered unless Freight & charges are paid</td>
                            <td class="col-md-4 tableStyle">(NOT NEGOTIABLE UNLESS CONSIGNED TO ORDER)</td>
                        </tr>
                    </tbody>
                </table>
                <table class="col-md-12 tableStyle">
                    <tbody>
                        @foreach($blDraft->blDetails as $bldetails)
                        <tr>
                            <td class="col-md-3.5 tableStyle">9- Marks and Nos / Container Nos / Seal Nos </br>
                            &nbsp &nbsp &nbsp{{ optional($bldetails->container)->code }} &nbsp {{ $bldetails->seal_no }}</td>
                            <td class="col-md-5.5 tableStyle">10- Number and kind of Packages / Description of Goods </br>
                            &nbsp &nbsp &nbsp{{ $bldetails->packs }} &nbsp {{ $bldetails->description }}</td>
                            <td class="col-md-1.5 tableStyle">11- Gross Weight (kg) </br>
                            &nbsp &nbsp &nbsp{{ $bldetails->gross_weight }}</td>
                            <td class="col-md-1.5 tableStyle">12- Measurement (cbm) </br>
                            &nbsp &nbsp &nbsp{{ $bldetails->measurement }}</td>
                        </tr>
                        @endforeach
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
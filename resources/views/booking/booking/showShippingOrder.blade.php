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
                    <div class="row">
                        <div class="col-md-6 text-center">
                            <img src="{{asset('assets/img/msl.png')}}" style="width: 400px;" alt="logo">
                        </div>
                        <table class="col-md-6 " style="    height: 20px;">
                        
                            <tbody>
                            <tr>
                                <td style="height: 54px;"></td>
                            </tr>
                                <tr>
                                    <th class="text-center thstyle underline">إذن شحن - Order Shipping</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </br>
                </br>
                @php
                $mytime = Carbon\Carbon::now();
                $containerCount = 0;
                $firstContainerDetail = $booking->bookingContainerDetails->first();
                $containerType = optional($firstContainerDetail->containerType)->name;
                $haz = optional($firstContainerDetail->haz)->name;
                foreach($booking->bookingContainerDetails as $detail){
                    $containerCount = $containerCount + $detail->qty;
                    $containerType = optional($detail->containerType)->name;
                }
                @endphp
                <table class="col-md-12 tableStyle" >
                    <tbody>
                        <tr>
                            <td class="col-md-4 tableStyle underline" >IMO No.</td>
                            <td class="col-md-4 tableStyle text-right underline" >560-161-094</td>
                            <td class="col-md-4 tableStyle text-right underline" > الرقم الضريبي</td>
                        </tr>
                        <tr>
                            <td class="col-md-4 tableStyle underline" >Booking Ref.</td>
                            <td class="col-md-4 tableStyle text-center" >{{ $booking->ref_no }}</td>
                            <td class="col-md-4 tableStyle text-right underline" >رقم الحجز</td>
                        </tr>
                        <tr>
                            <td class="col-md-4 tableStyle underline" >Alexandria on</td>
                            <td class="col-md-4 tableStyle text-center" >{{ $mytime->toDateTimeString() }}</td>
                            <td class="col-md-4 tableStyle text-right underline" >الأسكندرية في</td>
                        </tr>
                        <tr>
                            <td class="col-md-4 tableStyle underline" >Please receive on M/V</td>
                            <td class="col-md-4 tableStyle text-center" >{{ $booking->voyage->vessel->name }} / {{ $booking->voyage->voyage_no}}</td>
                            <td class="col-md-4 tableStyle text-right underline" >الرجاء الإستلام على الباخرة / رحلة</td>
                        </tr>
                        <tr>
                            <td class="col-md-4 tableStyle underline" >ETA</td>
                            <td class="col-md-4 tableStyle text-center" >{{optional($firstVoyagePort)->eta}}</td>
                            <td class="col-md-4 tableStyle text-right underline" >المتوقع وصولها في</td>
                        </tr>
                        <tr>
                            <td class="col-md-4 tableStyle underline" >Shipper</td>
                            <td class="col-md-4 tableStyle text-center" >{{optional($booking->customer)->name}} <br>
                            {{optional($booking->customer)->address}} &nbsp {{optional($booking->customer->country)->name}} &nbsp {{optional($booking->customer)->landline}}</td>
                            <td class="col-md-4 tableStyle text-right underline" >الشاحن</td>
                        </tr>
                        <tr>
                            <td class="col-md-4 tableStyle underline" >POL</td>
                            <td class="col-md-4 tableStyle text-center" >{{optional($booking->loadPort)->name}}</td>
                            <td class="col-md-4 tableStyle text-right underline" >ميناء الشحن</td>
                        </tr>
                        <tr>
                            <td class="col-md-4 tableStyle underline" >FPOD</td>
                            <td class="col-md-4 tableStyle text-center" >{{optional($booking->dischargePort)->name}}</td>
                            <td class="col-md-4 tableStyle text-right underline" >ميناء الوصول</td>
                        </tr>
                        <tr>
                            <td class="col-md-4 tableStyle underline" >Numbers of</td>
                            <td class="col-md-4 tableStyle text-center" >{{$containerCount}} X {{$containerType}}</td>
                            <td class="col-md-4 tableStyle text-right underline" >عدد الحاويات</td>
                        </tr>
                        <tr>
                            <td class="col-md-4 tableStyle underline" >Cargo</td>
                            <td class="col-md-4 tableStyle text-center" >{{$booking->commodity_description}}</td>
                            <td class="col-md-4 tableStyle text-right underline" >وصف البضاعة / وزن</td>
                        </tr>
                        <tr>
                            <td class="col-md-4 tableStyle underline" ></td>
                            <td class="col-md-4 tableStyle text-center" >{{$haz}}</td>
                            <td class="col-md-4 tableStyle text-right underline" ></td>
                        </tr>
                    </tbody>
                </table>
                <table class="col-md-12 tableStyle" >
                    <tbody>
                        <tr>
                            <td class="col-md-6 tableStyle underline" style="border: 1px solid #000 !important;"></td>
                            <td class="col-md-6 tableStyle text-right" style="border: 1px solid #000 !important;"></td>
                        </tr>
                        <tr>
                            <td class="col-md-6 tableStyle text-center" style="border: 1px solid #000 !important;" colspan="2"></td>
                        </tr>
                        <tr>
                            <td class="col-md-6 tableStyle"></td>
                            <td class="col-md-6 tableStyle text-right underline"></td>
                        </tr>
                        <tr>
                            <td class="col-md-6 tableStyle"></td>
                            <td class="col-md-6 tableStyle text-right underline">ملحوظة</td>
                        </tr>
                        <tr>
                            <td class="col-md-6 tableStyle" style="border-bottom-style: 1px solid #000 !important;">The shipping agency has the right not to ship the container in case
of non delivery of the shipping order before 24 hours from vessel
arrival.</td>
                            <td class="col-md-6 tableStyle text-right">للتوكيل الحق في عدم شحن الحاويه في حالة عدم تسليم إذن الشحن قبل وصول الباخره بـ 24
                            ساعه</td>
                        </tr>
                        <tr>
                            <td class="col-md-6 tableStyle"></td>
                            <td class="col-md-6 tableStyle text-right underline"></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border-bottom: solid;"></td>
                        </tr>
                    </tbody>
                </table>


                </div>
                <div class="row">
                        <div class="col-md-12 text-center">
                <button onclick="window.print()" class="btn btn-primary hide mt-3">Print This Shipping Order</button>
                <a href="{{route('booking.index')}}" class="btn btn-danger hide mt-3">{{trans('forms.cancel')}}</a>
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
        font-size: 20px !important;
        font-weight: bolder !important;
        border: none;
        margin-bottom: 1rem;
        height: 40px;
        color: black;
        text-transform: uppercase;
        padding: 0 .75rem;
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
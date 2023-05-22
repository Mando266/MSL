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
                        <div class="col-md-6">
                            <img src="{{asset('assets/img/msl.png')}}" style="width: 400px;" alt="logo">
                        </div>
                        <div class="col-md-6 tableStyle text-right underline" style="font-size: 30px; font-weight:bold !important">
                            {{optional($booking->principal)->name}}
                        </div>
                        <div class="col-md-3">
                        </div>
                        <table class="col-md-6 " style="height: 20px;">
                        
                            <tbody>
                            <tr>
                                <td style="height: 45px;"></td>
                            </tr>
                                <tr>
                                    <th class="text-center thstyle underline">اذن تسليم</th>
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
                $haz = $firstContainerDetail->haz;
                foreach($booking->bookingContainerDetails as $detail){
                    $containerCount = $containerCount + $detail->qty;
                    $containerType = optional($detail->containerType)->name;
                }
                @endphp
                <table class="col-md-12 tableStyle" >
                    <tbody>
                        <tr>
                            <td class="col-md-10 tableStyle" >&nbsp &nbsp &nbsp &nbsp{{ $mytime->format("D, F j, Y") }}</td>
                            <td class="col-md-2 tableStyle text-right underline" ></td>
                        </tr>
                        <tr>
                            <td class="col-md-9 tableStyle text-right underline" >{{optional($booking->dischargePort)->name}}</td>
                            <td class="col-md-3 tableStyle text-right underline" >السادة</td>
                        </tr>
                        <tr>
                            <td class="col-md-9 tableStyle text-right underline" ></td>
                            <td class="col-md-3 tableStyle text-right underline" ></td>
                        </tr>
                        {{-- <tr>
                        <td class="col-md-9 tableStyle text-right underline"></td>
                            @if(optional($booking->principal)->code == 'PLS')
                            <td class="col-md-3 tableStyle text-right underline" >{{optional($booking->principal)->code}} SOC</td>
                            @elseif(optional($booking->principal)->code == 'MAS')
                            <td class="col-md-3 tableStyle text-right underline" >{{optional($booking->principal)->code}} COC</td>
                            @else
                            <td class="col-md-3 tableStyle text-right underline" >{{optional($booking->principal)->code}} </td>
                            @endif
                        </tr> --}}
                   
                        <tr>
                            <td class="col-md-9 tableStyle" style="padding-left: 80px;">{{optional($booking->customer)->name}} <br>
                            </td>
                            <td class="col-md-3 tableStyle text-right underline" >برجاء تسليم السادة</td>
                        </tr>
                        <tr>
                            <td class="col-md-9 tableStyle" style="padding-left: 80px;">{{ $booking->ref_no }}</td>
                            <td class="col-md-3 tableStyle text-right underline" > بوليصه رقم</td>
                        </tr>
                        <tr>
                            <td class="col-md-9 tableStyle" style="padding-left: 80px;">{{ $booking->voyage->vessel->name }} / {{ $booking->voyage->voyage_no}}</td>
                            <td class="col-md-3 tableStyle text-right underline" >البضاعة المذكوره أدناه والواردة علي </td>
                        </tr>
                        <tr>
                            <td class="col-md-9 tableStyle" style="padding-left: 80px;">{{optional($firstVoyagePort)->eta}}</td>
                            <td class="col-md-3 tableStyle text-right underline" >تاريخ الوصول</td>
                        </tr>
                        <tr>
                            <td class="col-md-9 tableStyle" style="padding-left: 80px;">{{$containerCount}} X {{$containerType}}</td>
                            <td class="col-md-3 tableStyle text-right underline" >عدد الحاويات</td>
                        </tr>
                        <tr>
                            <td class="col-md-9 tableStyle" style="padding-left: 80px;">{{optional($booking->quotation)->ofr}}</td>
                            <td class="col-md-3 tableStyle text-right underline" >النولون</td>
                        </tr>
                        <tr>
                            <td class="col-md-9 tableStyle" style="padding-left: 80px;">{{optional($booking->quotation)->import_detention}}</td>
                            <td class="col-md-3 tableStyle text-right underline" >السماح</td>
                        </tr>
                        <tr>
                            <td class="col-md-9 tableStyle" style="padding-left: 80px;">{{optional($booking)->commodity_description}}</td>
                            <td class="col-md-3 tableStyle text-right underline" >وصف البضاعه</td>
                        </tr>
                    </tbody>
                </table>
                <div class="row">
                <div class="col-md-1">

</div>
<table class="col-md-10 tableStyle" >
    <tbody>
        <tr>
            <td class="col-md-2 tableStyle underline" style="border: 1px solid #000; border-right-style: hidden; font-size: 14px; padding: .75rem;">
                Container No.
            </td>
            <td class="col-md-2 tableStyle underline" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                Size
            </td>
            <td class="col-md-2 tableStyle underline" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                Type
            </td>
            <td class="col-md-2 tableStyle underline" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                Seal
            </td>
            <td class="col-md-2 tableStyle underline" style="border: 1px solid #000; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                Weight KG 
            </td>
        </tr>
        @foreach($booking->bookingContainerDetails as $detail)
        @if($detail->qty == 1)
        <tr>

            <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-right-style: hidden; font-size: 14px; padding: .75rem;">
                {{optional($detail->container)->code}}
            </td>
            <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
            {{substr(optional($detail->containerType)->name, 0, 2)}}
            </td>
            <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                {{optional($detail->containerType)->code}}
            </td>
            <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                {{$detail->seal_no}}
            </td>
            <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                {{$detail->weight}}
            </td>
            </tr>

            @elseif($detail->container == 000 )
                @for($i=0 ; $i <$detail->qty ; $i++)
                <tr>

                <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-right-style: hidden; font-size: 14px; padding: .75rem;">
                {{optional($detail->container)->code}}
                </td>
                <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
            {{substr(optional($detail->containerType)->name, 0, 2)}}
            </td>
            <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                {{optional($detail->containerType)->code}}
            </td>
            <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-right-style: hidden; border-left-style: hidden; font-size: 14px; padding: .75rem;">
                {{$detail->seal_no}}
            </td>
            <td class="col-md-2 tableStyle" style="border: 1px solid #000; border-left-style: hidden; font-size: 14px; padding: .75rem;">
            {{$detail->weight}}
            </td>
                <!-- {{$detail->qty}}  Containers -->
  
        </tr>
        @endfor
        @endif
        @endforeach
    </tbody>
</table>
                </div>
                <table class="col-md-12 tableStyle" >
                    <tbody>
                        <tr>
                            <td class="col-md-12 tableStyle text-right underline" >فترة السماح :- {{optional($booking->quotation)->import_detention}} أيام</td>
                        </tr>
                        <tr>
                            <td class="col-md-12 tableStyle text-right underline" >ملاحظات اذن التسليم
                                شركة ميدل أيست غير مسئولة عن الوزن والمقاس المبين بعالية والبضاعة تم تعبئتها  وتفريغها تحت مسئولية الشاحن والمستلم 
                                دون اجنى مسئولية علي الخط الملاحي أو الوكيل الملاحي وعلي الجهات الرقابية والجمركية أخذ كافة الأجراءات الجمركية والقانونية اللازمة
                                ومراجعة المشمول ومحتوباته ويعتبر الخط الملاحي ناقل للحاوية  فقط  بأعتباره مالك الحاوية</td>
                        </tr>
                    </tbody>
                </table>


                </div>
                <div class="row">
                        <div class="col-md-12 text-center">
                <button onclick="window.print()" class="btn btn-primary hide mt-3">Print This Delivery Order</button>
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
        font-size: 22px;
        font-weight: 500 !important;
        border: none;
        margin-bottom: 1rem;
        height: 40px;
        color: black;
        padding: 0 .75rem;
    }
    .underline{
        text-decoration: underline;
    }
    .thstyle {
        background-color: #fff !important;
        color: black !important;
        height: 35px;
        border: 1px solid #000 !important;
        font-size: 25px !important;
        font-weight: bolder !important;
    }
</style>
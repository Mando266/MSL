@extends('layouts.app')
@section('content')

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
                    @php
                            $net_weight = 0;
                            $gross_weight = 0;
                            $measurement = 0;
                            $packages = 0;
                        @endphp
                            @foreach($blDraft->blDetails as $blkey => $bldetails)
                                @php
                                    $packages = $packages + $bldetails->packs;
                                    $net_weight = $net_weight + $bldetails->net_weight;
                                    $gross_weight = $gross_weight + $bldetails->gross_weight;
                                    $measurement = $measurement + $bldetails->measurement;
                                @endphp
                            @endforeach
                    <table class="col-md-12 tableStyle" style="margin-bottom: 0rem;">
                        <tbody>
                            <tr>
                                <th class="col-md-6 tableStyle" style="height: 150px;">Shipper (full style and address) </br></br>
                                <span style="font-size: 14px; margin-left: 12px;">{{ optional($blDraft->customer)->name }}</span>
                                <textarea class="tableStyle" name="shipper"  style="border-style: hidden; overflow: hidden; height: 140px; width: 100%; resize: none; background-color: white; padding-top: unset;" cols="30" rows="10" readonly>
                                    {{ old('shipper',$blDraft->customer_shipper_details) }}
                                    </textarea>
                                </th>
                                <th class="col-md-6 tableStyle " colspan="2" > <h3 style="font-weight: 900;">SEA WAYBILL</h3><br>
                                <div class="col-md-12 text-center">
                                    <img src="{{asset('assets/img/msl-logo.png')}}" style="width: 204px;" alt="logo">
                                </div>
                                </th>
                            </tr>
                        </tbody>
                    </table>
                            <table class="col-md-12 tableStyle" style="border-top-style: hidden; margin-bottom: 0rem;">
                                <tr>
                                    <th rowspan="2" class="col-md-6 tableStyle" style=" height: 52px;">Consignee (full style and address) or Order </br></br>
                                        <span style="font-size: 14px; margin-left: 12px;">{{ optional($blDraft->customerConsignee)->name }}</span>
                                        <textarea class="tableStyle" name="consignee"  style="border-style: hidden; overflow: hidden; height: 52px; width: 100%; resize: none; background-color: white; padding-top: unset;" cols="30" rows="10" readonly>
                                         {!! $blDraft->customer_consignee_details !!}
                                        </textarea>
                                    </th>
                                    <td class="col-md-3 tableStyle" >B/L No. </br>
                                        &nbsp{{ $blDraft->ref_no }}</td>
                                    <td class="col-md-3 tableStyle" >Reference No. </br>
                                        &nbsp{{ optional($blDraft->booking)->ref_no }}</td>
                                </tr>
                                <tr>
                                    <td class=" col-md-3 tableStyle" colspan="2">Vessel Voyage No </br>
                                    &nbsp{{ optional($blDraft->voyage->vessel)->name }} &nbsp {{ optional($blDraft->voyage)->voyage_no }}
                                    </td>
                                </tr>
                            </table>
                            </tr>
                            <tr>
                            <table class="col-md-12 tableStyle" style="border-top-style: hidden; margin-bottom: 0rem;">
                                <tr>
                                    <th rowspan="2" class="col-md-6 tableStyle" style=" height: 52px;">Notify Party (full style and address) </br></br>
                                    <span style="font-size: 14px; margin-left: 12px;">{{ optional($blDraft->customerNotify)->name }}</span>
                                    <textarea class="tableStyle" name="notify"  style="border-style: hidden; overflow: hidden; height: 52px; width: 100%; resize: none; background-color: white; padding-top: unset;" cols="30" rows="10" readonly>
                                    {!! $blDraft->customer_notifiy_details !!}
                                    </textarea>
                                    </th>
                                    <td class="col-md-6 tableStyle" >Port of loading </br>
                                    &nbsp{{ optional($blDraft->loadPort)->name }}</td>
                                </tr>
                                <tr>
                                    <td class=" col-md-6 tableStyle" colspan="2">Port of discharge </br>
                                        &nbsp{{ optional($blDraft->dischargePort)->name }}
                                    </td>
                                </tr>
                            </table>
                    <table class="col-md-12 tableStyle" style="margin-bottom: 0rem;">
                        <thead>
                            <tr>
                                <th class="col-md-12 tableStyle text-center" style="height: 34px;" colspan="5">PARTICULARS DECLARED BY THE SHIPPER BUT NOT ACKNOWLEDGED BY THE CARRIER</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="col-md-4 tableStyle text-center" style="border-bottom-style: hidden; font-size: 13px;" colspan="2">Container No./Seal No./Marks and Numbers</td>
                                <td class="col-md-4 tableStyle text-center" style="border-bottom-style: hidden; font-size: 13px;">Number and kind of packages; description of cargo</td>
                                <td class="col-md-2 tableStyle text-center" style="border-bottom-style: hidden; font-size: 13px;">Gross weight, kg </td>
                                <td class="col-md-2 tableStyle text-center" style="border-bottom-style: hidden; font-size: 13px;">Measurement, m3</td>
                            </tr>
                            <tr>
                                <td class="col-md-4 tableStyle text-center" style="border-top-style: hidden; border-bottom-style: hidden;" colspan="2"></td>
                                <td class="col-md-4 tableStyle text-center" style="border-top-style: hidden; border-bottom-style: hidden;">
                                <textarea class="tableStyle" name="maindesc"  style="border-style: hidden; overflow: hidden; height: 355px; width: 455px; padding-bottom: unset; resize: none; background-color: white;" cols="30" rows="18" readonly>
                                    {!!  $blDraft->descripions  !!}
                                    </textarea></td>
                                <td class="col-md-2 tableStyle text-center" style="border-top-style: hidden; border-bottom-style: hidden;"></td>
                                <td class="col-md-2 tableStyle text-center" style="border-top-style: hidden; border-bottom-style: hidden;"></td>
                            </tr>
                            
                            <tr>
                                <td class="col-md-2 tableStyle text-center" style="font-size: 16px; height: 90px; border-top-style: hidden; padding-top: unset; padding-bottom: unset; border-bottom-style: hidden; border-right-style: hidden;">
                                @foreach($blDraft->blDetails as  $bldetails)
                                {{ optional($bldetails->container)->code }} <br>
                                @endforeach
                                </td>
                                <td class="col-md-2 tableStyle text-center" style="font-size: 16px; height: 90px; border-top-style: hidden; padding-top: unset; padding-bottom: unset; border-bottom-style: hidden;">
                                @foreach($blDraft->blDetails as  $bldetails)
                                {{ $bldetails->seal_no }} <br>
                                @endforeach
                                </td>
                                <td class="col-md-4 tableStyle" style="font-size: 16px; height: 90px; border-top-style: hidden; padding-top: unset; padding-bottom: unset; border-bottom-style: hidden;">
                                @foreach($blDraft->blDetails as  $bldetails)
                                &nbsp &nbsp &nbsp{{ $bldetails->packs }} <br>
                                @endforeach
                                </td>
                                <td class="col-md-2 tableStyle text-center" style="font-size: 16px; height: 90px; border-top-style: hidden; padding-top: unset; padding-bottom: unset; border-bottom-style: hidden;">
                                @foreach($blDraft->blDetails as  $bldetails)
                                {{ $bldetails->gross_weight }} <br>
                                @endforeach
                                </td>
                                <td class="col-md-2 tableStyle text-center" style="font-size: 16px; height: 90px; border-top-style: hidden; padding-top: unset; padding-bottom: unset; border-bottom-style: hidden;">
                                @foreach($blDraft->blDetails as  $bldetails)
                                {{ $bldetails->net_weight }} <br>
                                @endforeach
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="col-md-2 tableStyle text-center" style="border-right-style: hidden;"></td>
                                <td class="col-md-2 tableStyle text-center" ></td>
                                <td class="col-md-4 tableStyle text-center" ></td>
                                <td class="col-md-2 tableStyle text-center" ></td>
                                <td class="col-md-2 tableStyle text-center" ></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="col-md-12 tableStyle" style="border-top-style: hidden;">
                        <tbody>
                            <tr>
                                <td class="col-md-6 tableStyle" style="text-transform: initial; font-size: 14px;" rowspan="3">SHIPPED on board in apparent good order and condition (unless otherwise stated
herein) the total number of Containers/Packages or Units indicated in the Box
opposite entitled “Total number of Containers/Packages or Units received by the
Carrier” and the cargo as specified above, weight, measure, marks, numbers,
quality, contents and value unknown, for carriage to the Port of discharge or so
near thereunto as the vessel may safely get and lie always afloat, to be delivered
in the like good order and condition at the Port of discharge unto the lawful holder
of the Bill of Lading, on payment of freight as indicated to the right plus other
charges incurred in accordance with the provisions contained in this Bill of Lading.
In accepting this Bill of Lading the Merchant* expressly accepts and agrees to all
its stipulations on both Page 1 and Page 2, whether written, printed, stamped or
otherwise incorporated, as fully as if they were all signed by the Merchant.
One original Bill of Lading must be surrendered duly endorsed in exchange for the
cargo or delivery order, whereupon all other Bills of Lading to be void.
IN WITNESS whereof the Carrier, Master or their Agent has signed the number of
original Bills of Lading stated below right, all of this tenor and date.</td>
                            <td class="col-md-6 tableStyle" colspan="2">Total number of Containers/Packages or Units received by the Carrier <br>
                            &nbsp{{$blDraft->blDetails->count()}} X {{ optional($blDraft->equipmentsType)->name }}
                            </td>
                            </tr>
                            <tr>
                                <td class="col-md-3 tableStyle" >Shipper’s declared value <br>
                                &nbsp
                                </td>
                                <td class="col-md-3 tableStyle" >Declared value charge <br>
                                &nbsp
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-6 tableStyle" colspan="2">Freight details and charges <br>
                                &nbsp {{$blDraft->payment_kind}}<br>
                                &nbsp <br>
                                &nbsp <br>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-6 tableStyle" rowspan="3">Carrier’s name/principal place of business <br>
                                &nbsp <br>
                                &nbsp <br>
                                &nbsp <br>
                                &nbsp <br>
                                &nbsp <br>
                                &nbsp <br>
                                &nbsp <br>
                                &nbsp <br>
                                </td>
                                <td class="col-md-3 tableStyle" >Date shipped on board <br>
                                &nbsp {{$blDraft->date_of_issue}}
                                </td>
                                <td class="col-md-3 tableStyle" >Place and date of issue <br>
                                &nbsp {{optional($blDraft->booking->agent)->city}} &nbsp {{optional($blDraft->booking->agent->country)->name}} &nbsp {{$blDraft->date_of_issue}}
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-3 tableStyle" colspan="2">Number of original Bills of Lading <br>
                                &nbsp
                                </td>
                            </tr>
                            <tr>
                                <td class="col-md-6 tableStyle" colspan="2">Pre-carriage by** <br>
                                &nbsp
                                </td>
                            </tr>
                        </tbody>
                    </table>
    
</div>
@endsection
@push('scripts')
<script>
    let a = document.getElementsByName("shipper")[0].value
    document.getElementsByName("shipper")[0].value = a.trim()
    let b = document.getElementsByName("consignee")[0].value
    document.getElementsByName("consignee")[0].value = b.trim()
    let c = document.getElementsByName("notify")[0].value
    document.getElementsByName("notify")[0].value = c.trim()
    let d = document.getElementsByName("maindesc")[0].value
    document.getElementsByName("maindesc")[0].value = d.trim()
</script>
@endpush
@push('styles')
<style>
    @media print {
    .search_row,
    .hide {
        display: none !important;
        }
    }
    .tableStyle {
        font-size: 14px ;
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
    .word {
            width: 350Px;
            overflow-wrap: break-word;
            word-wrap: break-word;
            word-break: break-word;
        }
</style>
@extends('layouts.app')
@section('content')
<div class="layout-px-spacing transform" style="padding: 20px 20px 0px 20px !important">
        <div class="row layout-top-spacing ">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing ">
                <div class="widget widget-one">
                    <div class="widget-heading hide">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('bldraft.index')}}">Bl Draft </a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">EXPORT SERVICE MANIFEST</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol> 
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="col-md-12 text-center">
                    </div>
                    </br>
                    </br>
                    </br>

 
                    @php
                            $net_weight = 0;
                            $gross_weight = 0;
                            $measurement = 0;
                            $packages = 0;
                        @endphp
                            @foreach($blDraft->blDetails as $blkey => $bldetails)
                                @php
                                    $packages = $packages + (float)$bldetails->packs;
                                    $net_weight = $net_weight + (float)$bldetails->net_weight;
                                    $gross_weight = $gross_weight + (float)$bldetails->gross_weight;
                                    $measurement = $measurement + (float)$bldetails->measurement;
                                @endphp
                            @endforeach
                            <div class="row">
                                <div class="col-md-2">
                                    <img src="{{asset('assets/img/msl-logo.jpeg')}}" style="width: 260px;" alt="logo">
                                </div>
                                <table class="col-md-10 tableStyle" style="margin-bottom: 0rem; border-style: hidden;">
                                    <tbody>
                                        <tr>
                                            <td class="col-md-6 tableStyle text-center" style="height: 150px; font-size:18px" colspan="6">EXPORT SERVICE MANIFEST</br></br>
                                            <span style="font-size: 14px; margin-left: 12px;">VESSEL / VOYAGE &nbsp &nbsp{{ optional($blDraft->voyage->vessel)->name }} &nbsp {{ optional($blDraft->voyage)->voyage_no }}</span>
                                            
                                            </td>
                                            </tbody>
                                </table>
                            </div>
                            <table class="col-md-12 tableStyle" style="margin-bottom: 0rem; border-style: hidden;">
                                    <tbody>

                                            <td class="tableStyle" style="border-style: hidden;">Port of Loading </br>
                                            {{ optional($blDraft->loadPort)->name }}
                                            </td>
                                            <td class="tableStyle" style="border-style: hidden;">Transhipment Port </br>
                                                &nbsp
                                            </td>
                                            <td class="tableStyle" style="border-style: hidden;">Port of Discharge </br>
                                            {{ optional($blDraft->dischargePort)->name }}
                                            </td>
                                            <td class="tableStyle" style="border-style: hidden;">Final Destination </br>
                                            {{ optional($blDraft->dischargePort)->name }}
                                            </td>
                                            <td class="tableStyle" style="border-style: hidden;">Date of Sailing </br>
                                            {{ $blDraft->date_of_issue }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                    <table class="col-md-12 tableStyle" style="border-top-style: hidden; border-right-style: hidden; margin-bottom: 1rem;">
                   
                        <tbody>
                        <tr style="border-bottom-style: 1px solid !important; margin-bottom: 1rem;">
                                <td class="tableStyle" style="border-left-style: hidden; font-size: 16px;">Book No./ BL No.</td>
                                <td class="tableStyle" style="border-left-style: hidden; font-size: 16px;">Shipper / Consignee / Notify</td>
                                <td class="tableStyle" style="border-left-style: hidden; font-size: 16px;">No. and Kind of Packages - Description of Goods
                                </td>
                                <td class=" tableStyle" style="border-left-style: hidden; font-size: 16px;">Weight</td>
                                <td class=" tableStyle" style="border-left-style: hidden; font-size: 16px;">Measurement</td>
                            </tr>
             
                        <tr class="col-md-12 tableStyle">
                            <td class="tableStyle" style="border-left-style: hidden;">{{ optional($blDraft->booking)->ref_no }} <br>
                            {{ $blDraft->ref_no }}
                            </td>
                            <td class="tableStyle" style="border-left-style: hidden;">
                            <textarea class="tableStyle" style="border: none; height:290px; resize: none; background-color: white;"></textarea>

                            </td>
                            <td class="tableStyle" style="border-left-style: hidden; width: 200px !important;">No. of Containers: {{ $blDraft->blDetails->count() }} <br> <br> 
                                <!-- <textarea class="tableStyle" name="descripions"  style="border-style: hidden; height: 400px; width: -webkit-fill-available; resize: none; background-color: white;" cols="30" rows="10" readonly> -->
                                    {{ old('descripions',$blDraft->descripions) }}
                                <!-- </textarea> -->
                            </td>
                            <td class="tableStyle" style="border-left-style: hidden;">{{ $gross_weight }}</td>
                            <td class="tableStyle" style="border-left-style: hidden;">{{ $bldetails->measurement }}</td>
                        </tr>
                        
                        </tbody>
                    </table>
                    <table class="col-md-12 tableStyle" style="border-style: hidden;">
                        <tbody>
                            <tr style="border-bottom-style: 1px solid !important; margin-bottom: 1rem;">
                                <td class=" tableStyle" style="border-left-style: hidden;">Container No.</td>
                                <td class=" tableStyle" style="border-left-style: hidden;">Size</td>
                                <td class=" tableStyle" style="border-left-style: hidden;">Iso</td>
                                <td class=" tableStyle" style="border-left-style: hidden;">Seal</td>
                                <td class=" tableStyle" style="border-left-style: hidden;">Packages/Type</td>
                                <td class=" tableStyle" style="border-left-style: hidden;">G. Weight</td>
                                <td class=" tableStyle" style="border-left-style: hidden;">Measurement</td>
                                <td class=" tableStyle" style="border-left-style: hidden;">Net Weight</td>
                                <td class=" tableStyle" style="border-left-style: hidden; width: 139px !important;">HAZ / Reefer/ OOG Details</td>
                            </tr>
                        @foreach($blDraft->blDetails as  $bldetails)
                        <tr class="col-md-12 tableStyle" >
                            <td class="tableStyle" style="border-right-style: hidden;">{{ optional($bldetails->container)->code }}</td>
                            <td class="tableStyle" style="border-right-style: hidden;">{{ optional($blDraft->equipmentsType)->name }}</td>
                            <td class="tableStyle" style="border-right-style: hidden;">{{ optional($bldetails->container)->iso}}</td>
                            <td class="tableStyle" style="border-right-style: hidden;">{{ $bldetails->seal_no }}</td>
                            <td class="tableStyle" style="border-right-style: hidden;">{{ $bldetails->packs }} &nbsp {{ $bldetails->pack_type }}</td>
                            <td class="tableStyle" style="border-right-style: hidden;">{{ $bldetails->gross_weight }}</td>
                            <td class="tableStyle" style="border-right-style: hidden;">{{ $bldetails->measurement }}</td>
                            <td class="tableStyle" style="border-right-style: hidden;">{{ $bldetails->net_weight }}</td>
                            <td class="tableStyle" style="border-right-style: hidden; width: 139px !important;">{{ $bldetails->description }}</td>
                        </tr>
                        @endforeach
                        <tr style="border-top-style: 1px solid !important; margin-bottom: 1rem;">
                                <td class=" tableStyle" style="border-left-style: hidden;"></td>
                                <td class=" tableStyle" style="border-left-style: hidden;"></td>
                                <td class=" tableStyle" style="border-left-style: hidden;"></td>
                                <td class=" tableStyle text-right" style="border-left-style: hidden;">Total</td>
                                <td class=" tableStyle " style="border-left-style: hidden;">{{ $packages }}</td>
                                <td class=" tableStyle col-md-1" style="border-left-style: hidden;">{{ $gross_weight }}</td>
                                <td class=" tableStyle col-md-1" style="border-left-style: hidden;">{{ $measurement }}</td>
                                <td class=" tableStyle col-md-1" style="border-left-style: hidden;">{{ $net_weight }}</td>
                                <td class=" tableStyle" style="border-left-style: hidden;"></td>
                            </tr>
                        </tbody>
                    </table>
                <div class="row">
                        <div class="col-md-12 text-center">
                <button onclick="window.print()" class="btn btn-primary hide mt-3">Print This Manifest</button>
                <a href="{{route('bldraft.index')}}" class="btn btn-danger hide mt-3">{{trans('forms.cancel')}}</a>
                </div>
    
</div>
</div>
</div>
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
    let d = document.getElementsByName("descripions")[0].value
    document.getElementsByName("descripions")[0].value = d.trim()
</script>
@endpush
@push('styles')
<style>
    @media print {
    .search_row,
    .transform{
        transform: rotate(90deg);
    }
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
    td{
        width: 50Px !important;
    }
</style>
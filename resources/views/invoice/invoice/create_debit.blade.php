@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="{{route('invoice.index')}}">Invoice</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">New Debit</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">

                    <form id="createForm" action="{{route('invoice.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                        <div class="form-row">
                            <input type="hidden" name="bldraft_id" value="{{request()->input('bldraft_id')}}">
                                <div class="form-group col-md-6">
                                <label for="customer">Customer<span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" name="customer_id"  id="customer" data-live-search="true" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @if($bldraft != null)
                                        @if(optional($bldraft->booking->forwarder)->name != null)
                                        <option value="{{optional($bldraft->booking)->ffw_id}}">{{ optional($bldraft->booking->forwarder)->name }} Forwarder</option>
                                        @endif
                                        @if(optional($bldraft->customerNotify)->name != null )
                                        <option value="{{optional($bldraft)->customer_notifiy_id}}">{{ optional($bldraft->customerNotify)->name }} Notify</option>
                                        @endif
                                        <option value="{{optional($bldraft)->customer_id}}">{{ optional($bldraft->customer)->name }} Shipper</option>
                                    @endif
                                </select>
                                @error('customer')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="customer">Customer Name</label>
                                    <input type="text" id="notifiy" class="form-control"  name="customer"
                                    placeholder="Customer Name" autocomplete="off">
                                    @error('customer')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                            </div>

                        </div>
                        <div class="form-row">
                        <div class="form-group col-md-3" >
                                    <label>Place Of Acceptence</label>
                                        @if(optional($bldraft)->place_of_acceptence_id != null)
                                        <input type="text" class="form-control" placeholder="Place Of Acceptence" autocomplete="off" value="{{(optional($bldraft->placeOfAcceptence)->code)}}" style="background-color:#fff" disabled>
                                        @endif
                                </div>

                                <div class="form-group col-md-3" >
                                    <label>Load Port</label>
                                        @if(optional($bldraft)->load_port_id != null)
                                        <input type="text" class="form-control" placeholder="Load Port" autocomplete="off" value="{{(optional($bldraft->loadPort)->code)}}" style="background-color:#fff" disabled>
                                        @endif
                                </div>
                            <div class="form-group col-md-3" >
                                <label for="Date">Booking Ref</label>
                                    @if(optional($bldraft)->booking_id != null)
                                    <input type="text" class="form-control" placeholder="Booking Ref" autocomplete="off" value="{{(optional($bldraft->booking)->ref_no)}}" style="background-color:#fff" disabled>
                                    @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="voyage_id">Vessel / Voyage </label>
                                <select class="selectpicker form-control" id="voyage_id" name="voyage_id"  data-live-search="true" data-size="10"
                                    title="{{trans('forms.select')}}" disabled>
                                    @foreach ($voyages as $item)
                                    @if(optional($bldraft)->voyage_id != null && optional($bldraft->booking)->transhipment_port == null)
                                        <option value="{{$item->id}}" {{$item->id == old('voyage_id',$bldraft->voyage_id) ? 'selected':''}}>{{$item->vessel->name}} / {{$item->voyage_no}} - {{ optional($item->leg)->name }}</option>
                                        @elseif(optional($bldraft->booking)->voyage_id_second != null && optional($bldraft->booking)->transhipment_port != null)
                                        <option value="{{$item->id}}" {{$item->id == old('voyage_id',$bldraft->booking->voyage_id_second) ? 'selected':''}}>{{$item->vessel->name}} / {{$item->voyage_no}} - {{ optional($item->leg)->name }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3" >
                                        <label>Discharge Port</label>
                                        @if(optional($bldraft)->discharge_port_id != null)
                                        <input type="text" class="form-control" placeholder="Discharge Port" autocomplete="off" value="{{(optional($bldraft->dischargePort)->code)}}" style="background-color:#fff" disabled>
                                        @endif
                                </div>

                                <div class="form-group col-md-3" >
                                    <label>Port of Delivery</label>
                                        @if(optional($bldraft)->place_of_delivery_id != null)
                                        <input type="text" class="form-control" placeholder="Port of Delivery" autocomplete="off" value="{{(optional($bldraft->placeOfDelivery)->code)}}" style="background-color:#fff" disabled>
                                        @endif
                                </div>
                                <div class="form-group col-md-3" >
                                    <label>Equipment Type</label>
                                        @if(optional($bldraft)->equipment_type_id != null)
                                        <input type="text" class="form-control" placeholder="Equipment Type" name="bl_kind" autocomplete="off" value="{{(optional($bldraft->equipmentsType)->name)}}" style="background-color:#fff" disabled>
                                        @endif
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="status">Invoice Status<span class="text-warning"> * </span></label>
                                    <select class="form-control" data-live-search="true" name="invoice_status" title="{{trans('forms.select')}}" required>
                                        <option value="draft">Draft</option>
                                            @permission('Invoice-Ready_to_Confirm')
                                                <option value="ready_confirm">Ready To Confirm</option>
                                            @endpermission
                                            @if($bldraft->bl_status == 1 && Auth::user()->id == 15)
                                                <option value="confirm">Confirm</option>
                                            @endif
                                   </select>
                                    @error('invoice_status')
                                    <div style="color:red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3" >
                                    <label>QTY</label>
                                        <input type="text" class="form-control" placeholder="Qty" name="qty" autocomplete="off" value="{{$qty}}" style="background-color:#fff" disabled>
                                </div>
                                <div class="form-group col-md-3" >
                                    <label for="Date">Date</label>
                                        <input type="date" class="form-control" name="date" placeholder="Date" autocomplete="off" required value="{{old('date',date('Y-m-d'))}}">
                                </div>
                                <div style="padding: 30px;">
                                <input class="form-check-input" type="radio" name="rate" id="rate" value="eta" checked>
                                        <label class="form-check-label" for="exchange_rate">

                                            @if(optional($bldraft)->voyage_id != null && optional($bldraft->booking)->transhipment_port == null)
                                                ETA Rate {{ optional($bldraft->voyage)->exchange_rate }}
                                            @elseif(optional($bldraft->booking)->voyage_id_second != null && optional($bldraft->booking)->transhipment_port != null)
                                                ETA
                                                Rate {{ optional(optional($bldraft->booking)->secondvoyage)->exchange_rate }}
                                            @endif
                                        </label>
                                        <br>
                                        <input class="form-check-input" type="radio" name="rate" id="custom_rate_radio" >
                                            <label class="form-check-label" for="custom_rate_radio">Custom Rate</label>
                                        <input type="text" name="customize_exchange_rate" id="custom_rate_input" style="display: none;" placeholder="Enter custom rate">

                                    </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12 form-group">
                                    <label> Notes </label>
                                    <textarea class="form-control" name="notes">
                                        {{ isset($notes) ? implode("\n", $notes) : '' }}
                                    </textarea>
                                </div>
                            </div>
                        <h4>Charges</h4>
                        <table id="containerDepit" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Charge Description</th>
                                        <th class="text-center">Rate 20/40</th>
                                        <th class="text-center">Amount 20/40</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(!isset($detentionAmount))
                                    <tr>
                                        <td>
                                        <select class="selectpicker form-control"
                                                id="Charge Description" data-live-search="true"
                                                name="invoiceChargeDesc[0][charge_description]"
                                                data-size="10"
                                                title="{{trans('forms.select')}}">
                                            @foreach ($charges as $item)
                                                <option value="{{$item->name}}" {{$item->name == old($item->charge_description) ? 'selected':''}}>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                            <!-- <input type="text" id="Charge Description" name="invoiceChargeDesc[0][charge_description]" class="form-control" autocomplete="off" placeholder="Charge Description" value ="Ocean Freight" > -->
                                        </td>
                                        <td><input type="text" class="form-control" id="size_small" name="invoiceChargeDesc[0][size_small]" value="{{(optional($bldraft->booking->quotation)->ofr)}}"
                                            placeholder="Weight" autocomplete="off" disabled style="background-color: white;">
                                        </td>
                                        <td><input type="text" class="form-control" id="ofr" name="invoiceChargeDesc[0][total_amount]" value="{{(optional($bldraft->booking->quotation)->ofr) * $qty }}"
                                            placeholder="Ofr" autocomplete="off" disabled style="background-color: white;">
                                        </td>
                                        <td><input type="text" class="form-control" id="ofr"
                                                   name="invoiceChargeDesc[0][total]" value="{{$total_storage}}"
                                                   placeholder="Total" autocomplete="off" disabled
                                                   style="background-color: white;">
                                        </td>
                                        <td><input type="text" name="invoiceChargeDesc[0][usd_vat]"
                                                   class="form-control" autocomplete="off"
                                                   placeholder="USD After VAT" disabled></td>

                                        <td><input type="text" class="form-control" id="ofr"
                                                   name="invoiceChargeDesc[0][egy_amount]"
                                                   value="{{$total_storage}}"
                                                   placeholder="Egp Amount  " autocomplete="off" disabled
                                                   style="background-color: white;" disabled>
                                        </td>
                                        <td><input type="text" name="invoiceChargeDesc[0][egp_vat]"
                                                   class="form-control" autocomplete="off"
                                                   placeholder="Egp After VAT" disabled></td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td>
                                        <select class="selectpicker form-control"
                                                id="Charge Description" data-live-search="true"
                                                name="invoiceChargeDesc[0][charge_description]"
                                                data-size="10"
                                                title="{{trans('forms.select')}}">
                                            @foreach ($charges as $item)
                                                <option value="{{$item->name}}" {{$item->name == old($item->charge_description) ? 'selected':''}}>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                            <!-- <input type="text" id="Charge Description" name="invoiceChargeDesc[0][charge_description]" class="form-control" autocomplete="off" placeholder="Charge Description" value ="Ocean Freight" > -->
                                        </td>
                                        <td><input type="text" class="form-control" id="size_small" name="invoiceChargeDesc[0][size_small]" value="{{$detentionAmount}}"
                                            placeholder="Weight" autocomplete="off" disabled style="background-color: white;">
                                        </td>
                                        <td><input type="text" class="form-control" id="ofr" name="invoiceChargeDesc[0][total_amount]" value="{{$detentionAmount}}"
                                            placeholder="Ofr" autocomplete="off" disabled style="background-color: white;">
                                        </td>

                                    </tr>
                                    @endif
                            </tbody>
                        </table>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
                                    <a href="{{route('invoice.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                                </div>
                           </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    const rateRadio = document.getElementById('rate');
    const exchangeRateRadio = document.getElementById('exchange_rate');
    const customRateRadio = document.getElementById('custom_rate_radio');
    const customRateInput = document.getElementById('custom_rate_input');

    // Initial check on page load
    toggleCustomRateInput();

    // Event listeners for radio button changes
    rateRadio.addEventListener('change', toggleCustomRateInput);
    exchangeRateRadio.addEventListener('change', toggleCustomRateInput);
    customRateRadio.addEventListener('change', toggleCustomRateInput);

    // Function to toggle the display of the custom rate input field
    function toggleCustomRateInput() {
        if (customRateRadio.checked) {
            customRateInput.style.display = 'inline-block';
        } else {
            customRateInput.style.display = 'none';
        }
    }
</script>
<script>
    $(document).ready(function() {
        localStorage.removeItem('cart');
        $("#createForm").validate();
    });
    $('#createForm').submit(function() {
        $('input').removeAttr('disabled');
    });
</script>
<script>
    $('#createForm').submit(function() {
        $('select').removeAttr('disabled');
    });
</script>
<script>
        $(function(){
                let customer = $('#customer');
                $('#customer').on('change',function(e){
                    let value = e.target.value;
                    let response =    $.get(`/api/master/customers/${customer.val()}`).then(function(data){
                        let notIfiy = data.customer[0] ;
                        let notifiy = $('#notifiy').val(' ' + notIfiy.name);
                    notifiy.html(list2.join(''));
                });
            });
        });
</script>

@endpush

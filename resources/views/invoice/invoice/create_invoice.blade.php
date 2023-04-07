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
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">New Invoice</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
           
                    <form id="createForm" action="{{route('invoice.store_invoice')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                        <div class="form-row">
                            <input type="hidden" name="bldraft_id" value="{{request()->input('bldraft_id')}}">
                                <div class="form-group col-md-6">
                                <label for="customer">Customer<span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" name="customer_id" id="customer" data-live-search="true" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                        @if($bldraft != null)
                                        @if(optional($bldraft->booking->forwarder)->name != null)
                                        <option value="{{optional($bldraft->booking)->ffw_id}}">{{ optional($bldraft->booking->forwarder)->name }} Forwarder</option>
                                        @endif
                                        <option value="{{optional($bldraft)->customer_id}}">{{ optional($bldraft->customer)->name }} Shipper</option>
                                        @endif
                                </select>
                                @error('customer_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div> 
                            <div class="form-group col-md-6">
                                <label for="customer_id">Customer Name</label>
                                    <input type="text" id="notifiy" class="form-control"  name="customer"
                                    placeholder="Customer Name" autocomplete="off" required>
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
                                <select class="selectpicker form-control" id="voyage_id" name="voyage_id" data-live-search="true" data-size="10"
                                    title="{{trans('forms.select')}}" disabled>
                                    @foreach ($voyages as $item)
                                        @if(optional($bldraft)->voyage_id != null)
                                            <option value="{{$item->id}}" {{$item->id == old('voyage_id',$bldraft->voyage_id) ? 'selected':''}}>{{$item->vessel->name}} / {{$item->voyage_no}}</option>
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
                                        @if($bldraft->bl_status == 1)
                                        <option value="confirm">Confirm</option>
                                        @endif
                                    </select>
                                    @error('invoice_status')
                                    <div style="color:red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3" >
                                    <label for="Date">Date</label>
                                        <input type="date" class="form-control" name="date" placeholder="Date" autocomplete="off" required value="{{old('date',date('Y-m-d'))}}">
                                </div>
                                <div class="form-group col-md-3" >
                                    <label>QTY</label>
                                        <input type="text" class="form-control" placeholder="Qty" name="qty" autocomplete="off" value="{{$qty}}" style="background-color:#fff" disabled>
                                </div>
                                <div class="form-group col-md-2" >
                                    <label>TAX Hold</label>
                                        <input type="text" class="form-control" placeholder="TAX %" name="tax_discount" autocomplete="off"  style="background-color:#fff" value="0">
                                </div>
                                <div class="col-md-2 form-group " >
                                    <div style="padding: 30px;">
                                        <input class="form-check-input" type="radio" name="exchange_rate" id="exchange_rate" value="eta" checked>
                                        <label class="form-check-label" for="exchange_rate">
                                        ETA Rate {{ optional($bldraft->voyage)->exchange_rate }}
                                        </label>
                                        <br>
                                        <input class="form-check-input" type="radio" name="exchange_rate" id="exchange_rate" value="etd">
                                        <label class="form-check-label" for="exchange_rate">
                                          ETD Rate {{ optional($bldraft->voyage)->exchange_rate_etd }}
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-2" >
                                    <div style="padding: 30px;">
                                        <input class="form-check-input" type="radio" name="add_egp" id="add_egp" value="true" checked>
                                        <label class="form-check-label" for="add_egp">
                                            EGP AND USD
                                        </label>
                                        <br>
                                        <input class="form-check-input" type="radio" name="add_egp" id="add_egp" value="false">
                                        <label class="form-check-label" for="add_egp">
                                          USD
                                        </label>
                                        <br>
                                        <input class="form-check-input" type="radio" name="add_egp" id="add_egp" value="onlyegp">
                                        <label class="form-check-label" for="add_egp">
                                          EGP
                                        </label> 
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12 form-group">
                                    <label> Notes </label>
                                    <textarea class="form-control" name="notes"></textarea>
                                </div> 
                            </div>
                        <h4>Charges<h4>
                        <table id="charges" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Charge Description</th>
                                        <th class="text-center">USD Amount</th>
                                        <th class="text-center">VAT</th>
                                        <th class="text-center">TOTAL</th>
                                        <th class="text-center">Egp Amount</th>
                                        <th class="text-center"><a id="add"> Add <i class="fas fa-plus"></i></a></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($triffDetails->triffPriceDetailes as $key => $detail)
                                  
                            <tr>
                                <td>
                                    <input type="text" id="Charge Description" name="invoiceChargeDesc[{{ $key }}][charge_description]" class="form-control" autocomplete="off" placeholder="Charge Description" value ="{{ $detail->charge_type }}" >
                                </td>
                                <td><input type="text" class="form-control" id="size_small" name="invoiceChargeDesc[{{ $key }}][size_small]" value="{{ $detail->selling_price }}"
                                    placeholder="Amount" autocomplete="off" disabled style="background-color: white;">
                                </td>
                                <td><input type="text" class="form-control" id="ofr" name="invoiceChargeDesc[{{ $key }}][vat]" value="{{ $detail->selling_price * 0 }}"
                                    placeholder="VAT" autocomplete="off" style="background-color: white;">
                                </td>
                                @if($detail->unit == "Container")
                                <td><input type="text" class="form-control" id="ofr" name="invoiceChargeDesc[{{ $key }}][total]" value="{{$detail->selling_price * $qty}}"
                                    placeholder="Total" autocomplete="off" disabled style="background-color: white;">
                                </td>
                                @elseif($detail->unit == "Document")
                                <td><input type="text" class="form-control" id="ofr" name="invoiceChargeDesc[{{ $key }}][total]" value="{{$detail->selling_price}}"
                                    placeholder="Total" autocomplete="off" disabled style="background-color: white;">
                                </td>
                                @endif
                                @if($detail->unit == "Container")
                                <td><input type="text" class="form-control" id="ofr" name="invoiceChargeDesc[{{ $key }}][egy_amount]" value="{{$detail->selling_price * $qty * optional($bldraft->voyage)->exchange_rate }}"
                                    placeholder="Egp Amount  " autocomplete="off" disabled style="background-color: white;">
                                </td>
                                @elseif($detail->unit == "Document")
                                <td><input type="text" class="form-control" id="ofr" name="invoiceChargeDesc[{{ $key }}][egy_amount]" value="{{$detail->selling_price * optional($bldraft->voyage)->exchange_rate}}"
                                    placeholder="Egp Amount  " autocomplete="off" disabled style="background-color: white;">
                                </td>
                                @endif
                                    <td style="width:85px;">
                                        <button type="button" class="btn btn-danger remove" ><i class="fa fa-trash"></i></button>
                                    </td>
                            </tr>
                            @endforeach
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
<script>

$(document).ready(function(){
    $("#charges").on("click", ".remove", function () {
        $(this).closest("tr").remove();
    });
    var counter  = <?= isset($key)? ++$key : 0 ?>;
    $("#add").click(function(){
       var tr = '<tr>'+
           '<td><input type="text" name="invoiceChargeDesc['+counter+'][charge_description]" class="form-control" autocomplete="off" placeholder="Charge Description" required></td>'+
           '<td><input type="text" name="invoiceChargeDesc['+counter+'][size_small]" class="form-control" autocomplete="off" placeholder="Amount" required></td>'+
           '<td><input type="text" name="invoiceChargeDesc['+counter+'][vat]" class="form-control" autocomplete="off" placeholder="VAT"></td>'+
           '<td><input type="text" name="invoiceChargeDesc['+counter+'][total]" class="form-control" autocomplete="off" placeholder="Total" required></td>'+
           '<td><input type="text" name="invoiceChargeDesc['+counter+'][egy_amount]" class="form-control" autocomplete="off" placeholder="Egp Amount"></td>'+
           '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>'
       '</tr>';
       counter++;
      $('#charges').append(tr);
    });
});
</script>


@endpush
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
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Edit Custmized Invoice</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">

                <form id="editForm" action="{{route('invoice.update',['invoice'=>$invoice])}}" method="POST" >
                            @csrf
                            @method('put')
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label for="Invoice">Invoice No</label>
                                    <input type="text" id="Invoice" class="form-control"  name="invoice_no"
                                    placeholder="Invoice No" autocomplete="off" value="{{old('invoice_no',$invoice->invoice_no)}}">
                            </div> 
                            <input type="hidden" name="bldraft_id" value="{{request()->input('bldraft_id')}}">
                                <div class="form-group col-md-6">
                                <label for="customer">Customer<span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" name="customer_id" id="customer" data-live-search="true" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                        @foreach($ffws as $ffw)
                                            <option value="{{$ffw->id}}" {{$ffw->id == old('customer_id',$invoice->customer_id) ? 'selected':''}}>{{ $ffw->name }} Forwarder</option>
                                        @endforeach
                                        @foreach($shippers as $shipper)
                                            <option value="{{$shipper->id}}" {{$shipper->id == old('customer_id',$invoice->customer_id) ? 'selected':''}}>{{ $shipper->name }} Shipper</option>
                                        @endforeach
                                </select>
                                @error('customer_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div> 
                            <div class="form-group col-md-4">
                                <label for="customer_id">Customer Name</label>
                                    <input type="text" id="notifiy" class="form-control"  name="customer"
                                    placeholder="Customer Name" autocomplete="off" value="{{old('customer',$invoice->customer)}}" required>
                            </div> 
                        </div>
                        <div class="form-row">
                        <div class="form-group col-md-3" >
                                    <label>Place Of Acceptence</label>
                                        <select class="selectpicker form-control" id="place_of_acceptence_id" data-live-search="true" name="place_of_acceptence" data-size="10"
                                            title="{{trans('forms.select')}}">
                                        @foreach($ports as $port)
                                            <option value="{{$port->id}}" {{$port->id == old('place_of_acceptence_id',$invoice->place_of_acceptence) ? 'selected':''}}>{{$port->name}}</option>
                                        @endforeach
                                        </select>
                                    @error('place_of_acceptence')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div> 

                                <div class="form-group col-md-3" >
                                    <label>Load Port</label>
                                    <select class="selectpicker form-control" id="load_port" data-live-search="true" name="load_port" data-size="10"
                                        title="{{trans('forms.select')}}">
                                           @foreach($ports as $port)
                                           <option value="{{$port->id}}" {{$port->id == old('load_port',$invoice->load_port) ? 'selected':''}}>{{$port->name}}</option>
                                           @endforeach
                                           </select>
                                       @error('load_port')
                                       <div style="color: red;">
                                           {{$message}}
                                       </div>
                                       @enderror
                                </div> 
                            <div class="form-group col-md-3" >
                                <label for="Date">Booking Ref</label>
                                <select class="selectpicker form-control" id="booking_ref" data-live-search="true" name="booking_ref" data-size="10"
                                    title="{{trans('forms.select')}}">
                                    @foreach($bookings as $booking)
                                        <option value="{{$booking->id}}" {{$booking->id == old('booking_ref',$invoice->booking_ref) ? 'selected':''}}>{{$booking->ref_no}}</option>
                                    @endforeach
                                </select>
                                @error('booking_ref')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div> 
                            <div class="form-group col-md-3">
                                <label for="voyage_id">Vessel / Voyage </label>
                                <select class="selectpicker form-control" id="voyage_id" name="voyage_id" data-live-search="true" data-size="10"
                                    title="{{trans('forms.select')}}">
                                    @foreach ($voyages as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('voyage_id',$invoice->voyage_id) ? 'selected':''}}>{{$item->vessel->name}} / {{$item->voyage_no}}</option>
                                    @endforeach
                                </select>
                                    @error('voyage_id')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                            </div>

                            </div> 
                            <div class="form-row">
                                <div class="form-group col-md-3" >
                                    <label>Discharge Port</label>
                                    <select class="selectpicker form-control" id="discharge_port" data-live-search="true" name="discharge_port" data-size="10"
                                        title="{{trans('forms.select')}}">
                                        @foreach($ports as $port)
                                            <option value="{{$port->id}}" {{$port->id == old('discharge_port',$invoice->discharge_port) ? 'selected':''}}>{{$port->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('discharge_port')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div> 

                                <div class="form-group col-md-3" >
                                    <label>Port of Delivery</label>
                                    <select class="selectpicker form-control" id="port_of_delivery" data-live-search="true" name="port_of_delivery" data-size="10"
                                        title="{{trans('forms.select')}}">
                                        @foreach($ports as $port)
                                            <option value="{{$port->id}}" {{$port->id == old('port_of_delivery',$invoice->port_of_delivery) ? 'selected':''}}>{{$port->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('port_of_delivery')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div> 
                                <div class="form-group col-md-3" >
                                    <label>Equipment Type</label>
                                    <select class="selectpicker form-control" id="equipment_type" data-live-search="true" name="equipment_type" data-size="10"
                                        title="{{trans('forms.select')}}">
                                        @foreach($equipmentTypes as $equipmentType)
                                            <option value="{{$equipmentType->id}}" {{$equipmentType->id == old('equipment_type',$invoice->equipment_type) ? 'selected':''}}>{{$equipmentType->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('equipment_type')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div> 

                                @if($invoice->invoice_status != "confirm")
                                <div class="form-group col-md-3">
                                    <label for="status">Invoice Status<span class="text-warning"> * </span></label>
                                    <select class="form-control" data-live-search="true" name="invoice_status" title="{{trans('forms.select')}}" required>
                                        <option value="draft" {{ old('invoice_status',$invoice->invoice_status) == "draft" ? 'selected':'' }}>Draft</option>
                                        <option value="confirm" {{ old('invoice_status',$invoice->invoice_status) == "confirm" ? 'selected':'' }}>Confirm</option>
                                    </select>
                                    @error('invoice_status')
                                    <div style="color:red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                @endif
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3" >
                                    <label for="Date">Date</label>
                                        <input type="date" class="form-control" name="date" placeholder="Date" autocomplete="off" value="{{old('date',$invoice->date)}}">
                                </div>
                                <div class="form-group col-md-3" >
                                    <label>QTY</label>
                                        <input type="text" class="form-control" placeholder="Qty" name="qty" autocomplete="off" value="{{old('qty',$invoice->qty)}}" style="background-color:#fff">
                                </div>
                                <!-- <div class="col-md-3 form-group">
                                    <div style="padding: 30px;">
                                        <input class="form-check-input" type="radio" name="exchange_rate" id="exchange_rate" value="eta" checked>
                                        <label class="form-check-label" for="exchange_rate">
                                        ETA Rate {{ optional($invoice->voyage)->exchange_rate }}
                                        </label>
                                        <br>
                                        <input class="form-check-input" type="radio" name="exchange_rate" id="exchange_rate" value="etd">
                                        <label class="form-check-label" for="exchange_rate">
                                          ETD Rate {{ optional($invoice->voyage)->exchange_rate_etd }}
                                        </label>
                                    </div>
                                </div> -->
                                <div class="form-group col-md-3" >
                                    <label>TAX Hold</label>
                                        <input type="text" class="form-control" placeholder="TAX %" name="tax_discount"  value="{{old('tax_discount',$invoice->tax_discount)}}" autocomplete="off"  style="background-color:#fff" >
                                </div>
                                <div class="form-group col-md-3" >
                                    <div style="padding: 30px;">
                                        @if($invoice->invoice_status != "confirm")
                                        <input class="form-check-input" type="radio" name="add_egp" id="add_egp" value="true" {{ "true" == old('add_egp',$invoice->add_egp) ? 'checked':''}}>
                                        <label class="form-check-label" for="add_egp">
                                            EGP AND USD
                                        </label>
                                        <br>
                                        @endif
                                        <input class="form-check-input" type="radio" name="add_egp" id="add_egp" value="false" {{ "false" == old('add_egp',$invoice->add_egp) ? 'checked':''}}>
                                        <label class="form-check-label" for="add_egp">
                                          USD
                                        </label>
                                        <br>
                                        <input class="form-check-input" type="radio" name="add_egp" id="add_egp" value="onlyegp" {{ "onlyegp" == old('add_egp',$invoice->add_egp) ? 'checked':''}}>
                                        <label class="form-check-label" for="add_egp">
                                          EGP
                                        </label>
                                    </div>
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
                                       @if($invoice->type == "invoice")
                                        <th class="text-center"><a id="add"> Add <i class="fas fa-plus"></i></a></th>
                                        
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                            @foreach($invoice_details as $key => $item)
                            <tr>
                                <input type="hidden" value ="{{ $item->id }}" name="invoiceChargeDesc[{{ $key }}][id]">
                                <td>
                                    <input type="text" id="charge_description" name="invoiceChargeDesc[{{ $key }}][charge_description]" class="form-control" 
                                    autocomplete="off" placeholder="Charge Description" value="{{old('charge_description',$item->charge_description)}}"  style="background-color: white;" requierd> 
                                </td>
                                <td>
                                    <input type="text" id="size_small" name="invoiceChargeDesc[{{ $key }}][size_small]" class="form-control" 
                                    autocomplete="off" placeholder="" value="{{old('size_small',$item->size_small)}}"  style="background-color: white;" requierd> 
                                </td>
                                <td>
                                    <input type="text" id="vat"  class="form-control" 
                                    autocomplete="off" placeholder="VAT" value="{{ (int)$item->size_small * 0 }}" disabled style="background-color: white;"> 
                                </td>
                             
                                <td><input type="text" class="form-control" id="ofr" name="invoiceChargeDesc[{{ $key }}][total_amount]" value="{{old('total_amount',$item->total_amount)}}"
                                    placeholder="Total" autocomplete="off"  style="background-color: white;" requierd>
                                </td>
                              
                                <td><input type="text" class="form-control" id="ofr" name="invoiceChargeDesc[{{ $key }}][total_egy]" value="{{old('total_egy',$item->total_egy)}}"
                                    placeholder="Egp Amount" autocomplete="off" style="background-color: white;" requierd>
                                </td>
                                @if($invoice->type == "invoice")
                                    <td style="width:85px;">
                                    <button type="button" class="btn btn-danger remove" onclick="removeItem({{$item->id}})"><i class="fa fa-trash"></i></button>
                                    </td>
                                @endif
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary mt-3">{{trans('forms.edit')}}</button>
                                    <a href="{{route('invoice.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                                </div>
                           </div>
                           <input name="removed" id="removed" type="hidden"  value="">
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
var removed = [];
function removeItem( item )
{
    removed.push(item);
    console.log(removed);
    document.getElementById("removed").value = removed;
}
$(document).ready(function(){
    $("#charges").on("click", ".remove", function () {
    $(this).closest("tr").remove();
    });
    var counter  = <?= isset($key)? ++$key : 0 ?>;

    $("#add").click(function(){
       var tr = '<tr>'+
           '<td><input type="text" name="invoiceChargeDesc['+counter+'][charge_description]" class="form-control" autocomplete="off" placeholder="Charge Description"></td>'+
           '<td><input type="text" name="invoiceChargeDesc['+counter+'][size_small]" class="form-control" autocomplete="off" placeholder="Amount"></td>'+
           '<td><input type="text" value="0" class="form-control" autocomplete="off" placeholder="VAT"></td>'+
           '<td><input type="text" name="invoiceChargeDesc['+counter+'][total_amount]" class="form-control" autocomplete="off" placeholder="Total"></td>'+
           '<td><input type="text" name="invoiceChargeDesc['+counter+'][total_egy]" class="form-control" autocomplete="off" placeholder="Egp Amount" requierd></td>'+
           '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>'
       '</tr>';
       counter++;
      $('#charges').append(tr);
    });
});
</script>

<script>
    $('#editForm').submit(function() {
        $('input').removeAttr('disabled');
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
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
                                <select class="selectpicker form-control" name="customer_id" id="customer" data-live-search="true" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                        @foreach($ffws as $ffw)
                                            <option value="{{$ffw->id}}">{{ $ffw->name }} Forwarder</option>
                                        @endforeach
                                        @foreach($shippers as $shipper)
                                            <option value="{{$shipper->id}}">{{ $shipper->name }} Shipper</option>
                                        @endforeach
                                        @foreach($suppliers as $supplier)
                                            <option value="{{$supplier->id}}">{{ $supplier->name }} Supplier</option>
                                        @endforeach
                                        @foreach($notify as $notifys)
                                            <option value="{{$notifys->id}}">{{ $notifys->name }} Notify</option>
                                        @endforeach
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
                                    <select class="selectpicker form-control" id="place_of_acceptence_id" data-live-search="true" name="place_of_acceptence" data-size="10"
                                 title="{{trans('forms.select')}}" >
                                        @foreach($ports as $port)
                                            <option value="{{$port->id}}" {{$port->id == old('place_of_acceptence_id') ? 'selected':''}}>{{$port->name}}</option>
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
                                 title="{{trans('forms.select')}}" >
                                        @foreach($ports as $port)
                                        <option value="{{$port->id}}" {{$port->id == old('load_port') ? 'selected':''}}>{{$port->name}}</option>
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
                                        <option value="{{$booking->id}}" {{$booking->id == old('booking_ref') ? 'selected':''}}>{{$booking->ref_no}}</option>
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
                                        <option value="{{$item->id}}" {{$item->id == old('voyage_id') ? 'selected':''}}>{{$item->vessel->name}} / {{$item->voyage_no}} - {{ optional($item->leg)->name }}</option>
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
                                            <option value="{{$port->id}}" {{$port->id == old('discharge_port') ? 'selected':''}}>{{$port->name}}</option>
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
                                        <option value="{{$port->id}}" {{$port->id == old('port_of_delivery') ? 'selected':''}}>{{$port->name}}</option>
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
                                            <option value="{{$equipmentType->id}}" {{$equipmentType->id == old('equipment_type') ? 'selected':''}}>{{$equipmentType->name}}</option>
                                            @endforeach
                                        </select>
                                    @error('equipment_type')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div> 

                                <div class="form-group col-md-3">
                                    <label for="status">Invoice Status<span class="text-warning"> * </span></label>
                                    <select class="form-control" data-live-search="true" name="invoice_status" title="{{trans('forms.select')}}" required>
                                        <option value="draft">Draft</option>
                                        <option value="ready_confirm">Ready To Confirm</option>
                                        @if(Auth::user()->id == 15)
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
                                        <input type="date" class="form-control" name="date" placeholder="Date" autocomplete="off" value="{{old('date',date('Y-m-d'))}}">
                                </div>
                                <div class="form-group col-md-3" >
                                    <label>QTY<span class="text-warning"> * (Required.) </span></label>
                                        <input type="text" class="form-control" placeholder="Qty" name="qty" autocomplete="off" style="background-color:#fff" required>
                                </div>
                            </div> 
                            <div class="form-row">
                                <div class="col-md-12 form-group">
                                    <label> Notes </label>
                                    <textarea class="form-control" name="notes"></textarea>
                                </div> 
                            </div>
                        <h4>Charges<h4>
                            <table id="debit" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Charge Description</th>
                                        <th class="text-center">Rate</th>
                                        <th class="text-center">Multiply QTY</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center"><a id="add"> Add <i class="fas fa-plus"></i></a></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="text" id="Charge Description" name="invoiceChargeDesc[0][charge_description]" class="form-control" autocomplete="off" placeholder="Charge Description" value ="Ocean Freight" required>
                                        </td>
                                        <td><input type="text" class="form-control" id="size_small" name="invoiceChargeDesc[0][size_small]" value=""
                                            placeholder="Amount" autocomplete="off"  style="background-color: white;"  required>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="invoiceChargeDesc[0][enabled]" id="item_0_enabled_yes" value="1" checked>
                                                <label class="form-check-label" for="item_0_enabled_yes">Yes</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="invoiceChargeDesc[0][enabled]" id="item_0_enabled_no" value="0">
                                                <label class="form-check-label" for="item_0_enabled_no">No</label>
                                            </div>
                                        </td>
                                        <td><input type="text" class="form-control" id="ofr" name="invoiceChargeDesc[0][total_amount]" value=""
                                            placeholder="Total" autocomplete="off" disabled style="background-color: white;" required>
                                        </td>
                                    </tr>
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
    $(document).on('input', 'input[name="qty"]', function() {
        var qty = $(this).val();
        $('#debit tbody tr').each(function() {
            var sizeSmall = $(this).find('input[name$="[size_small]"]').val();
            var enabled = $(this).find('input[name$="[enabled]"]:checked').val();
            var totalAmount = enabled == 1 ? sizeSmall * qty : sizeSmall;
            $(this).find('input[name$="[total_amount]"]').val(totalAmount);
        });
    });
    $('body').on('input', 'input[name$="[size_small]"]', function() {
    // Get the current row
    var row = $(this).closest('tr');

    // Get the qty value from the QTY input field
    var qty = $('input[name="qty"]').val();

    // Get the size_small value from the current row
    var sizeSmall = $(this).val();

    // Get the enabled value from the current row
    var enabled = row.find('input[name$="[enabled]"]:checked').val();

    // Calculate the total amount and update the total_amount input field of the current row
    var totalAmount = enabled == 1 ? qty * sizeSmall : sizeSmall;
    row.find('input[name$="[total_amount]"]').val(totalAmount);
});
$('body').on('change', 'input[name$="[enabled]"]', function() {
    var row = $(this).closest('tr');
    var sizeSmall = row.find('input[name$="[size_small]"]').val();
    var qty = $('input[name="qty"]').val();
    var totalAmount = 0;
    if($(this).val() == 1) {
        totalAmount = sizeSmall * qty;
    } else {
        totalAmount = sizeSmall;
    }
    row.find('input[name$="[total_amount]"]').val(totalAmount);
});
</script>
<script>
    $('#createForm').submit(function() {
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

<script>
    $(document).ready(function(){
        $("#debit").on("click", ".remove", function () {
            $(this).closest("tr").remove();
        });
        var counter  = 1;
        $("#add").click(function(){
           var tr = '<tr>'+
               '<td><input type="text" name="invoiceChargeDesc['+counter+'][charge_description]" class="form-control" autocomplete="off" placeholder="Charge Description" required></td>'+
               '<td><input type="text" name="invoiceChargeDesc['+counter+'][size_small]" class="form-control" autocomplete="off" placeholder="Rate" required></td>'+
               '<td><div class="form-check"><input class="form-check-input" type="radio" name="invoiceChargeDesc['+counter+'][enabled]" id="item_'+counter+'_enabled_yes" value="1" checked><label class="form-check-label" for="item_'+counter+'_enabled_yes">Yes</label></div><div class="form-check"><input class="form-check-input" type="radio" name="invoiceChargeDesc['+counter+'][enabled]" id="item_'+counter+'_enabled_no" value="0"><label class="form-check-label" for="item_'+counter+'_enabled_no">No</label></div></td>'+
               '<td><input type="text" name="invoiceChargeDesc['+counter+'][total_amount]" class="form-control" autocomplete="off" placeholder="Total" disabled required></td>'+
               '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>'
           '</tr>';
           counter++;
          $('#debit').append(tr);
        });
    });
    </script>
@endpush
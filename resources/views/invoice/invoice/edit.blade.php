@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            @if($invoice->type == "invoice")
                            <li class="breadcrumb-item"><a a href="{{route('invoice.index')}}">Invoice</a></li> 
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Edit Invoice</a></li>
                            @else
                            <li class="breadcrumb-item"><a a href="{{route('invoice.index')}}">Invoice</a></li> 
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Edit Debit</a></li>
                            @endif
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
                                    placeholder="Invoice No" autocomplete="off" value="{{old('invoice_no',$invoice->invoice_no)}}" required>
                            </div> 
                            <input type="hidden" name="bldraft_id" value="{{request()->input('bldraft_id')}}">
                                <div class="form-group col-md-6">
                                <label for="customer">Customer<span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" name="customer_id" id="customer" data-live-search="true" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                        @if($bldraft != null)
                                        @if(optional($bldraft->booking->forwarder)->name != null)
                                        <option value="{{optional($bldraft->booking)->ffw_id}}" {{optional($bldraft->booking)->ffw_id == old('customer_id',$invoice->customer_id) ? 'selected':''}}>{{ optional($bldraft->booking->forwarder)->name }} Forwarder</option>
                                        @endif
                                        <option value="{{optional($bldraft)->customer_id}}" {{optional($bldraft)->customer_id == old('customer_id',$invoice->customer_id) ? 'selected':''}}>{{ optional($bldraft->customer)->name }} Shipper</option>
                                        @endif
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
                                <select class="selectpicker form-control" id="voyage_id" data-live-search="true" name="voyage_id"  data-size="10"
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
                                        <input type="text" class="form-control" placeholder="Equipment Type"  autocomplete="off" value="{{(optional($bldraft->equipmentsType)->name)}}" style="background-color:#fff" disabled>
                                        @endif
                                </div> 
                                @if($invoice->invoice_status != "confirm")
                                <div class="form-group col-md-3">
                                    <label for="status">Invoice Status<span class="text-warning"> * </span></label>
                                    <select class="form-control" data-live-search="true" name="invoice_status" title="{{trans('forms.select')}}" required>
                                        <option value="draft" {{ old('invoice_status',$invoice->invoice_status) == "draft" ? 'selected':'' }}>Draft</option>
                                        @if($bldraft->bl_status == 1)
                                        <option value="confirm" {{ old('invoice_status',$invoice->invoice_status) == "confirm" ? 'selected':'' }}>Confirm</option>
                                        @endif
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
                                        <input type="date" class="form-control" name="date" placeholder="Date" autocomplete="off" required value="{{old('date',$invoice->date)}}">
                                </div>
                                <div class="form-group col-md-3" >
                                    <label>QTY</label>
                                        <input type="text" class="form-control" placeholder="Qty"  autocomplete="off" value="{{$qty}}" style="background-color:#fff" disabled>
                                </div>
                                <!-- <div class="col-md-3 form-group">
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
                                </div> -->
                                @if($invoice->type == "invoice")

                                <div class="form-group col-md-3" >
                                    <label>TAX</label>
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
                                @endif
                            </div> 
                            <div class="form-row">
                                <div class="col-md-12 form-group">
                                    <label> Notes </label>
                                    <textarea class="form-control" name="notes">{{old('notes',$invoice->notes)}}</textarea>
                                </div> 
                            </div>
                        <h4>Charges<h4>
                    @if($invoice->type == "invoice")

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
                                        @else
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
                        @else
                        <table id="containerDetails" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">Charge Description</th>
                                    <th class="text-center">Rate 20/40</th>
                                    <th class="text-center">Amount 20/40</th>
                                </tr>
                            </thead>
                            <tbody>
                     @foreach($invoice_details as $key => $item)

                        <tr>
                            <input type="hidden" value ="{{ $item->id }}" name="invoiceChargeDesc[{{ $key }}][id]">

                            <td>
                                <input type="text" id="Charge Description" name="invoiceChargeDesc[{{ $key }}][charge_description]" class="form-control" autocomplete="off" placeholder="Charge Description" value="{{(old('charge_description',$item->charge_description))}}" >
                            </td>
                            <td><input type="text" class="form-control" id="size_small" name="invoiceChargeDesc[{{ $key }}][size_small]" value="{{(optional($bldraft->booking->quotation)->ofr)}}"
                                placeholder="Rate" autocomplete="off" disabled style="background-color: white;">
                            </td> 
                            <td><input type="text" class="form-control" id="ofr" name="invoiceChargeDesc[{{ $key }}][total_amount]" value="{{(optional($bldraft->booking->quotation)->ofr) * $qty}}"
                                placeholder="Ofr" autocomplete="off" disabled style="background-color: white;">
                            </td>
                        </tr>
                        @endforeach

                        </tbody>
                    </table>
                    @endif


                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary mt-3">Confirm</button>
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
    $('#editForm').submit(function() {
        $('input').removeAttr('disabled');
    });
</script>
<script>
    $('#editForm').submit(function() {
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
           '<td><input type="text" name="invoiceChargeDesc['+counter+'][charge_description]" class="form-control" autocomplete="off" placeholder="Charge Description" required></td>'+
           '<td><input type="text" name="invoiceChargeDesc['+counter+'][size_small]" class="form-control" autocomplete="off" placeholder="Amount" required></td>'+
           '<td><input type="text" value="0" class="form-control" autocomplete="off" placeholder="VAT"></td>'+
           '<td><input type="text" name="invoiceChargeDesc['+counter+'][total_amount]" class="form-control" autocomplete="off" placeholder="Total" required></td>'+
           '<td><input type="text" name="invoiceChargeDesc['+counter+'][total_egy]" class="form-control" autocomplete="off" placeholder="Egp Amount" requierd></td>'+
           '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>'
       '</tr>';
       counter++;
      $('#charges').append(tr);
    });
});
</script>
@endpush
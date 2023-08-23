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
                                        @elseif(optional($bldraft->booking->consignee)->name != null)
                                        <option value="{{optional($bldraft->booking)->customer_consignee_id}}" {{optional($bldraft->booking)->customer_consignee_id == old('customer_id',$invoice->customer_id) ? 'selected':''}}>{{ optional($bldraft->booking->consignee)->name }} Consignee</option>
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
                                            <option value="{{$item->id}}" {{$item->id == old('voyage_id',$bldraft->voyage_id) ? 'selected':''}}>{{$item->vessel->name}} / {{$item->voyage_no}} - {{ optional($item->leg)->name }}</option>
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
                                <div class="form-group col-md-2" >
                                    <label>QTY</label>
                                        <input type="text" class="form-control" placeholder="Qty"  name="qty" autocomplete="off" value="{{$qty}}" style="background-color:#fff" disabled>
                                </div>
                                @if($invoice->type == "invoice")

                                <div class="form-group col-md-2" >
                                    <label>TAX</label>
                                        <input type="text" class="form-control" placeholder="TAX %" name="tax_discount"  value="{{old('tax_discount',$invoice->tax_discount)}}" autocomplete="off"  style="background-color:#fff" >
                                </div>
                                <div class="col-md-2 form-group " >
                                    <div style="padding: 30px;">
                                        <input class="form-check-input" type="radio" name="rate" id="rate" value="eta" {{ "eta" == old('rate',$invoice->rate) ? 'checked':''}}>
                                        <label class="form-check-label" for="exchange_rate">
                                        ETA Rate {{ optional($bldraft->voyage)->exchange_rate }}
                                        </label>
                                        <br>
                                        <input class="form-check-input" type="radio" name="rate" id="exchange_rate" value="etd"  {{ "etd" == old('rate',$invoice->rate) ? 'checked':''}}>
                                        <label class="form-check-label" for="exchange_rate">
                                          ETD Rate {{ optional($bldraft->voyage)->exchange_rate_etd }}
                                        </label>
                                    </div>
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
                                <div class="form-group col-md-3">
                                    <label for="vat">VAT %:</label>
                                    <input type="text" class="form-control" id="vat" name="vat" value="{{old('vat',$invoice->vat)}}">
                                </div>
                                <div class="form-group col-md-3" >
                                    <label>Total USD</label>
                                        <input type="text" class="form-control" id="total_usd"  value="{{round($total,2)}}" autocomplete="off"  style="background-color:#fff" readonly>
                                </div>
                                <div class="form-group col-md-3" >
                                    <label>Total EGP</label>
                                        <input type="text" class="form-control" id="total_egp"  value="{{round($total_eg,2)}}" autocomplete="off"  style="background-color:#fff" readonly>
                                </div>
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
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">Add VAT</th>
                                        <th class="text-center">Multiply QTY</th>
                                        <th class="text-center">TOTAL USD</th>
                                        <th class="text-center">USD After VAT</th>
                                        <th class="text-center">Total Egp</th>
                                        <th class="text-center">EGP After VAT</th>
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
                                    <select class="selectpicker form-control" id="charge_description" data-live-search="true" name="invoiceChargeDesc[{{$key}}][charge_description]" data-size="10"
                                        title="{{trans('forms.select')}}" autofocus disabled>
                                        @foreach ($charges as $charge)
                                            <option value="{{$charge->id}}" {{$charge->id == old('charge_description',$item->charge_description)? 'selected':''}}>{{$charge->name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" id="size_small" name="invoiceChargeDesc[{{ $key }}][size_small]" class="form-control" 
                                    autocomplete="off" placeholder="" value="{{old('size_small',$item->size_small)}}"  style="background-color: white;" required> 
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input vatRadio" type="radio" name="invoiceChargeDesc[{{$key}}][add_vat]" id="item_{{$key}}_enabled_yes" value="1" {{ $item->add_vat ==  1 ? 'checked="checked"' :''}}>
                                        <label class="form-check-label" for="item_{{$key}}_enabled_yes">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input vatRadio" type="radio" name="invoiceChargeDesc[{{$key}}][add_vat]" id="item_{{$key}}_enabled_no" value="0" {{ $item->add_vat ==  0 ? 'checked="checked"' :''}}>
                                        <label class="form-check-label" for="item_{{$key}}_enabled_no">No</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="invoiceChargeDesc[{{$key}}][enabled]" id="item_{{$key}}_enabled_yes" value="1" {{ $item->enabled ==  1 ? 'checked="checked"' :''}} disabled>
                                        <label class="form-check-label" for="item_{{$key}}_enabled_yes">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="invoiceChargeDesc[{{$key}}][enabled]" id="item_{{$key}}_enabled_no"  value="0" {{$item->enabled == 0 ? 'checked="checked"' :''}} disabled>
                                        <label class="form-check-label" for="item_{{$key}}_enabled_no">No</label>
                                    </div>
                                </td>


                                <td><input type="text" class="form-control" id="usd_{{ $key }}" name="invoiceChargeDesc[{{ $key }}][total_amount]" value="{{old('total_amount',$item->total_amount)}}"
                                    placeholder="Total" autocomplete="off"  style="background-color: white;" required disabled>
                                </td>
                                <td><input type="text" id="usd_vat_{{ $key }}" name="invoiceChargeDesc[{{ $key }}][usd_vat]" class="form-control" autocomplete="off" placeholder="USD After VAT" disabled></td>


                                <td><input type="text" class="form-control" id="egp_{{ $key }}" name="invoiceChargeDesc[{{ $key }}][total_egy]" value="{{old('total_egy',$item->total_egy)}}"
                                    placeholder="Egp Amount" autocomplete="off" style="background-color: white;" required disabled>
                                </td>
                                
                                <td><input id="egp_vat_{{ $key }}" type="text" name="invoiceChargeDesc[{{ $key }}][egp_vat]" class="form-control" autocomplete="off" placeholder="Egp After VAT" disabled></td>

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
    @include('invoice.invoice._js_vat_table')
    <script>
    $('#editForm').submit(function() {
        $('input').removeAttr('disabled');
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
    $(document).on('input', 'input[name="rate"]', function() {
        var exchange = $(this).val();
        var qty = $('input[name="qty"]').val();
        var totEgp = 0;
        $('#charges tbody tr').each(function() {
            var sizeSmall = $(this).find('input[name$="[size_small]"]').val();
            var enabled = $(this).find('input[name$="[enabled]"]:checked').val();
            var totalAmount = enabled == 1 ? sizeSmall * qty : sizeSmall;
            $(this).find('input[name$="[total_amount]"]').val(totalAmount);
            var eta  = "{{optional($bldraft->voyage)->exchange_rate}}";
            var etd  = "{{optional($bldraft->voyage)->exchange_rate_etd}}";
            var exchangeRate = exchange === 'eta' ? eta : etd;
            var egpAmount = totalAmount * exchangeRate;
            totEgp = totEgp + parseFloat(egpAmount);
            $(this).find('input[name$="[total_egy]"]').val(egpAmount);
        });
        $('input[id="total_egp"]').val(totEgp);
        handleVatInput()

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

    // Calculate the total EGP Amount and update the Amount input field of the current row
    var exchange = $('input[name="rate"]:checked').val();
    var eta  = "{{optional($bldraft->voyage)->exchange_rate}}";
    var etd  = "{{optional($bldraft->voyage)->exchange_rate_etd}}";
    var exchangeRate = exchange === 'eta' ? eta : etd;
    var egpAmount = totalAmount * exchangeRate;
    row.find('input[name$="[total_egy]"]').val(egpAmount);
    // update total_egp and usd to calculate all rows
        handleVatInput()
});

$('body').on('input', 'input[name$="[size_small]"]', function() {
    // Get the current row
    var row = $(this).closest('tr');

    // Get the qty value from the QTY input field
    var qty = $('input[name="qty"]').val();

    // Get the size_small value from the current row
    var sizeSmall = $(this).val();

    var enabled = row.find('input[name$="[enabled]"]:checked').val();

    // Calculate the total amount and update the total_amount input field of the current row
    var totalAmount = enabled == 1 ? sizeSmall * qty : sizeSmall;

    row.find('input[name$="[total_amount]"]').val(totalAmount);
    var eta  = "{{optional($bldraft->voyage)->exchange_rate}}";
    var etd  = "{{optional($bldraft->voyage)->exchange_rate_etd}}";
    // Calculate the total EGP Amount and update the Amount input field of the current row
    var exchangeRate = $('input[name="rate"]:checked').val();
    exchangeRate = exchangeRate === 'eta' ? eta : etd;
    var egpAmount = totalAmount * exchangeRate;
    row.find('input[name$="[total_egy]"]').val(egpAmount);

    // update total_egp and usd to calculate all rows
    calculateTotals()

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
    // update total_egp and usd to calculate all rows 

        calculateTotals()

    });
});
</script>
@endpush
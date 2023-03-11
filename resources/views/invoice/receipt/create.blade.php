@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="{{route('receipt.index')}}">Receipt</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">New Receipt</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
           
                    <form id="createForm" action="{{route('receipt.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                        <div class="form-row">
                            <input type="hidden" name="bldraft_id" value="{{request()->input('bldraft_id')}}">
                            <input type="hidden" name="invoice_id" value="{{request()->input('invoice_id')}}">

                            <div class="col-md-2 form-group">
                                <div style="padding: 23px;">
                                    <input class="form-check-input" type="checkbox" name="bank_deposit">
                                    <label class="form-check-label">
                                    Bank Deposit
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2 form-group">
                                    <input type="text" class="form-control" name="amount">
                            </div>
                            <div class="col-md-2 form-group">
                                <div style="padding: 23px;">
                                    <input class="form-check-input" type="checkbox" name="bank_transfer">
                                    <label class="form-check-label">
                                    Bank Transfer
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2 form-group">
                                    <input type="text" class="form-control" name="amount">
                            </div>
                        </div> 
                        <div class="form-row">
                            <div class="col-md-2 form-group">
                                <div style="padding: 23px;">
                                    <input class="form-check-input" type="checkbox" name="bank_check">
                                    <label class="form-check-label">
                                    Cheak 
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2 form-group">
                                    <input type="text" class="form-control" name="amount">
                            </div>
                            <div class="col-md-2 form-group">
                                <div style="padding: 23px;">
                                    <input class="form-check-input" type="checkbox" name="bank_cash">
                                    <label class="form-check-label">
                                        Cash
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2 form-group">
                                    <input type="text" class="form-control" name="amount">
                            </div>
                        </div> 
                        <div class="form-row">
                            <div class="col-md-2 form-group">
                                <div style="padding: 23px;">
                                    <input class="form-check-input" type="checkbox" name="matching">
                                    <label class="form-check-label">
                                        Matching
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2 form-group">
                                    <input type="text" class="form-control" name="amount">
                            </div>
                        </div> 
                        <div class="form-row">
                            <div class="col-md-4 form-group">
                                <label> Total </label>
                                    <input  class="form-control"  type="text" name="total_payment" value="{{$invoice->add_egp == "false"? $total." USD" : $total_eg." EGP"}}" readonly>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label> Customer Credit </label>
                                        <input  class="form-control"  type="text" name="customer_credit" value="{{optional($invoice->customerShipperOrFfw)->credit}}" readonly>
                                </div>
                                    <div class="col-md-4 form-group">
                                        <label> Customer Debit </label>
                                            <input  class="form-control"  type="text" name="customer_debit" value="{{optional($invoice->customerShipperOrFfw)->debit}}" readonly>
                                    </div>     
                        </div> 
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
                                    <a href="{{route('receipt.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
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
@endpush
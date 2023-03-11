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
                            <div class="col-md-6 form-group">
                            <h5>Invoice No : {{$invoice->invoice_no}}<h5> 
                            </div>
                            <div class="col-md-6 form-group">
                                <h5>BLdraft No : {{$bldraft->ref_no}}<h5> 
                            </div>
                        </div>


                        <div class="form-row">
                            <input type="hidden" name="bldraft_id" value="{{request()->input('bldraft_id')}}">
                            <input type="hidden" name="invoice_id" value="{{request()->input('invoice_id')}}">

                            <div class="col-md-2 form-group">
                                <div style="padding: 23px;">
                                    <label class="form-check-label">
                                    Bank Deposit
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2 form-group">
                                    <input type="text" class="form-control" name="bank_deposit">
                            </div>
                            <div class="col-md-2 form-group">
                                <div style="padding: 23px;">
                                    <label class="form-check-label">
                                    Bank Transfer
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2 form-group">
                                    <input type="text" class="form-control" name="bank_transfer">
                            </div>
                        </div> 
                        <div class="form-row">
                            <div class="col-md-2 form-group">
                                <div style="padding: 23px;">
                                    <label class="form-check-label">
                                    Cheak 
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2 form-group">
                                    <input type="text" class="form-control" name="bank_check">
                            </div>
                            <div class="col-md-2 form-group">
                                <div style="padding: 23px;">
                                    <label class="form-check-label">
                                        Cash
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2 form-group">
                                    <input type="text" class="form-control" name="bank_cash">
                            </div>
                        </div> 
                        <div class="form-row">
                            <div class="col-md-2 form-group">
                                <div style="padding: 23px;">
                                    <label class="form-check-label">
                                        Matching
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2 form-group">
                                    <input type="text" class="form-control" name="matching">
                            </div>
                        </div> 
                        <div class="form-row">
                            <div class="col-md-4 form-group">
                                <label> Total </label>
                                    <input  class="form-control"  type="text" name="total_payment" value="{{$invoice->add_egp == "false"? $total." USD" : $total_eg." EGP"}}" readonly>
                                </div>
                                <div class="col-md-2 form-group">
                                    <label> Customer Credit USD </label>
                                        <input  class="form-control"  type="text" name="customer_credit" value="{{optional($invoice->customerShipperOrFfw)->credit}}" readonly>
                                </div>
                                <div class="col-md-2 form-group">
                                    <label> Customer Credit EGP </label>
                                        <input  class="form-control"  type="text" name="customer_credit" value="{{optional($invoice->customerShipperOrFfw)->credit_egp}}" readonly>
                                </div>
                                <div class="col-md-2 form-group">
                                    <label> Customer Debit USD </label>
                                        <input  class="form-control"  type="text" name="customer_debit" value="{{optional($invoice->customerShipperOrFfw)->debit}}" readonly>
                                </div>    
                                <div class="col-md-2 form-group">
                                    <label> Customer Debit EGP </label>
                                        <input  class="form-control"  type="text" name="customer_debit" value="{{optional($invoice->customerShipperOrFfw)->debit_egp}}" readonly>
                                </div>  
                        </div> 
                        <div class="form-row">
                            <div class="col-md-12 form-group">
                                <label> Notes </label>
                                <textarea class="form-control" name=""></textarea>
                            </div> 
                        </div> 
                        @if($oldReceipts->count() != 0)  
                            <h4>Receipts<h4>
                            <table id="charges" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Receipt No</th>
                                            <th class="text-center">Customer</th>
                                            <th class="text-center">Invoice</th>
                                            <th class="text-center">BL Draft</th>
                                            <th class="text-center">Payment Method</th>
                                            <th class="text-center">Amount Paid</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                <tr>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                </tr>
                                </tbody>
                            </table>
                        @endif
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

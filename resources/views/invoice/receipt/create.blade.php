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
                            <h5>Invoice No : <h5> 
                            </div>
                            <div class="col-md-6 form-group">
                                <h5>BLdraft No : <h5> 
                            </div>
                        </div>


                        <div class="form-row">
                            <input type="hidden" name="bldraft_id" value="{{request()->input('bldraft_id')}}">
                            <input type="hidden" name="invoice_id" value="{{request()->input('invoice_id')}}">

                            <div class="col-md-2 form-group">
                                <div style="padding: 23px;">
                                    <input class="form-check-input" type="checkbox" name="">
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
                                    <input class="form-check-input" type="checkbox" name="">
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
                                    <input class="form-check-input" type="checkbox" name="">
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
                                    <input class="form-check-input" type="checkbox" name="">
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
                                    <input class="form-check-input" type="checkbox" name="">
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
                                    <input  class="form-control"  type="text" name="">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label> Customer Credit </label>
                                        <input  class="form-control"  type="text" name="">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label> Customer Debit </label>
                                        <input  class="form-control"  type="text" name="">
                                </div>     
                        </div> 
                        <div class="form-row">
                            <div class="col-md-12 form-group">
                                <label> Notes </label>
                                <textarea class="form-control" name=""></textarea>
                            </div> 
                        </div> 

                        <h4>Bl Engaged<h4>
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

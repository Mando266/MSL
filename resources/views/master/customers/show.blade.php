@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data </a></li>
                            <li class="breadcrumb-item"><a href="{{route('customers.index')}}">Customers</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Customer Show</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('customers.edit',['customer'=>$customer])}}" method="get">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nameInput">Name</label>
                                <input type="text" class="form-control" id="nameInput"  value="{{$customer->name}}" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="contactInput">Contact Person</label>
                                <input type="text" class="form-control" id="contactInput"  value="{{$customer->contact_person}}" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="codeInput">Code</label>
                                <input type="text" class="form-control" id="codeInput"  value="{{$customer->code}}" disabled>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="phoneInput">Phone</label>
                                <input type="text" class="form-control" id="phoneInput" value="{{$customer->phone}}" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="emailInput">Email</label>
                                <input type="text" class="form-control" id="emailInput" value="{{$customer->email}}" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="emailInput">Tax Card No</label>
                            <input type="text" class="form-control" id="emailInput" value="{{$customer->tax_card_no}}" disabled>
                            </div>
                        </div>
                        <div class="form-row">
                        <div class="form-group col-md-4">
                                <label for="countryInput">Country</label>
                                <input type="text" class="form-control" id="countryInput" value="{{optional($customer->country)->name}}" disabled>

                            </div>
                            <div class="form-group col-md-4">
                                <label for="cityInput">City</label>
                                <input type="text" class="form-control" id="cityInput" value="{{$customer->city}}" disabled>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="cityInput">Sales Person</label>
                                <input type="text" class="form-control" id="cityInput" value="{{optional($customer->User)->name}}" disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="customer_role_id">Customer Role</label>
                                <input type="text" class="form-control" id="customer_role_id" value="{{{optional($customer->CustomerRoles)->name}}}" disabled>
                            </div>
                        </div>
                       <div class="row">
                            <div class="col-md-12 text-center">
                                @permission('Customer-Edit')
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.edit')}}</button>
                                @endpermission
                                <a href="{{route('customers.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                       </div>


                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
    <link href="{{asset('plugins/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" >
@endpush
@push('scripts')
<script src="{{asset('plugins/bootstrap-select/bootstrap-select.min.js')}}"></script>
@endpush

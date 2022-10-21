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
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Add New Customer</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('customers.store')}}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nameInput">Name *</label>
                            <input type="text" class="form-control" id="nameInput" name="name" value="{{old('name')}}"
                                 placeholder="Name" autocomplete="off" autofocus>
                                @error('name')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="codeInput">Contact Person</label>
                                <input type="text" class="form-control" id="contact_personInput" name="contact_person" value="{{old('contact_person')}}"
                                    placeholder="Contact Person" autocomplete="off">
                                @error('contact_person')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="countryInput">{{trans('company.country')}}</label>
                                <select class="selectpicker form-control" id="countryInput" data-live-search="true" name="country_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($countries as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('country_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
  
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="cityInput">City</label>
                            <input type="text" class="form-control" id="cityInput" name="city" value="{{old('city')}}"
                                placeholder="City" autocomplete="off">
                            @error('city')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="cityInput">Address</label>
                            <input type="text" class="form-control" id="addressInput" name="address" value="{{old('address')}}"
                                placeholder="Address" autocomplete="off">
                            @error('city')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                                <label for="phoneInput">Phone</label>
                                <input type="text" class="form-control" id="phoneInput" name="phone" value="{{old('phone')}}"
                                    placeholder="Phone" autocomplete="off">
                                @error('phone')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                                <label for="emailInput">Email</label>
                                <input type="text" class="form-control" id="emailInput" name="email" value="{{old('email')}}"
                                    placeholder="Email" autocomplete="off">
                                @error('email')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        <div class="form-group col-md-4">
                            <label for="tax_card_noInput">Tax Card</label>
                            <input type="text" class="form-control" id="tax_card_noInput" name="tax_card_no" value="{{old('tax_card_no')}}"
                                placeholder="Tax Card" autocomplete="off">
                            @error('tax_card_no')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                                    <label for="sales_person_idInput">Sales Person</label>
                                    <select class="selectpicker form-control" id="sales_person_idInput" data-live-search="true" name="sales_person_id" data-size="10"
                                    title="{{trans('forms.select')}}">
                                        @foreach ($users as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('sales_person_id') ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('sales_person_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-4">
                                    <label for="customer_role_idInput">Customer Role</label>
                                    <select class="selectpicker form-control" id="customer_role_idInput" data-live-search="true" name="customer_role_id" data-size="10"
                                    title="{{trans('forms.select')}}">
                                        @foreach ($customer_roles as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('customer_role_id') ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('customer_role_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                    </div>

                       <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
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

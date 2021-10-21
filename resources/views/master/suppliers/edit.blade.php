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
                            <li class="breadcrumb-item"><a href="{{route('suppliers.index')}}">Suppliers</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> {{trans('forms.edit')}}</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('suppliers.update',['supplier'=>$supplier])}}" method="POST">
                        @csrf
                        @method('put')
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nameInput">Name *</label>
                            <input type="text" class="form-control" id="nameInput" name="name" value="{{old('name',$supplier->name)}}"
                                 placeholder="Name" autocomplete="disabled" autofocus>
                                @error('name')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="countryInput">{{trans('company.country')}}</label>
                                <select class="selectpicker form-control" id="countryInput" data-live-search="true" data-size="10"
                                name="country_id" title="{{trans('forms.select')}}">
                                    @foreach ($countries as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('country_id',$supplier->country_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="status">Container Depot</label>
                                <select class="selectpicker form-control"  name="is_container_depot">
                                    <option value="1" {{ old('status',$supplier->is_container_depot) == "1" ? 'selected':'' }}>Yes</option>
                                    <option value="0" {{ old('status',$supplier->is_container_depot) == "0" ? 'selected':'' }}>No</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label for="status">Services Provider</label>
                                <select class="selectpicker form-control"  name="is_container_services_provider">
                                    <option value="1" {{ old('status',$supplier->is_container_services_provider) == "1" ? 'selected':'' }}>Yes</option>
                                    <option value="0" {{ old('status',$supplier->is_container_services_provider) == "0" ? 'selected':'' }}>No</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-2">
                                <label for="status">Container Seller</label>
                                <select class="selectpicker form-control"  name="is_container_seller">
                                    <option value="1" {{ old('status',$supplier->is_container_seller) == "1" ? 'selected':'' }}>Yes</option>
                                    <option value="0" {{ old('status',$supplier->is_container_seller) == "0" ? 'selected':'' }}>No</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-2">
                                <label for="status">Container Trucker</label>
                                <select class="selectpicker form-control"  name="is_container_trucker">
                                    <option value="1" {{ old('status',$supplier->is_container_trucker) == "1" ? 'selected':'' }}>Yes</option>
                                    <option value="0" {{ old('status',$supplier->is_container_trucker) == "0" ? 'selected':'' }}>No</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-2">
                                <label for="status">Container Lessor</label>
                                <select class="selectpicker form-control"  name="is_container_lessor">
                                    <option value="1" {{ old('status',$supplier->is_container_lessor) == "1" ? 'selected':'' }}>Yes</option>
                                    <option value="0" {{ old('status',$supplier->is_container_lessor) == "0" ? 'selected':'' }}>No</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-2">
                                <label for="status">Container Haulage</label>
                                <select class="selectpicker form-control"  name="is_container_haulage">
                                    <option value="1" {{ old('status',$supplier->is_container_haulage) == "1" ? 'selected':'' }}>Yes</option>
                                    <option value="0" {{ old('status',$supplier->is_container_haulage) == "0" ? 'selected':'' }}>No</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-2">
                                <label for="status">Container Terminal</label>
                                <select class="selectpicker form-control"  name="is_container_terminal">
                                    <option value="1" {{ old('status',$supplier->is_container_terminal) == "1" ? 'selected':'' }}>Yes</option>
                                    <option value="0" {{ old('status',$supplier->is_container_terminal) == "0" ? 'selected':'' }}>No</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                       <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.update')}}</button>
                                <a href="{{route('suppliers.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                       </div>


                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
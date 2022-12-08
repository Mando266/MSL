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
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Add New Supplier</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area"> 
                <form id="createForm" action="{{route('suppliers.store')}}" method="POST">
                        @csrf
                        @if(session('alert'))
                            <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ session('alert') }}</p>
                        @endif
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nameInput"> Name *</label>
                            <input type="text" class="form-control" id="nameInput" name="name" value="{{old('name')}}"
                                 placeholder="Name" autocomplete="disabled" autofocus>
                                @error('name')
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
                            <div class="form-group col-md-4">
                            <label for="countryInput">Supplier Type</label>
                                </br>
                                <input type="checkbox" id="is_container_depotInput" name="is_container_depot" value="1"><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px;"> Container Depot </a>
                                </br>
                                <input type="checkbox" id="is_container_services_providerInput" name="is_container_services_provider" value="1"><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px;"> Container Services Provider</a>
                                </br>
                                <input type="checkbox" id="is_container_sellerInput" name="is_container_seller" value="1"><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px;"> Container Seller</a>
                                </br>
                                <input type="checkbox" id="is_container_truckerInput" name="is_container_trucker" value="1"><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px;"> Container Trucker </a>
                                </br>
                                <input type="checkbox" id="is_container_lessorInput" name="is_container_lessor" value="1"><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px;"> Container Lessor</a>
                                </br>
                                <input type="checkbox" id="is_container_haulageInput" name="is_container_haulage" value="1"><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px;"> Container Haulage</a>
                                </br>
                            </div>
                        </div>

                       <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
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
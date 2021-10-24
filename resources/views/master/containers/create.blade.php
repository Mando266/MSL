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
                            <li class="breadcrumb-item"><a href="{{route('containers.index')}}">Containers</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Add New Container</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('containers.store')}}" method="POST">
                        @csrf
                        <div class="form-row">
                        <div class="form-group col-md-4">
                                <label for="countryInput">Container Type</label>
                                <select class="selectpicker form-control" id="countryInput" data-live-search="true" name="container_type_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($container_types as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('container_type_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('container_type_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="codeInput">Number</label>
                                <input type="text" class="form-control" id="codeInput" name="code" value="{{old('code')}}"
                                    placeholder="Number" autocomplete="off">
                                @error('code')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="descriptionInput">Description</label>
                                <input type="text" class="form-control" id="descriptionInput" name="description" value="{{old('description')}}"
                                    placeholder="Tar Weight" autocomplete="off">
                                @error('description')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
  
                    <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="tar_weightInput">Tar Weight</label>
                                <input type="text" class="form-control" id="tar_weightInput" name="tar_weight" value="{{old('tar_weight')}}"
                                    placeholder="Tar Weight" autocomplete="off">
                                @error('tar_weight')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="max_payloadInput">Max Payload</label>
                                <input type="text" class="form-control" id="max_payloadInput" name="max_payload" value="{{old('max_payload')}}"
                                    placeholder="Max Payload" autocomplete="off">
                                @error('max_payload')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="production_yearInput">Production Year</label>
                                <input type="text" class="form-control" id="production_yearInput" name="production_year" value="{{old('production_year')}}"
                                    placeholder="Production Year" autocomplete="off">
                                @error('production_year')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                    </div>
                    <!-- <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="last_movementInput">Last Movement</label>
                                <input type="text" class="form-control" id="last_movementInput" name="last_movement" value="{{old('last_movement')}}"
                                    placeholder="Last Movement" autocomplete="off">
                                @error('last_movement')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                    </div> -->

                       <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
                                <a href="{{route('containers.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                       </div>


                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

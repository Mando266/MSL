@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Containers Movement </a></li>
                                <li class="breadcrumb-item"><a href="{{route('containers.index')}}">Containers</a></li>
                                <li class="breadcrumb-item active"><a
                                            href="javascript:void(0);"> {{trans('forms.edit')}}</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="widget-content widget-content-area">
                        <form id="createForm" action="{{route('containers.update',['container'=>$container])}}"
                              method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            @if(session('alert'))
                                <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ session('alert') }}</p>
                            @endif
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="countryInput">Container Type</label>
                                    <select class="selectpicker form-control" id="countryInput" data-live-search="true"
                                            name="container_type_id" data-size="10"
                                            title="{{trans('forms.select')}}">
                                        @foreach ($container_types as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('container_type_id',$container->container_type_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('container_type_id')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="codeInput">Container Number</label>
                                    <input type="text" class="form-control" id="codeInput" name="code"
                                           value="{{old('code',$container->code)}}"
                                           placeholder="Container Numbere" autocomplete="off">
                                    @error('code')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="countryInput"> Container Ownership Type</label>
                                    <select class="selectpicker form-control" id="countryInput" data-live-search="true"
                                            name="container_ownership_id" data-size="10"
                                            title="{{trans('forms.select')}}">
                                        @foreach ($container_ownership as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('container_ownership_id',$container->container_ownership_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('container_ownership_id')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="tar_weightInput">Tare Weight</label>
                                    <input type="text" class="form-control" id="tar_weightInput" name="tar_weight"
                                           value="{{old('tar_weight',$container->tar_weight)}}"
                                           placeholder="Tare Weight" autocomplete="off">
                                    @error('tar_weight')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="max_payloadInput">Max Payload</label>
                                    <input type="text" class="form-control" id="max_payloadInput" name="max_payload"
                                           value="{{old('max_payload',$container->max_payload)}}"
                                           placeholder="Max Payload" autocomplete="off">
                                    @error('max_payload')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="production_yearInput">Production Year</label>
                                    <input type="text" class="form-control" id="production_yearInput"
                                           name="production_year"
                                           value="{{old('production_year',$container->production_year)}}"
                                           placeholder="Production Year" autocomplete="off">
                                    @error('production_year')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="countryInput">Container Ownership</label>
                                    <select class="selectpicker form-control" id="countryInput" data-live-search="true"
                                            name="description" data-size="10"
                                            title="{{trans('forms.select')}}">
                                        @foreach ($sellers as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('description',$container->description) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('description')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="isoInput">Iso</label>
                                    <input type="text" class="form-control" id="isoInput" name="iso"
                                           value="{{old('iso',$container->iso)}}"
                                           placeholder="Iso" autocomplete="off">
                                    @error('iso')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="soc-coc-Input"> SOC/COC </label>
                                    <select class="selectpicker form-control" id="soc-coc-select"
                                            data-live-search="true" name="SOC_COC" data-size="10"
                                            title="{{trans('forms.select')}}">
                                        <option value="SOC" {{ ($container->type != "SOC") ?: 'selected' }}>SOC</option>
                                        <option value="COC" {{ ($container->type != "COC") ?: 'selected' }}>COC</option>
                                    </select>
                                    @error('soc-coc')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <div class="custom-file-container" data-upload-id="certificat">
                                        <label> <span style="color:#3b3f5c" ;> Certificate </span><a
                                                    href="javascript:void(0)" class="custom-file-container__image-clear"
                                                    title="Clear Image"></a></label>
                                        <label class="custom-file-container__custom-file">
                                            <input type="file"
                                                   class="custom-file-container__custom-file__custom-file-input"
                                                   name="certificat"
                                                   value="{{old('certificat',$container->certificat)}}" accept="pdf">
                                            <input type="hidden" name="MAX_FILE_SIZE" disabled value="10485760"/>
                                            <span class="custom-file-container__custom-file__custom-file-control"></span>
                                        </label>
                                        <div class="custom-file-container__image-preview"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit"
                                            class="btn btn-primary mt-3">{{trans('forms.update')}}</button>
                                    <a href="{{route('containers.index')}}"
                                       class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
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
    @include('master.containers._validate')
@endpush
@push('styles')
    <style>
        .input-container {
            display: flex;
        }

        .input-container input {
            flex: 1;
            margin-right: 5px;
        }

        .mySpan::after {
            content: "✖";
            color: red;
            visibility: hidden;
        }

        .invalid + span::after {
            content: "✖";
            color: red;
            visibility: visible;
        }

        .valid + span::after {
            content: "✓";
            color: green;
            visibility: visible;

        }


    </style>
@endpush

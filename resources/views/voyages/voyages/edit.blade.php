@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('voyages.index')}}">Voyages</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> {{trans('forms.edit')}}</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('voyages.update',['voyage'=>$voyage])}}" method="POST">
                        @csrf
                        @method('put')
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="ports"> PORT </label>
                                <select class="selectpicker form-control" id="ports" data-live-search="true" name="port_from_name" data-size="10"
                                    title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                    <option value="{{$item->name}}" {{$item->name == old('port_from_name',$voyage->port_from_name) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('port_from_name')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="terminals"> Terminal </label>
                                <select class="selectpicker form-control" id="terminals" data-live-search="true" name="terminal_name" data-size="10"
                                    title="{{trans('forms.select')}}">
                                    @foreach ($terminals as $item)
                                    <option value="{{$item->name}}" {{$item->name == old('terminal_name',$voyage->terminal_name) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('terminal_name')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                    <label for="ROAD NO	">ROAD NO</label>
                                <input type="text" class="form-control" id="road_no" name="road_no" value="{{old('road_no',$voyage->road_no)}}"
                                    placeholder="ROAD NO" autocomplete="off">
                                    @error('road_no')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                    <label for="ETA">ETA</label>
                                <input type="date" class="form-control" id="ETA" name="eta" value="{{old('eta',$voyage->eta)}}"
                                    placeholder="Voyage No" autocomplete="off">
                                    @error('eta')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="ETA">ETD</label>
                                <input type="date" class="form-control" id="ETA" name="etd" value="{{old('etd',$voyage->etd)}}"
                                    placeholder="ETD" autocomplete="off">
                                    @error('etd')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                        </div>
                       <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.update')}}</button>
                                <a href="{{route('voyages.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                       </div>


                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

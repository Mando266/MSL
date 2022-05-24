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
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
            <div class="widget-content widget-content-area">
                <form action="{{route('voyages.index')}}">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="vessel_port_idInput">Port</label>
                            <select class="selectpicker form-control" id="vessel_port_idInput" data-live-search="true" name="Port" data-size="10"
                                title="{{trans('forms.select')}}">
                                @foreach ($ports as $item)
                                    <option value="{{$item->name}}" {{$item->name == old('Port',request()->input('Port')) ? 'selected':''}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                            <div class="form-group col-md-3">
                                <label for="vessel_port_idInput">Port From</label>
                                <select class="selectpicker form-control" id="vessel_port_idInput" data-live-search="true" name="From" data-size="10"
                                    title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        <option value="{{$item->name}}" {{$item->name == old('From',request()->input('From')) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="vessel_port_idInput">Port To</label>
                                <select class="selectpicker form-control" id="vessel_port_idInput" data-live-search="true" name="To" data-size="10"
                                    title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        <option value="{{$item->name}}" {{$item->name == old('To',request()->input('To')) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="vessel_port_idInput">Vessel Name</label>
                                <select class="selectpicker form-control" id="vessel_port_idInput" data-live-search="true" name="vessel_id" data-size="10"
                                    title="{{trans('forms.select')}}">
                                    @foreach ($vessels as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('vessel_id',request()->input('vessel_id')) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="voyage_noInput">Voyage No</label>
                                <input type="text" class="form-control" id="voyage_noInput" name="voyage_no" value="{{request()->input('voyage_no')}}"
                                placeholder="Voyage No" autocomplete="off">
                                </div>
                            <div class="col-md-12 text-center">
                                <button  type="submit" class="btn btn-success mt-3">Search</button>
                                <a href="{{route('voyagesearch.create')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

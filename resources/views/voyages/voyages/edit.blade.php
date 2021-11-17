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
                            <div class="form-group col-md-6">
                                <label for="vessel_idInput">Vessel Name *</label>
                                <select class="selectpicker form-control" id="vessel_idInput" data-live-search="true" name="vessel_id" data-size="10"
                                    title="{{trans('forms.select')}}">
                                    @foreach ($vessels as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('vessel_id',$voyage->vessel_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('vessel_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="voyage_noInput">Voyage No *</label>
                            <input type="text" class="form-control" id="voyage_noInput" name="voyage_no" value="{{old('voyage_no',$voyage->voyage_no)}}"
                                 placeholder="Voyage No" autocomplete="off" autofocus>
                                @error('voyage_no')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                    <label for="leg_idInput">Leg</label>
                                    <select class="selectpicker form-control" id="leg_idInput" data-live-search="true" name="leg_id" data-size="10"
                                        title="{{trans('forms.select')}}">
                                        @foreach ($legs as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('leg_id',$voyage->leg_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('leg_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="line_idInput">Line</label>
                                    <select class="selectpicker form-control" id="line_idInput" data-live-search="true" name="line_id" data-size="10"
                                        title="{{trans('forms.select')}}">
                                        @foreach ($lines as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('line_id',$voyage->line_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('line_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
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

@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            {{-- <li class="breadcrumb-item"><a href="javascript:void(0);">Containers </a></li> --}}
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Containers</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Tracking</a></li>

                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form  action="{{route('tracking.index')}}" >

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="ContainerInput">Container Number </label>
                            <select class="selectpicker form-control" id="ContainerInput" data-live-search="true" name="code" data-size="10"
                             title="{{trans('forms.select')}}">
                                @foreach ($containers as $item)
                                    <option value="{{$item->code}}" {{$item->code == old('code') ? 'selected':''}}>{{$item->code}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="ContainerInput">BL No</label>
                            <input type="text" class="form-control" id="BLNoInput" name="bl_no" value="{{request()->input('bl_no')}}"
                            placeholder="BL No" autocomplete="off">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="ContainerInput">Booking No</label>
                            <input type="text" class="form-control" id="BookingNoInput" name="booking_no" value="{{request()->input('booking_no')}}"
                            placeholder="Booking No" autocomplete="off">
                        </div>
                        <div class="col-md-12 text-center">
                            <button  type="submit" class="btn btn-success mt-3">Search</button>
                            {{-- <a href="{{route('movements.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a> --}}
                        </div>
                    </div>


                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

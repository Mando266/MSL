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
                            <li class="breadcrumb-item"><a href="{{route('movements.index')}}">Movements</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Add New Movement</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('movements.store')}}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="ContainerInput">Container Number *</label>
                                <select class="selectpicker form-control" id="ContainerInput" data-live-search="true" name="container_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($containers as $item)
                                        <option value="{{$item->id}}" data-code="{{$item->container_type_id}}" {{$item->id == old('container_id') ? 'selected':''}}>{{$item->code}}</option>
                                    @endforeach
                                </select>
                                @error('container_type_id')
                                <div class ="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <input type="hidden" id="containersTypesInput" class="form-control" name="container_type_id" placeholder="Container Type" autocomplete="off" value="{{request()->input('container_type_id')}}">
                            {{-- <div class="form-group col-md-4">
                                <label for="containersTypesInput">Container Type *</label>
                                <select class="selectpicker form-control" id="containersTypesInput" data-live-search="true" name="container_type_id" data-size="10"
                                 title="{{trans('forms.select')}}" autofocus>
                                    @foreach ($containersTypes as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('container_type_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('container_type_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div> --}}
                            <div class="form-group col-md-4">
                                <label for="containersMovementsInput">Movement *</label>
                                <select class="selectpicker form-control" id="containersMovementsInput" data-live-search="true" name="movement_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($containersMovements as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('movement_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('container_type_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="movement_dateInput">Movement Date *</label>
                                <input type="date" class="form-control" id="movement_dateInput" name="movement_date" value="{{old('movement_date')}}"
                                     autocomplete="off" >
                                @error('movement_date')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="portlocationInput">Activity Location *</label>
                                <select class="selectpicker form-control" id="portlocationInput" data-live-search="true" name="port_location_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        <option value="{{$item->name}}" {{$item->name == old('port_location_id') ? 'selected':''}}>{{$item->code}} - {{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('port_location_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="portofloadInput">Port Of Load</label>
                                <select class="selectpicker form-control" id="portofloadInput" data-live-search="true" name="pol_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        <option value="{{$item->code}}" {{$item->code == old('pol_id') ? 'selected':''}}>{{$item->code}}</option>
                                    @endforeach
                                </select>
                                @error('pol_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="portofloadInput">Port Of Discharge</label>
                                <select class="selectpicker form-control" id="portofloadInput" data-live-search="true" name="pod_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        <option value="{{$item->code}}" {{$item->code == old('pod_id') ? 'selected':''}}>{{$item->code}}</option>
                                    @endforeach
                                </select>
                                @error('pod_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="vessel_idInput">Vessel Name</label>
                                <select class="selectpicker form-control" id="vessel_idInput" data-live-search="true" name="vessel_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($vessels as $item)
                                        <option value="{{$item->name}}" {{$item->name == old('vessel_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('vessel_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="voyage_idInput">Voyage No</label>
                                <input type="text" class="form-control" id="voyage_idInput" name="voyage_id" value="{{old('voyage_id')}}"
                                    placeholder="Voyage No" autocomplete="off">
                                @error('voyage_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="booking_noInput">Booking No</label>
                                <input type="text" class="form-control" id="booking_noInput" name="booking_no" value="{{old('booking_no')}}"
                                    placeholder="Booking No" autocomplete="off">
                                @error('booking_no')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="billInput">Bill Of Lading</label>
                                <input type="text" class="form-control" id="billInput" name="bl_no" value="{{old('bl_no')}}"
                                    placeholder="Bill Of Loading" autocomplete="off">
                                @error('bl_no')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="RemarkesInput">Remarkes</label>
                                <input type="text" class="form-control" id="RemarkesInput" name="remarkes" value="{{old('remarkes')}}"
                                    placeholder="Remarkes" autocomplete="off">
                                @error('remarkes')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                       <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
                                <a href="{{route('movements.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
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
<script>
    $(function(){
        $('#ContainerInput').on('change',function(){
            var option = $(this).find(":selected");
            var code = option.data('code');
            $('#containersTypesInput').val(code);
        });
    });
    </script>

    @endpush

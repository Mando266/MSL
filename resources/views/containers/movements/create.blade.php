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
                            <div class="form-group col-md-12">
                                <label for="ContainerInput">Container Number *</label>
                                <select class="selectpicker form-control" id="ContainerInput" data-live-search="true" name="movement[][container_id]" data-size="10"
                                 title="{{trans('forms.select')}}"  multiple="multiple">
                                 @if(isset($containers))
                                    @foreach ($containers as $item)
                                        <option value="{{$item->id}}" data-code="{{$item->container_type_id}}" {{$item->id == old('container_id') ? 'selected':''}}>{{$item->code}}</option>
                                    @endforeach
                                    <input type="hidden" id="containersTypesInput" class="form-control" name="container_type_id" placeholder="Container Type" autocomplete="off" value="{{request()->input('container_type_id')}}">
                                @else
                                    <option value="{{$container->id}}" selected data-code="{{$container->container_type_id}}" {{$container->id == old('container_id') ? 'selected':''}}>{{$container->code}}</option>
                                    <input type="hidden" id="containersTypesInput" class="form-control" name="container_type_id" placeholder="Container Type" autocomplete="off" value="1">
                                @endif
                                </select>
                                @error('container_type_id')
                                <div class ="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
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
                        </div>

                        <div class="form-row">
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
                            <div class="form-group col-md-4">
                                <label for="vessel_id">Vessel Name</label>
                                <select class="selectpicker form-control" id="vessel_id" data-live-search="true"  data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($vessels as $item)
                                        <option value="{{$item->id}}" data-code="{{$item->name}}" {{$item->name == old('vessel_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('vessel_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <input type="hidden" class="form-control" id="vessel" name="vessel_id"> 
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                            <label for="">Voyage No</label>
                                <select class="form-control" id="voyage" data-live-search="true" name="voyage_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                 <option>Select</option>
                                    @foreach ($voyages as $item)
                                        <option value="{{$item->voyage_no}}" {{$item->voyage_no == old('voyage_id') ? 'selected':''}}>{{$item->voyage_no}}</option>
                                    @endforeach
                                </select>
                                @error('voyage_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <!-- <div class="form-group col-md-4">
                                <label for="voyage_idInput">Voyage No</label>
                                <input type="text" class="form-control" id="voyage_idInput" name="voyage_id" value="{{old('voyage_id')}}"
                                    placeholder="Voyage No" autocomplete="off">
                                @error('voyage_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div> -->
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
                            <div class="form-group col-md-4">
                                <label for="TransshipmentInput">Transshipment Port </label>
                                <select class="selectpicker form-control" id="TransshipmentInput" data-live-search="true" name="transshipment_port_id" data-size="10"
                                title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        <option value="{{$item->name}}" {{$item->name == old('transshipment_port_id') ? 'selected':''}}>{{$item->code}} - {{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('transshipment_port_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="BookingInput">Booking Agent </label>
                                <select class="selectpicker form-control" id="BookingInput" data-live-search="true" name="booking_agent_id" data-size="10"
                                title="{{trans('forms.select')}}">
                                    @foreach ($agents as $item)
                                        <option value="{{$item->name}}" {{$item->name == old('booking_agent_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('booking_agent_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="BookingInput">Import Agent </label>
                                <select class="selectpicker form-control" id="BookingInput" data-live-search="true" name="import_agent" data-size="10"
                                title="{{trans('forms.select')}}">
                                    @foreach ($agents as $item)
                                        <option value="{{$item->name}}" {{$item->name == old('import_agent') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('import_agent')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="BookingInput">container Status </label>
                                <select class="selectpicker form-control" id="BookingInput" data-live-search="true" name="container_status" data-size="10"
                                title="{{trans('forms.select')}}">
                                    @foreach ($containerstatus as $item)
                                        <option value="{{$item->name}}" {{$item->name == old('container_status') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('container_status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="RemarkesInput">Free Time</label>
                                <input type="text" class="form-control" id="RemarkesInput" name="free_time" value="{{old('free_time')}}"
                                    placeholder="Free Time" autocomplete="off">
                                @error('free_time')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="RemarkesInput">Free Time Origin</label>
                                <input type="text" class="form-control" id="RemarkesInput" name="free_time_origin" value="{{old('free_time_origin')}}"
                                    placeholder="Free Time" autocomplete="off">
                                @error('free_time_origin')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
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
                        </div>
                        <div class="form-row">
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
                                <button type="submit" id="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
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
<script>
    $(function(){
        $('#vessel_id').on('change',function(){
            var option = $(this).find(":selected");
            var code = option.data('code');
            $('#vessel').val(code);
        });
    });
</script>

<script>
         $(function(){
                    let vessel = $('#vessel_id');
                    $('#vessel_id').on('change',function(e){
                        let value = e.target.value;
                        let response =    $.get(`/api/vessel/voyages/${vessel.val()}`).then(function(data){
                            let voyages = data.voyages || '';
                            let list2 = [];
                            for(let i = 0 ; i < voyages.length; i++){
                                list2.push(`<option value='${voyages[i].voyage_no}'>${voyages[i].voyage_no} </option>`);
                            }
                    let voyageno = $('#voyage');
                    voyageno.html(list2.join(''));
                        });
                    });
                });
</script>

<script>

document.getElementById('submit').onclick = function() {
    var selected = [];
    for (var option of document.getElementById('ContainerInput').options)
    {
        if (option.selected) {
            selected.push(option.value);
        }
    }
    // alert(selected);
}
</script>

@endpush

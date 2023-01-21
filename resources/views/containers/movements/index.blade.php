@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Containers</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Movements</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                        </br>
                        @if(Session::has('stauts'))
                        <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('stauts') }}</p>
                        @endif
                        @if(Session::has('message'))
                        <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('message') }}</p>
                        @endif
                        @permission('Movements-List')
                        <div class="row">
                            <div class="col-md-12 text-right mb-6">
                            @if(!$items->isEmpty())
                                        <a class="btn btn-info" href="{{ route('export.search',['container_id'=>request()->input('container_id'),'port_location_id'=>request()->input('port_location_id'),'voyage_id'=>request()->input('voyage_id'),
                                        'movement_id'=>request()->input('movement_id'),'bl_no'=>request()->input('bl_no'),'booking_no'=>request()->input('booking_no')]) }}">Export</a>
                            @endif
                        @endpermission
                        @permission('Movements-List')
                                <a class="btn btn-warning" href="{{ route('export.all') }}">Export All Data</a>
                        @endpermission
                        @permission('Movements-Create')

                                <a href="{{route('movements.create')}}" class="btn btn-primary">Add New Movement</a>
                        @endpermission

                            </div>
                        </div>
                        @permission('Movements-Create')
                        <div class="row">
                            <div class="col-md-12 text-right mb-6">
                                @if($movementerrors->count() == 0)
                                    <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <input type="file" name="file" onchange="unlock();">
                                        <button  id="buttonSubmit" class="btn btn-success  mt-3" disabled>Import</button>
                                    </form>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-12 text-right mb-6">

                                <form action="{{ route('overwrite') }}" method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <input type="file" name="file" onchange="unlockupdate();">
                                    <button  id="updatebuttonSubmit" class="btn btn-danger  mt-3" disabled>Overwrite</button>
                                </form>
                                @else
                                    </br>
                                <a href="{{route('movementerrors.index')}}" class="btn btn-danger"> Show Errors</a>
                                @endif
                            </div>
                        </div>
                        @endpermission
                        </br>

                    <form>  
                        <div class="form-row">
                            <div class="form-group col-md-9">
                                <label for="ContainerInput">Container Number</label>
                                <select class="selectpicker form-control" id="ContainerInput" data-live-search="true" name="container_id[]" data-size="10"
                                 title="{{trans('forms.select')}}"  multiple="multiple">
                                 @if(isset($containers))
                                    @foreach ($containers as $item)
                                        <option value="{{$item->id}}" data-code="{{$item->container_type_id}}" {{$item->id == old('container_id') ||in_array($item->id, request()->container_id ?? []) ? 'selected':''}}>{{$item->code}}</option>
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
                    
                            <div class="form-group col-md-3">
                                <label for="portlocationInput">Activity Location</label>
                                <select class="selectpicker form-control" id="portlocationInput" data-live-search="true" name="port_location_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        <option value="{{$item->code}}" {{$item->code == old('port_location_id', request()->input('port_location_id')) ? 'selected':''}}>{{$item->code}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                            <label for="">Voyage No</label>
                                <select class="selectpicker form-control" id="voyage" data-live-search="true" name="voyage_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($voyages as $item)
                                        <option value="{{$item->voyage_no}}" {{$item->voyage_no == old('voyage_id',request()->input('voyage_id')) ? 'selected':''}}>{{$item->voyage_no}} - {{{optional($item->vessel)->name}}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- <div class="form-group col-md-3">
                            <label for="Movement">Movement Date</label>
                                <input type="date" class="form-control" id="movement_dateInput" name="movement_date" value="{{request()->input('movement_date')}}">
                            </div> -->
                            <div class="form-group col-md-3">
                                <label for="containersMovementsInput">Movement </label>
                                <select class="selectpicker form-control" id="containersMovementsInput" data-live-search="true" name="movement_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($containersMovements as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('movement_id',request()->input('movement_id')) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="BLNo">BL No</label>
                                <select class="selectpicker form-control" id="BLNoInput" data-live-search="true" name="bl_no" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($movementsBlNo as $item)
                                        @if($item != null)
                                        <option value="{{$item}}" {{$item == old('bl_no',request()->input('bl_no')) ? 'selected':''}}>{{$item}}</option>
                                        @endif
                                    @endforeach
                                </select> 
                            </div>
                            
                            <div class="form-group col-md-3">
                                <label for="BookingInput">Container Status </label>
                                <select class="selectpicker form-control" id="BookingInput" data-live-search="true" name="container_status" data-size="10"
                                title="{{trans('forms.select')}}">
                                    @foreach ($containerstatus as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('container_status',request()->input('container_status')) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> 

                        <div class="form-row">
                        <div class="form-group col-md-3">
                                <label for="BLNo">Booking No</label>
                                <input type="text" class="form-control" id="BookingNoInput" name="booking_no" value="{{request()->input('booking_no')}}"
                                placeholder="Booking No" autocomplete="off">
                            </div>
                            <div class="form-group col-md-3">
                                    <label for="remarkes">Remarkes</label>
                                    <input type="text" class="form-control" id="remarkes" name="remarkes" value="{{request()->input('remarkes')}}"
                                    placeholder="Remarkes" autocomplete="off">
                            </div>
                        </div>

                            <div class="col-md-12 text-center">
                                <button  type="submit" class="btn btn-success mt-3">Search</button>
                                <a href="{{route('movements.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                        </div>
                    </form>
                    
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Container No</th>
                                        <th>Container Type</th>
                                        <th>movement code</th>
                                        <th>Ownership</th>
                                        <th>movement date</th>
                                        <th>stock status</th>
                                        <th>bl no</th>
                                        <th>VSL/VOY</th>
                                        <th>ACTIVITY LOCATION</th>
                                        <th>Pol</th>
                                        <th>Pod</th>   
                                        <th>free time destination</th>
                                        <th>import agent</th>   
                                        <th>booking agent</th>   
                                        <th>remarkes</th>
                                        <th class='text-center' style='width:100px;'>Container Movements</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($items,$loop)}}</td>
                                            <td>{{{optional($item->container)->code}}}</td>
                                            <td>{{{optional($item->containersType)->name}}}</td>
                                            <td>{{{optional($item->movementcode)->code}}}</td>
                                            <td>{{{optional($item->container->containersOwner)->name}}}</td>
                                            <td>{{$item->movement_date}}</td>
                                            <td>{{optional($item->movementcode->containerstatus)->name}}</td>
                                            <td>{{$item->bl_no}}</td>
                                            <td>{{$item->vessel_id}} {{$item->voyage_id}}</td>
                                            <td>{{$item->port_location_id}}</td>
                                            <td>{{$item->pol_id}}</td>
                                            <td>{{$item->pod_id}}</td>
                                            <td>{{$item->free_time}}</td>
                                            <td>{{$item->import_agent}}</td>
                                            <td>{{$item->booking_agent_id}}</td>
                                            <td>{{$item->remarkes}}</td>
                                            
                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('Movements-Show')
                                                    <li>
                                                        
                                                        <a href="{{route('movements.show',['movement'=>$item->container_id,'bl_no' => $plNo,'port_location_id' => request()->input('port_location_id'),'booking_no' => request()->input('booking_no'),'movement_id' => request()->input('movement_id'),'voyage_id' => request()->input('voyage_id')])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="show" target="blank">
                                                            <i class="far fa-eye text-primary"></i>
                                                        </a>
                                                        
                                                    </li>
                                                    @endpermission
                                                </ul>
                                            </td>
                                        </tr>
                                        
                                    @empty
                                        <tr class="text-center">
                                            <td colspan="20">{{ trans('home.no_data_found')}}</td>
                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>
                        </div>
                        @if($items->count() > 0)
                        <div class="paginating-container">
                            {{ $items->appends(request()->query())->links()}}
                        </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
function unlock(){
    document.getElementById('buttonSubmit').removeAttribute("disabled");
}
</script>
<script>
function unlockupdate(){
    document.getElementById('updatebuttonSubmit').removeAttribute("disabled");
}
</script>
@endpush
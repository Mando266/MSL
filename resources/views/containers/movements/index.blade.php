@extends('layouts.app')
@section('content')
@if(Session::has('message'))
<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif
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
                        @permission('Movements-Create')
                        <div class="row">
                            <div class="col-md-12 text-right mb-6">
                            <a href="{{route('movements.create')}}" class="btn btn-primary">Add New Movement</a>
                            @if($movementerrors->count() == 0)
                            <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <input type="file" name="file" onchange="unlock();">
                                    <button  id="buttonSubmit" class="btn btn-success  mt-3" disabled>Import</button>
                                    <a class="btn btn-warning  mt-3" href="{{ route('export') }}">Export</a>
                                </form>
                                @else
                                <a href="{{route('movementerrors.index')}}" class="btn btn-danger"> Show Errors</a>
                                @endif
                            </div>
                        </div>
                        @endpermission
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="ContainerInput">Container Number </label>
                                <select class="selectpicker form-control" id="ContainerInput" data-live-search="true" name="container_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($containers as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('container_id', request()->input('container_id')) ? 'selected':''}}>{{$item->code}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="portlocationInput">Activity Location *</label>
                                <select class="selectpicker form-control" id="portlocationInput" data-live-search="true" name="port_location_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        <option value="{{$item->code}}" {{$item->code == old('port_location_id', request()->input('port_location_id')) ? 'selected':''}}>{{$item->code}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- <div class="form-group col-md-3">
                            <label for="Movement">Movement Date</label>
                                <input type="date" class="form-control" id="movement_dateInput" name="movement_date" value="{{request()->input('movement_date')}}">
                            </div> -->
                            <div class="form-group col-md-3">
                                <label for="BLNo">BL No</label>
                                <input type="text" class="form-control" id="BLNoInput" name="bl_no" value="{{request()->input('bl_no')}}"
                                placeholder="BL No" autocomplete="off">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="BLNo">Booking No</label>
                                <input type="text" class="form-control" id="BookingNoInput" name="booking_no" value="{{request()->input('booking_no')}}"
                                placeholder="Booking No" autocomplete="off">
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
                                        <th>VSL/VOY</th>
                                        <th>ACTIVITY LOCATION</th>
                                        <th>Pol</th>
                                        <th>Pod</th>   
                                        <th>free time destination</th>   
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
                                            <td>{{$item->vessel_id}} {{$item->voyage_id}}</td>
                                            <td>{{$item->port_location_id}}</td>
                                            <td>{{$item->pol_id}}</td>
                                            <td>{{$item->pod_id}}</td>
                                            <td>{{$item->free_time}}</td>
                                            <td>{{$item->booking_agent_id}}</td>
                                            <td>{{$item->remarkes}}</td>
                                            
                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('Movements-Show')
                                                    <li>
                                                        <a href="{{route('movements.show',['movement'=>$item->container_id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="show">
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
                        <div class="paginating-container">
                            {{ $items->appends(request()->query())->links()}}
                        </div>
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
@endpush
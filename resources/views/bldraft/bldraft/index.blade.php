@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="javascript:void(0);">Bl Draft</a></li>
                                <li class="breadcrumb-item  active"><a href="javascript:void(0);">BL Gates</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div> 
                    @permission('BlDraft-Create')
                        <div class="row">
                            <div class="col-md-12 text-right mb-5">
                            <a class="btn btn-info" href="{{ route('export.BLloadList') }}">BL Loadlist</a> 
                            <a href="{{route('bookingContainersRefresh')}}" class="btn btn-success">Refresh Booking Containers</a>
                            <a href="{{route('bldraft.selectbooking')}}" class="btn btn-primary">New Bl Draft</a>
                            </div>
                        </div>
                    @endpermission
                    </br>
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="Refrance">Bl Number </label>
                                <select class="selectpicker form-control" id="Refrance" data-live-search="true" name="ref_no" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($blDraftNo as $item)
                                        <option value="{{$item->ref_no}}" {{$item->ref_no == old('ref_no',request()->input('ref_no')) ? 'selected':''}}>{{$item->ref_no}}</option>
                                    @endforeach
                                </select>
                            </div>
 
                            <div class="form-group col-md-3">
                                    <label for="customer_id">Agreement Party</label>
                                    <select class="selectpicker form-control" id="customer_id" data-live-search="true" name="customer_id" data-size="10"
                                        title="{{trans('forms.select')}}">
                                        @foreach ($customers as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('customer_id',request()->input('customer_id')) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="POL">POL</label>
                                <select class="selectpicker form-control" id="POL" data-live-search="true" name="load_port_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('load_port_id',request()->input('load_port_id')) ? 'selected':''}}>{{$item->code}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="place_of_delivery_id">POD</label>
                                <select class="selectpicker form-control" id="discharge_port_id" data-live-search="true" name="discharge_port_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item) 
                                        <option value="{{$item->id}}" {{$item->id == old('discharge_port_id',request()->input('discharge_port_id')) ? 'selected':''}}>{{$item->code}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="voyage_id">Vessel / Voyage </label>
                            <select class="selectpicker form-control" id="voyage_id" data-live-search="true" name="voyage_id" data-size="10"
                                title="{{trans('forms.select')}}">
                                @foreach ($voyages as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('voyage_id') ? 'selected':''}}>{{$item->vessel->name}} / {{$item->voyage_no}}</option>
                                @endforeach
                            </select>
                            @error('voyage_id')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>

                        <div class="form-row">
                            <div class="col-md-12 text-center">
                                <button  type="submit" class="btn btn-success mt-3">Search</button>
                                <a href="{{route('bldraft.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                        </div>
                    </form>
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Booking no</th>
                                        <th>Bl Draft ref no</th>
                                        <th>Agreement Party</th>
                                        <th>forwarder</th>
                                        <th>consignee</th>
                                        <th>load port</th>
                                        <th>discharge port</th>
                                        <th>Equipment Type</th>
                                        <th>containers</th>
                                        <th>voyage vessel</th>
                                        <th>Bl Draft Creation</th>
                                        <th>BL Status</th>
                                        <th>BL Manafest</th>
                                        <th>BL Service Manafest</th>
                                        <th>UPDATE BOOKING</th>

                                        <th class='text-center' style='width:100px;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($items,$loop)}}</td>
                                            <td>{{optional($item->booking)->ref_no}}</td>
                                            <td>{{$item->ref_no}}</td>
                                            <td>{{optional($item->customer)->name}}</td>
                                            <td>{{optional($item->booking->forwarder)->name}}</td>
                                            <td>{{optional($item->booking->consignee)->name}}</td>
                                            <td>{{optional($item->loadPort)->code}}</td>
                                            <td>{{optional($item->dischargePort)->code}}</td>
                                            <td>{{optional($item->equipmentsType)->name}}</td> 
                                            <td> 
                                                @foreach($item->blDetails as $blDetail)
                                                <table style="border: hidden;">
                                                    <td>{{ optional($blDetail->container)->code }}</td>
                                                </table>
                                                @endforeach
                                            </td>
                                            <td>{{optional($item->voyage)->vessel->name}}  {{optional($item->voyage)->voyage_no}}</td>
                                            <td>{{{$item->created_at}}}</td>
                                            <td class="text-center">
                                                @if($item->bl_status == 1)
                                                    <span class="badge badge-info"> Confirm </span>
                                                @else
                                                    <span class="badge badge-danger"> Draft </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @permission('BlDraft-List')
                                                    <ul class="table-controls">
                                                        <li>
                                                            <a href="{{route('bldraft.manifest',['bldraft'=>$item->id])}}" target="_blank">
                                                                <i class="fas fa-file-pdf text-primary" style='font-size:large;'></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                @endpermission
                                            </td>
                                            <td class="text-center">
                                                @permission('BlDraft-List')
                                                    <ul class="table-controls">
                                                        <li>
                                                            <a href="{{route('bldraft.serviceManifest',['bldraft'=>$item->id])}}" target="_blank">
                                                                <i class="fas fa-file-pdf text-primary" style='font-size:large;'></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                @endpermission
                                            </td>
                                            <td class="text-center">
                                                @permission('Booking-Edit')
                                                    <a href="{{ route('bookingContainersRefresh',['id'=>$item->id]) }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                        <i class="far fa-edit text-success">Sync</i>
                                                    </a>
                                                @endpermission
                                            </td>
                                            <td class="text-center">
                                                 <ul class="table-controls">
                                                    @permission('Booking-Edit')
                                                    <li>
                                                        <a href="{{route('bldraft.edit',['bldraft'=>$item->id,'booking_id'=>$item->booking_id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                            <i class="far fa-edit text-success"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission
                                                    @permission('Booking-Show')
                                                    <li>
                                                        <a href="{{route('bldraft.show',['bldraft'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="show">
                                                            <i class="far fa-eye text-primary"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission 
                                                    @permission('BlDraft-Delete')
                                                    @if($item->has_bl == 0)
                                                    <li>
                                                        <form action="{{route('bldraft.destroy',['bldraft'=>$item->id])}}" method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                        <button style="border: none; background: none;" type="submit" class="fa fa-trash text-danger show_confirm"></button>
                                                        </form> 
                                                    </li>
                                                    @endif
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script type="text/javascript">
 
     $('.show_confirm').click(function(event) {
          var form =  $(this).closest("form");
          var name = $(this).data("name");
          event.preventDefault();
          swal({
              title: `Are you sure you want to delete this BL?`,
              icon: "warning",
              buttons: true,
              dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              form.submit();
            }
          });
      });
  
</script>
@endpush

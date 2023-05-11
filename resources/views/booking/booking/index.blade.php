@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="javascript:void(0);">Booking</a></li>
                                <li class="breadcrumb-item  active"><a href="javascript:void(0);">Booking Gates</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div>
                    @permission('Booking-Create')
                        <div class="row">
                            <div class="col-md-12 text-right mb-5">
                            <a href="{{route('booking.selectQuotation')}}" class="btn btn-primary">New Booking</a>
                            <a class="btn btn-warning" href="{{ route('export.booking') }}">Export</a>
                            <a class="btn btn-info" href="{{ route('export.loadList') }}">Loadlist</a> 
                            <!-- <a class="btn btn-info" href="{{ route('booking.referManifest') }}">Reefer Manifest</a>  -->
                            </div>
                        </div>
                    @endpermission
</br>
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="Refrance">BOOKING REF NO</label>
                                <select class="selectpicker form-control" id="Refrance" data-live-search="true" name="ref_no" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($bookingNo as $item)
                                        <option value="{{$item->ref_no}}" {{$item->ref_no == old('ref_no',request()->input('ref_no')) ? 'selected':''}}>{{$item->ref_no}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                    <label for="customer_id">Customer</label>
                                    <select class="selectpicker form-control" id="customer_id" data-live-search="true" name="customer_id" data-size="10"
                                        title="{{trans('forms.select')}}">
                                        @foreach ($customers as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('customer_id',request()->input('customer_id')) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                            </div>
                            <div class="form-group col-md-3">
                                    <label for="ffw_id">Fright Forwarder</label>
                                    <select class="selectpicker form-control" id="ffw_id" data-live-search="true" name="ffw_id" data-size="10"
                                        title="{{trans('forms.select')}}">
                                        @foreach ($ffw as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('ffw_id',request()->input('ffw_id')) ? 'selected':''}}>{{$item->name}}</option>
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
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="place_of_delivery_id">POD</label>
                                <select class="selectpicker form-control" id="discharge_port_id" data-live-search="true" name="discharge_port_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item) 
                                        <option value="{{$item->id}}" {{$item->id == old('discharge_port_id',request()->input('discharge_port_id')) ? 'selected':''}}>{{$item->code}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="voyage_id">Vessel / Voyage </label>
                                <select class="selectpicker form-control" id="voyage_id" data-live-search="true" name="voyage_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($voyages as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('voyage_id',request()->input('voyage_id')) ? 'selected':''}}>{{$item->vessel->name}} / {{$item->voyage_no}}</option>
                                    @endforeach
                                </select>
                                @error('voyage_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="Principal">Principal Name  </span></label>
                                <select class="selectpicker form-control" id="Principal" data-live-search="true" name="principal_name" data-size="10"
                                title="{{trans('forms.select')}}">
                                    @foreach ($line as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('principal_name',request()->input('principal_name')) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('principal_name')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="status">Booking Status </span></label>
                                <select class="selectpicker form-control" data-live-search="true" name="booking_confirm" title="{{trans('forms.select')}}">
                                    <option value="1" {{ request()->input('booking_confirm') == "1" ? 'selected':'' }}>Confirm</option>
                                    <option value="3" {{ request()->input('booking_confirm') == "3" ? 'selected':'' }}>Draft</option>
                                    <option value="2" {{ request()->input('booking_confirm') == "2" ? 'selected':'' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="Container">Container No</label>
                                <select class="selectpicker form-control" id="Container" data-live-search="true" name="container_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($containers as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('container_id') ? 'selected':''}}>{{$item->code}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12 text-center">
                                <button  type="submit" class="btn btn-success mt-3">Search</button>
                                <a href="{{route('booking.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                        </div>
                    </form>
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <!-- <th>Quotation no</th> -->
                                        <th>Booking ref no</th>
                                        <th>Shipper</th>
                                        <th>Forwarder</th>
                                        <th>vessel</th>
                                        <th>voyage</th>
                                        <th>Main Line</th>
                                        <th>Vessel Operator</th>
                                        <th>load port</th>
                                        <th>discharge port</th>
                                        <th>Equipment Type</th>
                                        <th>qty</th>
                                        <th>Containers Status</th>
                                        <th>Booking Creation</th>
                                        <th>Booking Status</th>
                                        <th class='text-center' style='width:100px;'>add bl</th>
                                        <th>Shipping Order</th>
                                        <th>Gate In</th>
                                        <th>Gate Out</th>
                                        <th class='text-center' style='width:100px;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                    <?php 
                                    $qty = 0;
                                    $assigned = 0;
                                    $unassigned = 0;
                                    ?>
                                        @foreach($item->bookingContainerDetails as $bookingContainerDetail)
                                            <?php 
                                            $qty += $bookingContainerDetail->qty;
                                            if($bookingContainerDetail->qty == 1 && $bookingContainerDetail->container_id == "000"){
                                                $unassigned += 1;
                                            }elseif($bookingContainerDetail->qty == 1 && $bookingContainerDetail->container_id == null){
                                                $unassigned += 1;
                                            }elseif($bookingContainerDetail->qty == 1){
                                                $assigned += 1;
                                            }else{
                                                $unassigned += $bookingContainerDetail->qty;
                                            }
                                            ?>
                                        @endforeach
                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($items,$loop)}}</td>
                                            <!-- <td>{{optional($item->quotation)->ref_no}}</td> -->
                                            <td>{{$item->ref_no}}</td>
                                            <td>{{optional($item->customer)->name}}</td>
                                            <td>{{optional($item->forwarder)->name}}</td>
                                            <td>{{optional($item->voyage)->vessel->name}}</td>
                                            <td>{{optional($item->voyage)->voyage_no}}</td>
                                            <td>{{optional($item->principal)->name}}</td>
                                            <td>{{optional($item->operator)->name}}</td>
                                            <td>{{optional($item->loadPort)->code}}</td>
                                            <td>{{optional($item->dischargePort)->code}}</td>
                                            <td>
                                                <table style="border: hidden;">
                                                @if($item->bookingContainerDetails->count() > 0)
                                                    <td>{{ optional($item->bookingContainerDetails[0]->containerType)->name }}</td>
                                                    @endif
                                                </table>
                                            </td>
                                            <td>{{ $qty }}</td>
                                            <td>
                                                {{ $assigned }} Assigned<br>
                                                {{ $unassigned }} Unassigned 
                                            </td>
                                            <td>{{{$item->created_at}}}</td>
                                            <td class="text-center">
                                                @if($item->booking_confirm == 1)
                                                    <span class="badge badge-info"> Confirm </span>
                                                @elseif($item->booking_confirm == 3)
                                                    <span class="badge badge-warning"> Draft </span>
                                                @elseif($item->booking_confirm == 2)
                                                <span class="badge badge-danger"> Cancelled </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                 <ul class="table-controls">
                                                 @if($item->booking_confirm == "1")
                                                    @permission('Booking-Create')
                                                        @if($item->has_bl == 0)
                                                            <li>
                                                                <a href="{{route('bldraft.create',['booking_id'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="show">
                                                                    <i class="fas fa-plus text-primary"></i>
                                                                </a>
                                                            </li>
                                                            @else
                                                                @if(optional($item->bldraft)->bl_status == 1)
                                                                    <span class="badge badge-info"> Confirmed </span>
                                                                @else
                                                                    <span class="badge badge-warning"> Draft </span>
                                                                @endif
                                                        @endif
                                                    @endpermission
                                                
                                                @endif
                                                </ul>
                                            </td>
                                            <td class="text-center">
                                                @permission('Booking-Show')
                                                    <ul class="table-controls">
                                                    @if($item->booking_confirm == 1)
                                                        <li>
                                                            <a href="{{route('booking.showShippingOrder',['booking'=>$item->id])}}" target="_blank">
                                                                <i class="fas fa-file-pdf text-primary" style='font-size:large;'></i>
                                                            </a>
                                                        </li>
                                                    @endif
                                                    </ul>
                                                @endpermission
                                            </td>
                                            <td class="text-center">
                                                @permission('Booking-Show')
                                                    <ul class="table-controls">
                                                    @if($item->booking_confirm == 1)
                                                        <li>
                                                            <a href="{{route('booking.showGateIn',['booking'=>$item->id])}}" target="_blank">
                                                                <i class="fas fa-file-pdf text-primary" style='font-size:large;'></i>
                                                            </a>
                                                        </li>
                                                    @endif
                                                    </ul>
                                                @endpermission
                                            </td>
                                            <td class="text-center">
                                                @permission('Booking-Show')
                                                    <ul class="table-controls">
                                                    @if($item->booking_confirm == 1)
                                                        <li>
                                                            <a href="{{route('booking.selectGateOut',['booking'=>$item->id])}}" target="_blank">
                                                                <i class="fas fa-file-pdf text-primary" style='font-size:large;'></i>
                                                            </a>
                                                        </li>
                                                    @endif
                                                    </ul>
                                                @endpermission
                                            </td>
                                        
                                            <td class="text-center">
                                                 <ul class="table-controls">
                                                 @if($item->certificat == !null)
                                                    <li>
                                                        <a href='{{asset($item->certificat)}}' target="_blank">
                                                            <i class="fas fa-file-pdf text-primary" style='font-size:large;'></i>
                                                        </a>
                                                    </li>
                                                    @endif
                                                    @permission('Booking-Edit')
                                                    <li>
                                                        <a href="{{route('booking.edit',['booking'=>$item->id,'quotation_id'=>$item->quotation_id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                            <i class="far fa-edit text-success"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission
                                                    @permission('Booking-Show')
                                                    <li>
                                                        <a href="{{route('booking.show',['booking'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="show">
                                                            <i class="far fa-eye text-primary"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission 

                                                    {{-- @if($item->has_bl == 0)
                                                    @permission('Booking-Delete')
                                                    <li>
                                                        <form action="{{route('booking.destroy',['booking'=>$item->id])}}" method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                        <button style="border: none; background: none;" type="submit" class="fa fa-trash text-danger show_confirm"></button>
                                                        </form> 
                                                    </li>
                                                    @endpermission
                                                    @endif --}}
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
              title: `Are you sure you want to delete this Booking?`,
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

@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="javascript:void(0);">Quotations</a></li>
                                <li class="breadcrumb-item  active"><a href="javascript:void(0);">Quotations List</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div>
                    </br>
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="Refrance">Refrance Number </label>
                                <select class="selectpicker form-control" id="Refrance" data-live-search="true" name="ref_no" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($quotation as $item)
                                        <option value="{{$item->ref_no}}" {{$item->ref_no == old('ref_no') ? 'selected':''}}>{{$item->ref_no}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="status">Status </label>
                                <select class="selectpicker form-control" id="status" data-live-search="true" name="status" data-size="10"
                                 title="{{trans('forms.select')}}">
                                        <option value="pending">Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-12 text-center">
                                <button  type="submit" class="btn btn-success mt-3">Search</button>
                                <a href="{{route('quotations.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                        </div>
                    </form>
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ref no</th>
                                        <th>Agent</th>
                                        <th>Customer</th>
                                        <th>validity from</th>
                                        <th>validity_to</th>
                                        <th>place of acceptence</th>
                                        <th>place of delivery</th>
                                        <th>load port id</th>
                                        <th>discharge port</th>
                                        <th>Status </th>
                                        <th class='text-center' style='width:100px;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($items,$loop)}}</td>
                                            <td>{{$item->ref_no}}</td>
                                            <td>{{optional($item->agent)->name}}</td>
                                            <td>{{optional($item->customer)->name}}</td>
                                            <td>{{$item->validity_from}}</td>
                                            <td>{{$item->validity_to}}</td>
                                            <td>{{optional($item->placeOfAcceptence)->name}}</td>
                                            <td>{{optional($item->placeOfDelivery)->name}}</td>
                                            <td>{{optional($item->loadPort)->name}}</td>
                                            <td>{{{optional($item->dischargePort)->name}}}</td>
                                            <td class="text-center">
                                                @if($item->status == "pending")
                                                    <span class="badge badge-info"> Pending </span>
                                                @elseif($item->status == "approved")
                                                    <span class="badge badge-success"> Approved </span>
                                                @elseif($item->status == "rejected")
                                                    <span class="badge badge-danger"> Rejected </span>
                                                @endif
                                            </td>
                                            <!-- <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('Quotation-Show')
                                                    <li>
                                                        <a href="{{route('quotations.show',['quotation'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="show">
                                                            <i class="far fa-eye text-primary"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission
                                                </ul>
                                            </td> -->
                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('Quotation-Show')
                                                    <li>
                                                        <a href="{{route('quotations.show',['quotation'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="show">
                                                            <i class="far fa-eye text-primary"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission
                                                    @permission('Quotation-Delete')
                                                    <li>
                                                        <form action="{{route('quotations.destroy',['quotation'=>$item->id])}}" method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                        <button style="border: none; background: none;" type="submit" class="fa fa-trash text-danger show_confirm"></button>
                                                        </form> 
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
                            {{ $items->links() }}
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
              title: `Are you sure you want to delete this record?`,
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

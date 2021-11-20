@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('movements.index')}}">Movements</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Movement Details </a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <h5><span style='color:#1b55e2';>Container No / Type:</span> {{$containers->code}} / {{{optional($containers->containersTypes)->name}}}</h5>

                <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>Movement</th>
                                        <th>Movement Date</th>
                                        <th>ACTIVITY LOCATION</th>
                                        <th>Pol</th>
                                        <th>Pod</th>
                                        <th>VSL/VOY</th>
                                        <th>BOOKING</th>
                                        <th>BL No</th>
                                        <th>REMARKS</th>
                                        <th class='text-center' style='width:100px;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                        <tr>
                                            <td>{{{optional($item->movementcode)->name}}}</td>
                                            <td>{{$item->movement_date}}</td>
                                            <td>{{{optional($item->location)->code}}}</td>
                                            <td>{{{optional($item->pol)->code}}}</td>
                                            <td>{{{optional($item->pod)->code}}}</td>
                                            <td>{{{optional($item->vessel)->code}}} {{{optional($item->voyage)->voyage_no}}}</td>
                                            <td>{{$item->booking_no}}</td>
                                            <td>{{$item->bl_no}}</td>
                                            <td>{{$item->remarkes}}</td>
                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('Movements-Edit')
                                                    <li>
                                                            <a href="{{route('movements.edit',['movement'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                                <i class="far fa-edit text-success"></i>
                                                            </a>
                                                    </li>
                                                    @endpermission
                                                    @permission('Movements-Delete')
                                                    <li>
                                                        <form action="{{route('movements.destroy',['movement'=>$item->id])}}" method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                        <button style="border: none; background: none;" type="submit" class="fa fa-trash text-danger"></button>
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
                    </div>
                       <div class="row">
                            <div class="col-md-12 text-center">
                                <a href="{{route('movements.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                       </div>


                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="{{asset('plugins/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" >
@endpush
@push('scripts')
<script src="{{asset('plugins/bootstrap-select/bootstrap-select.min.js')}}"></script>
@endpush

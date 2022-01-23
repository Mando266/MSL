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
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Tracking</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </br>
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label style="color: #1b55e2"> BL NO</label><input class="form-control" value="{{ request()->input('bl_no')}}">
                        </div>
                    </div>
                        <div class="widget-content widget-content-area">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-condensed mb-4">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>container</th>
                                            <th>Movements</th>
                                            <th>Movement Date</th>
                                            <th>VSL/VOY</th>
                                             <th>ACTIVITY LOCATION</th>
                                            <th>Pol</th>
                                            <th>Pod</th>
                                            <th>BL No</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $group=>$actions)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>

                                            <td class="text-left">{{ucwords($group)}}</td>
                                            <td>
                                                @foreach ($actions as $action)
                                                <div class="n-chk">
                                                    <label>
                                                        <input class="new-control-input"
                                                         data-name="{{ $action->container_id }}"
                                                        {{in_array($action->id,old('items',[])) ? : ''}} value="{{ $action->id }}">
                                                        <span class="new-control-indicator"></span>{{{optional($action->movementcode)->code}}}
                                                    </label>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($actions as $action)
                                                <div class="n-chk">
                                                    <label>
                                                        <input class="new-control-input"
                                                         data-name="{{ $action->container_id }}"
                                                        {{in_array($action->id,old('items',[])) ? : ''}} value="{{ $action->id }}">
                                                        <span class="new-control-indicator"></span>{{$action->movement_date}}
                                                    </label>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($actions as $action)
                                                <div class="n-chk">
                                                    <label>
                                                        <input class="new-control-input"
                                                         data-name="{{ $action->container_id }}"
                                                        {{in_array($action->id,old('items',[])) ? : ''}} value="{{ $action->id }}">
                                                        <span class="new-control-indicator"></span>{{$action->vessel_id}} / {{$action->voyage_id}}
                                                    </label>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($actions as $action)
                                                <div class="n-chk">
                                                    <label>
                                                        <input class="new-control-input"
                                                         data-name="{{ $action->container_id }}"
                                                        {{in_array($action->id,old('items',[])) ? : ''}} value="{{ $action->id }}">
                                                        <span class="new-control-indicator"></span>{{$action->port_location_id}}
                                                    </label>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($actions as $action)
                                                <div class="n-chk">
                                                    <label>
                                                        <input class="new-control-input"
                                                         data-name="{{ $action->container_id }}"
                                                        {{in_array($action->id,old('items',[])) ? : ''}} value="{{ $action->id }}">
                                                        <span class="new-control-indicator"></span>{{$action->pol_id}}
                                                    </label>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($actions as $action)
                                                <div class="n-chk">
                                                    <label>
                                                        <input class="new-control-input"
                                                         data-name="{{ $action->container_id }}"
                                                        {{in_array($action->id,old('items',[])) ? : ''}} value="{{ $action->id }}">
                                                        <span class="new-control-indicator"></span>{{$action->pod_id}}
                                                    </label>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($actions as $action)
                                                <div class="n-chk">
                                                    <label>
                                                        <input class="new-control-input"
                                                         data-name="{{ $action->container_id }}"
                                                        {{in_array($action->id,old('items',[])) ? : ''}} value="{{ $action->id }}">
                                                        <span class="new-control-indicator"></span>{{$action->bl_no}}
                                                    </label>
                                                @endforeach
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
<link href="{{asset('plugins/sweetalerts/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/sweetalerts/sweetalert.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/components/custom-sweetalert.css')}}" rel="stylesheet" type="text/css" />
@endpush
@push('scripts')
    <script src="{{asset('plugins/sweetalerts/sweetalert2.min.js')}}"></script>
    <script src="{{asset('plugins/sweetalerts/custom-sweetalert.js')}}"></script>
    <script src="{{ asset('app/admin/role.js') }}"></script>
@endpush

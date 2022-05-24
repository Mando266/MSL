@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Voyages</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                        @permission('Voyages-Create')
                        <div class="row">
                            <div class="col-md-12 text-right mb-5">
                            <a href="{{route('voyages.create')}}" class="btn btn-primary">Add New Voyage</a>
                            </div>
                        </div>
                        @endpermission
                    </div>
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Vessel Code</th>
                                        <th>Vessel Name</th>
                                        <th>Voyage No</th>
                                        <th>Leg</th>
                                        {{-- <th>port</th> --}}

                                        <th class='text-center' style='width:100px;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{{optional($item->vessel)->code}}}</td>
                                            <td>{{{optional($item->vessel)->name}}}</td>
                                            <td>{{$item->voyage_no}}</td>
                                            <td>{{{optional($item->leg)->name}}}</td>
                                            {{-- <td>{{$item->port_from_name}}</td> --}}

                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    <!-- @permission('Voyages-Edit')
                                                    <li>
                                                        <a href="{{route('voyages.edit',['voyage'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                            <i class="far fa-edit text-success"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission -->
                                                    @permission('Voyages-Show')
                                                    <li>
                                                        <a href="{{route('voyages.show',['voyage'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="show">
                                                            <i class="far fa-eye text-primary"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission
                                                    @permission('Voyages-Delete')
                                                    <li>
                                                        <form action="{{route('voyages.destroy',['voyage'=>$item->id])}}" method="post">
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
                        <div class="paginating-container">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

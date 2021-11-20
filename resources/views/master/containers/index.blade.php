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
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Containers</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                        @permission('Containers-Create')
                        <div class="row">
                            <div class="col-md-12 text-right mb-5">
                            <a href="{{route('containers.create')}}" class="btn btn-primary">Add New Container</a>
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
                                        <th>Number</th>
                                        <th>Type</th>
                                        <!-- <th>Code</th> -->
                                        <th>Ownership</th>
                                        <th>tar weight</th>
                                        <th>max payload</th>
                                        <th>production year</th>
                                        <th>REMARKS</th>
                                        <th class='text-center' style='width:100px;'></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($items,$loop)}}</td>
                                            <td>{{$item->code}}</td>
                                            <td>{{{optional($item->containersTypes)->name}}}</td>
                                            <!-- <td>{{{optional($item->containersTypes)->code}}}</td> -->
                                            <td>{{{optional($item->containersOwner)->name}}}</td>
                                            <td>{{$item->tar_weight}}</td>
                                            <td>{{$item->max_payload}}</td>
                                            <td>{{$item->production_year}}</td>
                                            <td>{{$item->description}}</td>

                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('Containers-Edit')
                                                    <li>
                                                        <a href="{{route('containers.edit',['container'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                            <i class="far fa-edit text-success"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission
                                                    @permission('Containers-Delete')
                                                    <li>
                                                        <form action="{{route('containers.destroy',['container'=>$item->id])}}" method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                        <button style="border: none; background: none;" type="submit" class="fa fa-trash text-danger"></button>
                                                        </form>
                                                    </li>
                                                    @endpermission
                                                    @if($item->certificat == !null)
                                                    <li>
                                                        <a href='{{asset($item->certificat)}}' target="_blank">
                                                            <i class="fas fa-file-pdf text-primary" style='font-size:large;'></i>
                                                        </a>
                                                    </li>
                                                    @endif
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

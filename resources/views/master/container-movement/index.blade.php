@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Containers Movement</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Movement Code</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                        @permission('ContainersMovement-Create')
                        <div class="row">
                            <div class="col-md-12 text-right mb-5">
                            <a href="{{route('container-movement.create')}}" class="btn btn-primary">Add New Movement Code</a>
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
                                        <th>Name</th>
                                        <th>code</th>
                                        <th>container status</th>
                                        <th>stock type</th>
                                        <th>ALLOWED NEXT MOVES</th>
                                        
                                        <th class='text-center' style='width:100px;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($items,$loop)}}</td>
                                            <td>{{$item->name}}</td>
                                            <td>{{$item->code}}</td>
                                            <td>{{{optional($item->containerstatus)->name}}}</td>
                                            <td>{{{optional($item->containerstock)->name}}}</td>
                                            <td>{{$item->next_move}}</td>
                                    
                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('ContainersMovement-Edit')
                                                    <li>
                                                        <a href="{{route('container-movement.edit',['container_movement'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                            <i class="far fa-edit text-success"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission
                                                    @permission('ContainersMovement-Delete')
                                                    <li>
                                                        <form action="{{route('container-movement.destroy',['container_movement'=>$item->id])}}" method="post">
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
                            {{ $items->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
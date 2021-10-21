@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Customers</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                        @permission('Customers-Create')
                        <div class="row">
                            <div class="col-md-12 text-right mb-5">
                            <a href="{{route('customers.create')}}" class="btn btn-primary">Add New Customer</a>
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
                                        <th>phone</th>
                                        <th>Country</th>
                                        <th>city</th>
                                        <th>tax card</th>
                                        <th>sales person</th>
                                        <th>Role</th>

                                        <th class='text-center' style='width:100px;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $item)
                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($items,$loop)}}</td>
                                            <td>{{$item->name}}</td>
                                            <td>{{$item->phone}}</td>
                                            <td>{{optional($item->country)->name}}</td>
                                            <td>{{$item->city}}</td>
                                            <td>{{$item->tax_card_no}}</td>
                                            <td>{{optional($item->User)->name}}</td>
                                            <td>{{{optional($item->CustomerRoles)->name}}}</td>

                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('Customers-Edit')
                                                    <li>
                                                        <a href="{{route('customers.edit',['customer'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                            <i class="far fa-edit text-success"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission
                                                    @permission('Customers-Delete')
                                                    <li>
                                                        <form action="{{route('customers.destroy',['customer'=>$item->id])}}" method="post">
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

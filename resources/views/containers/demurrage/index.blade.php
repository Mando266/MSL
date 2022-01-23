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
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Demurrage & Dentention</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                        @permission('Demurrage-Create')
                        <div class="row">
                            <div class="col-md-12 text-right mb-5">
                            <a href="{{route('demurrage.create')}}" class="btn btn-primary">Add New Demurrage & Dentention</a>
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
                                            <th>Tariff Ref No</th>
                                            <th>validity from</th>
                                            <th>validity to</th>
                                            <th>Container Type/Size</th>
                                            <th>period Details</th>
                                            {{-- <th>period</th>
                                            <th>calendar days</th>
                                            <th>rate per day</th> --}}
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($items as $item)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{{optional($item->country)->name}}} {{{optional($item->ports)->code}}} {{{optional($item->bound)->name}}}</td>
                                            <td>{{$item->validity_from}}</td>
                                            <td>{{$item->validity_to}}</td>
                                            <td>{{{optional($item->containersType)->name}}}</td>
                                            {{-- <td>{{$item->period}}</td>
                                            <td>{{$item->number_off_dayes}}</td>
                                            <td>{{$item->rate}}</td> --}}

                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('Demurrage-Show')
                                                    <li>
                                                        <a href="{{route('demurrage.show',['demurrage'=>$item->id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="show">
                                                            <i class="far fa-eye text-primary"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission
                                                </ul>
                                            </td>
                                            <td class="text-center">
                                                <ul class="table-controls">
                                                    @permission('Demurrage-Delete')
                                                    <li>
                                                        <form action="{{route('demurrage.destroy',['demurrage'=>$item->id])}}" method="post">
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
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- const date1 = new Date('7/13/2010');
const date2 = new Date('12/15/2010');
const diffTime = Math.abs(date2 - date1);
console.log(diffDays + "days"); --}}

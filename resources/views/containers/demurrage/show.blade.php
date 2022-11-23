@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('demurrage.index')}}">Demurrages</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Demurrage Details </a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                {{-- <form id="createForm" action="{{route('voyages.edit',['voyage'=>$voyage])}}" method="get"> --}}

                    <h5><span style='color:#1b55e2';>Tariff Ref No:</span> {{$demurrage->is_storge}} {{{optional($demurrage->bound)->name}}} {{{optional($demurrage->ports)->code}}} {{{optional($demurrage->containersType)->name}}}</h5> 
                <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                            <th>period</th>
                                            <th>calendar days</th>
                                            <th>rate per day</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($period as $period)
                                        <tr>
                                            <td>{{$period->period}}</td>
                                            <td>{{$period->number_off_dayes}}</td>
                                            <td>{{$period->rate}}</td>
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
                                <a href="{{route('demurrage.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                       </div>


                    {{-- </form> --}}
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

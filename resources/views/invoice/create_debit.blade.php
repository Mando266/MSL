@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="{{route('invoice.index')}}">Invoice</a></li> 
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Create New Debit Invoice</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    <form id="createForm" action="{{route('invoice.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                        <div class="form-row">
                            <input type="hidden" name="bldraft_id" value="{{request()->input('bldraft_id')}}">
                            
                                <div class="form-group col-md-6">
                                <label for="customer">Customer<span class="text-warning"> * (Required.) </span></label>
                                <select class="selectpicker form-control" id="customer" data-live-search="true" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                        @if($bldraft != null)
                                        <option value="{{optional($bldraft->booking)->ffw_id}}">{{ optional($bldraft->booking->forwarder)->name }} Forwarder</option>
                                        @endif
                                </select>
                                @error('customer_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div> 
                            <div class="form-group col-md-6">
                                <label for="customer_id">Customer Name</label>
                                    <input type="text" id="notifiy" class="form-control"  name="customer"
                                    placeholder="Customer Name" autocomplete="off" required>
                            </div> 
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3" >
                                <label for="Date">Booking Ref</label>
                                    @if(optional($bldraft)->booking_id != null)
                                    <input type="text" class="form-control" placeholder="Booking Ref" autocomplete="off" value="{{(optional($bldraft->booking)->ref_no)}}" style="background-color:#fff" disabled>
                                    @else
                                    <input type="text" class="form-control" placeholder="Booking Ref"  style="background-color:#fff" disabled>
                                    @endif
                            </div> 
                            <div class="form-group col-md-3">
                                <label for="voyage_id">Vessel / Voyage <span style="color: red;">*</span></label>
                                <select class="selectpicker form-control" id="voyage_id" data-live-search="true" data-size="10"
                                    title="{{trans('forms.select')}}" disabled>
                                    @foreach ($voyages as $item)
                                    @if(optional($bldraft)->voyage_id != null)
                                            <option value="{{$item->id}}" {{$item->id == old('voyage_id',$bldraft->voyage_id) ? 'selected':''}}>{{$item->vessel->name}} / {{$item->voyage_no}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                                <div class="form-group col-md-3" >
                                    <label>Place Of Acceptence</label>
                                        @if(optional($bldraft)->place_of_acceptence_id != null)
                                        <input type="text" class="form-control" placeholder="Booking Ref" autocomplete="off" value="{{(optional($bldraft->placeOfAcceptence)->code)}}" style="background-color:#fff" disabled>
                                        @else
                                        <input type="text" class="form-control" placeholder="Booking Ref"  style="background-color:#fff" disabled>
                                        @endif
                                </div> 

                                <div class="form-group col-md-3" >
                                    <label>Load Port</label>
                                        @if(optional($bldraft)->load_port_id != null)
                                        <input type="text" class="form-control" placeholder="Booking Ref" autocomplete="off" value="{{(optional($bldraft->placeOfAcceptence)->code)}}" style="background-color:#fff" disabled>
                                        @else
                                        <input type="text" class="form-control" placeholder="Booking Ref"  style="background-color:#fff" disabled>
                                        @endif
                                </div> 
                            </div> 
                            <div class="form-row">
                                <div class="form-group col-md-4" >
                                    <label for="Date">Date</label>
                                        <input type="date" class="form-control" name="date" placeholder="Date" autocomplete="off" required value="{{old('date',date('Y-m-d'))}}">
                                </div>
                            </div> 
 
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
                                    <a href="{{route('invoice.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                                </div>
                           </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
        $(function(){
                let customer = $('#customer');
                $('#customer').on('change',function(e){
                    let value = e.target.value;
                    let response =    $.get(`/api/master/customers/${customer.val()}`).then(function(data){
                        let notIfiy = data.customer[0] ;
                        let notifiy = $('#notifiy').val(' ' + notIfiy.name);
                    notifiy.html(list2.join(''));
                });
            });
        });
</script>
@endpush
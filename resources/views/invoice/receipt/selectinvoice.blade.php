@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="{{route('receipt.index')}}">Receipt</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">New Receipt</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    <form id="createForm" action="{{route('receipt.create')}}" method="get">
                            @csrf
                            <form>
                    <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="Bldraft">BlDraft Number <span class="text-warning"></span></label>
                                <select class="selectpicker form-control" id="Bldraft" data-live-search="true" name="bldraft_id" data-size="10"
                                 title="{{trans('forms.select')}}" required>
                                    @foreach ($bldrafts as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('bldraft_id',request()->input('bldraft_id')) ? 'selected':''}}>{{$item->ref_no}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="invoice">Invoice No</label>
                                <select class="form-control" id="invoice" data-live-search="true" name="invoice_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                 <option value="">Select...</option>
                                    @foreach ($invoiceRef as $item)     
                                        <option value="{{$item->id}}" {{$item->id == old('invoice_id',request()->input('invoice_id')) ? 'selected':''}}>{{$item->invoice_no}}</option>
                                    @endforeach
                                </select>
                            </div>
                    </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">Next</button>
                                <a href="{{route('receipt.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
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
                let bldraft = $('#Bldraft');
                $('#Bldraft').on('change',function(e){
                    let value = e.target.value;
                    let response =    $.get(`/api/master/invoices/${bldraft.val()}`).then(function(data){
                        let invoices = data.invoices || '';
                        let list2 = [`<option value=''>Select...</option>`];
                        for(let i = 0 ; i < invoices.length; i++){
                            list2.push(`<option value='${invoices[i].id}'>${invoices[i].invoice_no} </option>`);
                        }
                let invoice = $('#invoice');
                invoice.html(list2.join(''));
                });
            });
        });
</script>
@endpush
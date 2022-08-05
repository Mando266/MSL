@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="{{route('quotations.index')}}">Quotations</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Create New Quotation</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    <form id="createForm" action="{{route('quotations.store')}}" method="POST">
                            @csrf
                        @if($isSuperAdmin)
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="agent_id">Agent</label>
                                <select class="selectpicker form-control" id="agent_id" data-live-search="true" name="agent_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($agents as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('agent_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('agent_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="customer_id">Customer</label>
                                <select class="selectpicker form-control" id="customer_id" data-live-search="true" name="customer_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($customers as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('customer_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <div class="form-check">
                                    <label for="rate">Agreement Party</label><br>
                                    <input type="radio" name="rate" id="rate_sh" value="rate_sh">
                                    <label for="rate_sh">SH</label>&nbsp;
                                    <input type="radio" name="rate" id="rate_cn" value="rate_cn">
                                    <label for="rate_cn">CN</label>&nbsp;
                                    <input type="radio" name="rate" id="rate_nt" value="rate_nt">
                                    <label for="rate_nt">NT</label>&nbsp;
                                    <input type="radio" name="rate" id="rate_fwd" value="rate_fwd">
                                    <label for="rate_fwd">FWD</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="validity_from">Validity From</label>
                                <input type="date" class="form-control" id="validity_from" name="validity_from" value="{{old('validity_from')}}"
                                     autocomplete="off" >
                                @error('validity_from')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="validity_to">Validity To</label>
                                <input type="date" class="form-control" id="validity_to" name="validity_to" value="{{old('validity_to')}}"
                                     autocomplete="off" >
                                @error('validity_to')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="place_of_acceptence_id">Place Of Acceptence</label>
                                <select class="selectpicker form-control" id="place_of_acceptence_id" data-live-search="true" name="place_of_acceptence_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('place_of_acceptence_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('place_of_acceptence_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="place_of_delivery_id">Place Of Delivery</label>
                                <select class="selectpicker form-control" id="place_of_delivery_id" data-live-search="true" name="place_of_delivery_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('place_of_delivery_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('place_of_delivery_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="load_port_id">Load Port</label>
                                <select class="selectpicker form-control" id="load_port_id" data-live-search="true" name="load_port_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('load_port_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('load_port_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="discharge_port_id">Discharge Port</label>
                                <select class="selectpicker form-control" id="discharge_port_id" data-live-search="true" name="discharge_port_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('discharge_port_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('discharge_port_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="equipment_type_id">Equipment Type</label>
                                <select class="selectpicker form-control" id="equipment_type_id" data-live-search="true" name="equipment_type_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($container_types as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('equipment_type_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('equipment_type_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="export_detention">Export Free Time</label>
                                <input type="text" class="form-control" id="export_detention" name="export_detention" value="{{old('export_detention')}}"
                                    placeholder="Export Detention" autocomplete="off">
                                @error('export_detention')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="import_detention">Import Free Time</label>
                                <input type="text" class="form-control" id="import_detention" name="import_detention" value="{{old('import_detention')}}"
                                    placeholder="Import Detention" autocomplete="off">
                                @error('import_detention')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                            <table id="quotationDes" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Charge Desc</th>
                                        <th>mode</th>
                                        <th>currency</th>
                                        <th>charge unit</th>

                                        <th>
                                            <a id="add"> Add CHARGE <i class="fas fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <input type="text" id="charge_desc" name="quotationDes[0][charge_desc]" placeholder="Charge Desc" class="form-control" autocomplete="off">
                                    </td>
                                    <td>
                                        <input type="text" id="mode" name="quotationDes[0][mode]" class="form-control" placeholder="Mode" autocomplete="off">
                                    </td>
                                    <td>
                                        <select class="selectpicker form-control" id="currency" data-live-search="true" name="quotationDes[0][currency]" data-size="10"
                                        title="{{trans('forms.select')}}">
                                            @foreach ($currency as $item)
                                                <option value="{{$item->name}}" {{$item->id == old('currency') ? 'selected':''}}>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" id="charge_unit" name="quotationDes[0][charge_unit]" placeholder="Charge Unit" class="form-control" autocomplete="off">
                                    </td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
                                    <a href="{{route('quotations.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
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
    $(document).ready(function(){
    $("#quotationDes").on("click", ".remove", function () {
    $(this).closest("tr").remove();
    });
    var counter  = 1;
    $("#add").click(function(){
            var tr = '<tr>'+
        '<td><input type="text" name="quotationDes['+counter+'][charge_desc]"  placeholder="Charge Desc" class="form-control"></td>'+
        '<td><input type="text" name="quotationDes['+counter+'][mode]" placeholder="Mode" class="form-control"></td>'+
        '<td><select class="form-control" data-live-search="true" name="quotationDes['+counter+'][currency]" data-size="10" title="{{trans('forms.select')}}"><option>Select...</option>@foreach ($currency as $item)<option value="{{$item->name}}" {{$item->id == old('currency') ? 'selected':''}}>{{$item->name}}</option>@endforeach</select></td>'+
        '<td><input type="text" name="quotationDes['+counter+'][charge_unit]" placeholder="Charge Unit" class="form-control"></td>'+
        '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>'
        '</tr>';
        counter++;
        $('#quotationDes').append(tr);
    });
});

</script>
@endpush

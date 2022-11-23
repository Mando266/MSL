@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Triffs </a></li>
                            <li class="breadcrumb-item"><a a href="{{route('demurrage.index')}}">Demurrage & Dentention</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Add New Demurrage & Dentention</a></li>

                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    <form id="createForm" action="{{route('demurrage.update',['demurrage'=>$demurrage])}}" method="POST">
                            @csrf
                            @method('put')
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="countryInput">{{trans('company.country')}} *</label>
                                    <select class="selectpicker form-control" id="countryInput" data-live-search="true" name="country_id" data-size="10"
                                     title="{{trans('forms.select')}}">
                                        @foreach ($countries as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('country_id') || $item->id == $demurrage->country_id ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('country_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="port">Port *</label>
                                    <select class="selectpicker form-control" id="porrt" data-live-search="true" name="port_id" data-size="10"
                                            title="{{trans('forms.select')}}">
                                            @foreach ($ports as $item)
                                                <option value="{{$item->id}}" {{$item->id == old('port_id') || $item->id == $demurrage->port_id ? 'selected':''}}>{{$item->name}}</option>
                                            @endforeach
                                    </select>
                                    @error('port_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="containersTypesInput">Container Type *</label>
                                    <select class="selectpicker form-control" id="containersTypesInput" data-live-search="true" name="container_type_id" data-size="10"
                                    title="{{trans('forms.select')}}" autofocus>
                                        @foreach ($containersTypes as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('container_type_id') || $item->id == $demurrage->container_type_id ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('container_type_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="bounds">Bound *</label>
                                    <select class="selectpicker form-control" id="bounds" data-live-search="true" name="bound_id" data-size="10"
                                    title="{{trans('forms.select')}}" autofocus>
                                        @foreach ($bounds as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('bound_id') || $item->id == $demurrage->bound_id ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('bound_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="currency">Currency</label>
                                    <select class="selectpicker form-control" id="currency" data-live-search="true" name="currency" data-size="10"
                                    title="{{trans('forms.select')}}" autofocus>
                                        @foreach ($currency as $item)
                                            <option value="{{$item->name}}" {{$item->id == old('currency') || $item->id == $demurrage->currency ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('currency')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="validity_from">Validity From</label>
                                    <input type="date" class="form-control" id="currency" name="validity_from" value="{{old('validity_from',$demurrage->validity_from)}}"
                                     placeholder="Validity From" autocomplete="off" >
                                    @error('validity_from')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="validity_from">Validity to</label>
                                    <input type="date" class="form-control" id="currency" name="validity_to" value="{{old('validity_to',$demurrage->validity_to)}}"
                                     placeholder="Validity To" autocomplete="off" >
                                    @error('validity_to')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="Triffs">Triff</label>
                                    <select class="selectpicker form-control" id="Triffs" data-live-search="true" name="tariff_id" data-size="10"
                                    title="{{trans('forms.select')}}" autofocus>
                                        @foreach ($triffs as $item)
                                            <option value="{{$item->name}}" {{$item->name == old('tariff_id') || $item->name == $demurrage->tariff_id ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('tariff_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="is_storge">Detention OR Storage</label>
                                    <select class="selectpicker form-control" id="is_storge" data-live-search="true" name="is_storge" data-size="10"
                                    title="{{trans('forms.select')}}" autofocus>
                                            <option value="Detention" {{$demurrage->is_storge == "Detention"? 'selected':''}}>Detention</option>
                                            <option value="Storage" {{$demurrage->is_storge == "Storage"? 'selected':''}}>Storage</option>
                                    </select>
                                    @error('is_storge')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="containersTypesInput">Terminals</label>
                                    <select class="selectpicker form-control" id="containersTypesInput" data-live-search="true" name="terminal_id" data-size="10"
                                    title="{{trans('forms.select')}}" autofocus>
                                        @foreach ($terminals as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('terminal_id',$demurrage->terminal_id) ? 'selected':''}}>{{$item->name}} {{$item->code}}</option>
                                        @endforeach
                                    </select>
                                    @error('terminal_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <table id="period" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Period</th>
                                        <th>Rate</th>
                                        <th>Calendar Days</th>
                                        <th>
                                            <a id="add"> Add Period <i class="fas fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($demurrage->periods as $key => $period)
                                <tr>
                                <input type="hidden" value ="{{ $period->id }}" name="period[{{ $key }}][id]" >
                                    <td>
                                        <input type="text" id="period" name="period[{{$key}}][period]" class="form-control" autocomplete="off" value="{{old('period[$key][period]',$period->period)}}">
                                    </td>
                                    <td>
                                        <input type="text" id="rate" name="period[{{$key}}][rate]" class="form-control" autocomplete="off" value="{{old('period[$key][rate]',$period->rate)}}">
                                    </td>
                                    <td>
                                        <input type="text" id="dayes" name="period[{{$key}}][number_off_dayes]" class="form-control" autocomplete="off" value="{{old('period[$key][number_off_dayes]',$period->number_off_dayes)}}">
                                    </td>
                                    <td style="width:85px;">
                                        <button type="button" class="btn btn-danger remove" onclick="removeItem({{ $period->id }})"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <input name="removed" id="removed" type="hidden"  value="">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary mt-3">{{trans('forms.update')}}</button>
                                    <a href="{{route('demurrage.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
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
    var removed = [];
function removeItem( item )
{
    removed.push(item);
    document.getElementById("removed").value = removed;
}

    $(document).ready(function(){
    $("#period").on("click", ".remove", function () {
    $(this).closest("tr").remove();
    });
    var counter  = <?= isset($key)? ++$key : 0 ?>;
    $("#add").click(function(){
            var tr = '<tr>'+
        '<td><input type="text" name="period['+counter+'][period]" class="form-control"></td>'+
        '<td><input type="text" name="period['+counter+'][rate]" class="form-control"></td>'+
        '<td><input type="text" name="period['+counter+'][number_off_dayes]" class="form-control"></td>'+
        '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>'
        '</tr>';
        counter++;
        $('#period').append(tr);
    });
});
</script>
@endpush

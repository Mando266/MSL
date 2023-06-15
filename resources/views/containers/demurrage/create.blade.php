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
                    <form id="createForm" action="{{route('demurrage.store')}}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="countryInput">{{trans('company.country')}} <span class="text-warning"> * (Required.) </span></label>
                                    <select class="selectpicker form-control" id="country" data-live-search="true" name="country_id" data-size="10"
                                     title="{{trans('forms.select')}}" required>
                                        @foreach ($countries as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('country_id') ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('country_id')
                                    <div style="color:red;">
                                        {{$message}} 
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="port">Port <span class="text-warning"> * (Required.) </span></label>
                                    <select class="selectpicker form-control" id="port" data-live-search="true" name="port_id" data-size="10" required>
                                        <option value="">Select...</option>
                                            @foreach ($ports as $item)
                                                <option value="{{$item->id}}" {{$item->id == old('port_id') ? 'selected':''}}>{{$item->name}}</option>
                                            @endforeach
                                    </select>
                                    @error('port_id')
                                    <div style="color:red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
  
                                <div class="form-group col-md-4">
                                    <label for="containersTypesInput">Container Type <span class="text-warning"> * (Required.) </span></label>
                                    <select class="selectpicker form-control" id="containersTypesInput" data-live-search="true" name="container_type_id" data-size="10"
                                    title="{{trans('forms.select')}}" required>
                                        @foreach ($containersTypes as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('container_type_id') ? 'selected':''}}>{{$item->name}}</option>
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
                                    <label for="bounds">Bound <span class="text-warning"> * (Required.) </span></label>
                                    <select class="selectpicker form-control" id="bounds" data-live-search="true" name="bound_id" data-size="10"
                                    title="{{trans('forms.select')}}" required>
                                        @foreach ($bounds as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('bound_id') ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('bound_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="currency">Currency <span class="text-warning"> * (Required.) </span></label>
                                    <select class="selectpicker form-control" id="currency" data-live-search="true" name="currency" data-size="10"
                                    title="{{trans('forms.select')}}" required>
                                        @foreach ($currency as $item)
                                            <option value="{{$item->name}}" {{$item->id == old('currency') ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('currency')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="validity_from">Validity From <span class="text-warning"> * (Required.) </span></label>
                                    <input type="date" class="form-control" id="currency" name="validity_from" value="{{old('validity_from')}}"
                                     placeholder="Validity From" autocomplete="off"required >
                                    @error('validity_from')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="validity_from">Validity to <span class="text-warning"> * (Required.) </span></label>
                                    <input type="date" class="form-control" id="currency" name="validity_to" value="{{old('validity_to')}}"
                                     placeholder="Validity To" autocomplete="off" required>
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
                                            <option value="{{$item->name}}" {{$item->name == old('tariff_id') ? 'selected':''}}>{{$item->name}}</option>
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
                                    title="{{trans('forms.select')}}" required>
                                            <option value="Detention">Detention</option>
                                            <option value="Export">Export</option>
                                            <option value="power charges">power charges</option>
                                            <option value="Import">Import</option>
                                    </select>
                                    @error('is_storge')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-8">
                                    <label for="containersTypesInput">Trminal <span class="text-warning"> * (Required.) </span></label>
                                    <select class="selectpicker form-control" id="terminal" data-live-search="true" name="terminal_id" data-size="10" required>
                                        <option value="">Select...</option>
                                        @foreach ($terminals as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('terminal_id') ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('terminal_id')
                                    <div style="color:red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                <label for="BookingInput">Container Status </label>
                                <select class="selectpicker form-control" id="BookingInput" data-live-search="true" name="container_status" data-size="10"
                                title="{{trans('forms.select')}}" required>
                                    @foreach ($containerstatus as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('container_status') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('container_status')
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
                                <tr>
                                    <td>
                                        <input type="text" id="period" name="period[0][period]" class="form-control" autocomplete="off">
                                    </td>
                                    <td>
                                        <input type="text" id="rate" name="period[0][rate]" class="form-control" autocomplete="off">
                                    </td>
                                    <td>
                                        <input type="text" id="dayes" name="period[0][number_off_dayes]" class="form-control" autocomplete="off">
                                    </td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
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
    $(document).ready(function(){
    $("#period").on("click", ".remove", function () {
    $(this).closest("tr").remove();
    });
    var counter  = 1;
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
<script>
        $(function(){
                let country = $('#country');
                let company_id = "{{optional(Auth::user())->company->id}}";
                $('#country').on('change',function(e){
                    let value = e.target.value;
                    let response =    $.get(`/api/master/ports/${country.val()}/${company_id}`).then(function(data){
                        let ports = data.ports || '';
                        let list2 = [`<option value=''>Select...</option>`];
                        for(let i = 0 ; i < ports.length; i++){
                            list2.push(`<option value='${ports[i].id}'>${ports[i].name} </option>`);
                        }
                let port = $('#port');
                port.html(list2.join(''));
                $('.selectpicker').selectpicker('refresh');
                });
            });
        });
</script>
<script>
        $(function(){
                let port = $('#port');
                $('#port').on('change',function(e){
                    let value = e.target.value;
                    let response =    $.get(`/api/master/terminals/${port.val()}`).then(function(data){
                        let terminals = data.terminals || '';
                        let list2 = [`<option value=''>Select...</option>`];
                        for(let i = 0 ; i < terminals.length; i++){
                            list2.push(`<option value='${terminals[i].id}'>${terminals[i].name} </option>`);
                        }
                let terminal = $('#terminal');
                terminal.html(list2.join(''));
                $('.selectpicker').selectpicker('refresh');
                });
            });
        });
</script>
@endpush

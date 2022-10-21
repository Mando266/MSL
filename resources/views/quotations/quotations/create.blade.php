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
                            <div class="form-group col-md-3">
                                <label for="countryInput">Load Country </label>
                                <select class="selectpicker form-control" id="country" data-live-search="true" data-size="10"
                                    title="{{trans('forms.select')}}">
                                    @foreach ($country as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('country_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="agent_id">Load Agent</label>
                                <select class="form-control" id="agentload" data-live-search="true" name="agent_id" data-size="10">
                                 <option value="">Select...</option>
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
                            <div class="form-group col-md-3">
                                <label for="countryInput">Discharge Country</label>
                                <select class="selectpicker form-control" id="countryDis" data-live-search="true" data-size="10"
                                    title="{{trans('forms.select')}}">
                                    @foreach ($country as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('country_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="agent_id">Discharge Agent</label>
                                <select class="form-control" id="agentDis" data-live-search="true" name="discharge_agent_id" data-size="10">
                                 <option value="">Select...</option>
                                    @foreach ($agents as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('discharge_agent_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('discharge_agent_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        <div class="form-row">
                            <div class="form-group col-md-4">
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
                            <div class="form-group col-md-2">
                                <div class="form-check">
                                    <label for="rate">SOC</label><br>
                                    <input type="radio" name="soc" id="rate_sh" value="1">
                                    <label for="rate_sh">Y</label>&nbsp;
                                    <input type="radio" name="soc" id="rate_cn" value="0">
                                    <label for="rate_cn">N</label>&nbsp;
                                </div>
                                @error('soc')
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
                            @error('rate')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="OFR">OFR</label>
                                <input type="text" class="form-control" id="ofr" name="ofr" value="{{old('ofr')}}"
                                     autocomplete="off" placeholder="OFR">
                                @error('ofr')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="validity_from">Validity From</label>
                                <input type="date" class="form-control" id="validity_from" name="validity_from" value="{{old('validity_from')}}"
                                     autocomplete="off" >
                                @error('validity_from')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
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
                            <div class="form-group col-md-4">
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
                            <div class="form-group col-md-4">
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
                            <div class="form-group col-md-4">
                                <label for="pick_up_location">Pick Up Location</label>
                                <select class="selectpicker form-control" id="pick_up_location" data-live-search="true" name="pick_up_location" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('pick_up_location') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('pick_up_location')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                        <div class="form-group col-md-4">
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
                            <div class="form-group col-md-4">
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
                            <div class="form-group col-md-4">
                                <label for="place_return_id">Place Of Return</label>
                                <select class="selectpicker form-control" id="place_return_id" data-live-search="true" name="place_return_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('place_return_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('place_return_id')
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
                                    placeholder="Import Free Time" autocomplete="off">
                                @error('import_detention')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <!-- <div class="form-group col-md-3">
                                <label for="import_storage">Import Storage</label>
                                <input type="text" class="form-control" id="import_storage" name="import_storage" value="{{old('import_storage')}}"
                                    placeholder="Import Storage" autocomplete="off">
                                @error('import_storage')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div> -->
                        </div>
                        <div class="form-row">
                            <!-- <div class="form-group col-md-3">
                                <label for="export_storage">Export Storage</label>
                                <input type="text" class="form-control" id="export_storage" name="export_storage" value="{{old('export_storage')}}"
                                    placeholder="Export Storage" autocomplete="off">
                                @error('export_storage')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div> -->
                            <div class="form-group col-md-4">
                                <label for="oog_dimensions">OOG Dimensions</label>
                                <input type="text" class="form-control" id="oog_dimensions" name="oog_dimensions" value="{{old('oog_dimensions')}}"
                                    placeholder="OOG Dimensions" autocomplete="off">
                                @error('oog_dimensions')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="commodity_code">Commodity Code</label>
                                <input type="text" class="form-control" id="commodity_code" name="commodity_code" value="{{old('commodity_code')}}"
                                    placeholder="Commodity Code" autocomplete="off">
                                @error('commodity_code')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="commodity_des">Commodity Description</label>
                                <input type="text" class="form-control" id="commodity_des" name="commodity_des" value="{{old('commodity_des')}}"
                                    placeholder="Commodity Description" autocomplete="off">
                                @error('commodity_des')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <h4>Import Price</h4>

                            <table id="quotationTriffLoad" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>charge type</th>
                                        <th>unit</th>
                                        <th>selling price</th>
                                        <th>cost</th>
                                        <th>currency</th>
                                        <th>agency revene</th>
                                        <th>liner</th>
                                        <th>payer</th>
                                        <th>Equipment Type</th>
                                        <th>
                                            <a id="add"> Add CHARGE <i class="fas fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                    
                            </table>
                            <h4>Export Price</h4>
                            <table id="quotationTriffDischarge" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>charge type</th>
                                        <th>unit</th>
                                        <th>selling price</th>
                                        <th>cost</th>
                                        <th>currency</th>
                                        <th>agency revene</th>
                                        <th>liner</th>
                                        <th>payer</th>
                                        <th>Equipment Type</th>

                                        <th>
                                            <a id="adddis"> Add CHARGE <i class="fas fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                      
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
    $("#quotationTriffLoad").on("click", ".remove", function () {
    $(this).closest("tr").remove();
    });
    var counter  = 1;
    $("#add").click(function(){
            var tr = '<tr>'+
                '<td><input type="text" name="quotationDes['+counter+'][charge_desc]" class="form-control" autocomplete="off" required></td>'+
                '<td><select class="form-control" data-live-search="true" name="quotationDes['+counter+'][charge_unit]"><option>Select</option><option value="Container">Container</option><option value="Document">Document</option></select></td>'+
                '<td><input type="text" name="quotationDes['+counter+'][selling_price]" class="form-control"></td>'+
                '<td><input type="text" name="quotationDes['+counter+'][cost]" class="form-control"></td>'+
                '<td><select class="form-control" data-live-search="true" name="quotationDes['+counter+'][currency]" data-size="10"><option>Select</option>@foreach ($currency as $item)<option value="{{$item->name}}">{{$item->name}}</option>@endforeach</select></td>'+
                '<td><input type="text" name="quotationDes['+counter+'][agency_revene]" class="form-control" autocomplete="off" required></td>'+
                '<td><input type="text" name="quotationDes['+counter+'][liner]" class="form-control" autocomplete="off" required></td>'+
                '<td><select class="form-control" data-live-search="true" name="quotationDes['+counter+'][payer]"><option>Select</option><option value="Liner" >Liner</option><option value="Shipper" >Shipper</option><option value="Conee" >Conee</option><option value="Else" >Else</option></select></td>'+
                '<td><select class="form-control" data-live-search="true" name="quotationDes['+counter+'][equipment_type_id]" data-size="10"><option>Select</option><option value="All">All</option>@foreach ($equipment_types as $item)<option value="{{$item->name}}">{{$item->name}}</option>@endforeach</select></td>'+
                '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>'
            '</tr>';
        counter++;
        $('#quotationTriffLoad').append(tr);
    });
});

</script>

<script>
    $(document).ready(function(){
    $("#quotationTriffDischarge").on("click", ".remove", function () {
    $(this).closest("tr").remove();
    });
    var counter  = 1;
    $("#adddis").click(function(){
            var tr = '<tr>'+
            '<td><input type="text" name="quotationDes['+counter+'][charge_desc]" class="form-control" autocomplete="off" required></td>'+
                '<td><select class="form-control" data-live-search="true" name="quotationDes['+counter+'][charge_unit]"><option>Select</option><option value="Container">Container</option><option value="Document">Document</option></select></td>'+
                '<td><input type="text" name="quotationDes['+counter+'][selling_price]" class="form-control"></td>'+
                '<td><input type="text" name="quotationDes['+counter+'][cost]" class="form-control"></td>'+
                '<td><select class="form-control" data-live-search="true" name="quotationDes['+counter+'][currency]" data-size="10"><option>Select</option>@foreach ($currency as $item)<option value="{{$item->name}}">{{$item->name}}</option>@endforeach</select></td>'+
                '<td><input type="text" name="quotationDes['+counter+'][agency_revene]" class="form-control" autocomplete="off" required></td>'+
                '<td><input type="text" name="quotationDes['+counter+'][liner]" class="form-control" autocomplete="off" required></td>'+
                '<td><select class="form-control" data-live-search="true" name="quotationDes['+counter+'][payer]"><option>Select</option><option value="Liner" >Liner</option><option value="Shipper" >Shipper</option><option value="Conee" >Conee</option><option value="Else" >Else</option></select></td>'+
                '<td><select class="form-control" data-live-search="true" name="quotationDes['+counter+'][equipment_type_id]" data-size="10"><option>Select</option><option value="All">All</option>@foreach ($equipment_types as $item)<option value="{{$item->name}}">{{$item->name}}</option>@endforeach</select></td>'+
                '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>'
        '</tr>';
        counter++;
        $('#quotationTriffDischarge').append(tr);
    });
});

</script>
<script>
        $(function(){
                let country = $('#country');
                $('#country').on('change',function(e){
                    let value = e.target.value;
                    let response =    $.get(`/api/agent/agentCountry/${country.val()}`).then(function(data){
                        let agents = data.agents || '';
                        let list2 = [`<option value=''>Select...</option>`];
                        for(let i = 0 ; i < agents.length; i++){
                            list2.push(`<option value='${agents[i].id}'>${agents[i].name} </option>`);
                        }
                let agent = $('#agentload');
                agent.html(list2.join(''));
                });
            });
        });
</script>

<script>
        $(function(){
                let country = $('#countryDis');
                $('#countryDis').on('change',function(e){
                    let value = e.target.value;
                    let response =    $.get(`/api/agent/agentCountry/${country.val()}`).then(function(data){
                        let agents = data.agents || '';
                        let list2 = [`<option value=''>Select...</option>`];
                        for(let i = 0 ; i < agents.length; i++){
                            
                            list2.push(`<option value='${agents[i].id}'>${agents[i].name} </option>`);
                            
                        }
                let agent = $('#agentDis');
                agent.html(list2.join(''));
                });
            });
        });
</script>

<!-- start table load price -->
<script>
        $(function(){
                let agent = $('#agentload');
                $('#agentload').on('change',function(e){
                    let value = e.target.value;
                    let response =    $.get(`/api/agent/loadPrice/${agent.val()}`).then(function(data){
                        let agentTriff = data.agentTriff || '';
                        let list2 = [];
                        for(let i = 0 ; i < agentTriff.length; i++){
                            var table = document.getElementById("quotationTriffLoad");
                            
                            // Create an empty <tr> element and add it to the 1st position of the table:
                            var row = table.insertRow();
                            for(x in agentTriff[i])
                            {
                                if(x != "id" && x != "quotation_triff_id" && x != "id" && x != "add_to_quotation" && x != "created_at" && x != "updated_at"  && x != "is_import_or_export" ){
                                    var cell = row.insertCell();
                                    var input = document.createElement("INPUT");
                                    input.setAttribute("type", "text");
                                    input.setAttribute("name", "agentTriff["+i+"]["+x+"]");
                                    input.setAttribute('readonly', true);
                                    input.value = agentTriff[i][x]
                                    input.classList.add("form-control");
                                    cell.appendChild(input);
                                }
                                console.log(input)
                            }
                            var buttonCell = row.insertCell()
                            var myButton = document.createElement("BUTTON")
                            myButton.classList.add("btn","btn-danger" ,"remove")
                            myButton.setAttribute('type','button')
                            let icon = document.createElement('i')
                            icon.classList.add("fa","fa-trash")
                            myButton.append(icon)
                            buttonCell.appendChild(myButton)

                            // list2.push(`<option value='${agentTriff[i].id}'>${agentTriff[i].charge_type} - ${agentTriff[i].unit} - ${agentTriff[i].selling_price} - ${agentTriff[i].cost} - ${agentTriff[i].currency}</option>`);
                        }
                let agentTriffs = $('#load');
                agentTriffs.html(list2.join(''));
                });
            });
        });
</script>
<!-- end load price -->

<script>
        $(function(){
                let agent = $('#agentDis');
                $('#agentDis').on('change',function(e){
                    let value = e.target.value;
                    let response =    $.get(`/api/agent/dischargePrice/${agent.val()}`).then(function(data){
                        let agentTriff = data.agentTriff || '';
                        let list2 = [];
                        for(let i = 0 ; i < agentTriff.length; i++){
                            var table = document.getElementById("quotationTriffDischarge");
                            
                            // Create an empty <tr> element and add it to the 1st position of the table:
                            var row = table.insertRow();
                            for(x in agentTriff[i])
                            {
                                if(x != "id" && x != "quotation_triff_id" && x != "id" && x != "add_to_quotation" && x != "created_at" && x != "updated_at"  && x != "is_import_or_export" ){
                                    var cell = row.insertCell();
                                    var input = document.createElement("INPUT");
                                    input.setAttribute("type", "text");
                                    input.setAttribute("name", "agentTriff["+i+"]["+x+"]");
                                    input.setAttribute('readonly', true);
                                    input.value = agentTriff[i][x]
                                    input.classList.add("form-control");
                                    cell.appendChild(input);
                                }
                                console.log(input)
                            }
                            var buttonCell = row.insertCell()
                            var myButton = document.createElement("BUTTON")
                            myButton.classList.add("btn","btn-danger" ,"remove")
                            myButton.setAttribute('type','button')
                            let icon = document.createElement('i')
                            icon.classList.add("fa","fa-trash")
                            myButton.append(icon)
                            buttonCell.appendChild(myButton)

                            // list2.push(`<option value='${agentTriff[i].id}'>${agentTriff[i].charge_type} - ${agentTriff[i].unit} - ${agentTriff[i].selling_price} - ${agentTriff[i].cost} - ${agentTriff[i].currency}</option>`);
                        }
                let agentTriffs = $('#Discharch');
                agentTriffs.html(list2.join(''));
                });
            });
        });
</script>
@endpush

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
                    <form id="editForm" action="{{route('quotations.update',['quotation'=>$quotation])}}" method="POST">
                            @csrf
                            @method('put')
                            @if($isSuperAdmin)
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="countryInput">Discharge Country</label>
                                <select class="selectpicker form-control" id="countryDis" name="countrydis" data-live-search="true" data-size="10"
                                    title="{{trans('forms.select')}}">
                                    @foreach ($country as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('countrydis',$quotation->countrydis) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('countrydis')
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
                                        <option value="{{$item->id}}" {{$item->id == old('discharge_agent_id',$quotation->discharge_agent_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('discharge_agent_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="countryInput">Load Country </label>
                                <select class="selectpicker form-control" id="country" name="countryload" data-live-search="true" data-size="10"
                                    title="{{trans('forms.select')}}">
                                    @foreach ($country as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('countryload',$quotation->countryload) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('countryload')
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
                                        <option value="{{$item->id}}" {{$item->id == old('agent_id',$quotation->agent_id) ? 'selected':''}}>{{$item->name}}</option>
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
                                <label for="Principal">Principal Name </label>
                                <select class="selectpicker form-control" id="Principal" data-live-search="true" name="principal_name" data-size="10"
                                title="{{trans('forms.select')}}">
                                    @foreach ($line as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('principal_name',$quotation->principal_name) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('principal_name')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="vessel_name">Vessel Operator </label>
                                <select class="selectpicker form-control" id="vessel_name" data-live-search="true" name="vessel_name" data-size="10"
                                title="{{trans('forms.select')}}">
                                    @foreach ($line as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('vessel_name',$quotation->vessel_name) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('principal_name')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="customer_id">Customer</label>
                                <select class="selectpicker form-control" id="customer_id" data-live-search="true" name="customer_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($customers as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('customer_id',$quotation->customer_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            @if($isSuperAdmin)
                            <div class="form-group col-md-2">
                                <label for="rate">Show Import Triff</label><br>
                                    <select class="form-control" id="show" data-live-search="true" name="show_import" data-size="10">
                                            <option value="0" {{$quotation->show_import == old('show_import',$quotation->show_import) ? 'selected':''}}>No</option>
                                            <option value="1" {{$quotation->show_import == old('show_import',$quotation->show_import) ? 'selected':''}}>Yes</option>
                                    </select>
                            </div>
                            @else
                            @endif
                            <div class="form-group col-md-3">
                                <div class="form-check">
                                    <label for="rate">Agreement Party</label><br>
                                    <input type="radio" name="rate" id="rate_sh" value="rate_sh" {{$quotation->rate_sh == 1 ? 'checked="checked"' :''}}>
                                    <label for="rate_sh">SH</label>&nbsp;
                                    <input type="radio" name="rate" id="rate_cn" value="rate_cn" {{$quotation->rate_cn == 1 ? 'checked="checked"' :''}}>
                                    <label for="rate_cn">CN</label>&nbsp;
                                    <input type="radio" name="rate" id="rate_nt" value="rate_nt" {{$quotation->rate_nt == 1 ? 'checked="checked"' :''}}>
                                    <label for="rate_nt">NT</label>&nbsp;
                                    <input type="radio" name="rate" id="rate_fwd" value="rate_fwd" {{$quotation->rate_fwd == 1 ? 'checked="checked"' :''}}>
                                    <label for="rate_fwd">FWD</label>
                                </div>
                            </div>
                            
                            <div class="form-group col-md-3" style="padding-top: 30px;">
                                <div class="form-check">
                                <input type="checkbox" id="soc" name="soc" value="1" {{$quotation->soc == 1 ? 'checked="checked"' : '' }}><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px; margin-right: 10px;"> SOC </a>
                                
                                <input type="checkbox" id="imo" name="imo" value="1" {{$quotation->imo == 1 ? 'checked="checked"' : '' }}><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px; margin-right: 10px;"> IMO </a>
                                
                                <input type="checkbox" id="oog" name="oog" value="1" {{$quotation->oog == 1 ? 'checked="checked"' : '' }}><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px; margin-right: 10px;"> OOG </a>
                                
                                <input type="checkbox" id="rf" name="rf" value="1" {{$quotation->rf == 1 ? 'checked="checked"' : '' }}><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px; margin-right: 10px;"> RF </a>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="OFR">OFR</label>
                                <input type="text" class="form-control" id="ofr" name="ofr" value="{{old('ofr',$quotation->ofr)}}"
                                     autocomplete="off" placeholder="OFR">
                                @error('ofr')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="validity_from">Validity From</label>
                                <input type="date" class="form-control" id="validity_from" name="validity_from" value="{{old('validity_from',$quotation->validity_from)}}"
                                     autocomplete="off" >
                                @error('validity_from')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="validity_to">Validity To</label>
                                <input type="date" class="form-control" id="validity_to" name="validity_to" value="{{old('validity_to',$quotation->validity_to)}}"
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
                                        <option value="{{$item->id}}" {{$item->id == old('place_of_acceptence_id',$quotation->place_of_acceptence_id) ? 'selected':''}}>{{$item->name}}</option>
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
                                        <option value="{{$item->id}}" {{$item->id == old('load_port_id',$quotation->load_port_id) ? 'selected':''}}>{{$item->name}}</option>
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
                                        <option value="{{$item->id}}" {{$item->id == old('pick_up_location',$quotation->pick_up_location) ? 'selected':''}}>{{$item->name}}</option>
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
                                        <option value="{{$item->id}}" {{$item->id == old('place_of_delivery_id',$quotation->place_of_delivery_id) ? 'selected':''}}>{{$item->name}}</option>
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
                                        <option value="{{$item->id}}" {{$item->id == old('discharge_port_id',$quotation->discharge_port_id) ? 'selected':''}}>{{$item->name}}</option>
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
                                        <option value="{{$item->id}}" {{$item->id == old('place_return_id',$quotation->place_return_id) ? 'selected':''}}>{{$item->name}}</option>
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
                                        <option value="{{$item->id}}" {{$item->id == old('equipment_type_id',$quotation->equipment_type_id) ? 'selected':''}}>{{$item->name}}</option>
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
                                <input type="text" class="form-control" id="export_detention" name="export_detention" value="{{old('export_detention',$quotation->export_detention)}}"
                                    placeholder="Export Detention" autocomplete="off">
                                @error('export_detention')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="import_detention">Import Free Time</label>
                                <input type="text" class="form-control" id="import_detention" name="import_detention" value="{{old('import_detention',$quotation->import_detention)}}"
                                    placeholder="Import Free Time" autocomplete="off">
                                @error('import_detention')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <!-- <div class="form-group col-md-3">
                                <label for="import_storage">Import Storage</label>
                                <input type="text" class="form-control" id="import_storage" name="import_storage" value="{{old('import_storage',$quotation->import_storage)}}"
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
                                <input type="text" class="form-control" id="export_storage" name="export_storage" value="{{old('export_storage',$quotation->export_storage)}}"
                                    placeholder="Export Storage" autocomplete="off">
                                @error('export_storage')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div> -->
                            <div class="form-group col-md-4">
                                <label for="oog_dimensions">HAZ / Reefer/ OOG Details / Haz Approval Ref</label>
                                <input type="text" class="form-control" id="oog_dimensions" name="oog_dimensions" value="{{old('oog_dimensions',$quotation->oog_dimensions)}}"
                                    placeholder="HAZ / Reefer/ OOG Details / Haz Approval Ref" autocomplete="off">
                                @error('oog_dimensions')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="commodity_code">Commodity Code</label>
                                <input type="text" class="form-control" id="commodity_code" name="commodity_code" value="{{old('commodity_code',$quotation->commodity_code)}}"
                                    placeholder="Commodity Code" autocomplete="off">
                                @error('commodity_code')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="commodity_des">Commodity Description</label>
                                <input type="text" class="form-control" id="commodity_des" name="commodity_des" value="{{old('commodity_des',$quotation->commodity_des)}}"
                                    placeholder="Commodity Description" autocomplete="off">
                                @error('commodity_des')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <h4>Discharge Price</h4>
                            <table id="quotationTriffDischarge" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>charge type</th>
                                        <th>unit</th>
                                        <th>selling price</th>
                                        <!-- <th>cost</th> -->
                                        <th>currency</th>
                                        <!-- <th>agency revene</th> -->
                                        <!-- <th>liner</th> -->
                                        <th>payer</th>
                                        <th>Equipment Type</th>

                                        <th>
                                            <a id="adddis"> Add CHARGE <i class="fas fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                @foreach($quotation->quotationDesc as $key=>$desc)
                                    <tr id="quotationTriffDischargeRow">
                                            <input type="hidden" value ="{{ $desc->id }}" name="quotationDis[{{ $key }}][id]">
                                        <td>
                                            <input type="text" id="quotationDis" name="quotationDis[{{$key}}][charge_type]" class="form-control" autocomplete="off"  value="{{old('charge_type',$desc->charge_type)}}" readonly>
                                            @error('charge_type')
                                            <div style="color:red;">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </td>

                                        <td>
                                            <select class="selectpicker form-control" id="unit" data-live-search="true" name="quotationDis[{{$key}}][unit]" data-size="10" 
                                                title="{{trans('forms.select')}}">
                                                    <option value="Container" {{$desc->unit == old('unit') ||  $desc->unit == "Container"? 'selected':'disabled'}}>Container</option>
                                                    <option value="Document" {{$desc->unit == old('unit') ||  $desc->unit == "Document"? 'selected':'disabled'}}>Document</option>
                                            </select>
                                            @error('unit')
                                            <div style="color:red;">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </td>

                                        <td>
                                            <input type="text" id="dayes" name="quotationDis[{{$key}}][selling_price]" class="form-control" autocomplete="off" value="{{old('selling_price',$desc->selling_price)}}" readonly>
                                            @error('selling_price')
                                            <div style="color:red;">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </td>

                                        <td>
                                            <select class="selectpicker form-control" id="currency" data-live-search="true" name="quotationDis[{{$key}}][currency]" data-size="10"
                                            title="{{trans('forms.select')}}" autofocus>
                                                @foreach ($currency as $item)
                                                    <option value="{{$item->name}}" {{$item->name == old('currency') || $item->name == $desc->currency? 'selected':'disabled'}}>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        
                                        <td>
                                            <select class="selectpicker form-control" id="payer" data-live-search="true" name="quotationDis[{{$key}}][payer]" data-size="10"
                                            title="{{trans('forms.select')}}">
                                                    <option value="Liner" {{$desc->payer == old('payer') ||  $desc->payer == "Liner"? 'selected':'disabled'}}>Liner</option>
                                                    <option value="Shipper" {{$desc->payer == old('payer') ||  $desc->payer == "Shipper"? 'selected':'disabled'}}>Shipper</option>
                                                    <option value="Conee" {{$desc->payer == old('payer') ||  $desc->payer == "Conee"? 'selected':'disabled'}}>Conee</option>
                                                    <option value="Else" {{$desc->payer == old('payer') ||  $desc->payer == "Else"? 'selected':'disabled'}}>Else</option>
                                            </select>
                                            @error('payer')
                                            <div style="color:red;">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </td>
                                        
                                        <td>
                                            <select class="selectpicker form-control" id="equipments_type" data-live-search="true" name="quotationDis[{{$key}}][equipments_type]" 
                                            data-size="10" title="{{trans('forms.select')}}">
                                                <option value="ALL" {{$desc->equipment_type_id == old('equipments_type') || $desc->equipment_type_id == "ALL" ? 'selected':'disabled'}}>All</option>
                                                @foreach ($equipment_types as $item)
                                                    <option value="{{$item->name}}" {{$item->name == old('equipments_type') || $item->name == $desc->equipment_type_id ? 'selected':'disabled'}}>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('equipments_type')
                                            <div style="color:red;">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </td>
                                        <td style="width:85px;">
                                            <button type="button" class="btn btn-danger remove" onclick="removeDesc({{$desc->id}})">
                                                <i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <h4>Load Price</h4>
                            <table id="quotationTriffLoad" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>charge type</th>
                                        <th>unit</th>
                                        <th>selling price</th>
                                        <!-- <th>cost</th> -->
                                        <th>currency</th>
                                        <!-- <th>agency revene</th> -->
                                        <!-- <th>liner</th> -->
                                        <th>payer</th>
                                        <th>Equipment Type</th>
                                        <th>
                                            <a id="add"> Add CHARGE <i class="fas fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($quotation->quotationLoad as $key=>$load)
                                    <tr id="quotationTriffLoadRow">
                                            <input type="hidden" value ="{{ $load->id }}" name="quotationLoad[{{ $key }}][id]">
                                        <td>
                                            <input type="text" id="quotationLoad" name="quotationLoad[{{$key}}][charge_type]" class="form-control" autocomplete="off"  value="{{old('charge_type',$load->charge_type)}}" readonly>
                                            @error('charge_type')
                                            <div style="color:red;">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </td>

                                        <td>
                                            <select class="selectpicker form-control" id="unit" data-live-search="true" name="quotationLoad[{{$key}}][unit]" data-size="10" 
                                                title="{{trans('forms.select')}}">
                                                    <option value="Container" {{$load->unit == old('unit') ||  $load->unit == "Container"? 'selected':'disabled'}}>Container</option>
                                                    <option value="Document" {{$load->unit == old('unit') ||  $load->unit == "Document"? 'selected':'disabled'}}>Document</option>
                                            </select>
                                            @error('unit')
                                            <div style="color:red;">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </td>

                                        <td>
                                            <input type="text" id="dayes" name="quotationLoad[{{$key}}][selling_price]" class="form-control" autocomplete="off" value="{{old('selling_price',$load->selling_price)}}" readonly>
                                            @error('selling_price')
                                            <div style="color:red;">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </td>

                                        <td>
                                            <select class="selectpicker form-control" id="currency" data-live-search="true" name="quotationLoad[{{$key}}][currency]" data-size="10"
                                            title="{{trans('forms.select')}}" autofocus>
                                                @foreach ($currency as $item)
                                                    <option value="{{$item->name}}" {{$item->name == old('currency') || $item->name == $load->currency? 'selected':'disabled'}}>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        
                                        <td>
                                            <select class="selectpicker form-control" id="payer" data-live-search="true" name="quotationLoad[{{$key}}][payer]" data-size="10"
                                            title="{{trans('forms.select')}}">
                                                    <option value="Liner" {{$load->payer == old('payer') ||  $load->payer == "Liner"? 'selected':'disabled'}}>Liner</option>
                                                    <option value="Shipper" {{$load->payer == old('payer') ||  $load->payer == "Shipper"? 'selected':'disabled'}}>Shipper</option>
                                                    <option value="Conee" {{$load->payer == old('payer') ||  $load->payer == "Conee"? 'selected':'disabled'}}>Conee</option>
                                                    <option value="Else" {{$load->payer == old('payer') ||  $load->payer == "Else"? 'selected':'disabled'}}>Else</option>
                                            </select>
                                            @error('payer')
                                            <div style="color:red;">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </td>
                                        
                                        <td>
                                            <select class="selectpicker form-control" id="equipments_type" data-live-search="true" name="quotationLoad[{{$key}}][equipments_type]" data-size="10"
                                            title="{{trans('forms.select')}}">
                                                <option value="ALL" {{$load->equipment_type_id == old('equipments_type') || $load->equipment_type_id == "ALL" ? 'selected':'disabled'}}>All</option>
                                                @foreach ($equipment_types as $item)
                                                    <option value="{{$item->name}}" {{$item->name == old('equipments_type') || $item->name == $load->equipment_type_id ? 'selected':'disabled'}}>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('equipments_type')
                                            <div style="color:red;">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </td>
                                        <td style="width:85px;">
                                            <button type="button" class="btn btn-danger remove" onclick="removeLoad({{$load->id}})">
                                                <i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary mt-3">{{trans('forms.edit')}}</button>
                                    <a href="{{route('quotations.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                                </div>
                           </div>
                           <input name="removedDesc" id="removedDesc" type="hidden"  value="">
                           <input name="removedLoad" id="removedLoad" type="hidden"  value="">
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    var removedDesc = [];
    var removedLoad = [];
function removeDesc( item )
{
    removedDesc.push(item);
    console.log(removedDesc);
    document.getElementById("removedDesc").value = removedDesc;
}
function removeLoad( item )
{
    removedLoad.push(item);
    console.log(removedLoad);
    document.getElementById("removedLoad").value = removedLoad;
}
    var tableLoad = document.getElementById("quotationTriffLoad");
    var importCount = tableLoad.tBodies[0].rows.length;
    var tableDisc = document.getElementById("quotationTriffDischarge");
    var exportCount = tableDisc.tBodies[0].rows.length;
    $(document).ready(function(){
        $("#quotationTriffLoad").on("click", ".remove", function () {
            $(this).closest("tr").remove();
        });
    
    // var importCount = 1;

    $("#add").click(function(){
            var tr = '<tr>'+
                '<td><input type="text" name="quotationLoad['+importCount+'][charge_type]" class="form-control" autocomplete="off" required></td>'+
                '<td><select class="form-control" data-live-search="true" name="quotationLoad['+importCount+'][unit]"><option>Select</option><option value="Container">Container</option><option value="Document">Document</option></select></td>'+
                '<td><input type="text" name="quotationLoad['+importCount+'][selling_price]" class="form-control"></td>'+
                // '<td><input type="text" name="quotationLoad['+importCount+'][cost]" class="form-control"></td>'+
                '<td><select class="form-control" data-live-search="true" name="quotationLoad['+importCount+'][currency]" data-size="10"><option>Select</option>@foreach ($currency as $item)<option value="{{$item->name}}">{{$item->name}}</option>@endforeach</select></td>'+
                // '<td><input type="text" name="quotationLoad['+importCount+'][agency_revene]" class="form-control" autocomplete="off" required></td>'+
                // '<td><input type="text" name="quotationLoad['+importCount+'][liner]" class="form-control" autocomplete="off" required></td>'+
                '<td><select class="form-control" data-live-search="true" name="quotationLoad['+importCount+'][payer]"><option>Select</option><option value="Liner" >Liner</option><option value="Shipper" >Shipper</option><option value="Conee" >Conee</option><option value="Else" >Else</option></select></td>'+
                '<td><select class="form-control" data-live-search="true" name="quotationLoad['+importCount+'][equipments_type]" data-size="10"><option>Select</option><option value="All">All</option>@foreach ($equipment_types as $item)<option value="{{$item->name}}">{{$item->name}}</option>@endforeach</select></td>'+
                '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>'
            '</tr>';
        importCount++;
        $('#quotationTriffLoad').append(tr);
    });
});

</script>

<script>
    $(document).ready(function(){
        $("#quotationTriffDischarge").on("click", ".remove", function () {
            $(this).closest("tr").remove();
        });
    $("#adddis").click(function(){
            var tr = '<tr>'+
                '<td><input type="text" name="quotationDis['+exportCount+'][charge_type]" class="form-control" autocomplete="off" required></td>'+
                '<td><select class="form-control" data-live-search="true" name="quotationDis['+exportCount+'][unit]"><option>Select</option><option value="Container">Container</option><option value="Document">Document</option></select></td>'+
                '<td><input type="text" name="quotationDis['+exportCount+'][selling_price]" class="form-control"></td>'+
                // '<td><input type="text" name="quotationDis['+exportCount+'][cost]" class="form-control"></td>'+
                '<td><select class="form-control" data-live-search="true" name="quotationDis['+exportCount+'][currency]" data-size="10"><option>Select</option>@foreach ($currency as $item)<option value="{{$item->name}}">{{$item->name}}</option>@endforeach</select></td>'+
                // '<td><input type="text" name="quotationDis['+exportCount+'][agency_revene]" class="form-control" autocomplete="off" required></td>'+
                // '<td><input type="text" name="quotationDis['+exportCount+'][liner]" class="form-control" autocomplete="off" required></td>'+
                '<td><select class="form-control" data-live-search="true" name="quotationDis['+exportCount+'][payer]"><option>Select</option><option value="Liner" >Liner</option><option value="Shipper" >Shipper</option><option value="Conee" >Conee</option><option value="Else" >Else</option></select></td>'+
                '<td><select class="form-control" data-live-search="true" name="quotationDis['+exportCount+'][equipments_type]" data-size="10"><option>Select</option><option value="All">All</option>@foreach ($equipment_types as $item)<option value="{{$item->name}}">{{$item->name}}</option>@endforeach</select></td>'+
                '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>'
        '</tr>';
        exportCount++;
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
                    let agentLoadLength = $('#quotationTriffLoad').find('tr').length;
                    for(let rowscount = 0 ; rowscount < agentLoadLength ; rowscount++){
                        $('#quotationTriffLoadRow').remove();
                    }
                    let equipment = $('#equipment_type_id').val();
                    let value = e.target.value;
                    let response =    $.get(`/api/agent/loadPrice/${agent.val()}/${equipment}`).then(function(data){
                        let agentTriff = data.agentTriff || '';
                        let list2 = [];
                        for(let i = 0 ; i < agentTriff.length; i++){
                            var table = document.getElementById("quotationTriffLoad");
                            
                            // Create an empty <tr> element and add it to the 1st position of the table:
                            var row = table.insertRow();
                            row.setAttribute("id", "quotationTriffLoadRow");
                            for(x in agentTriff[i])
                            {
                                if(x != "id" && x != "equipment_type_id" && x != "quotation_triff_id" && x != "id" && x != "add_to_quotation" && x != "created_at" && x != "updated_at"  && x != "is_import_or_export"  && x != "cost"  && x != "agency_revene"  && x != "liner"){
                                    var cell = row.insertCell();
                                    var input = document.createElement("INPUT");
                                    input.setAttribute("type", "text");
                                    input.setAttribute("name", "quotationLoad["+importCount+"]["+x+"]");
                                    input.setAttribute('readonly', true);
                                    if(x == 'equipments_type'){
                                        if(agentTriff[i]['equipment_type_id'] == 100){
                                            input.value = "ALL"
                                        }else{
                                            input.value = agentTriff[i][x]['name']
                                        }
                                    }else{
                                        input.value = agentTriff[i][x]
                                    }
                                    input.classList.add("form-control");
                                    cell.appendChild(input);
                                }
                                
                            }
                            var buttonCell = row.insertCell()
                            var myButton = document.createElement("BUTTON")
                            myButton.classList.add("btn","btn-danger" ,"remove")
                            myButton.setAttribute('type','button')
                            let icon = document.createElement('i')
                            icon.classList.add("fa","fa-trash")
                            myButton.append(icon)
                            buttonCell.appendChild(myButton)
                            importCount++;
                            // list2.push(`<option value='${agentTriff[i].id}'>${agentTriff[i].charge_type} - ${agentTriff[i].unit} - ${agentTriff[i].selling_price} - ${agentTriff[i].cost} - ${agentTriff[i].currency}</option>`);
                        }
                        
                let agentTriffs = $('#load');
                agentTriffs.html(list2.join(''));
                });//api fn
            });//on change
        });//fn
</script>
<!-- end load price -->

<script>
        $(function(){
                let agent = $('#agentDis');
                $('#agentDis').on('change',function(e){
                    let agentDisLength = $('#quotationTriffDischarge').find('tr').length;
                    for(let rowscount = 0 ; rowscount < agentDisLength; rowscount++){
                        $('#quotationTriffDischargeRow').remove();
                    }
                    let equipment = $('#equipment_type_id').val();
                    let value = e.target.value;
                    let response =    $.get(`/api/agent/dischargePrice/${agent.val()}/${equipment}`).then(function(data){
                        let agentTriff = data.agentTriff || '';
                        let list2 = [];
                        for(let i = 0 ; i < agentTriff.length; i++){
                            var table = document.getElementById("quotationTriffDischarge");
                            
                            // Create an empty <tr> element and add it to the 1st position of the table:
                            var row = table.insertRow();
                            row.setAttribute("id", "quotationTriffDischargeRow");
                            for(x in agentTriff[i])
                            {
                                if(x != "id" && x != "equipment_type_id" && x != "quotation_triff_id" && x != "id" && x != "add_to_quotation" && x != "created_at" && x != "updated_at"  && x != "is_import_or_export"  && x != "cost"  && x != "agency_revene"  && x != "liner"){
                                    var cell = row.insertCell();
                                    var input = document.createElement("INPUT");
                                    input.setAttribute("type", "text");
                                    input.setAttribute("name", "quotationDis["+exportCount+"]["+x+"]");
                                    input.setAttribute('readonly', true);
                                    if(x == 'equipments_type'){
                                        if(agentTriff[i]['equipment_type_id'] == 100){
                                            input.value = "ALL"
                                        }else{
                                            input.value = agentTriff[i][x]['name']
                                        }
                                    }else{
                                        input.value = agentTriff[i][x]
                                    }
                                    input.classList.add("form-control");
                                    cell.appendChild(input);
                                }
                            }
                            var buttonCell = row.insertCell()
                            var myButton = document.createElement("BUTTON")
                            myButton.classList.add("btn","btn-danger" ,"remove")
                            myButton.setAttribute('type','button')
                            let icon = document.createElement('i')
                            icon.classList.add("fa","fa-trash")
                            myButton.append(icon)
                            buttonCell.appendChild(myButton)
                            exportCount++;
                            // list2.push(`<option value='${agentTriff[i].id}'>${agentTriff[i].charge_type} - ${agentTriff[i].unit} - ${agentTriff[i].selling_price} - ${agentTriff[i].cost} - ${agentTriff[i].currency}</option>`);
                        }
                        
                let agentTriffs = $('#Discharch');
                agentTriffs.html(list2.join(''));
                });
            });
        });
</script>

<script>
        $(function(){
                    let agentDis = $('#agentDis');
                    let agentLoad = $('#agentload');
                    let equipment = $('#equipment_type_id');
                $('#equipment_type_id').on('change',function(e){
                    let agentDisLength = $('#quotationTriffDischarge').find('tr').length;
                    let agentLoadLength = $('#quotationTriffLoad').find('tr').length;
                    for(let rowscount = 0 ; rowscount < agentDisLength ; rowscount++){
                        $('#quotationTriffDischargeRow').remove();
                    }
                    for(let rowsloadcount = 0 ; rowsloadcount < agentLoadLength ; rowsloadcount++){
                        console.count(rowsloadcount)
                        $('#quotationTriffLoadRow').remove();
                    }
                    let value = e.target.value;
                    
                    if(agentDis.val() != ''){
                        let response =    $.get(`/api/agent/dischargePrice/${agentDis.val()}/${equipment.val()}`).then(function(data){
                        let agentTriff = data.agentTriff || '';
                        let list2 = [];
                        for(let i = 0 ; i < agentTriff.length; i++){
                            var table = document.getElementById("quotationTriffDischarge");
                            
                            // Create an empty <tr> element and add it to the 1st position of the table:
                            var row = table.insertRow();
                            row.setAttribute("id", "quotationTriffDischargeRow");
                            for(x in agentTriff[i])
                            {
                                if(x != "id" && x != "equipment_type_id" && x != "quotation_triff_id" && x != "id" && x != "add_to_quotation" && x != "created_at" && x != "updated_at"  && x != "is_import_or_export"  && x != "cost"  && x != "agency_revene"  && x != "liner"){
                                    var cell = row.insertCell();
                                    var input = document.createElement("INPUT");
                                    input.setAttribute("type", "text");
                                    input.setAttribute("name", "quotationDis["+exportCount+"]["+x+"]");
                                    input.setAttribute('readonly', true);
                                    if(x == 'equipments_type'){
                                        if(agentTriff[i]['equipment_type_id'] == 100){
                                            input.value = "ALL"
                                        }else{
                                            input.value = agentTriff[i][x]['name']
                                        }
                                    }else{
                                        input.value = agentTriff[i][x]
                                    }
                                    input.classList.add("form-control");
                                    cell.appendChild(input);
                                }
                            }
                            var buttonCell = row.insertCell()
                            var myButton = document.createElement("BUTTON")
                            myButton.classList.add("btn","btn-danger" ,"remove")
                            myButton.setAttribute('type','button')
                            let icon = document.createElement('i')
                            icon.classList.add("fa","fa-trash")
                            myButton.append(icon)
                            buttonCell.appendChild(myButton)
                            exportCount++;
                            // list2.push(`<option value='${agentTriff[i].id}'>${agentTriff[i].charge_type} - ${agentTriff[i].unit} - ${agentTriff[i].selling_price} - ${agentTriff[i].cost} - ${agentTriff[i].currency}</option>`);
                        }
                                
                        let agentTriffs = $('#Discharch');
                        agentTriffs.html(list2.join(''));
                        });
                    }
                    
                    
                    if(agentLoad.val() != ''){
                        let response =    $.get(`/api/agent/loadPrice/${agentLoad.val()}/${equipment.val()}`).then(function(data){
                        let agentTriff = data.agentTriff || '';
                        let list2 = [];
                        
                        for(let i = 0 ; i < agentTriff.length; i++){
                            var table = document.getElementById("quotationTriffLoad");
                            // Create an empty <tr> element and add it to the 1st position of the table:
                            var row = table.insertRow();
                            row.setAttribute("id", "quotationTriffLoadRow");
                            for(x in agentTriff[i])
                            {
                                
                                if(x != "id" && x != "equipment_type_id" && x != "quotation_triff_id" && x != "id" && x != "add_to_quotation" && x != "created_at" && x != "updated_at"  && x != "is_import_or_export"  && x != "cost"  && x != "agency_revene"  && x != "liner"){
                                    var cell = row.insertCell();
                                    var input = document.createElement("INPUT");
                                    input.setAttribute("type", "text");
                                    input.setAttribute("name", "quotationLoad["+importCount+"]["+x+"]");
                                    input.setAttribute('readonly', true);
                                    if(x == 'equipments_type'){
                                        
                                        if(agentTriff[i]['equipment_type_id'] == 100){
                                            input.value = "ALL"
                                        }else{
                                            input.value = agentTriff[i][x]['name']
                                        }
                                    }else{
                                        input.value = agentTriff[i][x]
                                    }
                                    input.classList.add("form-control");
                                    cell.appendChild(input);
                                }
                                
                            }
                            var buttonCell = row.insertCell()
                            var myButton = document.createElement("BUTTON")
                            myButton.classList.add("btn","btn-danger" ,"remove")
                            myButton.setAttribute('type','button')
                            let icon = document.createElement('i')
                            icon.classList.add("fa","fa-trash")
                            myButton.append(icon)
                            buttonCell.appendChild(myButton)
                            importCount++;
                            // list2.push(`<option value='${agentTriff[i].id}'>${agentTriff[i].charge_type} - ${agentTriff[i].unit} - ${agentTriff[i].selling_price} - ${agentTriff[i].cost} - ${agentTriff[i].currency}</option>`);
                        }
                        
                        let agentTriffs = $('#load');
                        agentTriffs.html(list2.join(''));
                        });
                    }
                    
            });
        });
</script>
@endpush


@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="{{route('booking.index')}}">Booking</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Booking Edit</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    <form id="editForm" action="{{route('booking.update',['booking'=>$booking])}}" method="POST">
                            @csrf
                            @method('put')
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                <label for="customer_id">Customer <span style="color: red;">*</span></label>
                                <select class="selectpicker form-control" id="customer_id" data-live-search="true" name="customer_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($customers as $item)
                                        @if($quotation->customer_id != null)
                                            @if(optional($quotation->customer)->CustomerRoles->count() == 1 && optional($quotation->customer)->CustomerRoles->first()->role_id == 6)
                                            <option value="{{$item->id}}" {{$item->id == old('customer_id',$quotation->customer_id) ? 'selected':''}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
                                            @else
                                            <option value="{{$item->id}}" {{$item->id == old('customer_id',$quotation->customer_id) ? 'selected':'disabled'}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
                                            @endif
                                        @else
                                        <option value="{{$item->id}}" {{$item->id == old('customer_id',$quotation->customer_id) ? 'selected':''}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
                                        @endif
                                    @endforeach
                                    </select>
                                        @error('customer_id')
                                        <div style="color: red;">
                                            {{$message}}
                                        </div>
                                        @enderror 
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="equipment_type_id">Equipment Type <span style="color: red;">*</span></label>
                                    <select class="selectpicker form-control" id="equipment_type_id" data-live-search="true" name="equipment_type_id" data-size="10"
                                    title="{{trans('forms.select')}}" disabled>
                                        @foreach ($equipmentTypes as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('equipment_type_id',$booking->equipment_type_id) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                        @error('equipment_type_id')
                                        <div style="color: red;">
                                            {{$message}}
                                        </div>
                                        @enderror
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="status">Booking Status<span style="color: red;">*</span></label>
                                    <select class="selectpicker form-control" data-live-search="true" name="booking_confirm" data-live-search="true">
                                        <option value="1" {{$booking->booking_confirm == old('booking_confirm',$quotation->booking_confirm) ? 'selected':''}}>Confirm</option>
                                        <option value="0" {{$booking->booking_confirm == old('booking_confirm',$quotation->booking_confirm) ? 'selected':''}}>Draft</option>
                                    </select>
                                    @error('booking_confirm')
                                    <div style="color:red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4" style="padding-top: 30px;">
                                    <div class="form-check">
                                        <input type="checkbox" id="soc" name="soc" value="1"  onclick="return false;" readonly {{$quotation->soc == 1 ? 'checked' : ''}}><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px; margin-right: 10px;"> SOC </a>
                                        
                                        <input type="checkbox" id="imo" name="imo" value="1"  onclick="return false;" readonly {{$quotation->imo == 1 ? 'checked' : ''}}><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px; margin-right: 10px;"> IMO </a>
                                        
                                        <input type="checkbox" id="oog" name="oog" value="1"  onclick="return false;" readonly {{$quotation->oog == 1 ? 'checked' : ''}}><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px; margin-right: 10px;"> OOG </a>
                                        
                                        <input type="checkbox" id="rf" name="rf" value="1"  onclick="return false;" readonly {{$quotation->rf == 1 ? 'checked' : ''}}><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px; margin-right: 10px;"> RF </a>
                                    </div>
                                </div>
                            </div> 
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="ffw_id">Forwarder Customer</label>
                                    <select class="selectpicker form-control" id="ffw_id" data-live-search="true" name="ffw_id" data-size="10"
                                    title="{{trans('forms.select')}}">
                                        @foreach ($ffw as $item)
                                            @if($quotation->customer_id != null)
                                                @if(optional($quotation->customer)->CustomerRoles->count() == 1 && optional($quotation->customer)->CustomerRoles->first()->role_id != 6)
                                                <option value="{{$item->id}}" {{$item->id == old('ffw_id',$quotation->customer_id) ? 'selected':''}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
                                                @else
                                                <option value="{{$item->id}}" {{$item->id == old('ffw_id',$quotation->customer_id) ? 'selected':'disabled'}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
                                                @endif
                                            @else
                                            <option value="{{$item->id}}" {{$item->id == old('ffw_id',$quotation->customer_id) ? 'selected':''}}>{{$item->name}} @foreach($item->CustomerRoles as $itemRole) - {{optional($itemRole->role)->name}}@endforeach</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('ffw_id')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div> 
                                <div class="form-group col-md-6">
                                    <label for="bl_release">BL Release <span style="color: red;">*</span></label>
                                    <select class="selectpicker form-control" id="bl_release" data-live-search="true" name="bl_release" data-size="10"
                                    title="{{trans('forms.select')}}">
                                        @foreach ($agents as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('bl_release',$booking->bl_release) ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('bl_release')
                                    <div style="color: red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>   
                            </div>                     
                            <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="place_of_acceptence_id">Place Of Acceptence <span style="color: red;">*</span></label>
                                 <select class="selectpicker form-control" id="place_of_acceptence_id" data-live-search="true" name="place_of_acceptence_id" data-size="10"
                                 title="{{trans('forms.select')}}" disabled>

                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('place_of_acceptence_id',$booking->place_of_acceptence_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('place_of_acceptence_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="load_port_id">Load Port <span style="color: red;">*</span></label>
                                 <select class="selectpicker form-control" id="load_port_id" data-live-search="true" name="load_port_id" data-size="10"
                                 title="{{trans('forms.select')}}" disabled>
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('load_port_id',$booking->load_port_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('load_port_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="shipper_ref_no">Shipper Ref No</label>
                                <input type="text" class="form-control" id="shipper_ref_no" name="shipper_ref_no" value="{{old('shipper_ref_no',$booking->shipper_ref_no)}}"
                                    placeholder="Shipper Ref No" autocomplete="off">
                                @error('shipper_ref_no')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="place_of_delivery_id">Place Of Delivery <span style="color: red;">*</span></label>
                                <select class="selectpicker form-control" id="place_of_delivery_id" data-live-search="true" name="place_of_delivery_id" data-size="10"
                                 title="{{trans('forms.select')}}" disabled>
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('place_of_delivery_id',$booking->place_of_delivery_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('place_of_delivery_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="discharge_port_id">Discharge Port <span style="color: red;">*</span></label>
                                <select class="selectpicker form-control" id="discharge_port_id" data-live-search="true" name="discharge_port_id" data-size="10"
                                 title="{{trans('forms.select')}}" disabled>
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('discharge_port_id',$booking->discharge_port_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('discharge_port_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="forwarder_ref_no">Forwarder Ref No</label>
                                <input type="text" class="form-control" id="forwarder_ref_no" name="forwarder_ref_no" value="{{old('forwarder_ref_no',$booking->forwarder_ref_no)}}"
                                    placeholder="Forwarder Ref No" autocomplete="off">
                                @error('forwarder_ref_no')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="voyage_id">First Vessel / Voyage <span style="color: red;">*</span></label>
                                <select class="selectpicker form-control" id="voyage_id" data-live-search="true" name="voyage_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($voyages as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('voyage_id',$booking->voyage_id) ? 'selected':''}}>{{$item->vessel->name}} / {{$item->voyage_no}}</option>
                                    @endforeach
                                </select>
                                @error('voyage_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="voyage_id_second">Second Vessel / Voyage</label>
                                <select class="selectpicker form-control" id="voyage_id_second" data-live-search="true" name="voyage_id_second" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($voyages as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('voyage_id_second',$booking->voyage_id_second) ? 'selected':''}}>{{$item->vessel->name}} / {{$item->voyage_no}}</option>
                                    @endforeach
                                </select>
                                @error('voyage_id_second')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="terminal_id">Discharge Terminal <span style="color: red;">*</span></label>
                                <select class="form-control" id="terminal" data-live-search="true" name="terminal_id" data-size="10"
                                 title="{{trans('forms.select')}}" disabled>
                                    @foreach ($terminals as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('terminal_id',$booking->terminal_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('terminal_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="discharge_etd">Discharge ETD</label>
                                <input type="date" class="form-control" id="discharge_etd" name="discharge_etd" value="{{old('discharge_etd',$booking->discharge_etd)}}"
                                    placeholder="Discharge ETD" autocomplete="off">
                                @error('discharge_etd')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="load_port_cutoff">Load Port Cutoff</label>
                                <input type="date" class="form-control" id="load_port_cutoff" name="load_port_cutoff" value="{{old('load_port_cutoff',$booking->load_port_cutoff)}}"
                                    placeholder="Load Port Cutoff" autocomplete="off">
                                @error('load_port_cutoff')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="load_port_dayes">Load Port Days</label>
                                <input type="text" class="form-control" id="load_port_dayes" name="load_port_dayes" value="{{old('load_port_dayes',$booking->load_port_dayes)}}"
                                    placeholder="Load Port Days" autocomplete="off">
                                @error('load_port_dayes')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="tariff_service">Tariff Service</label>
                                <input type="text" class="form-control" id="tariff_service" name="tariff_service" value="{{old('tariff_service',$booking->tariff_service)}}"
                                    placeholder="Tariff Service" autocomplete="off" disabled>
                                @error('tariff_service')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="commodity_code">Commodity Code</label>
                                <input type="text" class="form-control" id="commodity_code" name="commodity_code" value="{{old('commodity_code',$booking->commodity_code)}}"
                                    placeholder="Commodity Code" autocomplete="off">
                                @error('commodity_code')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="commodity_description">Commodity Description <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="commodity_description" name="commodity_description" value="{{old('commodity_description')}}"
                                    placeholder="Commodity Description" autocomplete="off">
                                @error('commodity_description')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <h4>Container Details</h4>
                            <table id="containerDetails" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Seal No</th>
                                        <th class="text-center">Container Type</th>
                                        <th class="text-center">QTY</th>
                                        <th class="text-center">Container No</th>
                                        <th class="text-center">HAZ / Reefer/ OOG Details / Haz Approval Ref</th>
                                        <th class="text-center">
                                            <a id="add"> Add Container <i class="fas fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($booking_details as $key => $item)
                                <tr>
                                        <input type="hidden" value ="{{ $item->id }}" name="containerDetails[{{ $key }}][id]">
                                    <td>
                                        <input type="text" id="seal_no" name="containerDetails[{{ $key }}][seal_no]" class="form-control" autocomplete="off" placeholder="Seal No" value="{{old('seal_no',$item->seal_no)}}"> 
                                    </td>

                                    <td class="containerDetailsID">  
                                        <select class="form-control" id="container_type" data-live-search="true" name="containerDetails[{{ $key }}][container_type]" data-size="10"
                                                title="{{trans('forms.select')}}" >
                                                @foreach ($equipmentTypes as $equipmentType)
                                                @if($quotation->equipment_type_id != null)
                                                <option value="{{$equipmentType->id}}" {{$equipmentType->id == old('container_type',$quotation->equipment_type_id) ? 'selected':'disabled'}}>{{$equipmentType->name}}</option>
                                                @else
                                                <option value="{{$equipmentType->id}}" {{$equipmentType->id == old('equipment_type_id',$quotation->equipment_type_id) ? 'selected':''}}>{{$equipmentType->name}}</option>
                                                @endif                                                
                                            @endforeach
                                        </select>
                                    </td>

                                    <td>
                                        <input type="text" id="etaInput" name="containerDetails[{{ $key }}][qty]" class="form-control input"  autocomplete="off" placeholder="QTY" value="{{old('qty',$item->qty)}}">
                                    </td>

                                    <td class="containerDetailsID">
                                        <select class="selectpicker form-control" id="containerDetailsID" data-live-search="true" name="containerDetails[{{ $key }}][container_id]" data-size="10"
                                                title="{{trans('forms.select')}}">
                                                <option value="000" selected>Select</option>
                                                @foreach ($containers as $container)
                                                    <option value="{{$container->id}}" {{$container->id == old('container_id',$item->container_id) ? 'selected':''}}>{{$container->code}}</option>
                                                @endforeach
                                        </select>
                                    </td>
                                    <td>
                                    <input type="text" id="haz" name="containerDetails[{{ $key }}][haz]" class="form-control" autocomplete="off" placeholder="HAZ / REEFER/ OOG DETAILS / HAZ APPROVAL REF" value="{{old('haz',$item->haz)}}">      
                                    </td>
                                    <td style="width:85px;">
                                        <button type="button" class="btn btn-danger remove" onclick="removeItem({{$item->id}})"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <input name="removed" id="removed" type="hidden"  value="">

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary mt-3">{{trans('forms.edit')}}</button>
                                    <a href="{{route('booking.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
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
    console.log(removed);
    document.getElementById("removed").value = removed;
}
$(document).ready(function(){
    $("#containerDetails").on("click", ".remove", function () {
    $(this).closest("tr").remove();
    });
    var counter  = <?= isset($key)? ++$key : 0 ?>;

    $("#add").click(function(){
            var tr = '<tr>'+
                '<td><input type="text" name="containerDetails['+counter+'][seal_no]" class="form-control" autocomplete="off" placeholder="Seal No"></td>'+
                '<td><select class="form-control" data-live-search="true" name="containerDetails['+counter+'][container_type]" data-size="10">@foreach ($equipmentTypes as $item)@if($quotation->equipment_type_id != null)<option value="{{$item->id}}" {{$item->id == old('container_type',$quotation->equipment_type_id) ? 'selected':'disabled'}}>{{$item->name}}</option>@else<option value="{{$item->id}}" {{$item->id == old('equipment_type_id',$quotation->equipment_type_id) ? 'selected':''}}>{{$item->name}}</option> @endif @endforeach</select></td>'+
                '<td><input type="text" name="containerDetails['+counter+'][qty]" class="form-control input" autocomplete="off" placeholder="QTY"></td>'+
                '<td class="containerDetailsID"><select id="containerDetailsID" class="form-control" data-live-search="true" name="containerDetails['+counter+'][container_id]" data-size="10"><option value="000">Select</option>@foreach ($containers as $item)<option value="{{$item->id}}">{{$item->code}}</option>@endforeach</select></td>'+
                '<td><input type="text" value="{{$quotation->oog_dimensions}}" name="containerDetails['+counter+'][haz]" class="form-control" autocomplete="off"></td>'+
                '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>'
        '</tr>';
        counter++;
        $('#containerDetails').append(tr);
    });
});
$(function(){
            $('#discharge_port_id').on('change',function(e){
                let value = e.target.value;
                let response =    $.get(`/api/master/terminals/${value}`).then(function(data){
                    let terminals = data.terminals || '';
                    let list2 = [`<option value=''>Select...</option>`];
                    for(let i = 0 ; i < terminals.length; i++){
                        list2.push(`<option value='${terminals[i].id}'>${terminals[i].name} </option>`);
                    }
            let terminal = $('#terminal');
            terminal.html(list2.join(''));
            });
        });
    });
    $(function(){
            $('#containerDetails').on('change','td.containerDetailsID select' ,function(e){
                let self = $(this);
                let parent = self.closest("tr");
                let name = e.target.name;
                let value = e.target.value;
                if(value == 000){
                    $(".input", parent).removeAttr('readonly');
                }else{
                    let valueee = 1;
                    $(".input", parent).val(valueee);
                    $(".input", parent).attr('readonly', true);
                }
        });
    });
    
</script>
@endpush

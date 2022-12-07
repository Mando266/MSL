@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="{{route('bldraft.index')}}">BL Draft</a></li> 
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Create New BL Draft</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                    <form id="createForm" action="{{route('bldraft.store')}}" method="POST">
                            @csrf
                        <div class="form-row">
                            <input type="hidden" value="{{$booking->id}}" name="booking_id">
                            <div class="form-group col-md-4">
                                <label for="customer_id">Customer Shipper <span style="color: red;">*</span></label>
                                <select class="selectpicker form-control" id="customer_id" data-live-search="true" name="customer_id" data-size="10"
                                 title="{{trans('forms.select')}}" disabled>
                                    @foreach ($customershipper as $item)
                                        @if($booking->customer_id != null)
                                        <option value="{{$item->id}}" {{$item->id == old('customer_id',$booking->customer_id) ? 'selected':'disabled'}}>{{$item->name}}</option>

                                        @endif
                                    @endforeach
                                </select>
                                @error('customer_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div> 
                            <div class="form-group col-md-8">
                                <label for="customer_id">Customer Shipper Details</label>
                                @if($booking->customer_id != null)
                                    <textarea class="form-control"  name="customer_shipper_details"
                                    placeholder="Customer Shipper Details" autocomplete="off">Phone : {{optional($booking->customer)->phone}} - Email : {{optional($booking->customer)->email}} - Address : {{optional($booking->customer)->address}}</textarea>
                                @endif
                            </div> 
                            <!-- <div class="form-group col-md-4">
                                <label for="customer_id">Customer Phone <span style="color: red;">*</span></label>
                                @if($booking->customer_id != null)
                                    <input type="text" class="form-control" id="customer_phone" name="customer_phone" value="{{optional($booking->customer)->phone}}"
                                    placeholder="Customer Phone" autocomplete="off">
                                @endif
                            </div> 
                            <div class="form-group col-md-4">
                                <label for="customer_id">Customer Address <span style="color: red;">*</span></label>
                                @if($booking->customer_id != null)
                                    <input type="text" class="form-control" id="customer_address" name="customer_address" value="{{optional($booking->customer)->address}}"
                                    placeholder="Customer Address" autocomplete="off">
                                @endif
                            </div>  -->
                        </div> 
                    <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="customer_id">Customer Consignee <span style="color: red;">*</span></label>
                                <select class="selectpicker form-control" id="customerconsignee" data-live-search="true" name="customer_consignee_id" data-size="10"
                                 title="{{trans('forms.select')}}" >
                                    @foreach ($customersConsignee as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('customer_consignee_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('customer_consignee_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div> 
                            <div class="form-group col-md-8">
                                <label for="customer_id">Customer Consignee Details</label>
                                    <textarea id="consignee" class="form-control"  name="customer_shipper_details"
                                    placeholder="Customer Consignee Details" autocomplete="off"></textarea>
                            </div> 
                    </div>
                    <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="customer_id">Customer Notifiy <span style="color: red;">*</span></label>
                                <select class="selectpicker form-control" id="customernotifiy" data-live-search="true" name="customer_notifiy_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($customersNotifiy as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('customer_notifiy_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('customer_notifiy_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-8">
                                <label for="customer_id">Customer Notifiy Details</label>
                                    <textarea id="notifiy" class="form-control"  name="customer_shipper_details"
                                    placeholder="Customer Notifiy Details" autocomplete="off"></textarea>
                            </div> 
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="place_of_acceptence_id">Place Of Acceptence <span style="color: red;">*</span></label>
                                <select class="selectpicker form-control" id="place_of_acceptence_id" data-live-search="true" name="place_of_acceptence_id" data-size="10"
                                title="{{trans('forms.select')}}" disabled>
                                @foreach ($ports as $item)
                                    @if($booking->place_of_acceptence_id != null)
                                    <option value="{{$item->id}}" {{$item->id == old('place_of_acceptence_id',$booking->place_of_acceptence_id) ? 'selected':'disabled'}}>{{$item->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('place_of_acceptence_id')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="load_port_id">Load Port <span style="color: red;">*</span></label>
                            <select class="selectpicker form-control" id="load_port_id" data-live-search="true" name="load_port_id" data-size="10"
                                title="{{trans('forms.select')}}" disabled>
                                @foreach ($ports as $item)
                                    @if($booking->load_port_id != null)
                                    <option value="{{$item->id}}" {{$item->id == old('load_port_id',$booking->load_port_id) ? 'selected':'disabled'}}>{{$item->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('load_port_id')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="place_of_delivery_id">Place Of Delivery <span style="color: red;">*</span></label>
                            <select class="selectpicker form-control" id="place_of_delivery_id" data-live-search="true" name="place_of_delivery_id" data-size="10"
                                title="{{trans('forms.select')}}" disabled>
                                @foreach ($ports as $item)
                                    @if($booking->place_of_delivery_id != null)
                                    <option value="{{$item->id}}" {{$item->id == old('place_of_delivery_id',$booking->place_of_delivery_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('place_of_delivery_id')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="discharge_port_id">Discharge Port <span style="color: red;">*</span></label>
                            <select class="selectpicker form-control" id="discharge_port_id" data-live-search="true" name="discharge_port_id" data-size="10"
                                title="{{trans('forms.select')}}" disabled>
                            
                                @foreach ($ports as $item)
                                @if($booking->discharge_port_id != null)
                                    <option value="{{$item->id}}" {{$item->id == old('discharge_port_id',$booking->discharge_port_id) ? 'selected':'disabled'}}>{{$item->name}}</option>
                                @endif
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
                        <div class="form-group col-md-3">
                            <label for="equipment_type_id">Equipment Type <span style="color: red;">*</span></label>
                                <select class="selectpicker form-control" id="equipment_type_id" data-live-search="true" name="equipment_type_id" data-size="10"
                                title="{{trans('forms.select')}}" disabled>
                                @foreach ($equipmentTypes as $item)
                                    @if($booking->equipment_type_id != null)
                                    <option value="{{$item->id}}" {{$item->id == old('equipment_type_id',$booking->equipment_type_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('equipment_type_id')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div> 
                        <div class="form-group col-md-3">
                            <label for="voyage_id">Vessel / Voyage <span style="color: red;">*</span></label>
                            <select class="selectpicker form-control" id="voyage_id" data-live-search="true" name="voyage_id" data-size="10"
                                title="{{trans('forms.select')}}" disabled>
                                @foreach ($voyages as $item)
                                    @if($booking->voyage_id != null)
                                        <option value="{{$item->id}}" {{$item->id == old('voyage_id',$booking->voyage_id) ? 'selected':''}}>{{$item->vessel->name}} / {{$item->voyage_no}}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('voyage_id')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="notes">DESCRIPTION</label>
                            <textarea name="descripions" class="form-control" placeholder="Descripion" autocomplete="off"></textarea>
                            @error('notes')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="notes">Date Of Issue</label>
                            <input type="date" name="date_of_issue" class="form-control" placeholder="Date Of Issue" autocomplete="off">
                            @error('date_of_issue')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group col-md-3">
                            <label for="notes">Declered Value</label>
                            <input type="text" name="declerd_value" class="form-control" placeholder="Declered Value" autocomplete="off">
                            @error('declerd_value')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-3">
                                <label for="status">Movement</label>
                                <select class="selectpicker form-control" data-live-search="true" name="movement" title="{{trans('forms.select')}}">
                                    <option value="FCL/FCL">FCL/FCL</option>
                                </select>
                                @error('movement')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                        </div>
                        <div class="form-group col-md-3">
                            <label for="notes">Number Of Original Bills</label>
                            <input type="text" name="number_of_original" class="form-control" placeholder="Number Of Original Bills" autocomplete="off">
                            @error('number_of_original')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                                <label for="status">Bl Kind</label>
                                <select class="selectpicker form-control" data-live-search="true" name="bl_kind" title="{{trans('forms.select')}}">
                                    <option value="Original">Original</option>
                                    <option value="Seaway BL">Seaway BL</option>
                                </select>
                                @error('bl_kind')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                        </div>
                    </div>

                    <table id="blDraft" class="table table-bordered">
                                <thead>
                                    <tr>
                                    <th class="text-center">Container</th>
                                        <th class="text-center">Seal No.s</th>
                                        <th class="text-center">Packs</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">Weight Kgs</th>
                                        <th class="text-center">Measure cbm</th>
                                    </tr>
                                </thead>
                                <tbody>
                            @foreach($booking_containers as $bookingContainer)
                            <tr>
                                <td>
                                  <select class="selectpicker form-control" id="containerDetailsID" data-live-search="true" name="blDraftdetails[0][container_id]" data-size="10"
                                          title="{{trans('forms.select')}}">
                                          <option value="">Select</option>
                                          @foreach ($containers as $item)
                                              <option value="{{$item->id}}" {{$item->id == old('container_id',$bookingContainer->container->id) ? 'selected':'disabled'}}>{{$item->code}}</option>
                                          @endforeach
                                  </select>
                                </td>
                                <td>
                                    <input type="text" id="seal_no" value="{{$bookingContainer->seal_no}}" name="blDraftdetails[0][seal_no]" class="form-control" autocomplete="off" placeholder="Seal No.S" readonly>
                                </td>
             
                                <td>
                                    <input type="text" id="Packs" name="blDraftdetails[0][packs]" class="form-control input"  autocomplete="off" placeholder="Packs" required>
                                </td>

                                <td>
                                    <input type="text" id="description" name="blDraftdetails[0][description]" class="form-control input"  autocomplete="off" placeholder="Description">
                                </td>

                                <td>
                                    <input type="text" id="gross_weight" name="blDraftdetails[0][gross_weight]" class="form-control input"  autocomplete="off" placeholder="Gross Weight" required>
                                </td>

                                <td>
                                    <input type="text" id="measurement" name="blDraftdetails[0][measurement]" class="form-control input"  autocomplete="off" placeholder="Measurement" required>
                                </td>
                            </tr>
                            @endforeach
                            @for($i=0 ; $i < $booking_qyt ; $i++)
                            <tr>
                                <td>
                                  <select class="selectpicker form-control" id="containerDetailsID" data-live-search="true" name="blDraftdetails[0][container_id]" data-size="10"
                                          title="{{trans('forms.select')}}">
                                          <option value="">Select</option>
                                          @foreach ($containers as $item)
                                              <option value="{{$item->id}}" {{$item->id == old('container_id') ? 'selected':''}}>{{$item->code}}</option>
                                          @endforeach
                                  </select>
                                </td>
                                <td>
                                    <input type="text" id="seal_no" name="blDraftdetails[0][seal_no]" class="form-control" autocomplete="off" placeholder="Seal No.S">
                                </td>
             
                                <td>
                                    <input type="text" id="Packs" name="blDraftdetails[0][packs]" class="form-control input"  autocomplete="off" placeholder="Packs" required>
                                </td>

                                <td>
                                    <input type="text" id="description" name="blDraftdetails[0][description]" class="form-control input"  autocomplete="off" placeholder="Description">
                                </td>

                                <td>
                                    <input type="text" id="gross_weight" name="blDraftdetails[0][gross_weight]" class="form-control input"  autocomplete="off" placeholder="Gross Weight" required>
                                </td>

                                <td>
                                    <input type="text" id="measurement" name="blDraftdetails[0][measurement]" class="form-control input"  autocomplete="off" placeholder="Measurement" required>
                                </td>
                            </tr>
                            @endfor

                            </tbody>
                        </table>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
                                    <a href="{{route('booking.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                                </div>
                           </div>
            </div> 
        </div> 
    </div> 
</div>                 
@endsection
@push('scripts')
<script>
    $('#createForm').submit(function() {
        $('select').removeAttr('disabled');
    });
</script>
<script>
        $(function(){
                let customerconsignee = $('#customerconsignee');
                $('#customerconsignee').on('change',function(e){
                    let value = e.target.value;
                    let response =    $.get(`/api/master/customers/${customerconsignee.val()}`).then(function(data){
                        let consIgnees = data.customer[0] ;
                    let consignee = $('#consignee').val('Phone :' + ' ' + consIgnees.phone + ' ' + '-' + ' ' +'Email :' +' ' + consIgnees.email +  ' ' + '-' + ' ' + 'Address Line1 :' +' ' + consIgnees.address + ' ' + '-' + ' ' + 'Address Line2 :' +' ' + consIgnees.cust_address + ' ' + '-' + ' ' +' Fax :' +' ' + consIgnees.fax);
                    consignee.html(list2.join(''));
                });
            });
        });
</script>

<script>
        $(function(){
                let customernotifiy = $('#customernotifiy');
                $('#customernotifiy').on('change',function(e){
                    let value = e.target.value;
                    let response =    $.get(`/api/master/customers/${customernotifiy.val()}`).then(function(data){
                        let notIfiy = data.customer[0] ;
                        let notifiy = $('#notifiy').val('Phone :' + ' ' + notIfiy.phone + ' ' + '-' + ' ' +'Email :' +' ' + notIfiy.email +  ' ' + '-' + ' ' + 'Address Line1 :' +' ' + notIfiy.address + ' ' + '-' + ' ' + 'Address Line2 :' +' ' + notIfiy.cust_address + ' ' + '-' + ' ' +' Fax :' +' ' + notIfiy.fax);
                    notifiy.html(list2.join(''));
                });
            });
        });
</script>
@endpush
                         
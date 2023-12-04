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
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">New Invoice</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="widget-content widget-content-area">

                        <form id="createForm" action="{{ route('invoice.store_invoice') }}" method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <input type="hidden" name="bldraft_id" value="{{request()->input('bldraft_id')}}">
                                <div class="form-group col-md-6">
                                    <label for="customer">Customer<span
                                                class="text-warning"> * (Required.) </span></label>
                                    <select class="selectpicker form-control" name="customer_id" id="customer"
                                            data-live-search="true" data-size="10"
                                            title="{{trans('forms.select')}}" required>
                                        @if($bldraft != null)
                                            @if(optional($bldraft->booking->forwarder)->name != null)
                                                <option value="{{optional($bldraft->booking)->ffw_id}}">{{ optional($bldraft->booking->forwarder)->name }}
                                                    Forwarder
                                                </option>
                                            @elseif(optional($bldraft->booking->consignee)->name != null)
                                                <option value="{{optional($bldraft->booking)->customer_consignee_id}}">{{ optional($bldraft->booking->consignee)->name }}
                                                    Consignee
                                                </option>
                                            @endif
                                            @if(optional($bldraft->customerNotify)->name != null)
                                                <option value="{{optional($bldraft)->customer_notifiy_id}}">{{ optional($bldraft->customerNotify)->name }}
                                                    Notify
                                                </option>
                                            @endif
                                            <option value="{{optional($bldraft)->customer_id}}">{{ optional($bldraft->customer)->name }}
                                                Shipper
                                            </option>
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
                                    <input type="text" id="notifiy" class="form-control" name="customer"
                                           placeholder="Customer Name" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Place Of Acceptence</label>
                                    @if(optional($bldraft)->place_of_acceptence_id != null)
                                        <input type="text" class="form-control" placeholder="Place Of Acceptence"
                                               autocomplete="off"
                                               value="{{(optional($bldraft->placeOfAcceptence)->code)}}"
                                               style="background-color:#fff" disabled>
                                    @endif
                                </div>

                                <div class="form-group col-md-3">
                                    <label>Load Port</label>
                                    @if(optional($bldraft)->load_port_id != null)
                                        <input type="text" class="form-control" placeholder="Load Port"
                                               autocomplete="off" value="{{(optional($bldraft->loadPort)->code)}}"
                                               style="background-color:#fff" disabled>
                                    @endif
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="Date">Booking Ref</label>
                                    @if(optional($bldraft)->booking_id != null)
                                        <input type="text" class="form-control" placeholder="Booking Ref"
                                               autocomplete="off" value="{{(optional($bldraft->booking)->ref_no)}}"
                                               style="background-color:#fff" disabled>
                                    @endif
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="voyage_id">Vessel / Voyage </label>
                                    <select class="selectpicker form-control" id="voyage_id" name="voyage_id"
                                            data-live-search="true" data-size="10"
                                            title="{{trans('forms.select')}}" disabled>
                                        @foreach ($voyages as $item)
                                            @if(optional($bldraft)->voyage_id != null && optional($bldraft->booking)->transhipment_port == null)
                                                <option value="{{$item->id}}" {{$item->id == old('voyage_id',$bldraft->voyage_id) ? 'selected':''}}>{{$item->vessel->name}}
                                                    / {{$item->voyage_no}} - {{ optional($item->leg)->name }}</option>
                                            @elseif(optional($bldraft->booking)->voyage_id_second != null && optional($bldraft->booking)->transhipment_port != null)
                                                <option value="{{$item->id}}" {{$item->id == old('voyage_id',$bldraft->booking->voyage_id_second) ? 'selected':''}}>{{$item->vessel->name}}
                                                    / {{$item->voyage_no}} - {{ optional($item->leg)->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Discharge Port</label>
                                    @if(optional($bldraft)->discharge_port_id != null)
                                        <input type="text" class="form-control" placeholder="Discharge Port"
                                               autocomplete="off" value="{{(optional($bldraft->dischargePort)->code)}}"
                                               style="background-color:#fff" disabled>
                                    @endif
                                </div>

                                <div class="form-group col-md-3">
                                    <label>Port of Delivery</label>
                                    @if(optional($bldraft)->place_of_delivery_id != null)
                                        <input type="text" class="form-control" placeholder="Port of Delivery"
                                               autocomplete="off"
                                               value="{{(optional($bldraft->placeOfDelivery)->code)}}"
                                               style="background-color:#fff" disabled>
                                    @endif
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Equipment Type</label>
                                    @if(optional($bldraft)->equipment_type_id != null)
                                        <input type="text" class="form-control" placeholder="Equipment Type"
                                               name="bl_kind" autocomplete="off"
                                               value="{{(optional($bldraft->equipmentsType)->name)}}"
                                               style="background-color:#fff" disabled>
                                    @endif
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="status">Invoice Status<span class="text-warning"> * </span></label>
                                    <select class="form-control" data-live-search="true" name="invoice_status"
                                            title="{{trans('forms.select')}}" required>
                                            <option value="draft">Draft</option>
                                            @if(Auth::user()->id == 2 || Auth::user()->id == 3)
                                            <option value="ready_confirm">Ready To Confirm</option>
                                            @elseif($bldraft->bl_status == 1 && Auth::user()->id == 15)
                                                <option value="confirm">Confirm</option>
                                            @endif
                                    </select>
                                    @error('invoice_status')
                                    <div style="color:red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="Date">Date</label>
                                    <input type="date" class="form-control" name="date" placeholder="Date"
                                           autocomplete="off" required value="{{old('date',date('Y-m-d'))}}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>QTY</label>
                                    <input type="text" class="form-control" placeholder="Qty" name="qty"
                                           autocomplete="off" value="{{$qty}}" style="background-color:#fff" disabled>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>TAX Hold</label>
                                    <input type="text" class="form-control" placeholder="TAX %" name="tax_discount"
                                           autocomplete="off" style="background-color:#fff" value="0">
                                </div>
                                <div class="col-md-2 form-group ">
                                    <div style="padding: 30px;">
                                        <input class="form-check-input" type="radio" name="exchange_rate"
                                               id="exchange_rate" value="eta" checked>
                                        <label class="form-check-label" for="exchange_rate">
                                            @if(optional($bldraft)->voyage_id != null && optional($bldraft->booking)->transhipment_port == null)
                                                ETA Rate {{ optional($bldraft->voyage)->exchange_rate }}
                                            @elseif(optional($bldraft->booking)->voyage_id_second != null && optional($bldraft->booking)->transhipment_port != null)
                                                ETA
                                                Rate {{ optional(optional($bldraft->booking)->secondvoyage)->exchange_rate }}
                                            @endif
                                        </label>
                                        <br>
                                        <input class="form-check-input" type="radio" name="exchange_rate"
                                               id="exchange_rate" value="etd">
                                        <label class="form-check-label" for="exchange_rate">
                                            @if(optional($bldraft)->voyage_id != null && optional($bldraft->booking)->transhipment_port == null)
                                                ETD Rate {{ optional($bldraft->voyage)->exchange_rate_etd }}
                                            @elseif(optional($bldraft->booking)->voyage_id_second != null && optional($bldraft->booking)->transhipment_port != null)
                                                ETD
                                                Rate {{optional( optional($bldraft->booking)->secondvoyage)->exchange_rate_etd }}
                                            @endif
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <div style="padding: 30px;">
                                        <input class="form-check-input" type="radio" name="add_egp" id="add_egp"
                                               value="true" checked>
                                        <label class="form-check-label" for="add_egp">
                                            EGP AND USD
                                        </label>
                                        <br>
                                        <input class="form-check-input" type="radio" name="add_egp" id="add_egp"
                                               value="false">
                                        <label class="form-check-label" for="add_egp">
                                            USD
                                        </label>
                                        <br>
                                        <input class="form-check-input" type="radio" name="add_egp" id="add_egp"
                                               value="onlyegp">
                                        <label class="form-check-label" for="add_egp">
                                            EGP
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-3 form-group">
                                    <label> VAT % </label>
                                    <input type="text" class="form-control" placeholder="VAT %" name="vat"
                                           autocomplete="off" value="0" style="background-color:#fff" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12 form-group">
                                    <label> Notes </label>
                                    <textarea class="form-control" name="notes">
                                        {{ isset($notes) ? implode("\n", $notes) : '' }}
                                    </textarea>
                                </div>
                            </div>
                            <h4>Charges</h4>
                            <table id="charges" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center">Charge Description</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Add VAT</th>
                                    <th class="text-center">Multiply QTY</th>
                                    <th class="text-center">TOTAL USD</th>
                                    <th class="text-center">USD After VAT</th>
                                    <th class="text-center">Total Egp</th>
                                    <th class="text-center">EGP After VAT</th>
                                    <th class="text-center"><a id="add"> Add <i class="fas fa-plus"></i></a>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!isset($total_storage))
                                    @isset($notes)
                                        <tr>
                                            <td>
                                                <select class="selectpicker form-control"
                                                        id="Charge Description" data-live-search="true"
                                                        name="invoiceChargeDesc[0][charge_description]"
                                                        data-size="10"
                                                        title="{{trans('forms.select')}}" disabled>
                                                    <option value="{{ $chargeName }}"
                                                            selected>{{ $chargeName }}</option>
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control" id="size_small"
                                                       name="invoiceChargeDesc[0][size_small]"
                                                       value="{{ $storageAmount }}"
                                                       placeholder="Amount" autocomplete="off" disabled
                                                       style="background-color: white;">
                                            </td>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                           name="invoiceChargeDesc[0][add_vat]"
                                                           id="item_0_enabled_yes" value="1">
                                                    <label class="form-check-label"
                                                           for="item_0_enabled_yes">Yes</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                           name="invoiceChargeDesc[0][add_vat]"
                                                           id="item_0_enabled_no" value="0" checked>
                                                    <label class="form-check-label"
                                                           for="item_0_enabled_no">No</label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                           name="invoiceChargeDesc[0][enabled]"
                                                           id="item_0_enabled_yes" value="1"
                                                           disabled>
                                                    <label class="form-check-label"
                                                           for="item_0_enabled_yes">Yes</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                           name="invoiceChargeDesc[0][enabled]"
                                                           id="item_0_enabled_no" value="0"
                                                           checked disabled>
                                                    <label class="form-check-label"
                                                           for="item_0_enabled_no">No</label>
                                                </div>
                                            </td>
                                            <td><input type="text" class="form-control" id="ofr"
                                                       name="invoiceChargeDesc[0][total]"
                                                       value="{{ $storageAmount }}"
                                                       placeholder="Total" autocomplete="off" disabled
                                                       style="background-color: white;">
                                            </td>

                                            <td><input type="text" name="invoiceChargeDesc[0][usd_vat]"
                                                       class="form-control" autocomplete="off"
                                                       placeholder="USD After VAT" disabled></td>

                                            <td><input type="text" class="form-control" id="ofr"
                                                       name="invoiceChargeDesc[0][egy_amount]"
                                                       value=""
                                                       placeholder="Egp Amount  " autocomplete="off"
                                                       disabled style="background-color: white;" disabled>
                                            </td>
                                            <td><input type="text" name="invoiceChargeDesc[0][egp_vat]"
                                                       class="form-control" autocomplete="off"
                                                       placeholder="Egp After VAT" disabled></td>

                                            <td style="width:85px;">
                                                <button type="button" class="btn btn-danger remove"><i
                                                            class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @else
                                        @foreach($triffDetails->triffPriceDetailes ?? [] as $key => $detail)
                                            <tr>
                                                <td>
                                                    <select class="selectpicker form-control"
                                                            id="Charge Description" data-live-search="true"
                                                            name="invoiceChargeDesc[{{ $key }}][charge_description]"
                                                            data-size="10"
                                                            title="{{trans('forms.select')}}" disabled>
                                                        @foreach ($charges as $item)
                                                            <option value="{{$item->name}}" {{$detail->charge_type == old('charge_description',$item->id) ? 'selected':''}}>{{$item->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control" id="size_small"
                                                           name="invoiceChargeDesc[{{ $key }}][size_small]"
                                                           value="{{ $detail->selling_price }}"
                                                           placeholder="Amount" autocomplete="off" disabled
                                                           style="background-color: white;">
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                               name="invoiceChargeDesc[{{$key}}][add_vat]"
                                                               id="item_{{$key}}_enabled_yes" value="1">
                                                        <label class="form-check-label"
                                                               for="item_{{$key}}_enabled_yes">Yes</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                               name="invoiceChargeDesc[{{$key}}][add_vat]"
                                                               id="item_{{$key}}_enabled_no" value="0" checked>
                                                        <label class="form-check-label"
                                                               for="item_{{$key}}_enabled_no">No</label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                               name="invoiceChargeDesc[{{$key}}][enabled]"
                                                               id="item_{{$key}}_enabled_yes" value="1"
                                                               {{ $detail->unit == "Container" ? 'checked' : ''}} disabled>
                                                        <label class="form-check-label"
                                                               for="item_{{$key}}_enabled_yes">Yes</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                               name="invoiceChargeDesc[{{$key}}][enabled]"
                                                               id="item_{{$key}}_enabled_no" value="0"
                                                               {{ $detail->unit == "Document" ? 'checked' : ''}} disabled>
                                                        <label class="form-check-label"
                                                               for="item_{{$key}}_enabled_no">No</label>
                                                    </div>
                                                </td>
                                                @if($detail->unit == "Container")
                                                    <td><input type="text" class="form-control" id="ofr"
                                                               name="invoiceChargeDesc[{{ $key }}][total]"
                                                               value="{{$detail->selling_price * $qty}}"
                                                               placeholder="Total" autocomplete="off" disabled
                                                               style="background-color: white;">
                                                    </td>
                                                @elseif($detail->unit == "Document")
                                                    <td><input type="text" class="form-control" id="ofr"
                                                               name="invoiceChargeDesc[{{ $key }}][total]"
                                                               value="{{$detail->selling_price}}"
                                                               placeholder="Total" autocomplete="off" disabled
                                                               style="background-color: white;">
                                                    </td>
                                                @endif
                                                <td><input type="text"
                                                           name="invoiceChargeDesc[{{ $key }}][usd_vat]"
                                                           class="form-control" autocomplete="off"
                                                           placeholder="USD After VAT" disabled></td>
                                                @if($detail->unit == "Container")
                                                    <td><input type="text" class="form-control" id="ofr"
                                                               name="invoiceChargeDesc[{{ $key }}][egy_amount]"
                                                               value="{{$detail->selling_price * $qty * optional($bldraft->voyage)->exchange_rate }}"
                                                               placeholder="Egp Amount  " autocomplete="off"
                                                               disabled style="background-color: white;"
                                                               disabled>
                                                    </td>
                                                @elseif($detail->unit == "Document")
                                                    <td><input type="text" class="form-control" id="ofr"
                                                               name="invoiceChargeDesc[{{ $key }}][egy_amount]"
                                                               value="{{$detail->selling_price * optional($bldraft->voyage)->exchange_rate}}"
                                                               placeholder="Egp Amount  " autocomplete="off"
                                                               disabled style="background-color: white;"
                                                               disabled>
                                                    </td>
                                                @endif
                                                <td><input type="text"
                                                           name="invoiceChargeDesc[{{ $key }}][egp_vat]"
                                                           class="form-control" autocomplete="off"
                                                           placeholder="Egp After VAT" disabled></td>

                                                <td style="width:85px;">
                                                    <button type="button" class="btn btn-danger remove"><i
                                                                class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset

                                @else
                                    <tr>
                                        <td>
                                            <input type="text" id="Charge Description"
                                                   name="invoiceChargeDesc[0][charge_description]"
                                                   class="form-control" autocomplete="off"
                                                   placeholder="Charge Description" value="Storage">
                                        </td>
                                        <td><input type="text" class="form-control" id="size_small"
                                                   name="invoiceChargeDesc[0][size_small]"
                                                   value="{{ $total_storage }}"
                                                   placeholder="Amount" autocomplete="off" disabled
                                                   style="background-color: white;">
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                       name="invoiceChargeDesc[0][add_vat]"
                                                       id="item_0_enabled_yes" value="1" disabled>
                                                <label class="form-check-label"
                                                       for="item_0_enabled_yes">Yes</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                       name="invoiceChargeDesc[0][add_vat]"
                                                       id="item_0_enabled_no" value="0" checked disabled>
                                                <label class="form-check-label"
                                                       for="item_0_enabled_no">No</label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                       name="invoiceChargeDesc[0][enabled]"
                                                       id="item_0_enabled_yes" value="1" disabled>
                                                <label class="form-check-label"
                                                       for="item_0_enabled_yes">Yes</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                       name="invoiceChargeDesc[0][enabled]"
                                                       id="item_0_enabled_no" value="0" checked disabled>
                                                <label class="form-check-label"
                                                       for="item_0_enabled_no">No</label>
                                            </div>
                                        </td>
                                        <td><input type="text" class="form-control" id="ofr"
                                                   name="invoiceChargeDesc[0][total]" value="{{$total_storage}}"
                                                   placeholder="Total" autocomplete="off" disabled
                                                   style="background-color: white;">
                                        </td>
                                        <td><input type="text" name="invoiceChargeDesc[0][usd_vat]"
                                                   class="form-control" autocomplete="off"
                                                   placeholder="USD After VAT" disabled></td>

                                        <td><input type="text" class="form-control" id="ofr"
                                                   name="invoiceChargeDesc[0][egy_amount]"
                                                   value="{{$total_storage}}"
                                                   placeholder="Egp Amount  " autocomplete="off" disabled
                                                   style="background-color: white;" disabled>
                                        </td>
                                        <td><input type="text" name="invoiceChargeDesc[0][egp_vat]"
                                                   class="form-control" autocomplete="off"
                                                   placeholder="Egp After VAT" disabled></td>

                                        <td style="width:85px;">
                                            <button type="button" class="btn btn-danger remove"><i
                                                        class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit"
                                            class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
                                    <a href="{{route('invoice.index')}}"
                                       class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
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
        $('#createForm').submit(function () {
            $('input').removeAttr('disabled');
        });
    </script>
    <script>
        $('#createForm').submit(function () {
            $('select').removeAttr('disabled');
        });
    </script>
    <script>
        $(function () {
            let customer = $('#customer');
            $('#customer').on('change', function (e) {
                let value = e.target.value;
                let response = $.get(`/api/master/customers/${customer.val()}`).then(function (data) {
                    let notIfiy = data.customer[0];
                    let notifiy = $('#notifiy').val(' ' + notIfiy.name);
                    notifiy.html(list2.join(''));
                });
            });
        });
    </script>
    <script>
        $(document).on('input', 'input[name="vat"]', function () {
            var vat = $(this).val();
            vat = vat / 100;
            var qty = $('input[name="qty"]').val();
            var exchange = $('input[name="exchange_rate"]').val();
            var eta = "{{optional($bldraft->voyage)->exchange_rate}}";
            var etd = "{{optional($bldraft->voyage)->exchange_rate_etd}}";
            var exchangeRate = exchange === 'eta' ? eta : etd;
            $('#charges tbody tr').each(function () {
                var sizeSmall = $(this).find('input[name$="[size_small]"]').val();
                var enabled = $(this).find('input[name$="[enabled]"]:checked').val();
                var add_vat = $(this).find('input[name$="[add_vat]"]:checked').val();
                var totalAmount = enabled == 1 ? sizeSmall * qty : sizeSmall * 1;
                var totalAmountAfterVat = add_vat == 1 ? totalAmount + (totalAmount * vat) : totalAmount;
                $(this).find('input[name$="[total]"]').val(totalAmount);
                $(this).find('input[name$="[usd_vat]"]').val(totalAmountAfterVat.toFixed(2));
                // Calculate the total EGP Amount and update the Amount input field of the current row
                var egpAmount = totalAmount * exchangeRate;
                var egpAmountAfterVat = totalAmountAfterVat * exchangeRate;
                $(this).find('input[name$="[egy_amount]"]').val(egpAmount);
                $(this).find('input[name$="[egp_vat]"]').val(egpAmountAfterVat.toFixed(2));
            });
        });
        $(document).on('input', 'input[name="exchange_rate"]', function () {
            var exchange = $(this).val();
            var qty = $('input[name="qty"]').val();
            var vat = $('input[name="vat"]').val();
            vat = vat / 100;
            $('#charges tbody tr').each(function () {
                var sizeSmall = $(this).find('input[name$="[size_small]"]').val();
                var enabled = $(this).find('input[name$="[enabled]"]:checked').val();
                var add_vat = $(this).find('input[name$="[add_vat]"]:checked').val();
                var totalAmount = enabled == 1 ? sizeSmall * qty : sizeSmall * 1;
                var totalAmountAfterVat = add_vat == 1 ? totalAmount + (totalAmount * vat) : totalAmount;
                $(this).find('input[name$="[total]"]').val(totalAmount);
                $(this).find('input[name$="[usd_vat]"]').val(totalAmountAfterVat.toFixed(2));
                var eta = "{{optional($bldraft->voyage)->exchange_rate}}";
                var etd = "{{optional($bldraft->voyage)->exchange_rate_etd}}";
                var exchangeRate = exchange === 'eta' ? eta : etd;
                var egpAmount = totalAmount * exchangeRate;
                var egpAmountAfterVat = totalAmountAfterVat * exchangeRate;
                $(this).find('input[name$="[egy_amount]"]').val(egpAmount);
                $(this).find('input[name$="[egp_vat]"]').val(egpAmountAfterVat.toFixed(2));
            });
        });

        $('body').on('change', 'input[name$="[enabled]"]', function () {
            var row = $(this).closest('tr');
            var sizeSmall = row.find('input[name$="[size_small]"]').val();
            var vat = $('input[name="vat"]').val();
            vat = vat / 100;
            var add_vat = row.find('input[name$="[add_vat]"]:checked').val();
            var qty = $('input[name="qty"]').val();
            var totalAmount = 0;
            if ($(this).val() == 1) {
                totalAmount = sizeSmall * qty;
            } else {
                totalAmount = sizeSmall * 1;
            }
            var totalAmountAfterVat = add_vat == 1 ? totalAmount + (totalAmount * vat) : totalAmount;

            row.find('input[name$="[total]"]').val(totalAmount);
            row.find('input[name$="[usd_vat]"]').val(totalAmountAfterVat.toFixed(2));

            // Calculate the total EGP Amount and update the Amount input field of the current row
            var exchange = $('input[name="exchange_rate"]:checked').val();
            var eta = "{{optional($bldraft->voyage)->exchange_rate}}";
            var etd = "{{optional($bldraft->voyage)->exchange_rate_etd}}";
            var exchangeRate = exchange === 'eta' ? eta : etd;
            var egpAmount = totalAmount * exchangeRate;
            var egpAmountAfterVat = totalAmountAfterVat * exchangeRate;
            row.find('input[name$="[egy_amount]"]').val(egpAmount);
            row.find('input[name$="[egp_vat]"]').val(egpAmountAfterVat.toFixed(2));
        });

        $('body').on('change', 'input[name$="[add_vat]"]', function () {
            var row = $(this).closest('tr');
            var sizeSmall = row.find('input[name$="[size_small]"]').val();
            var vat = $('input[name="vat"]').val();
            vat = vat / 100;
            var enabled = row.find('input[name$="[enabled]"]:checked').val();
            var qty = $('input[name="qty"]').val();
            var totalAmount = 0;
            if (enabled == 1) {
                totalAmount = sizeSmall * qty;
            } else {
                totalAmount = sizeSmall * 1;
            }
            var totalAmountAfterVat = $(this).val() == 1 ? totalAmount + (totalAmount * vat) : totalAmount;

            row.find('input[name$="[total]"]').val(totalAmount);
            row.find('input[name$="[usd_vat]"]').val(totalAmountAfterVat.toFixed(2));

            // Calculate the total EGP Amount and update the Amount input field of the current row
            var exchange = $('input[name="exchange_rate"]:checked').val();
            var eta = "{{optional($bldraft->voyage)->exchange_rate}}";
            var etd = "{{optional($bldraft->voyage)->exchange_rate_etd}}";
            var exchangeRate = exchange === 'eta' ? eta : etd;
            var egpAmount = totalAmount * exchangeRate;
            var egpAmountAfterVat = totalAmountAfterVat * exchangeRate;
            row.find('input[name$="[egy_amount]"]').val(egpAmount);
            row.find('input[name$="[egp_vat]"]').val(egpAmountAfterVat.toFixed(2));
        });

        $('body').on('input', 'input[name$="[size_small]"]', function () {
            // Get the current row
            var row = $(this).closest('tr');

            // Get the qty value from the QTY input field
            var qty = $('input[name="qty"]').val();

            var vat = $('input[name="vat"]').val();
            vat = vat / 100;
            var add_vat = row.find('input[name$="[add_vat]"]:checked').val();

            // Get the size_small value from the current row
            var sizeSmall = $(this).val();

            var enabled = row.find('input[name$="[enabled]"]:checked').val();

            // Calculate the total amount and update the total_amount input field of the current row
            var totalAmount = enabled == 1 ? sizeSmall * qty : sizeSmall * 1;
            var totalAmountAfterVat = add_vat == 1 ? totalAmount + (totalAmount * vat) : totalAmount;

            row.find('input[name$="[total]"]').val(totalAmount);
            row.find('input[name$="[usd_vat]"]').val(totalAmountAfterVat.toFixed(2));

            var eta = "{{optional($bldraft->voyage)->exchange_rate}}";
            var etd = "{{optional($bldraft->voyage)->exchange_rate_etd}}";
            // Calculate the total EGP Amount and update the Amount input field of the current row
            var exchangeRate = $('input[name="exchange_rate"]:checked').val();
            exchangeRate = exchangeRate === 'eta' ? eta : etd;
            var egpAmount = totalAmount * exchangeRate;
            var egpAmountAfterVat = totalAmountAfterVat * exchangeRate;
            row.find('input[name$="[egy_amount]"]').val(egpAmount);
            row.find('input[name$="[egp_vat]"]').val(egpAmountAfterVat.toFixed(2));

        });
    </script>

    <script>

        $(document).ready(function () {
            localStorage.removeItem('cart');
            $("#charges").on("click", ".remove", function () {
                $(this).closest("tr").remove();
            });
            var counter = '<?= isset($key) ? ++$key : 0 ?>';
            $("#add").click(function () {
                var tr = '<tr>' +
                    '<td><select class="selectpicker form-control" data-live-search="true" id="selectpickers" name="invoiceChargeDesc[' + counter + '][charge_description]" data-size="10"><option>Select</option>@foreach ($charges as $item)<option value="{{$item->name}}">{{$item->name}}</option>@endforeach</select></td>' +
                    '<td><input type="text" name="invoiceChargeDesc[' + counter + '][size_small]" class="form-control" autocomplete="off" placeholder="Amount" required></td>' +
                    '<td><div class="form-check"><input class="form-check-input" type="radio" name="invoiceChargeDesc[' + counter + '][add_vat]" id="item_' + counter + '_enabled_yes" value="1"><label class="form-check-label" for="item_' + counter + '_enabled_yes">Yes</label></div><div class="form-check"><input class="form-check-input" type="radio" name="invoiceChargeDesc[' + counter + '][add_vat]" id="item_' + counter + '_enabled_no" value="0" checked><label class="form-check-label" for="item_' + counter + '_enabled_no">No</label></div></td>' +
                    '<td><div class="form-check"><input class="form-check-input" type="radio" name="invoiceChargeDesc[' + counter + '][enabled]" id="item_' + counter + '_enabled_yes" value="1" checked><label class="form-check-label" for="item_' + counter + '_enabled_yes">Yes</label></div><div class="form-check"><input class="form-check-input" type="radio" name="invoiceChargeDesc[' + counter + '][enabled]" id="item_' + counter + '_enabled_no" value="0"><label class="form-check-label" for="item_' + counter + '_enabled_no">No</label></div></td>' +
                    '<td><input type="text" name="invoiceChargeDesc[' + counter + '][total]" class="form-control" autocomplete="off" placeholder="Total" required></td>' +
                    '<td><input type="text" name="invoiceChargeDesc[' + counter + '][usd_vat]" class="form-control" autocomplete="off" placeholder="USD After VAT" disabled></td>' +
                    '<td><input type="text" name="invoiceChargeDesc[' + counter + '][egy_amount]" class="form-control" autocomplete="off" placeholder="Egp Amount" disabled></td>' +
                    '<td><input type="text" name="invoiceChargeDesc[' + counter + '][egp_vat]" class="form-control" autocomplete="off" placeholder="Egp After VAT"></td>' +
                    '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>'
                '</tr>';
                counter++;
                $('#charges').append(tr);
                $('.selectpicker').selectpicker("render");
                $('#selectpickers').selectpicker();
            });
            $('input[name$="[size_small]"]').trigger("input")
        });
    </script>

@endpush
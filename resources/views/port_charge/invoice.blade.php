@extends('layouts.app')

@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Port Charge</a></li>
                                <li class="breadcrumb-item active"><a href="{{route('movements.index')}}">Invoice</a>
                                </li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div>
                    <div class="widget-content widget-content-area">
                        <form id="createForm" action="{{route('movements.store')}}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text bg-transparent border-0"
                                                       for="payment_type">
                                                    Payment Type
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6"> <!-- Adjust the width here -->
                                            <input type="text" class="form-control" id="payment_type"
                                                   name="payment_type" value="{{old('payment_type')}}"
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                    @error('payment_type')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text bg-transparent border-0"
                                                       for="invoice_type">
                                                    Invoice Type
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6"> <!-- Adjust the width here -->
                                            <select class="selectpicker form-control rounded-0" id="invoice_type"
                                                    name="invoice_type"
                                                    data-live-search="true" data-size="10"
                                                    title="{{trans('forms.select')}}">
                                                <option value="02-VESSEL DISCHARGING AND LOADIND OPERATIONS">
                                                    02-VESSEL DISCHARGING AND LOADIND OPERATIONS
                                                </option>
                                                <option value="03-VESSEL OUTBOUND CONTAINERS STORAGE">
                                                    03-VESSEL OUTBOUND CONTAINERS STORAGE
                                                </option>

                                            </select>
                                        </div>
                                    </div>
                                    @error('invoice_type')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text bg-transparent border-0"
                                                       for="invoice_no">
                                                    Invoice No
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6"> <!-- Adjust the width here -->
                                            <input type="text" class="form-control" id="invoice_no"
                                                   name="invoice_no" value="{{old('invoice_no')}}"
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                    @error('invoice_no')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text bg-transparent border-0"
                                                       for="invoice_date">
                                                    Invoice Date
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6"> <!-- Adjust the width here -->
                                            <input type="date" class="form-control" id="invoice_date"
                                                   name="invoice_date" value="{{old('invoice_date')}}"
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                    @error('invoice_date')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text bg-transparent border-0"
                                                       for="exchange_rate">
                                                    Exchange Rate
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6"> <!-- Adjust the width here -->
                                            <input type="text" class="form-control" id="exchange_rate"
                                                   name="exchange_rate" value="{{old('exchange_rate')}}"
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                    @error('exchange_rate')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text bg-transparent border-0"
                                                       for="invoice_status">
                                                    Invoice Status
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6"> <!-- Adjust the width here -->
                                            <input type="text" class="form-control" id="invoice_status"
                                                   name="invoice_status" value="{{old('invoice_status')}}"
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                    @error('invoice_status')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text bg-transparent border-0"
                                                       for="shipping_line">
                                                    Shipping Line
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6"> <!-- Adjust the width here -->
                                            <select class="selectpicker form-control rounded-0" id="shipping_line"
                                                    name="shipping_line"
                                                    data-live-search="true" data-size="10"
                                                    title="{{trans('forms.select')}}">
                                                @foreach ($lines as $item)
                                                    <option
                                                            value="{{$item->id}}" {{$item->id == old('shipping_line') ? 'selected':''}}>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @error('shipping_line')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text bg-transparent border-0" for="vessel_id">
                                                    Vessel Name
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6"> <!-- Adjust the width here -->
                                            <select class="selectpicker form-control rounded-0" id="vessel_id"
                                                    name="vessel_id"
                                                    data-live-search="true" data-size="10"
                                                    title="{{trans('forms.select')}}">
                                                @foreach ($vessels as $item)
                                                    <option
                                                            value="{{$item->id}}" {{$item->id == old('vessel_id') ? 'selected':''}}>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @error('vessel_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text bg-transparent border-0" for="voyage_id">
                                                    Voyage No
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6"> <!-- Adjust the width here -->
                                            <select class="selectpicker form-control rounded-0" id="voyage"
                                                    name="voyage_id"
                                                    data-live-search="true" data-size="10"
                                                    title="{{trans('forms.select')}}">
                                                @foreach ($voyages as $item)
                                                    <option
                                                            value="{{$item->id}}" {{$item->id == old('voyage_id') ? 'selected':''}}>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @error('voyage_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="widget-content widget-content-area">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-condensed mb-4">
                                        <thead>
                                        <tr>
                                            {{--- TODO  ---}}
                                            <th rowspan="3" style="min-width: 222px">Service</th>
                                            <th rowspan="3" style="min-width: 222px">BL NO</th>
                                            <th rowspan="3" style="min-width: 222px">CONTAINER NO</th>
                                            <th rowspan="3" style="min-width: 222px">TS</th>
                                            <th rowspan="3" style="min-width: 222px">SHIPMENT TYPE</th>
                                            <th rowspan="3" style="min-width: 222px">QUOTATION TYPE</th>
                                            <th colspan=2>THC</th>
                                            <th colspan=7>STORAGE</th>
                                            <th colspan=2>POWER</th>
                                            <th colspan=2>SHIFTING</th>
                                            <th colspan=2>DISINF</th>
                                            <th colspan=2>HAND-FES-EM</th>
                                            <th colspan=2>GAT-LIFT OFF-INBND-EM-FT40</th>
                                            <th colspan=2>GAT-LIFT ON-INBND-EM-FT40</th>
                                            <th colspan=2>PTI</th>
                                            <th colspan=2>WIRE-TRNSHP</th>
                                        </tr>
                                        <tr>
                                            <th rowspan=2 height=98>20FT</th>
                                            <th rowspan=2>40FT</th>
                                            <th rowspan=2>Free Time</th>
                                            <th colspan=3>Slab1</th>
                                            <th colspan=3>Slab2</th>
                                            <th rowspan=2>20FT</th>
                                            <th rowspan=2>40FT</th>
                                            <th rowspan=2>20FT</th>
                                            <th rowspan=2>40FT</th>
                                            <th rowspan=2>20FT</th>
                                            <th rowspan=2>40FT</th>
                                            <th rowspan=2>20FT</th>
                                            <th rowspan=2>40FT</th>
                                            <th rowspan=2>20FT</th>
                                            <th rowspan=2>40FT</th>
                                            <th rowspan=2>20FT</th>
                                            <th rowspan=2>40FT</th>
                                            <th colspan=2>20FT & 40FT</th>
                                            <th rowspan=2>20FT</th>
                                            <th rowspan=2>40FT</th>
                                        </tr>
                                        <tr>
                                            <th>Period</th>
                                            <th>20 FT</th>
                                            <th>40 FT</th>
                                            <th>Period</th>
                                            <th>20 FT</th>
                                            <th>40 FT</th>
                                            <th>failed</th>
                                            <th>pass</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td><select name="service[]" class="form-control">
                                                    <option value="001-VSL-RE-STW-OPR">001-VSL-RE-STW-OPR</option>
                                                    <option value="005-VSL-DIS-OPR">005-VSL-DIS-OPR</option>
                                                    <option value="006-VSL-LOD-OPR">006-VSL-LOD-OPR</option>
                                                    <option value="007-VSL-TRNSHP-OPR">007-VSL-TRNSHP-OPR</option>
                                                    <option value="011-VSL-HOL-WRK">011-VSL-HOL-WRK</option>
                                                    <option value="018-YARD-SERV">018-YARD-SERV</option>
                                                    <option value="019-LOG-SERV">019-LOG-SERV</option>
                                                    <option value="020-HAND-FES">020-HAND-FES</option>
                                                    <option value="021-STRG-INBND-FL-CONTRS">021-STRG-INBND-FL-CONTRS
                                                    </option>
                                                    <option value="024-STRG-OUTBND-CONTRS-FL">
                                                        024-STRG-OUTBND-CONTRS-FL
                                                    </option>
                                                    <option value="025-STRG-OUTBND-CONTRS-EM">
                                                        025-STRG-OUTBND-CONTRS-EM
                                                    </option>
                                                    <option value="031-STRG-PR-DR-CONTRS">031-STRG-PR-DR-CONTRS</option>
                                                    <option value="033-REFR-CONTR-PWR-SUP">033-REFR-CONTR-PWR-SUP
                                                    </option>
                                                    <option value="037-MISC-REV-GAT-SERV">037-MISC-REV-GAT-SERV</option>
                                                    <option value="038-MISC-REV-YARD-CRN-SHIFTING">
                                                        038-MISC-REV-YARD-CRN-SHIFTING
                                                    </option>
                                                    <option value="039-MISC-REV-GAT-SERV-LIFT OFF">
                                                        039-MISC-REV-GAT-SERV-LIFT OFF
                                                    </option>
                                                    <option value="045-MISC-REV-ELEC-REP-SERV">
                                                        045-MISC-REV-ELEC-REP-SERV
                                                    </option>
                                                    <option value="051-VSL-OPR-ADD-PLAN">051-VSL-OPR-ADD-PLAN</option>
                                                    <option value="060-DISINFECTION OF CONTAINERS">060-DISINFECTION OF
                                                        CONTAINERS
                                                    </option>
                                                </select></td>
                                            <td><input type="text" class="form-control ref-no-td" name="bl_no[]"></td>
                                            <td><input type="text" id="container_no_input" name="container_no[]"
                                                       class="container_no form-control"></td>
                                            <td><input type="text" name="is_transhipment[]"
                                                       class="is_transhipment form-control"></td>
                                            <td><input type="text" name="shipment_type[]"
                                                       class="shipment_type form-control"></td>
                                            <td><input type="text" name="quotation_type[]"
                                                       class="quotation_type form-control"></td>
                                            <td><input type="text" name="thc_20ft[]" class="form-control"></td>
                                            <td><input type="text" name="thc_40ft[]" class="form-control"></td>
                                            <td><input type="text" name="free_time[]" class="form-control"></td>
                                            <td><input type="text" name="slab1_period[]" class="form-control"></td>
                                            <td><input type="text" name="slab1_20ft[]" class="form-control"></td>
                                            <td><input type="text" name="slab1_40ft[]" class="form-control"></td>
                                            <td><input type="text" name="slab2_period[]" class="form-control"></td>
                                            <td><input type="text" name="slab2_20ft[]" class="form-control"></td>
                                            <td><input type="text" name="slab2_40ft[]" class="form-control"></td>
                                            <td><input type="text" name="power_20ft[]" class="form-control"></td>
                                            <td><input type="text" name="power_40ft[]" class="form-control"></td>
                                            <td><input type="text" name="shifting_20ft[]" class="form-control"></td>
                                            <td><input type="text" name="shifting_40ft[]" class="form-control"></td>
                                            <td><input type="text" name="disinf_20ft[]" class="form-control"></td>
                                            <td><input type="text" name="disinf_40ft[]" class="form-control"></td>
                                            <td><input type="text" name="hand_fes_em_20ft[]" class="form-control"></td>
                                            <td><input type="text" name="hand_fes_em_40ft[]" class="form-control"></td>
                                            <td><input type="text" name="gat_lift_off_inbnd_em_ft40_20ft[]"
                                                       class="form-control"></td>
                                            <td><input type="text" name="gat_lift_off_inbnd_em_ft40_40ft[]"
                                                       class="form-control"></td>
                                            <td><input type="text" name="gat_lift_on_inbnd_em_ft40_20ft[]"
                                                       class="form-control"></td>
                                            <td><input type="text" name="gat_lift_on_inbnd_em_ft40_40ft[]"
                                                       class="form-control"></td>
                                            <td><input type="text" name="pti_20ft[]" class="form-control"></td>
                                            <td><input type="text" name="pti_40ft[]" class="form-control"></td>
                                            <td><input type="text" name="wire_trnshp_20ft[]" class="form-control"></td>
                                            <td><input type="text" name="wire_trnshp_40ft[]" class="form-control"></td>
                                        </tbody>

                                    </table>
                                </div>
                                <div class="paginating-container">
                                    {{ $portCharges->links() }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" id="submit"
                                            class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
                                    <a href="{{route('movements.index')}}"
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
@push('styles')
    <style>
        input {
            min-width: 100px;
        }
    </style>
@endpush
@push('scripts')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const containerNoInput = $('#container_no_input');
            const tbody = $('table tbody');

            containerNoInput.on('paste', e => {
                e.preventDefault();
                const clipboardData = e.originalEvent.clipboardData || window.clipboardData;
                const pastedContent = clipboardData.getData('text/plain');
                const containerNumbers = pastedContent.split('\n');

                const row = containerNoInput.closest('tr');
                containerNumbers.forEach(containerNumber => {
                    if (containerNumber.trim() !== '') {
                        const newRow = $('<tr>' +
                            `<td><select name="service[]" class="form-control">
                                <option value="001-VSL-RE-STW-OPR">001-VSL-RE-STW-OPR</option>
                                <option value="005-VSL-DIS-OPR">005-VSL-DIS-OPR</option>
                                <option value="006-VSL-LOD-OPR">006-VSL-LOD-OPR</option>
                                <option value="007-VSL-TRNSHP-OPR">007-VSL-TRNSHP-OPR</option>
                                <option value="011-VSL-HOL-WRK">011-VSL-HOL-WRK</option>
                                <option value="018-YARD-SERV">018-YARD-SERV</option>
                                <option value="019-LOG-SERV">019-LOG-SERV</option>
                                <option value="020-HAND-FES">020-HAND-FES</option>
                                <option value="021-STRG-INBND-FL-CONTRS">021-STRG-INBND-FL-CONTRS</option>
                                <option value="024-STRG-OUTBND-CONTRS-FL">024-STRG-OUTBND-CONTRS-FL</option>
                                <option value="025-STRG-OUTBND-CONTRS-EM">025-STRG-OUTBND-CONTRS-EM</option>
                                <option value="031-STRG-PR-DR-CONTRS">031-STRG-PR-DR-CONTRS</option>
                                <option value="033-REFR-CONTR-PWR-SUP">033-REFR-CONTR-PWR-SUP</option>
                                <option value="037-MISC-REV-GAT-SERV">037-MISC-REV-GAT-SERV</option>
                                <option value="038-MISC-REV-YARD-CRN-SHIFTING">038-MISC-REV-YARD-CRN-SHIFTING</option>
                                <option value="039-MISC-REV-GAT-SERV-LIFT OFF">039-MISC-REV-GAT-SERV-LIFT OFF</option>
                                <option value="045-MISC-REV-ELEC-REP-SERV">045-MISC-REV-ELEC-REP-SERV</option>
                                <option value="051-VSL-OPR-ADD-PLAN">051-VSL-OPR-ADD-PLAN</option>
                                <option value="060-DISINFECTION OF CONTAINERS">060-DISINFECTION OF CONTAINERS</option>
                             </select></td>` +
                            '<td><input type="text" name="bl_no[]" class="form-control ref-no-td"></td>' +
                            '<td><input type="text" name="container_no[]" class="form-control container_no" value="' + containerNumber + '"></td>' +
                            `<td><input type="text" name="is_transhipment[]"
                                                       class="is_transhipment form-control"></td>
                            <td><input type="text" name="shipment_type[]"
                                                       class="shipment_type form-control"></td>
                            <td><input type="text" name="quotation_type[]"
                                                       class="quotation_type form-control"></td>` +
                            '<td><input type="text" name="thc_20ft[]" class="form-control"></td>' +
                            '<td><input type="text" name="thc_40ft[]" class="form-control"></td>' +
                            '<td><input type="text" name="free_time[]" class="form-control"></td>' +
                            '<td><input type="text" name="slab1_period[]" class="form-control"></td>' +
                            '<td><input type="text" name="slab1_20ft[]" class="form-control"></td>' +
                            '<td><input type="text" name="slab1_40ft[]" class="form-control"></td>' +
                            '<td><input type="text" name="slab2_period[]" class="form-control"></td>' +
                            '<td><input type="text" name="slab2_20ft[]" class="form-control"></td>' +
                            '<td><input type="text" name="slab2_40ft[]" class="form-control"></td>' +
                            '<td><input type="text" name="power_20ft[]" class="form-control"></td>' +
                            '<td><input type="text" name="power_40ft[]" class="form-control"></td>' +
                            '<td><input type="text" name="shifting_20ft[]" class="form-control"></td>' +
                            '<td><input type="text" name="shifting_40ft[]" class="form-control"></td>' +
                            '<td><input type="text" name="disinf_20ft[]" class="form-control"></td>' +
                            '<td><input type="text" name="disinf_40ft[]" class="form-control"></td>' +
                            '<td><input type="text" name="hand_fes_em_20ft[]" class="form-control"></td>' +
                            '<td><input type="text" name="hand_fes_em_40ft[]" class="form-control"></td>' +
                            '<td><input type="text" name="gat_lift_off_inbnd_em_ft40_20ft[]" class="form-control"></td>' +
                            '<td><input type="text" name="gat_lift_off_inbnd_em_ft40_40ft[]" class="form-control"></td>' +
                            '<td><input type="text" name="gat_lift_on_inbnd_em_ft40_20ft[]" class="form-control"></td>' +
                            '<td><input type="text" name="gat_lift_on_inbnd_em_ft40_40ft[]" class="form-control"></td>' +
                            '<td><input type="text" name="pti_20ft[]" class="form-control"></td>' +
                            '<td><input type="text" name="pti_40ft[]" class="form-control"></td>' +
                            '<td><input type="text" name="wire_trnshp_20ft[]" class="form-control"></td>' +
                            '<td><input type="text" name="wire_trnshp_40ft[]" class="form-control"></td>' +
                            '</tr>');
                        tbody.append(newRow);
                        newRow.find('.container_no').trigger('change');
                        row.remove();
                    }
                });
            });

            $(document).on('change', '.container_no', function () {
                const containerNumber = $(this).val().trim()
                if (containerNumber !== '') {
                    const row = $(this).closest('tr')
                    const refNoCell = row.find('.ref-no-td')[0]
                    const isTsCell = row.find('.is_transhipment')[0]
                    const shipTypeCell = row.find('.shipment_type')[0]
                    const quoteTypeCell = row.find('.quotation_type')[0]

                    axios.get(`{{ route('port-charges.get-ref-no') }}`, {
                        params: {
                            vessel: $('#vessel_id').val(),
                            voyage: $('#voyage').val(),
                            container: containerNumber
                        }
                    }).then(response => {
                        if (response.data.status === 'success') {
                            refNoCell.value = response.data.ref_no
                            isTsCell.value = response.data.is_ts
                            shipTypeCell.value = response.data.shipment_type
                            quoteTypeCell.value = response.data.quotation_type
                        }
                    }).catch(() => {
                        console.error('Could not find ref_no')
                    })
                }
            })

            const vessel = $('#vessel_id')
            vessel.on('change', function (e) {
                const value = e.target.value
                $.get(`/api/vessel/voyages/${vessel.val()}`).then(data => {
                    const voyages = data.voyages || []
                    const voyageNo = $('#voyage')
                    const list2 = voyages.map(voyage => `<option value="${voyage.id}">${voyage.voyage_no} - ${voyage.leg}</option>`)
                    voyageNo.html(list2.join(''))
                    $('.selectpicker').selectpicker('refresh')
                })
            })
        })

    </script>
    {{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>--}}
    {{--    @include('port_charge._js')--}}
@endpush

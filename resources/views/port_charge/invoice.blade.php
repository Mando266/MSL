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
                                                <option value="08-STORAGE OF FULL INBOUND CONTAINERS">
                                                    08-STORAGE OF FULL INBOUND CONTAINERS
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
                                        <div class="col-md-6">
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
                                        <div class="col-md-6">
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
                                        <div class="col-md-6">
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
                                        <div class="col-md-6">
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
                                                       for="country">
                                                    Country
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="selectpicker form-control rounded-0" id="country"
                                                    name="country"
                                                    data-live-search="true" data-size="10"
                                                    title="{{trans('forms.select')}}">
                                                @foreach ($countries as $item)
                                                    <option
                                                            value="{{$item->id}}" {{$item->id == old('shipping_line') ? 'selected':''}}>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text bg-transparent border-0"
                                                       for="ports">
                                                    Port
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="selectpicker form-control rounded-0" id="ports"
                                                    name="country"
                                                    data-live-search="true" data-size="10"
                                                    title="{{trans('forms.select')}}">
                                                @foreach ($ports as $item)
                                                    <option
                                                            value="{{$item->id}}" {{$item->id == old('shipping_line') ? 'selected':''}}>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
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
                                        <div class="col-md-6">
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
                                        <div class="col-md-6">
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
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <label class="input-group-text bg-transparent border-0"
                                                       for="dynamic_fields">
                                                    Dynamic Fields
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="selectpicker form-control" id="dynamic_fields" multiple
                                                    data-size="10">
                                                @foreach ([
                                                            'thc_20ft', 'thc_40ft', 'storage_free', 'storage_slab1_period', 'storage_slab1_20ft',
                                                            'storage_slab1_40ft', 'storage_slab2_period', 'storage_slab2_20ft', 'storage_slab2_40ft',
                                                            'power_free', 'power_20ft', 'power_40ft', 'shifting_20ft', 'shifting_40ft', 'disinf_20ft', 'disinf_40ft',
                                                            'hand_fes_em_20ft', 'hand_fes_em_40ft', 'gat_lift_off_inbnd_em_ft40_20ft',
                                                            'gat_lift_off_inbnd_em_ft40_40ft', 'gat_lift_on_inbnd_em_ft40_20ft',
                                                            'gat_lift_on_inbnd_em_ft40_40ft', 'pti_failed', 'pti_passed', 'add_plan_20ft',
                                                            'add_plan_40ft'
                                                        ] as $field)
                                                    <option value="{{ $field }}">{{ $field }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-0">Total EGP</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" id="total_egp" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-0">Total USD</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" id="total_usd" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="container">
                                    <div id="table2-selects" class="d-none">
                                        <div class="text-center">
                                            <label>Empty/Export</label>
                                            <label class="col-md-3">From
                                                <select name="empty_export_from" class="form-control">
                                                    <option hidden selected>Select</option>
                                                    @foreach ($possibleMovements as $movement)
                                                        <option value="{{ $movement->id }}">{{ $movement->code }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                            <label class="col-md-3">To
                                                <select name="empty_export_to" class="form-control">
                                                    <option hidden selected>Select</option>
                                                    @foreach ($possibleMovements as $movement)
                                                        <option value="{{ $movement->id }}">{{ $movement->code }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="container">
                                    <div id="table3-selects" class="d-none">
                                        <div class="text-center">
                                            <label>Empty/Import</label>
                                            <label class="col-md-3">From
                                                <select name="empty_import_from" class="form-control">
                                                    <option hidden selected>Select</option>
                                                    @foreach ($possibleMovements as $movement)
                                                        <option value="{{ $movement->id }}">{{ $movement->code }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                            <label class="col-md-3">To
                                                <select name="empty_import_from" class="form-control">
                                                    <option hidden selected>Select</option>
                                                    @foreach ($possibleMovements as $movement)
                                                        <option value="{{ $movement->id }}">{{ $movement->code }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <button type="button" class="nav-link switch-table active" data-table="table1">
                                            Full
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link switch-table" data-table="table2">
                                            Empty/Export
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link switch-table" data-table="table3">
                                            Empty/Import
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link switch-table" data-table="table4">
                                            Transhipment
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            <div>
                                <button type="button" id="add-row" class="btn btn-info m-3">Add Row</button>
                            </div>
                            <div class="col-md-12">
                                <div class="table-container">
                                    <x-soa-table id="table1"/>
                                    <x-soa-table id="table2" class="d-none"/>
                                    <x-soa-table id="table3" class="d-none"/>
                                    <x-soa-table id="table4" class="d-none"/>
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

        .table-container {
            overflow-x: auto;
            max-width: 100%;
        }
    </style>
@endpush
@push('scripts')
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        $(document).ready(function () {
            setupEventHandlers()
            switchTables()
            calculateTotalUSD()
            loadVoyages()
        })

        function handleDynamicFieldsChange() {
            const selectedFields = $('#dynamic_fields').val()
            $('.dynamic-input').removeClass('included')

            selectedFields.forEach(selectedField => {
                $('.dynamic-input[data-field="' + selectedField + '"]').addClass('included')
            })
        }

        function calculateTotalUSD() {
            let totalUSD = 0

            $('.dynamic-input.included').each(function () {
                totalUSD += parseFloat($(this).val()) || 0
            })

            $('#total_usd').val(totalUSD)
        }

        function setupEventHandlers() {
            $(document).on('click', '.removeContact', handleRemoveRow)
            $("#add-row").on('click', handleAddRow)
            $(document).on('change', '.charge_type', handleChargeTypeChange)
            $(document).on('paste', '.container_no', handleContainerNoPaste)
            $(document).on('change', '.container_no', handleContainerNoChange)
            $('#vessel_id').on('change', loadVoyages)
            $(document).on('change', '#dynamic_fields', handleDynamicFieldsChange)
            $('body').on('DOMSubtreeModified', calculateTotalUSD)
        }

        function handleRemoveRow() {
            $(this).closest("tr").remove()
            calculateTotalUSD()
        }

        function handleAddRow() {
            const targetTable = $('table:not(.d-none)').first()
            const tableId = targetTable.attr('id')
            const newRow = getNewRow()

            targetTable.append(newRow)
            handleDynamicFieldsChange()
            updateChargeTypeOptions(tableId)
        }

        function handleChargeTypeChange() {
            const selectedValue = $(this).val()
            const row = $(this).closest('tr')
            const dynamicInputs = row.find('.dynamic-input')

            dynamicInputs.each(function () {
                const field = $(this).data('field')
                const value = selectedValue ? JSON.parse(selectedValue)[field] : ''
                $(this).val(value)
            })

            calculateTotalUSD()
        }

        function handleContainerNoPaste(e) {
            e.preventDefault()

            const clipboardData = getClipboardData(e)
            const containerNumbers = getPastedContainerNumbers(clipboardData)

            const row = $(this).closest('tr')
            const selectedCharge = row.find('.charge_type')[0].selectedIndex
            const selectedService = row.find('.service_type')[0].selectedIndex
            const tableId = row.closest('table').attr('id')

            const tbody = row.closest('tbody')
            appendNewRows(containerNumbers, selectedCharge, selectedService, tbody)
            row.remove()
            handleDynamicFieldsChange()
            updateChargeTypeOptions(tableId);
        }

        function getClipboardData(event) {
            return (event.originalEvent || event).clipboardData || window.clipboardData
        }

        function getPastedContainerNumbers(clipboardData) {
            const pastedContent = clipboardData.getData('text/plain')
            return pastedContent.split('\n').map(containerNumber => containerNumber.trim())
        }

        function appendNewRows(containerNumbers, selectedCharge, selectedService, tbody) {
            containerNumbers.forEach(containerNumber => {
                if (containerNumber !== '') {
                    const newRow = getNewRow(containerNumber)
                    tbody.append(newRow)
                    newRow.find('.charge_type')[0].selectedIndex = selectedCharge
                    newRow.find('.service_type')[0].selectedIndex = selectedService
                    newRow.find('.container_no').trigger('change')
                    newRow.find('.charge_type').trigger('change')
                }
            })
        }

        function handleContainerNoChange() {
            const containerNumber = $(this).val().trim()
            if (containerNumber !== '') {
                const row = $(this).closest('tr')
                const refNoCell = row.find('.ref-no-td')[0]
                const isTsCell = row.find('.is_transhipment')[0]
                const shipTypeCell = row.find('.shipment_type')[0]
                const quoteTypeCell = row.find('.quotation_type')[0]

                axios.get('{{ route('port-charges.get-ref-no') }}', {
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

            handleDynamicFieldsChange()
        }

        function loadVoyages() {
            const vessel = $('#vessel_id')
            const voyageNo = $('#voyage')

            $.get(`/api/vessel/voyages/${vessel.val()}`).then(data => {
                const voyages = data.voyages || []
                const options = voyages.map(voyage => `<option value="${voyage.id}">${voyage.voyage_no} - ${voyage.leg}</option>`)
                voyageNo.html('<option hidden selected>Select</option>' + options.join(''))
                $('.selectpicker').selectpicker('refresh')
            })
        }

        function switchTables() {
            const switchButtons = document.querySelectorAll('.switch-table')
            const tableContainer = document.querySelector('.table-container')
            const table2Selects = document.getElementById('table2-selects')
            const table3Selects = document.getElementById('table3-selects')

            switchButtons.forEach(button => {
                button.addEventListener('click', () => {
                    switchButtons.forEach(btn => {
                        btn.classList.remove('active')
                    })

                    button.classList.add('active')
                    const tableId = button.getAttribute('data-table')

                    table2Selects.classList.toggle('d-none', tableId !== 'table2')
                    table3Selects.classList.toggle('d-none', tableId !== 'table3')

                    tableContainer.querySelectorAll('table').forEach(table => {
                        table.classList.add('d-none')
                    })

                    const selectedTable = document.getElementById(tableId)
                    if (selectedTable) {
                        selectedTable.classList.remove('d-none')
                    }
                })
            })
        }

        function getNewRow(containerNumber = '') {
            const newRow = $(`
        <tr>
            <td style="width: 85px;">
                <button type="button" class="btn btn-danger removeContact">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
            <td>
                <select name="port_charge_type[]" class="form-control charge_type new_charge" required>
                    <option hidden selected>Select</option>
                    @foreach($portCharges as $portCharge)
            <option value="{{ json_encode($portCharge) }}">{{ $portCharge->name }}</option>
                    @endforeach
            </select>
        </td>
        <td>
            <select name="service[]" class="form-control service_type" required>
                <option selected hidden>Select</option>
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
            </select>
        </td>
        <td><input type="text" name="bl_no[]" class="form-control ref-no-td"></td>
        <td><input type="text" name="container_no[]" class="form-control container_no" value="${containerNumber}"></td>
            <td><input type="text" name="is_transhipment[]" class="is_transhipment form-control"></td>
            <td><input type="text" name="shipment_type[]" class="shipment_type form-control"></td>
            <td><input type="text" name="quotation_type[]" class="quotation_type form-control"></td>
            ${generateDynamicInputsHtml()}
        </tr>
    `);

            return newRow
        }

        function generateDynamicInputsHtml() {
            const dynamicFields = [
                'thc_20ft', 'thc_40ft', 'storage_free', 'storage_slab1_period', 'storage_slab1_20ft',
                'storage_slab1_40ft', 'storage_slab2_period', 'storage_slab2_20ft', 'storage_slab2_40ft',
                'power_free', 'power_20ft', 'power_40ft', 'shifting_20ft', 'shifting_40ft', 'disinf_20ft', 'disinf_40ft',
                'hand_fes_em_20ft', 'hand_fes_em_40ft', 'gat_lift_off_inbnd_em_ft40_20ft',
                'gat_lift_off_inbnd_em_ft40_40ft', 'gat_lift_on_inbnd_em_ft40_20ft',
                'gat_lift_on_inbnd_em_ft40_40ft', 'pti_failed', 'pti_passed', 'add_plan_20ft',
                'add_plan_40ft'
            ]

            let dynamicInputsHtml = ''
            dynamicFields.forEach(field => {
                dynamicInputsHtml += `
            <td>
                <input type="text" name="${field}[]" class="form-control dynamic-input" data-field="${field}">
            </td>
        `;
            })

            return dynamicInputsHtml
        }

        function updateChargeTypeOptions(tableId) {
            const chargeTypeSelect = $(`#${tableId} .new_charge`)
            const options = chargeTypeSelect.find('option:not([hidden])')

            const allowedValues = {
                'table1': ['FULL-IMPORT', 'FULL-EXPORT'],
                'table2': ['EMPTY-EXPORT'],
                'table3': ['EMPTY-IMPORT'],
                'table4': ['TRANSHIP']
            }

            options.each((index, option) => {
                const optionValue = JSON.parse(option.value).name

                if (!(allowedValues[tableId].some(allowed => optionValue.includes(allowed)))) {
                    option.remove()
                }
            })
        }


    </script>
@endpush

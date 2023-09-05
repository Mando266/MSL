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
                        <form id="createForm" action="{{ route('port-charge-invoices.store') }}" method="POST">
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
                                                <option value="02-VESSEL DISCHARGING AND LOADING OPERATIONS">
                                                    02-VESSEL DISCHARGING AND LOADING OPERATIONS
                                                </option>
                                                <option value="03-VESSEL OUTBOUND CONTAINERS STORAGE">
                                                    03-VESSEL OUTBOUND CONTAINERS STORAGE
                                                </option>
                                                <option value="05-WITHDRAWAL AND STUFFING CONTAINERS">
                                                    05-WITHDRAWAL AND STUFFING CONTAINERS
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
                                            <input type="number" class="form-control" id="exchange_rate"
                                                   name="exchange_rate" value="{{old('exchange_rate')}}"
                                                   min="0" max="1000" step="0.01">
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
                                                    name="country_id"
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
                                                    name="port_id"
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
                                                    name="shipping_line_id"
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
                                                    Applied Costs
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="selectpicker form-control" id="dynamic_fields" multiple
                                                    data-size="10" name="selected_costs[]" required>
                                                @foreach ([
                                                            'thc', 'storage','power', 'shifting',
                                                            'disinf','hand_fes_em',
                                                            'gat_lift_off_inbnd_em_ft40', 'gat_lift_on_inbnd_em_ft40',
                                                            'pti', 'add_plan'
                                                        ] as $field)
                                                    <option value="{{ $field }}">{{ $field }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                {{--                                <div class="form-group col-md-12">--}}
                                {{--                                    <div class="input-group">--}}
                                {{--                                        <div class="col-md-2">--}}
                                {{--                                            <div class="input-group-prepend">--}}
                                {{--                                                <span class="input-group-text bg-transparent border-0">VAT (%)</span>--}}
                                {{--                                            </div>--}}
                                {{--                                        </div>--}}
                                {{--                                        <div class="col-md-6">--}}
                                {{--                                            <input type="number" class="form-control" id="vat" name="vat" min="0"--}}
                                {{--                                                   max="100"--}}
                                {{--                                                   autocomplete="off">--}}
                                {{--                                        </div>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-0">Invoice EGP</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="invoice_egp" class="form-control" id="invoice_egp"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="input-group">
                                        <div class="col-md-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-transparent border-0">Invoice USD</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="invoice_usd" class="form-control" id="invoice_usd"
                                                   readonly>
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
                                            <input type="text" name="total_usd" class="form-control" id="total_usd"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="container">
                                    <div id="table2-selects" class="d-none">
                                        <div class="text-center">
                                            <label>Empty/Export</label>
                                            <label class="col-md-3">From
                                                <select name="empty_export_from_id" class="form-control">
                                                    <option hidden selected>Select</option>
                                                    @foreach ($possibleMovements as $movement)
                                                        <option value="{{ $movement->id }}">{{ $movement->code }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                            <label class="col-md-3">To
                                                <select name="empty_export_to_id" class="form-control">
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
                                                <select name="empty_import_from_id" class="form-control">
                                                    <option hidden selected>Select</option>
                                                    @foreach ($possibleMovements as $movement)
                                                        <option value="{{ $movement->id }}">{{ $movement->code }}</option>
                                                    @endforeach
                                                </select>
                                            </label>
                                            <label class="col-md-3">To
                                                <select name="empty_import_to_id" class="form-control">
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
                                <button type="button" id="add-many-containers" class="btn btn-info m-3">Add Many
                                    Containers
                                </button>
                                <button type="button" id="add-row" class="btn btn-info my-3 mx-1">Add Row</button>
                            </div>
                            <div class="col-md-12">
                                <div class="table-container">
                                    <x-soa-table id="table1" :rows="$rows"/>
                                    <x-soa-table id="table2" class="d-none"/>
                                    <x-soa-table id="table3" class="d-none"/>
                                    <x-soa-table id="table4" class="d-none"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" id="submit"
                                            class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
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

        .dynamic-input:not(.included) {
            display: none;
        }

        td:not(:has(input.included)) {
            display: none;
        }
    </style>
@endpush
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        $(document).ready(function () {
            setupEventHandlers()
            switchTables()
            calculateTotals()
            hideCellsWithoutIncludedInput()
            handleDynamicFieldsChange()
            updateChargeTypeOptions('table1')
            updateChargeTypeOptions('table2')
            updateChargeTypeOptions('table3')
            updateChargeTypeOptions('table4')
        })

        function setupEventHandlers() {
            $(document).on('click', '.removeContact', handleRemoveRow)
            $("#add-row").on('click', handleAddRow)
            $(document).on('change', '.charge_type', handleChargeTypeChange)
            $(document).on('paste', '.container_no', handleContainerNoPaste)
            $(document).on('change', '.container_no', handleContainerNoChange)
            $('#vessel_id').on('change', loadVoyages)
            $(document).on('change', '#dynamic_fields', handleDynamicFieldsChange)
            $(document).on('input', '.dynamic-input', calculateTotals)
            $(document).on('change', '.pti-type', handlePtiTypeChange);
            $(document).on('change', '.power-days', handlePowerDaysChange);
            $(document).on('change', '.add-plan-select', handleAddPlanChange);
            $(document).on('click change keyup paste', () => calculateTotals());
            $('form').on('submit', e => deleteEmptyOnSubmit(e));
            $("#add-many-containers").on('click', handleAddContainers);
        }

        function handleDynamicFieldsChange() {
            const selectedFields = $('#dynamic_fields').val();
            $('.dynamic-input').removeClass('included');

            selectedFields.forEach(selectedField => {
                $('.dynamic-input[data-field*="' + selectedField + '"]').addClass('included');
            });

            const options = $('#dynamic_fields option');

            options.each(function () {
                const optionValue = $(this).val();
                const formattedValue = optionValue.replace(/_/g, '-');
                const contains = selectedFields.some(field => field.includes(optionValue));
                $('[data-field="' + formattedValue + '"]').toggleClass('d-none', !contains);
            });
            hideCellsWithoutIncludedInput();
        }


        function calculateTotals() {
            const exchangeRate = parseFloat($('#exchange_rate').val());
            let totalUSD = 0
            let invoiceUSD = 0
            let invoiceEGP = 0
            let USD_to_EGP = 0

            $('.dynamic-input.included').each(function () {
                totalUSD += parseFloat($(this).val()) || 0
            })
            $('#table1').find('.dynamic-input.included').each(function () {
                USD_to_EGP += parseFloat($(this).val()) || 0
            })

            console.log(USD_to_EGP)
            invoiceUSD = totalUSD - USD_to_EGP

            if (!isNaN(exchangeRate)) {
                invoiceEGP = USD_to_EGP * exchangeRate;
            }

            $("#total_usd").val(totalUSD)
            $("#invoice_usd").val(invoiceUSD)
            $("#invoice_egp").val(invoiceEGP)
        }


        const handleAddContainers = async e => {
            const success = await swal({
                content: {
                    element: "textarea",
                    attributes: {
                        placeholder: "Enter Containers Here",
                        id: "containers-auto"
                    },
                },
                buttons: ["no", "yes"]
            })

            if (success) {
                let containersText = document.getElementById("containers-auto").value
                $('tbody tr').filter((index, element) => !$('.container_no', element).val().trim()).remove();
                await autoAddContainers(containersText)
            }
        }

        const autoAddContainers = async (containersText) => {
            const containers = containersText.split('\n').map(containerNumber => containerNumber.trim()).filter(e => e !== "")
            const vesselId = $('#vessel_id').val();
            const voyage = $('#voyage').val();
            if (containers.length > 0 && vesselId !== '' && voyage !== '') {
                for (const container of containers) {
                    await processContainer(container, vesselId, voyage);
                }

            }
        }

        const processContainer = async (container, vesselId, voyage) => {
            try {
                const response = await axios.get('{{ route('port-charges.get-ref-no') }}', {
                    params: {
                        vessel: vesselId,
                        voyage: voyage,
                        container: container
                    }
                });

                const {ref_no, is_ts, shipment_type, quotation_type} = response.data;
                let table = '';
                let selectedCharge = null;

                if (quotation_type === 'full') {
                    table = 'table1';
                    selectedCharge = shipment_type === 'Import' ? 1 : 2;
                } else if (quotation_type === 'empty' && shipment_type === 'Export') {
                    table = 'table2';
                    selectedCharge = 4;
                } else if (quotation_type === 'empty' && shipment_type === 'Import') {
                    table = 'table3';
                    selectedCharge = 3;
                } else if (is_ts == '1') {
                    table = 'table4';
                    if (quotation_type === 'full') {
                        selectedCharge = 5
                    }
                    if (quotation_type === 'empty') {
                        selectedCharge = 6
                    }
                }

                if (table !== '') {
                    const tbody = $(`#${table} tbody`);
                    appendSingleRow(container, tbody, selectedCharge);
                    updateChargeTypeOptions(table);
                    handleDynamicFieldsChange(table);
                }
            } catch (error) {
                console.error(error);
            }
        };

        function handleRemoveRow() {
            $(this).closest("tr").remove()
            calculateTotals()
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
            const selectedOption = $(this).find('option:selected');
            const selectedValue = $(this).val();
            const row = $(this).closest('tr');
            const dynamicInputs = row.find('.dynamic-input');
            const containerInput = row.find('.container_no');
            const refNoInput = row.find('.ref-no-td');
            const ptiTypeSelect = row.find('.pti-type');
            const powerDaysSelect = row.find('.power-days');
            const addPlanSelect = row.find('.add-plan-select');
            const quotationType = row.find('.quotation_type');
            const emptyExportFromSelect = $('[name="empty_export_from_id"]');
            const emptyExportToSelect = $('[name="empty_export_to_id"]');
            const emptyImportFromSelect = $('[name="empty_import_from_id"]');
            const emptyImportToSelect = $('[name="empty_import_to_id"]');

            if (selectedValue && selectedValue !== "Select" && containerInput.val() && refNoInput.val()) {
                const requestData = {
                    charge_type: selectedValue,
                    container_no: containerInput.val(),
                    bl_no: refNoInput.val(),
                    quotation_type: quotationType.val(),
                };

                if (selectedOption.text() === 'EMPTY-EXPORT') {
                    requestData.from = emptyExportFromSelect.find('option:selected').text();
                    requestData.to = emptyExportToSelect.find('option:selected').text();
                } else if (selectedOption.text() === 'EMPTY-IMPORT') {
                    requestData.from = emptyImportFromSelect.find('option:selected').text();
                    requestData.to = emptyImportToSelect.find('option:selected').text();
                }


                axios.post('{{ route('port-charges.calculate-invoice-row') }}', requestData)
                    .then(function (response) {
                        const dynamicFields = [
                            'thc', 'storage',
                            'power', 'shifting', 'disinf',
                            'hand_fes_em', 'gat_lift_off_inbnd_em_ft40',
                            'gat_lift_on_inbnd_em_ft40', 'add_plan'
                        ]
                        dynamicFields.forEach(item => {
                            row.find(`[name*="${item}"]`).val(response.data[item]);
                        });
                        powerDaysSelect.find('option[value="none"]').data('cost', response.data['power'])
                        powerDaysSelect.find('option[value="plus"]').data('cost', response.data['power_plus_one'])
                        powerDaysSelect.find('option[value="minus"]').data('cost', response.data['power_minus_one'])
                        ptiTypeSelect.find('option[value="failed"]').data('cost', response.data['pti_failed']);
                        ptiTypeSelect.find('option[value="passed"]').data('cost', response.data['pti_passed'])
                        addPlanSelect.find('option[value="1"]').data('cost', response.data['add_plan'])
                        ptiTypeSelect.trigger('change')

                        calculateTotals();
                    })
                    .catch(function (error) {
                        console.error('Error:', error);
                    });
            }
        }

        function handleAddPlanChange() {
            const addPlanCost = $(this).find(`option:selected`).data('cost');

            const row = $(this).closest('tr');
            const addPlanInput = row.find('input[name*="rows[add_plan][]"]')
            addPlanInput.val(addPlanCost);
        }

        function handlePowerDaysChange() {
            const powerCost = $(this).find(`option:selected`).data('cost');

            const row = $(this).closest('tr');
            const powerInput = row.find('input[name*="rows[power][]"]')
            powerInput.val(powerCost);
        }

        function addPtiTypeToSelect(e) {
            const dynamicFieldsSelect = $('#dynamic_fields');
            if (dynamicFieldsSelect.find('option:selected[value="pti"]').length > 0) {
                dynamicFieldsSelect.append('<option value="pti_type" selected>PTI Type</option>');
            }
            if (dynamicFieldsSelect.find('option:selected[value="power"]').length > 0) {
                dynamicFieldsSelect.append('<option value="power_days" selected>PTI Type</option>');
            }
        }

        function deleteEmptyOnSubmit(e) {
            $('form').find('tbody tr').each(function () {
                const containerNoInput = $(this).find('.container_no');
                const chargeTypeSelect = $(this).find('.charge_type');
                const blNoInput = $(this).find('.ref-no-td');

                const containerNoValue = containerNoInput[0].value;
                const selectedChargeType = chargeTypeSelect[0].value;
                const bl_no = blNoInput[0].value;

                if (containerNoValue === '') {
                    $(this).remove()
                } else if (selectedChargeType === 'Select') {
                    swal({
                        icon: 'error',
                        text: "Please Select Charge Type"
                    })
                    e.preventDefault()
                } else if (bl_no === "") {
                    swal({
                        icon: 'error',
                        text: "No matching details for container " + containerNoValue
                    })
                    e.preventDefault()
                }
            });
            if ($('tbody tr').length === 0) {
                swal({
                    icon: 'error',
                    text: "Please Enter Rows Before Submitting"
                })
                e.preventDefault()
            }
        }


        function handleContainerNoPaste(e) {
            e.preventDefault()

            const clipboardData = getClipboardData(e)
            const containerNumbers = getPastedContainerNumbers(clipboardData)

            const row = $(this).closest('tr')
            const selectedCharge = row.find('.charge_type')[0].value
            const selectedService = row.find('.service_type')[0].value
            const tableId = row.closest('table').attr('id')

            const tbody = row.closest('tbody')
            appendNewRows(containerNumbers, tbody, selectedCharge, selectedService)
            row.remove()
            handleDynamicFieldsChange()
            updateChargeTypeOptions(tableId);
        }

        const getClipboardData = event => (event.originalEvent || event).clipboardData || window.clipboardData


        function getPastedContainerNumbers(clipboardData) {
            const pastedContent = clipboardData.getData('text/plain')
            return pastedContent.split('\n').map(containerNumber => containerNumber.trim())
        }

        function appendNewRows(containerNumbers, tbody, selectedCharge, selectedService) {
            containerNumbers.forEach(containerNumber => appendSingleRow(containerNumber, tbody, selectedCharge, selectedService))
        }

        function appendSingleRow(containerNumber, tbody, selectedCharge = null, selectedService = null) {
            if (containerNumber !== '') {
                const newRow = getNewRow(containerNumber)
                tbody.append(newRow)
                if (selectedCharge !== null) {
                    newRow.find('.charge_type').val(selectedCharge);
                }
                if (selectedService !== null) {
                    newRow.find('.service_type').val(selectedService);
                }
                newRow.find('.container_no').trigger('change')
            }
        }

        function handleContainerNoChange() {
            const containerNumber = $(this).val().trim();
            const vesselId = $('#vessel_id').val();
            const voyage = $('#voyage').val();

            if (containerNumber !== '' && vesselId !== '' && voyage !== '') {
                const row = $(this).closest('tr');
                const refNoCell = row.find('.ref-no-td')[0];
                const isTsCell = row.find('.is_transhipment')[0];
                const shipTypeCell = row.find('.shipment_type')[0];
                const quoteTypeCell = row.find('.quotation_type')[0];
                axios.get('{{ route('port-charges.get-ref-no') }}', {
                    params: {
                        vessel: vesselId,
                        voyage: voyage,
                        container: containerNumber
                    }
                }).then(response => {
                    if (response.data.status === 'success') {
                        refNoCell.value = response.data.ref_no;
                        isTsCell.value = response.data.is_ts;
                        shipTypeCell.value = response.data.shipment_type;
                        quoteTypeCell.value = response.data.quotation_type;
                        row.find('.charge_type').trigger('change');
                    }
                }).catch(() => {
                    console.error('Could not find ref_no');
                });
            }

            handleDynamicFieldsChange();
        }


        function handlePtiTypeChange() {
            const selectedPtiType = $(this).val();
            const ptiValue = $(this).find(`option:selected`).data('cost');

            const row = $(this).closest('tr');
            const ptiInput = row.find('input[name*="pti"]');
            ptiInput.val(ptiValue);
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
                        <select name="rows[port_charge_id][]" class="form-control charge_type new_charge" required>
                            <option hidden selected>Select</option>
                            @foreach($portCharges as $portCharge)
            <option value="{{ $portCharge->id }}">{{ $portCharge->name }}</option>
                            @endforeach
            </select>
        </td>
        <td>
            <select name="rows[service][]" class="form-control service_type" required>
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
        <td><input type="text" name="rows[bl_no][]" class="form-control ref-no-td"></td>
        <td><input type="text" name="rows[container_no][]" class="form-control container_no" value="${containerNumber}"></td>
                    <td><input type="text" name="rows[is_transhipment][]" class="is_transhipment form-control"></td>
                    <td><input type="text" name="rows[shipment_type][]" class="shipment_type form-control"></td>
                    <td><input type="text" name="rows[quotation_type][]" class="quotation_type form-control"></td>
                    ${generateDynamicInputsHtml()}
                </tr>
            `);

            return newRow
        }

        function generateDynamicInputsHtml() {
            const dynamicFields = [
                'thc', 'storage', 'power',
                'shifting', 'disinf', 'hand_fes_em',
                'gat_lift_off_inbnd_em_ft40', 'gat_lift_on_inbnd_em_ft40',
                'pti', 'add_plan'
            ];

            const fieldSelectOptions = {
                pti: `
                <select style="min-width: 100px" class="form-control pti-type" name="rows[pti_type][]">
                    <option value="passed" data-cost="0" selected>Passed</option>
                    <option value="failed" data-cost="0">Failed</option>
                </select>
                `,
                power: `
                <select style="min-width: 100px" class="form-control power-days" name="power_days">
                    <option value="none" data-cost="0" selected>Normal</option>
                    <option value="plus" data-cost="0">Plus One Day</option>
                    <option value="minus" data-cost="0">Minus One Day</option>
                </select>
                `,
                add_plan: `
                <select style="min-width: 100px" class="form-control add-plan-select">
                    <option value="1" data-cost="0" selected>Added</option>
                    <option value="0" data-cost="0">Not added</option>
                </select>
                `
            };

            let dynamicInputsHtml = '';

            dynamicFields.forEach(field => {
                let td_dat_field = field.replace(/_/g, '-');
                const select = fieldSelectOptions[field] || '';
                const selectHtml = select ? `
                    <td data-field="${td_dat_field}">
                        ${select}
                    </td>
                ` : '';

                dynamicInputsHtml += `
                    <td data-field="${td_dat_field}">
                        <input type="text" name="rows[${field}][]" class="form-control dynamic-input" data-field="${field}_cost">
                    </td>
                    ${selectHtml}
                `;
            });

            return dynamicInputsHtml;
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
                const optionValue = option.textContent

                if (!(allowedValues[tableId].some(allowed => optionValue.includes(allowed)))) {
                    option.remove()
                }
            })
        }

        function hideCellsWithoutIncludedInput() {
            const firstVisibleTable = $('table:not(.d-none):first');
            firstVisibleTable.find('td:has(input.dynamic-input)').each(function () {
                const $input = $(this).find('input.dynamic-input');
                if (!$input.hasClass('included')) {
                    $(this).addClass('d-none');
                } else {
                    $(this).removeClass('d-none');
                }
            });
        }


    </script>
@endpush

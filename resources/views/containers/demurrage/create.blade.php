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
                                <li class="breadcrumb-item"><a a href="{{route('demurrage.index')}}">Demurrage &
                                        Dentention</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Add New Demurrage &
                                        Dentention</a></li>

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
                                    <select class="selectpicker form-control" id="country" data-live-search="true"
                                            name="country_id" data-size="10"
                                            title="{{trans('forms.select')}}" required>
                                        @foreach ($countries as $item)
                                            <option
                                                    value="{{$item->id}}" {{$item->id == old('country_id') ? 'selected':''}}>{{$item->name}}</option>
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
                                    <select class="selectpicker form-control" id="port" data-live-search="true"
                                            name="port_id" data-size="10" required>
                                        <option value="">Select...</option>
                                        @foreach ($ports as $item)
                                            <option
                                                    value="{{$item->id}}" {{$item->id == old('port_id') ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('port_id')
                                    <div style="color:red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="currency">Currency <span
                                                class="text-warning"> * (Required.) </span></label>
                                    <select class="selectpicker form-control" id="currency" data-live-search="true"
                                            name="currency" data-size="10"
                                            title="{{trans('forms.select')}}" required>
                                        @foreach ($currency as $item)
                                            <option
                                                    value="{{$item->name}}" {{$item->id == old('currency') ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('currency')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="validity_from">Validity From <span
                                                class="text-warning"> * (Required.) </span></label>
                                    <input type="date" class="form-control" id="currency" name="validity_from"
                                           value="{{old('validity_from')}}"
                                           placeholder="Validity From" autocomplete="off" required>
                                    @error('validity_from')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="validity_from">Validity to <span
                                                class="text-warning"> * (Required.) </span></label>
                                    <input type="date" class="form-control" id="currency" name="validity_to"
                                           value="{{old('validity_to')}}"
                                           placeholder="Validity To" autocomplete="off" required>
                                    @error('validity_to')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="Triffs">Triff</label>
                                    <select class="selectpicker form-control" id="triff_kind" data-live-search="true"
                                            name="tariff_id" data-size="10"
                                            title="{{trans('forms.select')}}" autofocus>
                                        @foreach ($triffs as $item)
                                            <option
                                                    value="{{$item->name}}" {{$item->name == old('tariff_id') ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('tariff_id')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="terminal">Terminal <span
                                                class="text-warning"> * (Required.) </span></label>
                                    <select class="selectpicker form-control" id="terminal" data-live-search="true"
                                            name="terminal_id" data-size="10" required>
                                        <option value="">Select...</option>
                                        @foreach ($terminals as $item)
                                            <option
                                                    value="{{$item->id}}" {{$item->id == old('terminal_id') ? 'selected':''}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('terminal_id')
                                    <div style="color:red;">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="tariff_type_id">Tariff Type</label>
                                    <select class="form-control" id="tariff_type_id" name="tariff_type_id" required>
                                        <option value="" selected hidden>Select Tariff Type...</option>
                                        @foreach ($tariffTypes as $tariffType)
                                            <option value="{{ $tariffType->id }}">{{ $tariffType->code }}
                                                - {{ $tariffType->description }}</option>
                                        @endforeach
                                    </select>
                                    @error('tariff_type_ide')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="container_status">Container Status </label>
                                    <input class="form-control" id="container_status" name="container_status" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="is_storage">Detention OR Storage</label>
                                    <input class="form-control" id="is_storage" name="is_storage" readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="bound_id">Bound</label>
                                    <input class="form-control" id="bound_id" name="bound_id" readonly>
                                </div>
                            </div>


                            <div class="container-fluid">
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row align-items-center">
                                                    <div class="col-md-8">
                                                        <h5 class="card-title">Period</h5>
                                                    </div>
                                                    <div class="col-md-4 text-right">
                                                        <button type="button" class="btn btn-sm btn-success"
                                                                id="addSlab">Create Slab
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-primary"
                                                                id="updateSlab">Update Slab
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-md-3 offset-md-1">
                                                        <label for="containersTypesInputHeader"
                                                               class="text-center w-30">Select Container Type:</label>
                                                        <select class="selectpicker form-control"
                                                                id="containersTypesInputHeader"
                                                                data-live-search="true" name="container_type_id"
                                                                data-size="10"
                                                                title="{{trans('forms.select')}}" required>
                                                            @foreach ($containersTypes as $item)
                                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="period" class="table table-bordered table-hover">
                                                        <thead class="thead-light">
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
                                                                <input type="text" id="period" name="period[0][period]"
                                                                       class="form-control period"
                                                                       autocomplete="off">
                                                                <input name="period[0][container_type]"
                                                                       class="container_type" hidden>
                                                            </td>
                                                            <td>
                                                                <input type="text" id="rate" name="period[0][rate]"
                                                                       class="form-control rate"
                                                                       autocomplete="off">
                                                            </td>
                                                            <td>
                                                                <input type="text" id="days"
                                                                       name="period[0][number_off_days]"
                                                                       class="form-control days" autocomplete="off">
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title">Slabs</h5>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-primary">Button 1
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-secondary">Button 2
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table id="slabs" class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th>Equipment Type</th>
                                                            <th>Status</th>
                                                            <th>Currency Code</th>
                                                            <th>Container Status</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit"
                                            class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
                                    <a href="{{route('demurrage.index')}}"
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
        var counter = 0
        document.addEventListener('DOMContentLoaded', function () {
            const addSlabButton = document.getElementById('addSlab');
            const periodTableBody = document.querySelector('#period tbody');
            const slabsTableBody = document.querySelector('#slabs tbody');
            const containersTypesInput = document.getElementById('containersTypesInputHeader');

            addSlabButton.addEventListener('click', function () {
                const periodRows = Array.from(periodTableBody.querySelectorAll('tr:not(.d-none)'));

                let count = counter++
                const equipmentType = containersTypesInput.value

                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td><input type="text" class="equipmentType form-control period" value="${equipmentType}" /></td>
                    <td><input type="text" class="status form-control period" value="Active" /></td>
                    <td><input type="text" class="currencyCode form-control period" value="Demurrage" /></td>
                    <td><input type="text" class="containerStatus form-control period" /></td>
                    <td>
                        <button class="removeSlabBtn btn btn-danger" id="${count}"><i class="fa fa-trash"></i></button>
                    </td>
                `;

                slabsTableBody.appendChild(newRow);

                // Remove the row from the period table

                // Optionally, you can add a remove button handler for the new row.
                newRow.querySelector('.removeSlabBtn').addEventListener('click', function (e) {
                    let rowId = e.target.id
                    this.closest('tr').remove()
                    console.log(`.row-${rowId}`)
                    document.querySelectorAll(`.row-${rowId}`).forEach(row => row.remove())
                });


                periodRows.forEach(row => {
                    const periodInput = row.querySelector('.period').value;
                    const rateInput = row.querySelector('.rate').value;
                    const daysInput = row.querySelector('.days').value;

                    row.querySelector('.container_type').value = equipmentType
                    row.className = `d-none row-${count}`;
                });
            });
        });
    </script>


    <script>
        $(document).ready(function () {
            $("#period").on("click", ".remove", function () {
                $(this).closest("tr").remove();
            });
            var counter = 1;
            $("#add").click(function () {
                var tr = '<tr>' +
                    '<td><input type="text" name="period[' + counter + '][period]" class="form-control period"><input name="period[' + counter + '][container_type]" class="container_type" hidden></td>' +
                    '<td><input type="text" name="period[' + counter + '][rate]" class="form-control rate"></td>' +
                    '<td><input type="text" id="days" name="period[' + counter + '][number_off_days]" class="form-control days" autocomplete="off"></td>' +
                    '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>' +
                    '</tr>';

                counter++;
                $('#period').append(tr);
                $('.selectpicker').selectpicker('refresh');
            });

        });
    </script>
    <script>
        $(document).ready(function () {
            $('#tariff_type_id').on('change', function () {
                let selectedOption = $(this).find('option:selected').text();
                updateInputs(selectedOption);
            });

            function updateInputs(selectedOption) {
                let bound_id = $('#bound_id');
                let is_storage = $('#is_storage');
                let container_status = $('#container_status');

                if (selectedOption.includes('EDET')) {
                    setValues('EXPORT', 'EXPORT/DETENTION', 'FULL');
                } else if (selectedOption.includes('ESTO')) {
                    setValues('EXPORT', 'EXPORT/STORAGE', 'FULL');
                } else if (selectedOption.includes('IDET')) {
                    setValues('IMPORT', 'IMPORT/DETENTION', 'FULL');
                } else if (selectedOption.includes('ISTO')) {
                    setValues('IMPORT', 'IMPORT/STORAGE', 'FULL');
                } else if (selectedOption.includes('EEST')) {
                    setValues('EXPORT', 'EXPORT/STORAGE', 'EMPTY');
                } else if (selectedOption.includes('IEST')) {
                    setValues('IMPORT', 'IMPORT/STORAGE', 'EMPTY');
                } else if (selectedOption.includes('PCEX')) {
                    setValues('EXPORT', 'EXPORT/POWER', 'FULL');
                } else if (selectedOption.includes('PCIM')) {
                    setValues('IMPORT', 'IMPORT/POWER', 'FULL');
                } else {
                    setValues('', '', '');
                }

                function setValues(bound, storage, status) {
                    bound_id.val(bound);
                    is_storage.val(storage);
                    container_status.val(status);
                }
            }

            $('#triff_kind').on('change', function (e) {
                console.log(123)
                let selectedOption = $(this).val();

                if (selectedOption.includes('Customer')) {
                    $("#tariff_type_id option[value='5'], #tariff_type_id option[value='6']").hide();

                    if ($("#tariff_type_id option:selected").is(':hidden')) {
                        $("#tariff_type_id").val($("#tariff_type_id option:visible:first").val());
                        $("#bound_id").val('')
                        $("#is_storage").val('')
                        $("#container_status").val('')
                    }
                } else {
                    $("#tariff_type_id option[value='5'], #tariff_type_id option[value='6']").show();
                }

                $('.selectpicker').selectpicker('refresh');
            })
        })
        $(function () {
            let country = $('#country');
            let company_id = "{{optional(Auth::user())->company->id}}";
            $('#country').on('change', function (e) {
                let value = e.target.value;
                let response = $.get(`/api/master/ports/${country.val()}/${company_id}`).then(function (data) {
                    let ports = data.ports || '';
                    let list2 = [`<option value=''>Select...</option>`];
                    for (let i = 0; i < ports.length; i++) {
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
        $(function () {
            let port = $('#port');
            $('#port').on('change', function (e) {
                let value = e.target.value;
                let response = $.get(`/api/master/terminals/${port.val()}`).then(function (data) {
                    let terminals = data.terminals || '';
                    let list2 = [`<option value=''>Select...</option>`];
                    for (let i = 0; i < terminals.length; i++) {
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

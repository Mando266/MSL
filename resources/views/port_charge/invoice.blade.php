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
                                                <option value="Storage Of Full Inbound Containers">Storage Of Full
                                                    Inbound Containers
                                                </option>
                                                <option value="Vessel Discharging And Loading Operations">Vessel
                                                    Discharging And Loading Operations
                                                </option>
                                                <option value="Withdrawal And Stuffing Containers">Withdrawal And
                                                    Stuffing Containers
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
                                            <th rowspan="3" style="min-width: 222px">NAME</th>
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
                                            <th rowspan="3">Edit</th>
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
                                        @foreach ($portCharges as $portCharge)
                                            <tr>
                                                <td>{{ $portCharge->chargeMatrix->name }}</td>
                                                <td class="editable">{{ $portCharge->thc_20ft }}</td>
                                                <td class="editable">{{ $portCharge->thc_40ft }}</td>
                                                <td class="editable">{{ $portCharge->storage_free }}</td>
                                                <td class="editable">{{ $portCharge->storage_slab1_period }}</td>
                                                <td class="editable">{{ $portCharge->storage_slab1_20ft }}</td>
                                                <td class="editable">{{ $portCharge->storage_slab1_40ft }}</td>
                                                <td class="editable">{{ $portCharge->storage_slab2_period }}</td>
                                                <td class="editable">{{ $portCharge->storage_slab2_20ft }}</td>
                                                <td class="editable">{{ $portCharge->storage_slab2_40ft }}</td>
                                                <td class="editable">{{ $portCharge->power_20ft }}</td>
                                                <td class="editable">{{ $portCharge->power_40ft }}</td>
                                                <td class="editable">{{ $portCharge->shifting_20ft }}</td>
                                                <td class="editable">{{ $portCharge->shifting_40ft }}</td>
                                                <td class="editable">{{ $portCharge->disinf_20ft }}</td>
                                                <td class="editable">{{ $portCharge->disinf_40ft }}</td>
                                                <td class="editable">{{ $portCharge->hand_fes_em_20ft }}</td>
                                                <td class="editable">{{ $portCharge->hand_fes_em_40ft }}</td>
                                                <td class="editable">{{ $portCharge->gat_lift_off_inbnd_em_ft40_20ft }}</td>
                                                <td class="editable">{{ $portCharge->gat_lift_off_inbnd_em_ft40_40ft }}</td>
                                                <td class="editable">{{ $portCharge->gat_lift_on_inbnd_em_ft40_20ft }}</td>
                                                <td class="editable">{{ $portCharge->gat_lift_on_inbnd_em_ft40_40ft }}</td>
                                                <td class="editable">{{ $portCharge->pti_failed }}</td>
                                                <td class="editable">{{ $portCharge->pti_passed }}</td>
                                                <td class="editable">{{ $portCharge->wire_trnshp_20ft }}</td>
                                                <td class="editable">{{ $portCharge->wire_trnshp_40ft }}</td>
                                                <td>
                                                    <button class="btn btn-info edit-button" data-id="{{ $portCharge->id }}">
                                                        Edit
                                                    </button>
                                                    {{--                                            <button class="btn btn-danger delete-button"--}}
                                                    {{--                                                    data-id="{{ $portCharge->id }}">--}}
                                                    {{--                                                Delete--}}
                                                    {{--                                            </button>--}}
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr class="add-row d-none">
                                            <td class="editable">
                                                <input class="form-control" type="text" name="thc_20ft"/>
                                            </td>
                                            <td class="editable">
                                                <input class="form-control" type="text" name="thc_40ft"/>
                                            </td>
                                            <td class="editable">
                                                <input class="form-control" type="text" name="storage_20ft"/>
                                            </td>
                                            <td class="editable">
                                                <input class="form-control" type="text" name="storage_40ft_first_5"/>
                                            </td>
                                            <td class="editable">
                                                <input class="form-control" type="text" name="storage_40ft_after_5"/>
                                            </td>
                                            <td class="editable">
                                                <input class="form-control" type="text" name="power_20ft"/>
                                            </td>
                                            <td class="editable">
                                                <input class="form-control" type="text" name="power_40ft"/>
                                            </td>
                                            <td class="editable"><input class="form-control" type="text" name="shifting_20ft"/>
                                            </td>
                                            <td class="editable"><input class="form-control" type="text" name="shifting_40ft"/>
                                            </td>
                                            <td class="editable"><input class="form-control" type="text" name="disinf_20ft"/>
                                            </td>
                                            <td class="editable"><input class="form-control" type="text" name="disinf_40ft"/>
                                            </td>
                                            <td class="editable"><input class="form-control" type="text"
                                                                        name="hand_fes_em_20ft"/>
                                            </td>
                                            <td class="editable"><input class="form-control" type="text"
                                                                        name="hand_fes_em_40ft"/>
                                            </td>
                                            <td class="editable">
                                                <input class="form-control" type="text"
                                                       name="gat_lift_off_inbnd_em_ft40_20ft"/>
                                            </td>
                                            <td class="editable">
                                                <input class="form-control" type="text"
                                                       name="gat_lift_off_inbnd_em_ft40_40ft"/></td>
                                            <td class="editable">
                                                <input class="form-control" type="text" name="gat_lift_on_inbnd_em_ft40_20ft"/>
                                            </td>
                                            <td class="editable">
                                                <input class="form-control" type="text" name="gat_lift_on_inbnd_em_ft40_40ft"/>
                                            </td>
                                            <td class="editable">
                                                <input class="form-control" type="text" name="pti_20ft"/>
                                            </td>
                                            <td class="editable">
                                                <input class="form-control" type="text" name="pti_40ft_failed"/>
                                            </td>
                                            <td class="editable">
                                                <input class="form-control" type="text" name="pti_40ft_pass"/>
                                            </td>
                                            <td class="editable">
                                                <input class="form-control" type="text" name="wire_trnshp_20ft"/>
                                            </td>
                                            <td class="editable">
                                                <input class="form-control" type="text" name="wire_trnshp_40ft"/>
                                            </td>
                                            <td class="actions-td">
                                                <button class="btn btn-success save-new-button">
                                                    Save
                                                </button>
                                            </td>
                                        </tr>


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
@push('scripts')
    <script>
        $(function () {
            let vessel = $('#vessel_id');
            $('#vessel_id').on('change', function (e) {
                let value = e.target.value;
                let response = $.get(`/api/vessel/voyages/${vessel.val()}`).then(function (data) {
                    let voyages = data.voyages || '';
                    let list2 = [];
                    for (let i = 0; i < voyages.length; i++) {
                        list2.push(`<option value='${voyages[i].id}'>${voyages[i].voyage_no} - ${voyages[i].leg}</option>`);
                    }
                    let voyageno = $('#voyage');
                    voyageno.html(list2.join(''));
                    $('.selectpicker').selectpicker('refresh');
                });
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    @include('port_charge._js')
@endpush

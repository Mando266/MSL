@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="">Storage</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Storage Calculation</a>
                            </li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>

                <div class="widget-content widget-content-area">
                    @if(isset($error))
                        <div class="error-message">
                            {{ $error }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="error-message">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="validation-errors">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{route('storage.store')}}" method="POST">
                        @csrf

                        @php
                            $calculation = session('calculation');
                            $input = session('input');
                            // dd(isset($input) ? $input['bl_no'] : '');
                        @endphp
                        <div class="form-row">
                            <div class="form-group col-md-4" >
                                <label>BL NO</label>
                                <select class="selectpicker form-control" id="blno" data-live-search="true" name="bl_no" data-size="10"
                                title="{{trans('forms.select')}}" required>
                                    @foreach($movementsBlNo as $item)
                                        <option
                                            value="{{$item->id}}" {{$item->id == old('bl_no',isset($input) ? $input['bl_no'] : '') ? 'selected':''}}>{{$item->ref_no}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="Date">Container No</label>
                                <select class="selectpicker form-control" id="port" data-live-search="true"
                                        name="container_code[]" data-size="10"
                                        title="{{trans('forms.select')}}" required multiple>
                                    <option
                                        value="all" {{ "all" == old('container_code',isset($input) ? $input['container_code'] : '') ? 'selected':'hidden'}}>
                                        All
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="Date">Services</label>
                                <select class="selectpicker form-control" data-live-search="true" name="service"
                                        data-size="10" id="service"
                                        title="{{trans('forms.select')}}" required>
                                    @foreach($services as $service)
                                        <option
                                            value="{{$service->id}}" {{$service->id == old('service',isset($input)?? $input['service']) ? 'selected':''}}>{{$service->description}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="countryInput">Select Triff</label>
                                <select class="selectpicker form-control" id="triff_id" data-live-search="true"
                                        name="Triff_id" data-size="10"
                                        title="{{trans('forms.select')}}" required>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>From</label>
                                <select class="selectpicker form-control" data-live-search="true" name="from" data-size="10"
                                        title="{{trans('forms.select')}}">
                                    @foreach ($movementsCode as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('from',isset($input) ? $input['from'] : '') ? 'selected' : ''}}>{{$item->code}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('from'))
                                    <span class="text-danger">{{ $errors->first('from') }}</span>
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label>To</label>
                                    <select class="selectpicker form-control" data-live-search="true" name="to" data-size="10"
                                    title="{{trans('forms.select')}}">
                                        @foreach ($movementsCode as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('to',isset($input) ? $input['to'] : '') ? 'selected' : ''}}>{{$item->code}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('to'))
                                        <span class="text-danger">{{ $errors->first('to') }}</span>
                                    @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label>Till Date</label>
                                <input type="date" name="date" class="form-control" value="{{old('date',isset($input) ? $input['date'] : '')}}">
                                @if ($errors->has('date'))
                                    <span class="text-danger">{{ $errors->first('date') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-info mt-3">Calculate</button>
                            </div>
                        </div>
                        @isset($calculation)
                            <h4 style="color:#1b55e2">Calculation
                                <h4>
                                    <table id="charges" class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th class="col-md-2 text-center">Container No</th>
                                            <th class="col-md-8 text-center" colspan="4">Calculation Details</th>
                                            <th class="col-md-2 text-center">Total ({{$calculation['currency']}})</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($calculation['containers'] as $item)
                                        <tr>
                                            <td class="col-md-2 text-center">{{$item['container_no']}} {{$item['container_type']}}</td>
                                            <td class="col-md-2" style="border-right-style: hidden;">
                                                From: {{$item['from']}} <br>
                                                To: {{$item['to']}}
                                            </td>
                                            <td class="col-md-2" style="border-right-style: hidden;">
                                                @foreach($item['periods'] as $period)
                                                    {{ $period['name'] }} <br>
                                                @endforeach
                                            </td>
                                            <td class="col-md-2" style="border-right-style: hidden;">
                                                @foreach($item['periods'] as $period)
                                                    {{ $period['days'] }} Days <br>
                                                @endforeach
                                            </td>
                                            <td class="col-md-2">
                                                @foreach($item['periods'] as $period)
                                                    {{ $period['total'] }} <br>
                                                @endforeach
                                            </td>
                                            <td class="col-md-2 text-center">
                                                {{$item['total']}}
                                            </td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td class="col-md-2" style="border-right-style: hidden;"></td>
                                            <td class="col-md-2" style="border-right-style: hidden;"></td>
                                            <td class="col-md-2" style="border-right-style: hidden;"></td>
                                            <td class="col-md-2" style="border-right-style: hidden;"></td>
                                            <td class="col-md-2"></td>
                                            <td class="col-md-2 text-center">
                                                {{$calculation['grandTotal']}}
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>

                        @endisset
                    </form>
                    @isset($calculation)
{{--                        <form id="createForm" action="{{route('invoice.create_invoice')}}" method="get">--}}
{{--                            @csrf--}}
{{--                            <input type="hidden" id="bldraft_id" name="bldraft_id">--}}
{{--                            <input type="hidden" value="{{$calculation['grandTotal']}}" name="total_storage">--}}
{{--                            <button type="submit" class="btn btn-primary mt-3">Create Invoice</button>--}}
{{--                        </form>--}}
                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let selectedCodes = '{{ implode(',',$input['container_code'] ??[]) }}'
    selectedCodes = selectedCodes.split(',')
    let selectedTriff = '{{ $input['Triff_id'] ?? '' }}'

    let service = $('#service');
    let company_id = "{{auth()->user()->company_id}}";

    $(document).ready(function () {
        const getTriff = () => {
            let value = service.val();
            $.get(`/api/storage/triffs/${service.val()}/${company_id}`).then(function (data) {
                let triffs = data.triffs || '';
                let list2 = [];
                for (let i = 0; i < triffs.length; i++) {
                    (triffs[i].id == selectedTriff) ?
                        list2.push(`<option value="${triffs[i].id}" selected>${triffs[i].is_storge} ${triffs[i].bound} ${triffs[i].portsCode} ${triffs[i].containersTypeName}</option>`) :
                        list2.push(`<option value="${triffs[i].id}">${triffs[i].tariffTypeCode}  ${triffs[i].portsCode} ${triffs[i].validfrom} ${triffs[i].validto}</option>`);
                }
                let triff = $('#triff_id');
                triff.html(list2.join(''));
                $('.selectpicker').selectpicker('refresh');
            });
        }
        if (service.val()) {
            getTriff()
        }

        service.on('change',() => getTriff())

        const getContainers = () => {
            let bl = $('#blno');
            $('#bldraft_id').val(bl.val());
            let isSelected = "";
            let company_id = "{{auth()->user()->company_id}}";
            let response = $.get(`/api/storage/bl/containers/${bl.val()}/${company_id}`).then(function (data) {
                let containers = data.containers || '';
                let list2 = [`<option value='all'>All</option>`];
                for (let i = 0; i < containers.length; i++) {
                    (selectedCodes.includes(containers[i].id.toString())) ?
                        list2.push(`<option value='${containers[i].id}' selected>${containers[i].code} </option>`) :
                        list2.push(`<option value='${containers[i].id}'>${containers[i].code} </option>`)
                }
                let container = $('#port');
                container.html(list2.join(''));
                $('.selectpicker').selectpicker('refresh');
            });
        }
        if ($('#blno').val()) {
            getContainers()
        }
        document.getElementById('blno').addEventListener('change', getContainers)

        $('#port').change(function () {
            var selectedValue = $(this).val();
            if (selectedValue.length > 1 && selectedValue.includes('all')) {
                selectedValue = selectedValue.filter(function (value) {
                    return value !== 'all';
                });
                $(this).val(selectedValue);
            }

            if (selectedValue.includes('all')) {
                $('#port option:not(:selected)').prop('disabled', true);
            } else {
                $('#port option').prop('disabled', false);
            }
            $('#port').selectpicker('refresh');
        });
    });



</script>

@endpush

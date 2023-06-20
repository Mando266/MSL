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
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Storage Calculation</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
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
                                        <option value="{{$item->ref_no}}" {{$item->ref_no == old('bl_no',isset($input) ? $input['bl_no'] : '') ? 'selected':''}}>{{$item->ref_no}}</option>
                                    @endforeach
                                    </select>
                            </div>
                            <div class="form-group col-md-4" >
                                <label for="Date">Container No</label>
                                    <select class="selectpicker form-control" id="port" data-live-search="true"name="container_code[]" data-size="10"
                                        title="{{trans('forms.select')}}" required multiple>
                                        <option value="all" {{ "all" == old('container_code',isset($input) ? $input['container_code'] : '') ? 'selected':'hidden'}}>All</option>
                                    </select>
                            </div> 
                            <div class="form-group col-md-4">
                                <label for="Date">Services</label>
                                    <select class="selectpicker form-control" data-live-search="true" name="service" data-size="10" id="service"
                                        title="{{trans('forms.select')}}" required>
                                        <option value="power charges" {{ "power charges" == old('service',isset($input) ? $input['service'] : '') ? 'selected' : ''}}>Power Charges</option>
                                        <option value="Export Empty"  {{ "Export Empty" == old('service',isset($input) ? $input['service'] : '') ? 'selected' : ''}}>Export Empty</option>
                                        <option value="Export Full"   {{ "Export Full" == old('service',isset($input) ? $input['service'] : '') ? 'selected' : ''}}>Export Full</option>
                                        <option value="Import Empty"  {{ "Import Empty" == old('service',isset($input) ? $input['service'] : '') ? 'selected' : ''}}>Import Empty</option>
                                        <option value="Import Full"   {{ "Import Full" == old('service',isset($input) ? $input['service'] : '') ? 'selected' : ''}}>Import Full</option>
                                    </select>
                            </div> 
                        </div> 
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="countryInput">Select Triff</label>
                                    <select class="selectpicker form-control" id="triff_id" data-live-search="true" name="Triff_id" data-size="10"
                                        title="{{trans('forms.select')}}" required>
                                    </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>From</label>
                                <input type="text" name="from" class="form-control" value="{{old('from',isset($input) ? $input['from'] : '')}}" readonly>
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
                        {{-- @dd(isset($calculation)) --}}
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-info mt-3">Calculate</button>
                            </div>
                       </div>
                       @isset($calculation)
                            <h4 style="color:#1b55e2">Calculation<h4>
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
                                            <td class="col-md-2 text-center">{{$item['container_no']}}</td>
                                            <td class="col-md-2" style="border-right-style: hidden;">
                                                From: {{$item['from']}} <br>
                                                From: {{$item['to']}}
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
    let company_id = "{{optional(Auth::user())->company->id}}";
    
    $(document).ready(function () {
        const getTriff = () => {
            let value = service.val();
            $.get(`/api/storage/triffs/${service.val()}/${company_id}`).then(function (data) {
                let triffs = data.triffs || '';
                let list2 = [];
                for (let i = 0; i < triffs.length; i++) {
                    (triffs[i].id == selectedTriff) ?
                        list2.push(`<option value="${triffs[i].id}" selected>${triffs[i].is_storge} ${triffs[i].bound} ${triffs[i].portsCode} ${triffs[i].containersTypeName}</option>`) :
                        list2.push(`<option value="${triffs[i].id}">${triffs[i].is_storge} ${triffs[i].bound} ${triffs[i].portsCode} ${triffs[i].containersTypeName}</option>`);
                }
                let triff = $('#triff_id');
                triff.html(list2.join(''));
                $('.selectpicker').selectpicker('refresh');
            });
        }
        if(service.val())
        {
            getTriff()
        }
        
        service.on('change',() => getTriff())
        
        const getContainers = () => {
            let bl = $('#blno');
            let isSelected = "";
            let company_id = "{{optional(Auth::user())->company->id}}";
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



        $(function(){
            let service = $('#service');
            $('#service').on('change',function(e){
                let value = e.target.value;
                if(value == "power charges"){
                    $('input[name="from"]').val("RCVS");
                }
                if(value == "Export Full"){
                    $('input[name="from"]').val("RCVS");
                }
                if(value == "Import Full"){
                    $('input[name="from"]').val("DCHF");
                }
                $('.selectpicker').selectpicker('refresh');
            });
        });
</script>

@endpush

@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Containers Movement</a></li>
                            <li class="breadcrumb-item"><a href="{{route('container-movement.index')}}">Movement Code</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Add New Movement Code</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('container-movement.store')}}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nameInput"> Name *</label>
                            <input type="text" class="form-control" id="nameInput" name="name" value="{{old('name')}}"
                                 placeholder="Name" autocomplete="off" autofocus>
                                @error('name')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="codeInput">Code</label>
                                <input type="text" class="form-control" id="codeInput" name="code" value="{{old('code')}}"
                                    placeholder="Code" autocomplete="off">
                                @error('code')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="container_statusInput"> Container Status </label>
                                <select class="selectpicker form-control" id="container_statusInput" data-live-search="true" name="container_status_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($container_status as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('container_status_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('container_status_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                        <div class="form-group col-md-4">
                                <label for="stock_type_idInput"> Stock Type </label>
                                <select class="selectpicker form-control" id="stock_type_idInput" data-live-search="true" name="stock_type_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($container_stock as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('stock_type_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('stock_type_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <label for="allowedInput">ALLOWED NEXT MOVES</label>
                            </br>
                            </br>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <input type="checkbox" id="is_on_hireInput" name="is_on_hire" value="1"><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px;"> ONHR (ON HIRE) </a>
                                </br>
                                </br>
                                <input type="checkbox" id="is_off_hireInput" name="is_off_hire" value="1"><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px;"> OFHR (OFF HIRE) </a>
                                </br>
                                </br>
                                <input type="checkbox" id="is_sntsInput" name="is_snts" value="1"><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px;"> SNTS (SENT TO SHIPPER) </a>
                                </br>
                                </br>
                                <input type="checkbox" id="is_rcvsInput" name="is_rcvs" value="1"><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px;"> RCVS (RECEIVED FROM SHIPPER) </a>
                                </br>
                                </br>
                                <input type="checkbox" id="is_load_fullInput" name="is_load_full" value="1"><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px;"> LODF (LOAD FULL) </a>
                                </br>
                                </br>
                                <input type="checkbox" id="is_dchfInput" name="is_dchf" value="1"><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px;"> DCHF (DISCHARGE FULL) </a>
                            </div>

                            <div class="form-group col-md-4">
                                <input type="checkbox" id="is_sntcInput" name="is_sntc" value="1"><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px;"> SNTC (SENT TO CONSIGNEE) </a>
                                </br>
                                </br>
                                <input type="checkbox" id="is_rcvcInput" name="is_rcvc" value="1"><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px;"> RCVC (RECEIVED FROM CONSIGNEE) </a>
                                </br>
                                </br>
                                <input type="checkbox" id="is_load_emptyInput" name="is_load_empty" value="1"><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px;"> LODE (LOAD EMPTY) </a>
                                </br>
                                </br>
                                <input type="checkbox" id="is_dcheInput" name="is_dche" value="1"><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px;"> DCHE (DISCHARGE EMPTY) </a>
                                </br>
                                </br>
                                <input type="checkbox" id="is_sntrInput" name="is_sntr" value="1"><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px;"> SNTR (SENT FOR STRIPPING) </a>
                                </br>
                                </br>
                                <input type="checkbox" id="is_rcveInput" name="is_rcve" value="1"><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px;"> RCVE (RECIEVED EMPTY) </a>
                            </div>

                            <div class="form-group col-md-4">

                                <input type="checkbox" id="is_lodtInput" name="is_lodt" value="1"><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px;"> LODT (LOAD FULL TRANSHIPMENT) </a>
                                </br>
                                </br>
                                <input type="checkbox" id="is_dchtInput" name="is_dcht" value="1"><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px;"> DCHT (DISCHARGE FULL TRANSHIPMENT) </a>
                                </br>
                                </br>
                                <input type="checkbox" id="is_trffInput" name="is_trff" value="1"><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px;"> TRFF (TRANSFER FULL CONTAINER) </a>
                                </br>
                                </br>
                                <input type="checkbox" id="is_trfeInput" name="is_trfe" value="1"><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px;"> TRFE (TRANSFER EMPTY CONTAINER) </a>
                                </br>
                                </br>
                                <input type="checkbox" id="is_rcvfInput" name="is_rcvf" value="1"><a style="font-size: 15px; color: #3b3f5c; letter-spacing: 1px;"> RCVF (RECIEVED FULL) </a>
                            </div>
                        </div>


                       <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
                                <a href="{{route('container-movement.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                       </div>


                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
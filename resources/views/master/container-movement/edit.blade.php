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
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> {{trans('forms.edit')}}</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('container-movement.update',['container_movement'=>$container_movement])}}" method="POST">
                        @csrf
                        @method('put')
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nameInput">Name *</label>
                            <input type="text" class="form-control" id="nameInput" name="name" value="{{old('name',$container_movement->name)}}"
                                 placeholder="Name" autocomplete="disabled" autofocus>
                                @error('name')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="codeInput">Code</label>
                                <input type="text" class="form-control" id="codeInput" name="code" value="{{old('code',$container_movement->code)}}"
                                    placeholder="Code" autocomplete="disabled">
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
                                        <option value="{{$item->id}}" {{$item->id == old('container_status_id',$container_movement->container_status_id) ? 'selected':''}}>{{$item->name}}</option>
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
                                        <option value="{{$item->id}}" {{$item->id == old('stock_type_id',$container_movement->stock_type_id) ? 'selected':''}}>{{$item->name}}</option>
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
                            <div class="form-group col-md-3">
                                <label for="status">ONHR (ON HIRE)</label>
                                <select class="selectpicker form-control"  name="is_on_hire">
                                    <option value="1" {{ old('status',$container_movement->is_on_hire) == "1" ? 'selected':'' }}>Yes</option>
                                    <option value="" {{ old('status',$container_movement->is_on_hire) == "" ? 'selected':'' }}>No</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="status">OFHR (OFF HIRE)</label>
                                <select class="selectpicker form-control"  name="is_off_hire">
                                    <option value="1" {{ old('status',$container_movement->is_off_hire) == "1" ? 'selected':'' }}>Yes</option>
                                    <option value="" {{ old('status',$container_movement->is_off_hire) == "" ? 'selected':'' }}>No</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="status">SNTS (SENT TO SHIPPER)</label>
                                <select class="selectpicker form-control"  name="is_snts">
                                    <option value="1" {{ old('status',$container_movement->is_snts) == "1" ? 'selected':'' }}>Yes</option>
                                    <option value="" {{ old('status',$container_movement->is_snts) == "" ? 'selected':'' }}>No</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="status">RCVS </label>
                                <select class="selectpicker form-control"  name="is_rcvs">
                                    <option value="1" {{ old('status',$container_movement->is_rcvs) == "1" ? 'selected':'' }}>Yes</option>
                                    <option value="" {{ old('status',$container_movement->is_rcvs) == "" ? 'selected':'' }}>No</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="status">LODF (LOAD FULL)</label>
                                <select class="selectpicker form-control"  name="is_load_full">
                                    <option value="1" {{ old('status',$container_movement->is_load_full) == "1" ? 'selected':'' }}>Yes</option>
                                    <option value="" {{ old('status',$container_movement->is_load_full) == "" ? 'selected':'' }}>No</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="status">DCHF (DISCHARGE FULL)</label>
                                <select class="selectpicker form-control"  name="is_dchf">
                                    <option value="1" {{ old('status',$container_movement->is_dchf) == "1" ? 'selected':'' }}>Yes</option>
                                    <option value="" {{ old('status',$container_movement->is_dchf) == "" ? 'selected':'' }}>No</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="status">SNTC (SENT TO CONSIGNEE)</label>
                                <select class="selectpicker form-control"  name="is_sntc">
                                    <option value="1" {{ old('status',$container_movement->is_sntc) == "1" ? 'selected':'' }}>Yes</option>
                                    <option value="" {{ old('status',$container_movement->is_sntc) == "" ? 'selected':'' }}>No</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="status">RCVC </label>
                                <select class="selectpicker form-control"  name="is_rcvc">
                                    <option value="1" {{ old('status',$container_movement->is_rcvc) == "1" ? 'selected':'' }}>Yes</option>
                                    <option value="" {{ old('status',$container_movement->is_rcvc) == "" ? 'selected':'' }}>No</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="status">LODE (LOAD EMPTY)</label>
                                <select class="selectpicker form-control"  name="is_load_empty">
                                    <option value="1" {{ old('status',$container_movement->is_load_empty) == "1" ? 'selected':'' }}>Yes</option>
                                    <option value="" {{ old('status',$container_movement->is_load_empty) == "" ? 'selected':'' }}>No</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="status">DCHE (DISCHARGE EMPTY)</label>
                                <select class="selectpicker form-control"  name="is_dche">
                                    <option value="1" {{ old('status',$container_movement->is_dche) == "1" ? 'selected':'' }}>Yes</option>
                                    <option value="" {{ old('status',$container_movement->is_dche) == "" ? 'selected':'' }}>No</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="status">SNTR (SENT FOR STRIPPING)</label>
                                <select class="selectpicker form-control"  name="is_sntr">
                                    <option value="1" {{ old('status',$container_movement->is_sntr) == "1" ? 'selected':'' }}>Yes</option>
                                    <option value="" {{ old('status',$container_movement->is_sntr) == "" ? 'selected':'' }}>No</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="status"> RCVE (RECIEVED EMPTY) </label>
                                <select class="selectpicker form-control"  name="is_rcve">
                                    <option value="1" {{ old('status',$container_movement->is_rcve) == "1" ? 'selected':'' }}>Yes</option>
                                    <option value="" {{ old('status',$container_movement->is_rcve) == "" ? 'selected':'' }}>No</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="status">LOAD FULL TRANSHIPMENT</label>
                                <select class="selectpicker form-control"  name="is_lodt">
                                    <option value="1" {{ old('status',$container_movement->is_lodt) == "1" ? 'selected':'' }}>Yes</option>
                                    <option value="" {{ old('status',$container_movement->is_lodt) == "" ? 'selected':'' }}>No</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="status">DCHT</label>
                                <select class="selectpicker form-control"  name="is_dcht">
                                    <option value="1" {{ old('status',$container_movement->is_dcht) == "1" ? 'selected':'' }}>Yes</option>
                                    <option value="" {{ old('status',$container_movement->is_dcht) == "" ? 'selected':'' }}>No</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="status">TRANSFER FULL CONTAINER</label>
                                <select class="selectpicker form-control"  name="is_trff">
                                    <option value="1" {{ old('status',$container_movement->is_trff) == "1" ? 'selected':'' }}>Yes</option>
                                    <option value="" {{ old('status',$container_movement->is_trff) == "" ? 'selected':'' }}>No</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-3">
                                <label for="status">TRANSFER EMPTY CONTAINER </label>
                                <select class="selectpicker form-control"  name="is_trfe">
                                    <option value="1" {{ old('status',$container_movement->is_trfe) == "1" ? 'selected':'' }}>Yes</option>
                                    <option value="" {{ old('status',$container_movement->is_trfe) == "" ? 'selected':'' }}>No</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="status">RCVF (RECIEVED FULL)</label>
                                <select class="selectpicker form-control"  name="is_rcvf">
                                    <option value="1" {{ old('status',$container_movement->is_rcvf) == "1" ? 'selected':'' }}>Yes</option>
                                    <option value="" {{ old('status',$container_movement->is_rcvf) == "" ? 'selected':'' }}>No</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                       <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.update')}}</button>
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
@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data </a></li>
                            <li class="breadcrumb-item"><a href="{{route('ports.index')}}">Ports</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> {{trans('forms.edit')}}</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('ports.update',['port'=>$port])}}" method="POST">
                        @csrf
                        @method('put')
                            @if(session('alert'))
                                <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ session('alert') }}</p>
                            @endif
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nameInput">Name *</label>
                            <input type="text" class="form-control" id="nameInput" name="name" value="{{old('name',$port->name)}}"
                                 placeholder="Name" autocomplete="off" autofocus>
                                @error('name')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="CodeInput">Code</label>
                                <input type="text" class="form-control" id="CodeInput" name="code" value="{{old('code',$port->code)}}"
                                    placeholder="Code" autocomplete="off">
                                @error('Code')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="countryInput">{{trans('company.country')}}</label>
                                <select class="selectpicker form-control" id="countryInput" data-live-search="true" data-size="10"
                                name="country_id" title="{{trans('forms.select')}}">
                                    @foreach ($countries as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('country_id',$port->country_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="via_portInput">Via Port</label>
                                <input type="text" class="form-control" id="via_portInput" name="via_port" value="{{old('via_port',$port->via_port)}}"
                                    placeholder="Via Port" autocomplete="off">
                                @error('via_port')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="portInput">Port Type</label>
                                <select class="selectpicker form-control" id="portInput" data-live-search="true" data-size="10"
                                name="port_type_id" title="{{trans('forms.select')}}">
                                    @foreach ($port_types as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('port_type_id',$port->port_type_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('port_type_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="agentInput">Agent</label>
                                <select class="selectpicker form-control" id="agentInput" data-live-search="true" data-size="10"
                                name="agent_id" title="{{trans('forms.select')}}">
                                    @foreach ($agents as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('agent_id',$port->agent_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('agent_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="terminalInput">Terminal</label>
                                <select class="selectpicker form-control" id="terminalInput" data-live-search="true" name="terminal_id" data-size="10"
                                    title="{{trans('forms.select')}}">
                                    @foreach ($terminals as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('terminal_id',$port->terminal_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('terminal_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="pick_up_locationInput">Pickup / Return Location</label>
                                <input type="text" class="form-control" id="pick_up_locationInput" name="pick_up_location" value="{{old('pick_up_location',$port->pick_up_location)}}"
                                    placeholder="Pickup / Return Location" autocomplete="off">
                                @error('pick_up_location')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="details">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" 
                                placeholder="Notes" autocomplete="off">{{ old('notes',$port->notes) }}</textarea>
                                @error('notes')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                       <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.update')}}</button>
                                <a href="{{route('ports.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                       </div>


                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

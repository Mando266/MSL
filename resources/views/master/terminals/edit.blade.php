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
                            <li class="breadcrumb-item"><a href="{{route('terminals.index')}}">Terminals</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> {{trans('forms.edit')}}</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('terminals.update',['terminal'=>$terminal])}}" method="POST">
                        @csrf
                        @method('put')
                            @if(session('alert'))
                            <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ session('alert') }}</p>
                            @endif
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nameInput">Name *</label>
                            <input type="text" class="form-control" id="nameInput" name="name" value="{{old('name',$terminal->name)}}"
                                 placeholder="Name" autocomplete="off" autofocus>
                                @error('name')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="CodeInput">Code</label>
                                <input type="text" class="form-control" id="CodeInput" name="code" value="{{old('code',$terminal->code)}}"
                                    placeholder="Code" autocomplete="off">
                                @error('Code')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="countryInput">{{trans('company.country')}}</label>
                                <select class="selectpicker form-control" id="country" data-live-search="true" data-size="10"
                                name="country_id" title="{{trans('forms.select')}}">
                                    @foreach ($countries as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('country_id',$terminal->country_id) ? 'selected':''}}>{{$item->name}}</option>
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
                                <label for="portInput">Port Name</label>
                                <select class="form-control" id="port" data-live-search="true" name="port_id" data-size="10">
                                <option value="">Select...</option>
                                    @foreach ($ports as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('port_id',$terminal->port_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('port_id')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                       <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.update')}}</button>
                                <a href="{{route('terminals.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
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
        $(function(){
                let country = $('#country');
                $('#country').on('change',function(e){
                    let value = e.target.value;
                    let response =    $.get(`/api/master/ports/${country.val()}`).then(function(data){
                        let ports = data.ports || '';
                        let list2 = [`<option value=''>Select...</option>`];
                        for(let i = 0 ; i < ports.length; i++){
                            list2.push(`<option value='${ports[i].id}'>${ports[i].name} </option>`);
                        }
                let port = $('#port');
                port.html(list2.join(''));
                });
            });
        });
</script>
@endpush
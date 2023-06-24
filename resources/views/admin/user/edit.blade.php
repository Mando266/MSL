@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Administration</a></li>
                            <li class="breadcrumb-item"><a href="{{route('users.index')}}">Users</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a href="javascript:void(0);">{{trans('forms.edit')}}</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('users.update',['user'=>$user->id])}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="agent_id">Agent</label>
                                <select class="selectpicker form-control" id="agent_id" data-live-search="true" name="agent_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($user_agent as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('agent_id',$user->agent_id) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('agent_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="userName">{{trans('user.user_name')}} * <span class="text-warning"> ( between 4 to 30 characters without spaces.) </span></label>
                            <input type="text" class="form-control" id="userName" name="name" value="{{old('name',$user->name)}}"
                                 placeholder="{{trans('user.user_name')}}" autocomplete="off" autofocus maxlength="30">
                                @error('name')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fullName">{{trans('user.full_name')}} *</label>
                            <input type="text" class="form-control" id="fullName" name="full_name" value="{{old('full_name',$user->full_name)}}"
                                 placeholder="{{trans('user.full_name')}}" autocomplete="off" maxlength="128">
                                @error('full_name')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="passwordInput">{{trans('login.password')}} * <span class="text-warning"> ( between 6 to 30 characters.) </span></label>
                                <input type="password" class="form-control" id="passwordInput" name="password" maxlength="30"
                                    placeholder="{{trans('login.password')}}" autocomplete="off" >
                                @error('password')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="passwordConfirmInput">{{trans('login.password_confirmation')}} *</label>
                                <input type="password" class="form-control" id="passwordConfirmInput" name="password_confirmation" maxlength="30"
                                    placeholder="{{trans('login.password_confirmation')}}" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-row mb-4">
                            <div class="form-group col-md-6">
                                <label for="email">{{trans('user.email')}}</label>
                            <input type="text" class="form-control" id="email" name="email" value="{{old('email',$user->email)}}" maxlength="128"
                                 placeholder="{{trans('user.email')}}" autocomplete="off" >
                                @error('email')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="empCode">{{trans('user.employee_no')}}</label>
                            <input type="text" class="form-control" id="empCode" name="employee_no" value="{{old('employee_no',$user->employee_no)}}"
                                 placeholder="{{trans('user.employee_no')}}" autocomplete="off" maxlength="15" >
                                @error('employee_no')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <hr/>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="lessor_id">Lessor or Seller</label>
                                <select class="selectpicker show-tick form-control" id="lessor_id" data-live-search="true" name="lessor_id" title="{{trans('forms.select')}}">
                                    <option value="0">All</option>
                                    @foreach ($lessors as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('lessor_id',$user->lessor_id) ? 'selected' :''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('lessor_id')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="operator_id">Vessel Operator</label>
                                <select class="selectpicker show-tick form-control" id="operator_id" data-live-search="true" name="operator_id" title="{{trans('forms.select')}}">
                                    <option value="0">All</option>
                                    @foreach ($operators as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('operator_id',$user->operator_id) ? 'selected' :''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('operator_id')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="role">{{trans('user.role')}} <span class="text-warning"> (user will be disabled if no role is selected)</span></label>
                                <select class="selectpicker show-tick form-control" id="role" data-live-search="true" name="role" title="{{trans('forms.select')}}">
                                    @foreach ($roles as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('role',optional($user->roles->first())->id) ? 'selected' :''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="status">{{trans('user.status')}}</label>
                                <select class="selectpicker form-control"  name="is_active">
                                    <option value="1" {{ old('status',$user->is_active) == "1" ? 'selected':'' }}>Enabled</option>
                                    <option value="0" {{ old('status',$user->is_active) == "0" ? 'selected':'' }}>Disabled</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>

                        </div>
                        <hr/>

                       <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary mt-3">{{trans('forms.update')}}</button>
                                <a href="{{route('users.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
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
<link href="{{asset('plugins/sweetalerts/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/sweetalerts/sweetalert.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/components/custom-sweetalert.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('plugins/file-upload/file-upload-with-preview.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/css/elements/avatar.css')}}" rel="stylesheet" type="text/css" />
<style>
    .custom-file-container label {
    color: #3b3f5c;
}
.custom-file-container__image-preview{
    display: none;
}
.avatar img {
    object-fit: scale-down;
}
</style>
@endpush
@push('scripts')
    <script src="{{asset('plugins/sweetalerts/sweetalert2.min.js')}}"></script>
    <script src="{{asset('plugins/sweetalerts/custom-sweetalert.js')}}"></script>
    <script src="{{ asset('plugins/file-upload/file-upload-with-preview.min.js')}}"></script>
    <script src="{{ asset('app/admin/user.js') }}"></script>
    <script>
        var firstUpload = new FileUploadWithPreview('avatar',{
            images: {
                    baseImage: '{{asset('assets/img/profile.png')}}',
                }
        })
    </script>
@endpush


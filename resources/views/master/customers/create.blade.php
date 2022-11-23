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
                            <li class="breadcrumb-item"><a href="{{route('customers.index')}}">Customers</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0);"> Add New Customer</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="createForm" action="{{route('customers.store')}}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nameInput">Name <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="nameInput" name="name" value="{{old('name')}}"
                                 placeholder="Name" autocomplete="off" autofocus>
                                @error('name')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="codeInput">Contact Person</label>
                                <input type="text" class="form-control" id="contact_personInput" name="contact_person" value="{{old('contact_person')}}"
                                    placeholder="Contact Person" autocomplete="off">
                                @error('contact_person')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="countryInput">{{trans('company.country')}} <span style="color: red;">*</span></label>
                                <select class="selectpicker form-control" id="countryInput" data-live-search="true" name="country_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($countries as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('country_id') ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
  
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="cityInput">City <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="cityInput" name="city" value="{{old('city')}}"
                                placeholder="City" autocomplete="off">
                            @error('city')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="cityInput">Address Line 1 <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="addressInput" name="address" value="{{old('address')}}"
                                placeholder="Address Line 1" autocomplete="off">
                            @error('address')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                                <label for="cust_address">Address Line 2</label>
                                <input type="text" class="form-control" id="cust_address" name="cust_address" value="{{old('cust_address')}}"
                                    placeholder="Address Line 2" autocomplete="off">
                                @error('cust_address')
                                <div style="color: red;">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="phoneInput">Phone <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="phoneInput" name="phone" value="{{old('phone')}}"
                                placeholder="Phone" autocomplete="off">
                            @error('phone')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="landline">Landline</label>
                            <input type="text" class="form-control" id="landline" name="landline" value="{{old('landline')}}"
                                placeholder="Landline" autocomplete="off">
                            @error('landline')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="emailInput">Email <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="emailInput" name="email" value="{{old('email')}}"
                                placeholder="Email" autocomplete="off">
                            @error('email')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="tax_card_noInput">Tax Card</label>
                            <input type="text" class="form-control" id="tax_card_noInput" name="tax_card_no" value="{{old('tax_card_no')}}"
                                placeholder="Tax Card" autocomplete="off">
                            @error('tax_card_no')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="sales_person_idInput">Sales Person</label>
                            <select class="selectpicker form-control" id="sales_person_idInput" data-live-search="true" name="sales_person_id" data-size="10"
                            title="{{trans('forms.select')}}">
                                @foreach ($users as $item)
                                    <option value="{{$item->id}}" {{$item->id == old('sales_person_id') ? 'selected':''}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                            @error('sales_person_id')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="fax">Fax</label>
                            <input type="text" class="form-control" id="fax" name="fax" value="{{old('fax')}}"
                                placeholder="Fax" autocomplete="off">
                            @error('fax')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="countryInput">Currency</label>
                            <select class="selectpicker form-control" id="currency" data-live-search="true" name="currency" data-size="10"
                            title="{{trans('forms.select')}}" autofocus>
                                @foreach ($currency as $item)
                                    <option value="{{$item->name}}" {{$item->id == old('currency') ? 'selected':''}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                            @error('currency')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="countryInput">Other Currency</label>
                            <select class="selectpicker form-control" id="othercurrency" data-live-search="true" name="othercurrency" data-size="10"
                            title="{{trans('forms.select')}}" autofocus>
                                @foreach ($currency as $item)
                                    <option value="{{$item->name}}" {{$item->name == old('othercurrency') ? 'selected':''}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                            @error('othercurrency')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="customers_website">Customer Website Url</label>
                            <input type="text" class="form-control" id="customers_website" name="customers_website" value="{{old('customers_website')}}"
                                placeholder="Customer Website Url" autocomplete="off">
                            @error('customers_website')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="notes">Note</label>
                            <input type="text" class="form-control" id="notes" name="notes" value="{{old('notes')}}"
                                placeholder="Note" autocomplete="off">
                            @error('notes')
                            <div style="color: red;">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <table id="customerRole" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Customer Roles</th>
                                    <th>
                                        <a id="add"> Add Role <i class="fas fa-plus"></i></a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>  
                                    <select class="selectpicker form-control" id="customer_roles" data-live-search="true" name="customerRole[0][role_id]" data-size="10"
                                            title="{{trans('forms.select')}}" required>
                                            @foreach ($customer_roles as $item)
                                            <option value="{{$item->id}}" {{$item->id == old('role_id') ? 'selected':''}}>{{$item->name}}</option>
                                            @endforeach
                                    </select>
                                </td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary mt-3">{{trans('forms.create')}}</button>
                            <a href="{{route('customers.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
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
      $(document).ready(function(){
        $("#customerRole").on("click", ".remove", function () {
        $(this).closest("tr").remove();
        });
     var counter  = 1;
        $("#add").click(function(){
                var tr = '<tr>'+
            '<td class="ports"><select class="form-control port"  data-live-search="true" name="customerRole['+counter+'][role_id]" data-size="10"><option value="">Select...</option>@foreach ($customer_roles as $item)<option value="{{$item->id}}" {{$item->id == old('role_id') ? 'selected':''}}>{{$item->name}}</option>@endforeach</select></td>'+
            '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>'
            '</tr>';
            counter++;
            $('#customerRole').append(tr);
        });
    });
</script>
@endpush

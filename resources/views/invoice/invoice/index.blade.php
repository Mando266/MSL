@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="javascript:void(0);">Invoice</a></li>
                                <li class="breadcrumb-item  active"><a href="javascript:void(0);">Invoice List</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div> 
                        <div class="row">
                            <div class="col-md-12 text-right mb-5">
                            @permission('Invoice-Create')
                            <a href="{{route('invoice.selectBL')}}" class="btn btn-primary">New Debit Note</a>
                            <a href="{{route('invoice.selectBLinvoice')}}" class="btn btn-info">New Invoice</a>
                            @endpermission
                            @permission('Invoice-List')
                            <a class="btn btn-warning" href="{{ route('export.invoice') }}">Export</a>
                            @endpermission
                            </div>
                        </div>
                    </br>
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="Type">Invoice Type</label>
                                <select class="selectpicker form-control" id="Type" data-live-search="true" name="type" data-size="10"
                                 title="{{trans('forms.select')}}">
                                        <option value="debit">Debit</option>
                                        <option value="invoice">Invoice</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="invoice">Invoice No</label>
                                <select class="selectpicker form-control" id="invoice" data-live-search="true" name="invoice_no" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($invoiceRef as $item)     
                                        <option value="{{$item->invoice_no}}" {{$item->invoice_no == old('invoice_no',request()->input('invoice_no')) ? 'selected':''}}>{{$item->invoice_no}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="Bldraft">Bl Number</label>
                                <select class="selectpicker form-control" id="Bldraft" data-live-search="true" name="bldraft_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($bldrafts as $item)    
                                        <option value="{{$item->id}}" {{$item->id == old('bldraft_id',request()->input('bldraft_id')) ? 'selected':''}}>{{$item->ref_no}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="Bldraft">Customer</label>
                                <select class="selectpicker form-control" id="customer_id" data-live-search="true" name="customer_id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($customers as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('customer_id',request()->input('customer_id')) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">

                            <div class="form-group col-md-3">
                                <label for="status">Bl Payment</label>
                                <select class="selectpicker form-control" data-live-search="true" name="payment_kind" title="{{trans('forms.select')}}">
                                    <option value="Prepaid">Prepaid </option>
                                    <option value="Collect">Collect</option>
                                </select>
                                @error('payment_kind')
                                <div style="color:red;">
                                    {{$message}}
                                </div>
                                @enderror
                        </div>
                    </div>

                        <div class="form-row">
                            <div class="col-md-12 text-center">
                                <button  type="submit" class="btn btn-success mt-3">Search</button>
                                <a href="{{route('invoice.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                            </div>
                        </div>
                    </form>
                    @php
                    $totalusd = 0;
                    $totalegp = 0;
                    @endphp
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Invoice No</th>
                                        <th>Customer</th>
                                        <th>Tax NO</th>
                                        <th>Bl No</th>
                                        <th>Voyage</th>
                                        <th>Vessel</th>
                                        <th>Date</th>
                                        <th>Invoice Type</th>
                                        <th>payment kind</th>
                                        <th>Total USD</th>
                                        <th>Total EGP</th>
                                        <th>Invoice Status</th>
                                        <th class='text-center' style='width:100px;'>Receipt</th>
                                        <th class='text-center' style='width:100px;'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($invoices as $invoice)
                                    @php
                                        $totalusd = 0;
                                        $totalegp = 0;
                                    @endphp
                                    @php
                                        foreach($invoice->chargeDesc as $chargeskey => $invoiceDesc ){
                                            $totalusd = $totalusd + (float)$invoiceDesc->total_amount;
                                            $totalegp = $totalegp + (float)$invoiceDesc->total_egy;
                                        }
                                    @endphp
                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($invoices,$loop)}}</td>
                                            <td>{{optional($invoice)->invoice_no}}</td>
                                            <td>{{$invoice->customer}}</td>
                                            <td>{{optional($invoice->customerShipperOrFfw)->tax_card_no}}</td>
                                            <td>{{optional($invoice->bldraft)->ref_no}}</td>
                                            <td>{{ $invoice->bldraft_id == 0 ? optional($invoice->voyage)->voyage_no : optional($invoice->bldraft->voyage)->voyage_no }}</td>
                                            <td>{{ $invoice->bldraft_id == 0 ? optional(optional($invoice->voyage)->vessel)->name : optional($invoice->bldraft->voyage->vessel)->name }}</td>
                                            <td>{{optional($invoice)->date}}</td>
                                            <td>{{optional($invoice)->type}}</td>
                                            <td>{{optional($invoice->bldraft)->payment_kind}}</td>

                                            <td>{{$totalusd}}</td>
                                            @if($invoice->type == "invoice" || $invoice->add_egp == "true")
                                            <td>{{$totalegp}}</td>
                                            @else
                                            <td></td>
                                            @endif
                                            <td class="text-center">
                                                @if($invoice->invoice_status == "confirm")
                                                    <span class="badge badge-info"> Confirm </span>
                                                @else
                                                    <span class="badge badge-danger"> Draft </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                 <ul class="table-controls">
                                                @if($invoice->invoice_status == "confirm")
                                                    <li>
                                                        <a href="{{route('invoice.receipt',['invoice'=>$invoice->id])}}" target="_blank" data-toggle="tooltip" data-placement="top" title="" data-original-title="show">
                                                            <i class="far fa-eye text-primary"></i>
                                                        </a>
                                                    </li>
                                                    @endif
                                                 </ul>
                                            </td>

                                            <td class="text-center">
                                                 <ul class="table-controls">
                                                    @permission('Invoice-Edit')
                                                    <li>
                                                        <a href="{{route('invoice.edit',['invoice'=>$invoice->id,'bldraft_id'=>$invoice->bldraft_id])}}" data-toggle="tooltip" data-placement="top" title="" data-original-title="edit">
                                                            <i class="far fa-edit text-success"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission
                                                    @permission('Invoice-Show')
                                                    <li>
                                                        <a href="{{route('invoice.show',['invoice'=>$invoice->id])}}" data-toggle="tooltip"  target="_blank"  data-placement="top" title="" data-original-title="show">
                                                            <i class="far fa-eye text-primary"></i>
                                                        </a>
                                                    </li>
                                                    @endpermission 
                                                    @permission('Invoice-Delete')
                                                    <li>
                                                        <form action="{{route('invoice.destroy',['invoice'=>$invoice->id])}}" method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                        <button style="border: none; background: none;" type="submit" class="fa fa-trash text-danger show_confirm"></button>
                                                        </form> 
                                                    </li>
                                                    @endpermission
                                                </ul>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="text-center">
                                            <td colspan="20">{{ trans('home.no_data_found')}}</td>
                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>
                        </div>
                        <div class="paginating-container">
                            {{ $invoices->appends(request()->query())->links()}}

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script type="text/javascript">
 
     $('.show_confirm').click(function(event) {
          var form =  $(this).closest("form");
          var name = $(this).data("name");
          event.preventDefault();
          swal({
              title: `Are you sure you want to delete this Invoice?`,
              icon: "warning",
              buttons: true,
              dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              form.submit();
            }
          });
      });
  
</script>
@endpush

@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="javascript:void(0);">Accounting</a></li>
                                <li class="breadcrumb-item  active"><a href="javascript:void(0);">Customer Statement List</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div> 
                        <div class="row">
                            <div class="col-md-12 text-right mb-5">

                            {{-- @permission('Invoice-List')
                            <a class="btn btn-warning" href="{{ route('export.statements') }}">Export</a>
                            @endpermission --}}
                            </div>
                        </div>
                    </br>
                    <form>
    
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="Bldraft">Customer</label>
                                <select class="selectpicker form-control" id="customer_id" data-live-search="true" name="id" data-size="10"
                                 title="{{trans('forms.select')}}">
                                    @foreach ($customers as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('id',request()->input('id')) ? 'selected':''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        {{-- <div class="form-group col-md-3">
                            <label for="voyage_id">Voyages </label>
                            <select class="selectpicker form-control" id="voyage_id" name="voyage_id" data-live-search="true" data-size="10"
                                title="{{trans('forms.select')}}">
                                @foreach ($voyages as $item)
                                        <option value="{{$item->id}}" {{$item->id == old('voyage_id',request()->input('voyage_id')) ? 'selected':''}}>{{$item->voyage_no}} {{optional($item->vessel)->name }}</option>
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
                            <label for="invoice">Invoice No</label>
                            <select class="selectpicker form-control" id="invoice" data-live-search="true" name="invoice_no" data-size="10"
                             title="{{trans('forms.select')}}">
                                @foreach ($invoiceRef as $item)     
                                    <option value="{{$item->invoice_no}}" {{$item->invoice_no == old('invoice_no',request()->input('invoice_no')) ? 'selected':''}}>{{$item->invoice_no}}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        </div>

                            <div class="form-row">
                                <div class="col-md-12 text-center">
                                    <button  type="submit" class="btn btn-success mt-3">Search</button>
                                    <a href="{{route('statements.index')}}" class="btn btn-danger mt-3">{{trans('forms.cancel')}}</a>
                                </div>
                            </div>
                    </form>
                
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        {{-- <th>#</th> --}}
                                        <th>Customer</th>
                                        <th>Invoice No</th>
                                        <th>Date Of Invoice</th>
                                        <th>Invoice Amount Usd</th>
                                        <th>Invoice Amount Egp</th>
                                        <th>Bl No</th>
                                        <th>Receipt No</th>
                                        <th>Receipt Amount</th>
                                        <th>Balance usd</th>
                                        <th>Balance egp</th>
                                        <th>vessel Name</th>
                                        <th>Voyage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($statements as $customer)
                                   
                                        <tr>
                                            {{-- <td rowspan="{{$customer->invoices->count() + $customer->creditNotes->count() + $customer->refunds->count() + 1}}">{{ App\Helpers\Utils::rowNumber($statements,$loop)}}</td> --}}
                                            <td rowspan="{{$customer->invoices->count() + $customer->creditNotes->count() + $customer->refunds->count() + 1}}">{{$customer->name}}</td>
                                            @foreach($customer->invoices as $invoice)
                                                @php
                                                    $totalusd = null;
                                                    $totalegp = null;
                                                    $receipts = null;
                                                    $totalreceipt = null;
                                                    if($invoice->receipts->count() != 0){
                                                        foreach($invoice->receipts as $receipt){
                                                            $receipts .= $receipt->receipt_no . "\n";
                                                            $totalreceipt += $receipt->paid;
                                                        }   
                                                    }
                                                    foreach($invoice->chargeDesc as $chargeskey => $invoiceDesc ){
                                                        $totalusd = $totalusd + (float)$invoiceDesc->total_amount;
                                                        $totalegp = $totalegp + (float)$invoiceDesc->total_egy;
                                                    }
                                                @endphp
                                                <tr>
                                                    <td>{{optional($invoice)->invoice_no}}</td>
                                                    <td>{{optional($invoice)->date}}</td>
                                                    @if( $invoice->add_egp != 'onlyegp')
                                                        <td >{{$totalusd}}</td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                    @if($invoice->add_egp == 'true' || $invoice->add_egp == 'onlyegp')
                                                        <td>{{$totalegp}}</td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                    <td>{{optional($invoice->bldraft)->ref_no}}</td>
                                                    <td class="text-center">
                                                        @if($invoice->receipts->count() != 0)
                                                            @foreach($invoice->receipts as $receipt)
                                                                {{$receipt->receipt_no}} <br>
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                    <td>{{$totalreceipt}}</td>
                                                    <td>{{optional($invoice->customerShipperOrFfw)->credit}}</td>
                                                    <td>{{optional($invoice->customerShipperOrFfw)->credit_egp}}</td>
                                                    <td>{{ $invoice->bldraft_id == 0 ? optional(optional($invoice->voyage)->vessel)->name : optional($invoice->bldraft->voyage->vessel)->name }}</td>
                                                    <td>{{ $invoice->bldraft_id == 0 ? optional($invoice->voyage)->voyage_no : optional($invoice->bldraft->voyage)->voyage_no }}</td>
                                                </tr>
                                            @endforeach
                                            @foreach($customer->creditNotes as $creditNote)
                                            <tr>
                                                <td>{{optional($creditNote)->credit_no}}</td>
                                                <td>{{optional($creditNote)->created_at}}</td>
                                                @if($creditNote->currency == "credit_usd")
                                                <td>{{optional($creditNote)->total_amount}}</td>
                                                @else
                                                <td></td>
                                                @endif
                                                @if($creditNote->currency == "credit_egp")
                                                <td>{{optional($creditNote)->total_amount}}</td>
                                                @else
                                                <td></td>
                                                @endif
                                            </tr>
                                            @endforeach
                                            @foreach($customer->refunds as $refund)
                                            <tr>
                                                <td>{{optional($refund)->receipt_no}}</td>
                                                <td>{{optional($refund)->created_at}}</td>
                                                @if($refund->status == "refund_usd")
                                                <td>{{optional($refund)->paid}}</td>
                                                @else
                                                <td></td>
                                                @endif
                                                @if($refund->status == "refund_egp")
                                                <td>{{optional($refund)->paid}}</td>
                                                @else
                                                <td></td>
                                                @endif
                                            </tr>
                                            @endforeach
                                            
                                                                                
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
                            {{ $statements->appends(request()->query())->links()}}

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

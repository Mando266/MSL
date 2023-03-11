@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="javascript:void(0);">Receipts</a></li>
                                <li class="breadcrumb-item  active"><a href="javascript:void(0);">Receipt Gates</a></li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div> 
                        <div class="row">
                            <div class="col-md-12 text-right mb-5">
                            @permission('Invoice-Create')
                            <a href="{{route('receipt.selectinvoice')}}" class="btn btn-primary">New Receipt</a>
                            @endpermission
                            </div>
                        </div>
                    </br>

             
                    <div class="widget-content widget-content-area">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-condensed mb-4">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Receipt No</th>
                                        <th>Invoice No</th>
                                        <th>Bl No</th>
                                        <th>Customer Name</th>
                                        <th>Payment Methods</th>
                                        <th>Total</th>
                                        <th>Paid</th>
                                        <th>User</th>
                                        <th class='text-center' style='width:100px;'>Receipt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($receipts as $receipt)
                                    
                                        <tr>
                                            <td>{{ App\Helpers\Utils::rowNumber($receipts,$loop)}}</td>
                                            <td>{{$receipt->receipt_no}}</td>
                                            <td>{{optional($receipt->invoice)->invoice_no}}</td>
                                            <td>{{optional($receipt->bldraft)->ref_no}}</td>
                                            <td>{{optional($receipt->invoice->customerShipperOrFfw)->name}}</td>
                                            <td>
                                                @if($receipt->bank_transfer != null)
                                                    Bank Transfer <br>
                                                @endif
                                                @if($receipt->bank_deposit != null)
                                                    Bank Deposit <br>
                                                @endif
                                                @if($receipt->bank_check != null)
                                                    Bank Check <br>
                                                @endif
                                                @if($receipt->bank_cash != null)
                                                    Cash <br>
                                                @endif
                                                @if($receipt->matching != null)
                                                    Matching <br>
                                                @endif
                                            </td>
                                            <td>{{$receipt->total}}</td>
                                            <td>{{$receipt->paid}}</td>
                                            <td>{{optional($receipt->user)->name}}</td>
                                            
                                            <td class="text-center">
                                                <ul class="table-controls">                                                
                                                @permission('Invoice-Show')
                                                <li>
                                                    <a href="{{route('receipt.show',['receipt'=>$receipt->id])}}" data-toggle="tooltip"  target="_blank"  data-placement="top" title="" data-original-title="show">
                                                        <i class="far fa-eye text-primary"></i>
                                                    </a>
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
                            {{ $receipts->appends(request()->query())->links()}}

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

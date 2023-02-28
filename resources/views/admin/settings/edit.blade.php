@extends('layouts.app')
@section('content')
<div class="layout-px-spacing">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
            <div class="widget widget-one">
                <div class="widget-heading">
                    <nav class="breadcrumb-two" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a a href="{{route('invoice.index')}}">Invoice</a></li> 
                            <li class="breadcrumb-item active"><a href="javascript:void(0);">New Debit Invoice</a></li>
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </nav>
                </div>
                <div class="widget-content widget-content-area">
                <form id="editForm" action="{{route('settings.update',['setting'=>$setting])}}" method="POST" >
                            @csrf
                            @method('put')
                        <div class="form-row">
                            <div class="form-group col-md-3" >
                                <label>Invoice Draft Serial</label>
                                    <input type="text" class="form-control" placeholder="Place Of Acceptence" autocomplete="off" value="{{old('invoice_draft',$setting->invoice_draft)}}" style="background-color:#fff" name="invoice_draft">
                                    
                            </div> 
                            <div class="form-group col-md-3" >
                                <label>Invoice Confirmed Serial</label>
                                    <input type="text" class="form-control" placeholder="Place Of Acceptence" autocomplete="off" value="{{old('invoice_confirm',$setting->invoice_confirm)}}" style="background-color:#fff" name="invoice_confirm">
                                    
                            </div>
                            <div class="form-group col-md-3" >
                                <label>Debit Draft Serial</label>
                                    <input type="text" class="form-control" placeholder="Place Of Acceptence" autocomplete="off" value="{{old('debit_draft',$setting->debit_draft)}}" style="background-color:#fff" name="debit_draft">
                                    
                            </div>
                            <div class="form-group col-md-3" >
                                <label>Invoice Confirmed Serial</label>
                                    
                                    <input type="text" class="form-control" placeholder="Place Of Acceptence" autocomplete="off" value="{{old('debit_confirm',$setting->debit_confirm)}}" style="background-color:#fff" name="debit_confirm">
                                    
                            </div>
                        </div>
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-primary mt-3">{{trans('forms.edit')}}</button>
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
var removed = [];
function removeItem( item )
{
    removed.push(item);
    document.getElementById("removed").value = removed;
}
$(document).ready(function(){
    $("#charges").on("click", ".remove", function () {
    $(this).closest("tr").remove();
    });

});
</script>

<script>
    $('#editForm').submit(function() {
        $('input').removeAttr('disabled');
    });
</script>
<script>
        $(function(){
                let customer = $('#customer');
                $('#customer').on('change',function(e){
                    let value = e.target.value;
                    let response =    $.get(`/api/master/customers/${customer.val()}`).then(function(data){
                        let notIfiy = data.customer[0] ;
                        let notifiy = $('#notifiy').val(' ' + notIfiy.name);
                    notifiy.html(list2.join(''));
                });
            });
        });
</script>
@endpush
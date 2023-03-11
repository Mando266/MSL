<?php

namespace App\Http\Controllers\Invoice;
use App\Models\Bl\BlDraft;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice\Invoice;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReceiptController extends Controller
{
   
    public function index()
    {
        //
    }

    public function selectinvoice()
    {
        $bldrafts = BlDraft::where('company_id',Auth::user()->company_id)->get();
        $invoiceRef = Invoice::orderBy('id','desc')->where('company_id',Auth::user()->company_id)->get();
        return view('invoice.receipt.selectinvoice',[
            'bldrafts'=>$bldrafts,
            'invoiceRef'=>$invoiceRef,
        ]);
    }
    
   
    public function create()
    {
        $invoice = Invoice::where('id',request('invoice_id'))->with('chargeDesc')->first();
        // dd($invoice);
        $total = 0;
        $total_eg = 0;
        $now = Carbon::now();
        foreach($invoice->chargeDesc as $chargeDesc){
            $total += $chargeDesc->total_amount;
            $total_eg += $chargeDesc->total_egy;
        }
        $total = $total - (($total * $invoice->tax_discount)/100);
        $total_eg = $total_eg - (($total_eg * $invoice->tax_discount)/100);
        return view('invoice.receipt.create',[
            'invoice'=>$invoice,
            'total'=>$total,
            'total_eg'=>$total_eg,
        ]);
    }

    public function store(Request $request)
    {
        //
    }

   
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

 
    public function destroy($id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Invoice;
use App\Models\Bl\BlDraft;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice\Invoice;
use App\Models\Master\CustomerHistory;
use App\Models\Master\Customers;
use App\Models\Receipt\Receipt;
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
        $bldraft = BlDraft::where('id',request('bldraft_id'))->first();
        $oldReceipts = Receipt::where('id',request('invoice_id'))->get();
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
            'bldraft'=>$bldraft,
            'oldReceipts'=>$oldReceipts,
            'total'=>$total,
            'total_eg'=>$total_eg,
        ]);
    }

    public function store(Request $request)
    {
        $invoice = Invoice::where('id',request('invoice_id'))->with('chargeDesc')->first();
        $bldraft = BlDraft::where('id',request('bldraft_id'))->first();
        $customer = Customers::where('id',$invoice->customer_id)->first();
        // dd($request->input(),$invoice);
        $paid = 0;
        if(isset($request->bank_deposit)){
            $paid += $request->bank_deposit;
        }
        if(isset($request->bank_transfer)){
            $paid += $request->bank_deposit;
        }
        if(isset($request->bank_check)){
            $paid += $request->bank_deposit;
        }
        if(isset($request->bank_cash)){
            $paid += $request->bank_deposit;
        }
        // i need to take from cus credit
        if(isset($request->matching)){
            if($invoice->add_egp == "false"){
                // currency USD
                if($request->matching <= $customer->credit){
                    $paid += $request->bank_deposit;
                    $customer->credit = $customer->credit - $request->matching;
                }else{
                    return redirect()->back()->with('error','Matching Amount Is Bigger Than Customer Credit USD')->withInput($request->input());
                }
            }elseif($invoice->add_egp == "onlyegp"){
                // currency EGP
                if($request->matching <= $customer->credit_egp){
                    $paid += $request->bank_deposit;
                    $customer->credit_egp = $customer->credit_egp - $request->matching;
                }else{
                    return redirect()->back()->with('error','Matching Amount Is Bigger Than Customer Credit EGP')->withInput($request->input());
                }
            }
        }
        if($invoice->add_egp == "false"){
            if($customer->debit != 0){
                if($customer->debit > $paid){
                    $customer->debit = $customer->debit - $paid;
                }else{
                    $customer->debit = 0;
                }
            }
        }elseif($invoice->add_egp == "onlyegp"){
            if($customer->debit_egp != 0){
                if($customer->debit_egp > $paid){
                    $customer->debit_egp = $customer->debit_egp - $paid;
                }else{
                    $customer->debit_egp = 0;
                }
            }
        }
        $receipt = Receipt::create([
            'invoice_id'=>$request->invoice_id,
            'bldraft_id'=>$request->bldraft_id,
            'bank_transfer'=>$request->bank_transfer,
            'bank_deposit'=>$request->bank_deposit,
            'bank_cash'=>$request->bank_cash,
            'bank_check'=>$request->bank_check,
            'matching'=>$request->matching,
            'total'=>$request->total_payment,
            'paid'=>$paid,
            'user_id'=>Auth::user()->id,
        ]);
        if($paid > $request->total_payment){
            if($invoice->add_egp == "false"){
                // currency USD
                $customer->credit = $customer->credit + ($paid - $request->total_payment);
                CustomerHistory::create([
                    'credit'=>($paid - $request->total_payment),
                    'receipt_id'=>$receipt->id,
                    'user_id'=>$customer->id
                ]);
            }elseif($invoice->add_egp == "onlyegp"){
                // currency EGP
                $customer->credit_egp = $customer->credit_egp + ($paid - $request->total_payment);
                CustomerHistory::create([
                    'credit_egp'=>($paid - $request->total_payment),
                    'receipt_id'=>$receipt->id,
                    'user_id'=>$customer->id
                ]);
            }
        }elseif($paid < $request->total_payment){
            if($invoice->add_egp == "false"){
                // currency USD
                $customer->debit = $customer->debit + ($paid - $request->total_payment);
                CustomerHistory::create([
                    'debit'=>($paid - $request->total_payment),
                    'receipt_id'=>$receipt->id,
                    'user_id'=>$customer->id
                ]);
            }elseif($invoice->add_egp == "onlyegp"){
                // currency EGP
                $customer->debit_egp = $customer->debit_egp + ($paid - $request->total_payment);
                CustomerHistory::create([
                    'debit_egp'=>($paid - $request->total_payment),
                    'receipt_id'=>$receipt->id,
                    'user_id'=>$customer->id
                ]);
            }
        }
        return redirect()->route('receipt.index')->with('success',trans('Receipt.created'));
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

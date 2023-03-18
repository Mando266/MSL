<?php

namespace App\Http\Controllers\Invoice;

use App\Filters\Receipt\ReceiptIndexFilter;
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
        $receipts = Receipt::filter(new ReceiptIndexFilter(request()))->orderBy('id','desc')->paginate(30);
        $customers  = Customers::where('company_id',Auth::user()->company_id)->get();
        $invoices  = Invoice::where('company_id',Auth::user()->company_id)->get();
        $bldrafts = BlDraft::where('has_bl',1)->where('bl_status','=',1)->where('company_id',Auth::user()->company_id)->get();

        return view('invoice.receipt.index',[
            'receipts'=>$receipts,
            'customers'=>$customers,
            'invoices'=>$invoices,
            'bldrafts'=>$bldrafts,
        ]);
    
    }

    public function selectinvoice()
    {
        $bldrafts = BlDraft::where('has_bl',1)->where('bl_status','=',1)->where('company_id',Auth::user()->company_id)->get();
        $customers  = Customers::where('company_id',Auth::user()->company_id)->get();
        $invoiceRef = Invoice::where('invoice_status','confirm')->orderBy('id','desc')->where('company_id',Auth::user()->company_id)->get();

        return view('invoice.receipt.selectinvoice',[
            'bldrafts'=>$bldrafts,
            'customers'=>$customers,
            'invoiceRef'=>$invoiceRef,
        ]);
    }
    
   
    public function create()
    {
        $invoice = Invoice::where('id',request('invoice_id'))->with('chargeDesc')->first();
        $bldraft = BlDraft::where('id',request('bldraft_id'))->first();
        $oldPayment = 0;
        $oldReceipts = Receipt::where('invoice_id',request('invoice_id'))->get();
        if($oldReceipts->count() != 0){
            foreach($oldReceipts as $oldReceipt){
                $oldPayment += $oldReceipt->paid;
            }
        }
        $total = 0;
        $total_eg = 0;
        $now = Carbon::now();
        foreach($invoice->chargeDesc as $chargeDesc){
            $total += $chargeDesc->total_amount;
            $total_eg += $chargeDesc->total_egy;
        }
        $total = $total - (($total * $invoice->tax_discount)/100);
        $total_eg = $total_eg - (($total_eg * $invoice->tax_discount)/100);
        if($invoice->add_egp == "false"){
            if($total <= $oldPayment){
                return redirect()->back()->with('error','Sorry You Cant Create Receipt for this invoice its already Paid')->withInput(request()->input());
            }else{
                $total -= $oldPayment;
            }
        }elseif($invoice->add_egp == "onlyegp"){
            if($total_eg <= $oldPayment){
                return redirect()->back()->with('error','Sorry You Cant Create Receipt for this invoice its already Paid')->withInput(request()->input());
            }else{
                $total_eg -= $oldPayment;
            }
        }
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
        $oldReceipts = Receipt::where('invoice_id',request('invoice_id'))->get();
        $customer = Customers::where('id',$invoice->customer_id)->first();
        // dd($request->input(),$invoice);
        $paid = 0;
        if(isset($request->bank_deposit)){
            $paid += $request->bank_deposit;         // Add Bank Deposit to Paid
        }
        if(isset($request->bank_transfer)){
            $paid += $request->bank_transfer;        // Add Bank Transfer to Paid
        }
        if(isset($request->bank_check)){
            $paid += $request->bank_check;
        }
        if(isset($request->bank_cash)){
            $paid += $request->bank_cash;
        }
        // i need to take from cus credit
        if(isset($request->matching)){
            if($invoice->add_egp == "false"){
                // currency USD
                if($request->matching <= $customer->credit){
                    $paid += $request->matching;
                    $customer->credit = $customer->credit - $request->matching;
                    $customer->save();
                }else{
                    return redirect()->back()->with('error','Matching Amount Is Bigger Than Customer Credit USD')->withInput($request->input());
                }
            }elseif($invoice->add_egp == "onlyegp"){
                // currency EGP
                if($request->matching <= $customer->credit_egp){
                    $paid += $request->matching;
                    $customer->credit_egp = $customer->credit_egp - $request->matching;
                    $customer->save();
                }else{
                    return redirect()->back()->with('error','Matching Amount Is Bigger Than Customer Credit EGP')->withInput($request->input());
                }
            }
        }
        // to Decrease Customer Debit
        if($invoice->add_egp == "false"){
            if($customer->debit != 0){
                if($customer->debit > $paid){
                    $customer->debit = $customer->debit - $paid;
                    $customer->save();
                }else{
                    $customer->debit = 0;
                    $customer->save();
                }
            }
        }elseif($invoice->add_egp == "onlyegp"){
            if($customer->debit_egp != 0){
                if($customer->debit_egp > $paid){
                    $customer->debit_egp = $customer->debit_egp - $paid;
                    $customer->save();
                }else{
                    $customer->debit_egp = 0;
                    $customer->save();
                }
            }
        }
        $receipt = Receipt::create([
            'company_id'=>Auth::user()->company_id,
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
        if($oldReceipts->count() == 0){
            // ADD to Credit
            if($paid > (int)$request->total_payment){
                if($invoice->add_egp == "false"){
                    // currency USD
                    $customer->credit = $customer->credit + ($paid - (int)$request->total_payment);
                    $customer->save();
                    CustomerHistory::create([
                        'credit'=>($paid - (int)$request->total_payment),
                        'receipt_id'=>$receipt->id,
                        'user_id'=>$customer->id
                    ]);
                }elseif($invoice->add_egp == "onlyegp"){
                    // currency EGP
                    $customer->credit_egp = $customer->credit_egp + ($paid - (int)$request->total_payment);
                    $customer->save();
                    CustomerHistory::create([
                        'credit_egp'=>($paid - (int)$request->total_payment),
                        'receipt_id'=>$receipt->id,
                        'user_id'=>$customer->id
                    ]);
                }
            }elseif($paid < (int)$request->total_payment){
                // ADD to Debit
                if($invoice->add_egp == "false"){
                    // currency USD
                    $customer->debit = $customer->debit + ((int)$request->total_payment - $paid);
                    $customer->save();
                    CustomerHistory::create([
                        'debit'=>((int)$request->total_payment - $paid),
                        'receipt_id'=>$receipt->id,
                        'user_id'=>$customer->id
                    ]);
                }elseif($invoice->add_egp == "onlyegp"){
                    // currency EGP
                    $customer->debit_egp = $customer->debit_egp + ((int)$request->total_payment - $paid);
                    $customer->save();
                    CustomerHistory::create([
                        'debit_egp'=>((int)$request->total_payment - $paid),
                        'receipt_id'=>$receipt->id,
                        'user_id'=>$customer->id
                    ]);
                }
            }
        }
        $customer->save();
        return redirect()->route('receipt.index')->with('success',trans('Receipt.created'));
    }

   
    public function show($id)
    {
        $receipt = Receipt::find($id);
        $now = Carbon::now();

        $exp = explode('.', $receipt->paid);
        $f = new \NumberFormatter("en_US", \NumberFormatter::SPELLOUT);
        if(count($exp) >1){
            $total =  ucfirst($f->format($exp[0])) . ' and ' . ucfirst($f->format($exp[1]));

        }else{
            $total =  ucfirst($f->format($exp[0]));
        }

  

        return view('invoice.receipt.show',[
            'receipt'=>$receipt,
            'now'=>$now,
            'total'=>$total,

        ]);
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

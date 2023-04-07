<?php

namespace App\Http\Controllers\Statements;

use App\Http\Controllers\Controller;
use App\Models\Invoice\Invoice;
use App\Models\Master\CustomerHistory;
use App\Models\Master\Customers;
use Illuminate\Http\Request;

class StatementController extends Controller
{
    public function customer()
    {
        $customer = Customers::where('id',request('customer_id'))->with('chargeDesc')->first();
        $receiptsHistory = CustomerHistory::where('user_id',$customer->id)->orderBy('id','desc')->get();
        $unpaidInvoices = DB::table('invoice')
        ->leftJoin('receipts', 'invoice.id', '=', 'receipts.invoice_id')
        ->whereNull('receipts.id')
        ->select('invoice.*')
        ->get();

        return view('statement.customer.statement',[
            'unpaidInvoices'=>$unpaidInvoices,
            'receiptsHistory'=>$receiptsHistory,
            'customer'=>$customer,
        ]);
    }
}

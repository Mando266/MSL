<?php

namespace App\Http\Controllers\Invoice;
use App\Models\Bl\BlDraft;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice\Invoice;
use Illuminate\Support\Facades\Auth;

class ReceiptController extends Controller
{
   
    public function index()
    {
        //
    }

    public function selectinvoice()
    {
        $this->authorize(__FUNCTION__,Receipt::class);
        $bldrafts = BlDraft::where('company_id',Auth::user()->company_id)->get();
        $invoiceRef = Invoice::orderBy('id','desc')->where('company_id',Auth::user()->company_id)->get();
        return view('invoice.receipt.selectinvoice',[
            'bldrafts'=>$bldrafts,
            'invoiceRef'=>$invoiceRef,
        ]);
    }
    
   
    public function create()
    {
        return view('invoice.receipt.create');
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

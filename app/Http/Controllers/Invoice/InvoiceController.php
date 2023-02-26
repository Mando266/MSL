<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use App\Models\Bl\BlDraft;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceChargeDesc;
use App\Models\Voyages\Voyages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::all();
        return view('invoice.index',[
            'invoices'=>$invoices
        ]);
    }

    public function selectBL()
    {
        $bldrafts = BlDraft::where('bl_status',1)->where('company_id',Auth::user()->company_id)->get();
        return view('invoice.invoice.selectBL',[
            'bldrafts'=>$bldrafts,
        ]);
    }

    public function create()
    {
        $bldrafts = BlDraft::findOrFail(request('bldraft_id'));

        $bl_id = request()->input('bldraft_id');
        if($bl_id != null){
            $bldraft = BlDraft::where('id',$bl_id)->with('blDetails')->first();
        }else{
            $bldraft = null;
        }
        $voyages    = Voyages::with('vessel')->where('company_id',Auth::user()->company_id)->get();


        return view('invoice.invoice.create_debit',[
            'bldrafts'=>$bldrafts,
            'bldraft'=>$bldraft,
            'voyages'=>$voyages,

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,Booking::class);
        request()->validate([
            'bldraft_id' => ['required'],
            'customer' => ['required'],
        ]);
        $bldraft = BlDraft::where('id',$request->bldraft_id)->with('blDetails')->first();
        $blkind = str_split($request->bl_kind, 2);
        $blkind = $blkind[0];
        $invoice = Invoice::create([
            'bldraft_id'=>$request->bldraft_id,
            'customer'=>$request->customer,
            'company_id'=>Auth::user()->company_id,
            'invoice_no'=>'',
            'date'=>$request->date,
            'invoice_kind'=>$blkind,
            'type'=>'debit',
            'invoice_status'=>$request->invoice_status,
        ]);
        $invoice_no = 'DRAFT';
        $invoice_no = $invoice_no . str_pad( $invoice->id, 4, "0", STR_PAD_LEFT );
        $invoice->invoice_no = $invoice_no;
        $invoice->save();
        $qty = $bldraft->blDetails->count();
        if($blkind == 40){
            foreach($request->input('invoiceChargeDesc',[])  as $chargeDesc){
                InvoiceChargeDesc::create([
                    'invoice_id'=>$invoice->id,
                    'charge_description'=>$chargeDesc['charge_description'],
                    'size_large'=>$chargeDesc['size_small'],
                    'total_amount'=>$qty * $chargeDesc['size_small'],
                ]);
            }
        }else{
            foreach($request->input('invoiceChargeDesc',[])  as $chargeDesc){
                InvoiceChargeDesc::create([
                    'invoice_id'=>$invoice->id,
                    'charge_description'=>$chargeDesc['charge_description'],
                    'size_small'=>$chargeDesc['size_small'],
                    'total_amount'=>$qty * $chargeDesc['size_small'],
                ]);
            }
        }
        

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
<?php

namespace App\Http\Controllers\Invoice;

use App\Filters\Quotation\InvoiceIndexFilter;
use App\Http\Controllers\Controller;
use App\Models\Bl\BlDraft;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceChargeDesc;
use App\Models\Voyages\VoyagePorts;
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
        $invoices = Invoice::filter(new InvoiceIndexFilter(request()))->orderBy('id','desc')->where('company_id',Auth::user()->company_id)->with('chargeDesc')->paginate(30);
        return view('invoice.invoice.index',[
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
        return redirect()->route('invoice.index')->with('success',trans('voyage.created'));


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = Invoice::with('chargeDesc')->find($id);
        $qty = $invoice->bldraft->blDetails->count();
        $firstVoyagePort = VoyagePorts::where('voyage_id',optional($invoice->bldraft->booking)->voyage_id)->where('port_from_name',optional($invoice->bldraft->booking->loadPort)->id)->first();
        $total = 0;
        foreach($invoice->chargeDesc as $chargeDesc){
            $total += $chargeDesc->total_amount;
        }
        $exp = explode('.', $total);
        $f = new \NumberFormatter("en_US", \NumberFormatter::SPELLOUT);
        if(count($exp) >1){
            $USD =  ucfirst($f->format($exp[0])) . ' and ' . ucfirst($f->format($exp[1]));

        }else{
            $USD =  ucfirst($f->format($exp[0]));

        }
        //dd($USD);
        if($invoice->type == 'debit'){
            return view('invoice.invoice.show_debit',[
                'invoice'=>$invoice,
                'qty'=>$qty,
                'total'=>$total,
                'firstVoyagePort'=>$firstVoyagePort,
                'USD'=>$USD,
            ]);
        }else{
            return view('invoice.invoice.show_invoice',[
                'invoice'=>$invoice,
                'qty'=>$qty,
                'total'=>$total,
                'firstVoyagePort'=>$firstVoyagePort,
                'USD'=>$USD,
            ]);
        }
        
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

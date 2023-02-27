<?php

namespace App\Http\Controllers\Invoice;

use App\Filters\Invoice\InvoiceIndexFilter;
use App\Http\Controllers\Controller;
use App\Models\Bl\BlDraft;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceChargeDesc;
use App\Models\Quotations\LocalPortTriff;
use App\Models\Voyages\VoyagePorts;
use App\Models\Voyages\Voyages;
use Carbon\Carbon;
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

    public function selectBLinvoice()
    {
        $this->authorize(__FUNCTION__,Invoice::class);
        $bldrafts = BlDraft::where('bl_status',1)->where('company_id',Auth::user()->company_id)->get();
        return view('invoice.invoice.selectBLinvoice',[
            'bldrafts'=>$bldrafts,
        ]);
    }
    
    public function selectBL()
    {
        $this->authorize(__FUNCTION__,Invoice::class);
        $bldrafts = BlDraft::where('bl_status',1)->where('company_id',Auth::user()->company_id)->get();
        return view('invoice.invoice.selectBL',[
            'bldrafts'=>$bldrafts,
        ]);
    }
    public function create_invoice()
    {
        $this->authorize(__FUNCTION__,Invoice::class);
        $bldrafts = BlDraft::findOrFail(request('bldraft_id'));

        $bl_id = request()->input('bldraft_id');
        if($bl_id != null){
            $bldraft = BlDraft::where('id',$bl_id)->with('blDetails')->first();
        }else{
            $bldraft = null;
        }
        $qty = $bldraft->blDetails->count();
        
        $voyages    = Voyages::with('vessel')->where('company_id',Auth::user()->company_id)->get();
        $triffDetails = LocalPortTriff::where('port_id',$bldraft->load_port_id)->where('validity_to','>=',Carbon::now()->format("Y-m-d"))
        ->with(["triffPriceDetailes" => function($q) use($bldraft){
            $q->where("equipment_type_id", optional($bldraft->equipmentsType)->id);
            $q->orwhere('equipment_type_id','100');
        }])->first();
        
        return view('invoice.invoice.create_invoice',[
            'bldrafts'=>$bldrafts,
            'qty'=>$qty,
            'bldraft'=>$bldraft,
            'triffDetails'=>$triffDetails,
            'voyages'=>$voyages,
        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,Invoice::class);
        $bldrafts = BlDraft::findOrFail(request('bldraft_id'));

        $bl_id = request()->input('bldraft_id');
        if($bl_id != null){
            $bldraft = BlDraft::where('id',$bl_id)->with('blDetails')->first();
        }else{
            $bldraft = null;
        }
        $voyages    = Voyages::with('vessel')->where('company_id',Auth::user()->company_id)->get();
        $qty = $bldraft->blDetails->count();

        return view('invoice.invoice.create_debit',[
            'bldrafts'=>$bldrafts,
            'qty'=>$qty,
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
        $this->authorize(__FUNCTION__,Invoice::class);
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
        $invoice_no = 'DRAFTD';
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

    public function storeInvoice(Request $request)
    {
        $this->authorize(__FUNCTION__,Invoice::class);
        request()->validate([
            'bldraft_id' => ['required'],
            'customer' => ['required'],
            'exchange_rate' => ['required'],
            'add_egp' => ['required'],
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
            'rate'=>$request->exchange_rate,
            'add_egp'=>$request->add_egp,
            'invoice_kind'=>$blkind,
            'type'=>'invoice',
            'invoice_status'=>$request->invoice_status,
        ]);
        $invoice_no = 'DRAFTV';
        $invoice_no = $invoice_no . str_pad( $invoice->id, 4, "0", STR_PAD_LEFT );
        $invoice->invoice_no = $invoice_no;
        $invoice->save();
        $egyrate = 0;
        if($request->exchange_rate == "eta"){
            $egyrate = optional($bldraft->voyage)->exchange_rate;
        }elseif($request->exchange_rate == "etd"){
            $egyrate = optional($bldraft->voyage)->exchange_rate_etd;
        }
        foreach($request->input('invoiceChargeDesc',[])  as $chargeDesc){
            InvoiceChargeDesc::create([
                'invoice_id'=>$invoice->id,
                'charge_description'=>$chargeDesc['charge_description'],
                'size_small'=>$chargeDesc['size_small'],
                'total_amount'=>$chargeDesc['total'],
                'total_egy'=>$chargeDesc['total'] * $egyrate
            ]);
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
        $total_eg = 0;

        foreach($invoice->chargeDesc as $chargeDesc){
            $total += $chargeDesc->total_amount;
            $total_eg += $chargeDesc->total_egy;
        }
        $exp = explode('.', $total);
        $f = new \NumberFormatter("en_US", \NumberFormatter::SPELLOUT);
        if(count($exp) >1){
            $USD =  ucfirst($f->format($exp[0])) . ' and ' . ucfirst($f->format($exp[1]));

        }else{
            $USD =  ucfirst($f->format($exp[0]));
        }

        $exp = explode('.', $total_eg);
        $f = new \NumberFormatter("en_US", \NumberFormatter::SPELLOUT);
        if(count($exp) >1){
            $EGP =  ucfirst($f->format($exp[0])) . ' and ' . ucfirst($f->format($exp[1]));

        }else{
            $EGP =  ucfirst($f->format($exp[0]));
        }

        if($invoice->type == 'debit'){
            return view('invoice.invoice.show_debit',[
                'invoice'=>$invoice,
                'qty'=>$qty,
                'total'=>$total,
                'total_eg'=>$total_eg,
                'firstVoyagePort'=>$firstVoyagePort,
                'USD'=>$USD,
                'EGP'=>$EGP,

            ]);
        }else{
            $gross_weight = 0;
            $amount = 0;
            $vat = 0;
            foreach($invoice->bldraft->blDetails as $bldetail){
                $gross_weight += $bldetail->gross_weight;
            }
            foreach($invoice->chargeDesc as $charge){
                $amount += $charge->size_small;
                $vat += $charge->size_small * 0;
            }
            return view('invoice.invoice.show_invoice',[
                'invoice'=>$invoice,
                'qty'=>$qty,
                'total'=>$total,
                'total_eg'=>$total_eg,
                'amount'=>$amount,
                'vat'=>$vat,
                'gross_weight'=>$gross_weight,
                'firstVoyagePort'=>$firstVoyagePort,
                'USD'=>$USD,
                'EGP'=>$EGP,
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

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $invoice = Invoice::find($id);
        InvoiceChargeDesc::where('invoice_id',$id)->delete();
        $invoice->delete(); 
        return back()->with('success',trans('Invoice.Deleted.Success'));
    }
}

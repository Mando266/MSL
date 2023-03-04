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
use App\Setting;
use App\Models\Master\Customers;

class InvoiceController extends Controller
{
   
    public function index()
    {
        $invoices = Invoice::filter(new InvoiceIndexFilter(request()))->orderBy('id','desc')
        ->where('company_id',Auth::user()->company_id)->with('chargeDesc')->paginate(30);
        $invoiceRef = Invoice::orderBy('id','desc')->where('company_id',Auth::user()->company_id)->get();
        $bldrafts = BlDraft::where('company_id',Auth::user()->company_id)->get();
        $customers  = Customers::where('company_id',Auth::user()->company_id)->get();
        return view('invoice.invoice.index',[
            'invoices'=>$invoices,
            'invoiceRef'=>$invoiceRef,
            'bldrafts'=>$bldrafts,
            'customers'=>$customers,
        ]);
    }

    public function selectBLinvoice()
    {
        $this->authorize(__FUNCTION__,Invoice::class);
        $bldrafts = BlDraft::
        //where('bl_status',1)->
        where('company_id',Auth::user()->company_id)->get();
        return view('invoice.invoice.selectBLinvoice',[
            'bldrafts'=>$bldrafts,
        ]);
    }
    
    public function selectBL()
    {
        $this->authorize(__FUNCTION__,Invoice::class);
        $bldrafts = BlDraft::
        //where('bl_status',1)->
        where('company_id',Auth::user()->company_id)->get();
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
            'customer_id'=>$request->customer_id,
            'company_id'=>Auth::user()->company_id,
            'invoice_no'=>'',
            'date'=>$request->date,
            'invoice_kind'=>$blkind,
            'type'=>'debit',
            'invoice_status'=>$request->invoice_status,
        ]);
        $setting = Setting::find(1);
        if($invoice->invoice_status == "confirm"){
            $invoice_no = $setting->debit_confirm;
            $setting->debit_confirm += 1;
        }else{
            $invoice_no = 'DRAFTD';
            $invoice_no = $invoice_no . str_pad( $setting->invoice_draft, 4, "0", STR_PAD_LEFT );
            $setting->debit_draft += 1;
        }
        $invoice->invoice_no = $invoice_no;
        $invoice->save();
        $setting->save();
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
        //dd($request->input());
        $this->authorize(__FUNCTION__,Invoice::class);
        request()->validate([
            'bldraft_id' => ['required'],
            'customer' => ['required'],
            'customer_id' => ['required'],
            'exchange_rate' => ['required'],
            'add_egp' => ['required'],
        ]);
        $bldraft = BlDraft::where('id',$request->bldraft_id)->with('blDetails')->first();
        $blkind = str_split($request->bl_kind, 2);
        $blkind = $blkind[0];
        $invoice = Invoice::create([
            'bldraft_id'=>$request->bldraft_id,
            'customer'=>$request->customer,
            'customer_id'=>$request->customer_id,
            'company_id'=>Auth::user()->company_id,
            'invoice_no'=>'',
            'date'=>$request->date,
            'rate'=>$request->exchange_rate,
            'add_egp'=>$request->add_egp,
            'invoice_kind'=>$blkind,
            'type'=>'invoice',
            'invoice_status'=>$request->invoice_status,
        ]);
        $setting = Setting::find(1);
        if($invoice->invoice_status == "confirm"){
            $invoice_no = $setting->invoice_confirm;
            $invoice->invoice_no = $invoice_no;
            $setting->invoice_confirm += 1;
        }else{
            $invoice_no = 'DRAFTV';
            $invoice_no = $invoice_no . str_pad( $setting->invoice_draft, 4, "0", STR_PAD_LEFT );
            $invoice->invoice_no = $invoice_no;
            $setting->invoice_draft += 1;
        }
        $setting->save();
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
                $amount = $amount + ( (float)$charge->size_small);
                $vat = $vat +  (((float)$charge->size_small) * 0);
            }
            $triffDetails = LocalPortTriff::where('port_id',optional($invoice->bldraft)->load_port_id)->where('validity_to','>=',Carbon::now()->format("Y-m-d"))
            ->with(["triffPriceDetailes" => function($q) use($invoice){
                $q->where("equipment_type_id", optional($invoice->bldraft->equipmentsType)->id);
                $q->orwhere('equipment_type_id','100');
            }])->first();

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
                'triffDetails'=>$triffDetails,

            ]);
        }
        
    }

    public function receipt($id)
    {
        $invoice = Invoice::with('chargeDesc')->find($id);
        $total = 0;
        $total_eg = 0;
        $now = Carbon::now();
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
        //dd($receipt);
        return view('invoice.invoice.receipt',[
            'invoice'=>$invoice,
            'now'=>$now,
            'total'=>$total,
            'total_eg'=>$total_eg,
            'USD'=>$USD,
            'EGP'=>$EGP,
        ]);
    }
    public function edit(Request $request, Invoice $invoice)
    {
        $bldraft = BlDraft::where('id',$request->bldraft_id)->with('blDetails')->first();
        $bl_id = request()->input('bldraft_id');
        if($bl_id != null){
            $bldraft = BlDraft::where('id',$bl_id)->with('blDetails')->first();
        }else{
            $bldraft = null;
        }
        $voyages    = Voyages::with('vessel')->where('company_id',Auth::user()->company_id)->get();
        $qty = $bldraft->blDetails->count();
        $invoice_details = InvoiceChargeDesc::where('invoice_id',$invoice->id)->with('invoice')->get();

        return view('invoice.invoice.edit',[
            'invoice'=>$invoice,
            'bldraft'=>$bldraft,
            'qty'=>$qty,
            'bldraft'=>$bldraft,
            'voyages'=>$voyages,
            'invoice_details'=>$invoice_details,
        ]);
    }
    public function update(Request $request, Invoice $invoice)
    {
        $this->authorize(__FUNCTION__,Invoice::class);
        $inputs = request()->all();
        unset($inputs['invoiceChargeDesc'],$inputs['_token'],$inputs['removed']);
        $invoice->update($inputs);
        InvoiceChargeDesc::destroy(explode(',',$request->removed));
        $invoice->createOrUpdateInvoiceChargeDesc($request->invoiceChargeDesc); 
        return redirect()->route('invoice.index')->with('success',trans('Invoice.Updated.Success'));
    }

    public function destroy($id)
    {
        $invoice = Invoice::find($id);
        InvoiceChargeDesc::where('invoice_id',$id)->delete();
        $invoice->delete(); 
        return back()->with('success',trans('Invoice.Deleted.Success'));
    }
}

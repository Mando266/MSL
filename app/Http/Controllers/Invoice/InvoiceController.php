<?php

namespace App\Http\Controllers\Invoice;

use App\Filters\Invoice\InvoiceIndexFilter;
use App\Http\Controllers\Controller;
use App\Models\Bl\BlDraft;
use App\Models\Booking\Booking;
use App\Models\Containers\Demurrage;
use App\Models\Invoice\ChargesDesc;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceChargeDesc;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Customers;
use App\Models\Master\Ports;
use App\Models\Quotations\LocalPortTriff;
use App\Models\Voyages\VoyagePorts;
use App\Models\Voyages\Voyages;
use App\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{

    public function index()
    {

        $invoices = Invoice::filter(new InvoiceIndexFilter(request()))
        ->where('company_id',Auth::user()->company_id)->with('chargeDesc','bldraft','receipts')
        ->orderBy('id', 'desc')
        ->get(); // Get all receipts from the database

        $sortedInvoices = $invoices->sortByDesc(function ($invoice) {
        $matches = [];
        preg_match('/\d+/', $invoice->invoice_no, $matches); // Extract the number from the receipt number using a regular expression
        return (int) $matches[0]; // Return the number as an integer for sorting
        });

        $perPage = 30;
        $page = request('page', 1);
        $offset = ($page - 1) * $perPage;

        // Create a new collection containing the receipts for the current page
        $currentPagesortedInvoices = collect($sortedInvoices->slice($offset, $perPage));

        // Create a paginator for the sortedInvoices
        $paginator = new LengthAwarePaginator(
        $currentPagesortedInvoices,
        $sortedInvoices->count(),
        $perPage,
        $page,
        ['path' => request()->url(), 'query' => request()->query()]
        );


        $exportinvoices = Invoice::filter(new InvoiceIndexFilter(request()))->orderBy('id','desc')
        ->where('company_id',Auth::user()->company_id)->with('chargeDesc','bldraft','receipts')->get();
        //dd($exportinvoices);
        session()->flash('invoice',$exportinvoices);
        $invoiceRef = Invoice::orderBy('id','desc')->where('company_id',Auth::user()->company_id)->get();
        $bldrafts = BlDraft::where('company_id',Auth::user()->company_id)->get();
        $voyages    = Voyages::where('company_id',Auth::user()->company_id)->get();
        $customers  = Customers::where('company_id',Auth::user()->company_id)->get();
        $etd = VoyagePorts::get();

        return view('invoice.invoice.index',[
            'invoices'=>$paginator,
            'invoiceRef'=>$invoiceRef,
            'bldrafts'=>$bldrafts,
            'customers'=>$customers,
            'voyages'=>$voyages,
            'etd'=>$etd,

        ]);
    }

    public function selectBLinvoice()
    {
        $bldrafts = BlDraft::
        //where('bl_status',1)->
        where('company_id',Auth::user()->company_id)->get();
        return view('invoice.invoice.selectBLinvoice',[
            'bldrafts'=>$bldrafts,
        ]);
    }

    public function selectBL()
    {
        $bldrafts = BlDraft::
        //where('bl_status',1)->
        where('company_id',Auth::user()->company_id)->get();
        return view('invoice.invoice.selectBL',[
            'bldrafts'=>$bldrafts,
        ]);
    }
    public function create_invoice()
    {

        $charges = ChargesDesc::where('company_id',Auth::user()->company_id)->orderBy('id')->get();

        if(request('bldraft_id') == "customize"){
            $ffws = Customers::where('company_id',Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
                return $query->where('role_id', 6);
            })->with('CustomerRoles.role')->get();
            $shippers = Customers::where('company_id', Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
                return $query->where('role_id', 1);
            })->with('CustomerRoles.role')->get();
            $suppliers = Customers::where('company_id', Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
                return $query->where('role_id', 7);
            })->with('CustomerRoles.role')->get();
            $notify = Customers::where('company_id',Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
                return $query->where('role_id', 3);
            })->with('CustomerRoles.role')->get();

            $voyages    = Voyages::with('vessel')->where('company_id',Auth::user()->company_id)->get();
            $ports = Ports::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
            $equipmentTypes = ContainersTypes::orderBy('id')->get();
            $bookings  = Booking::orderBy('id','desc')->where('company_id',Auth::user()->company_id)->get();

            return view('invoice.invoice.create_customize_invoice',[
                'shippers'=>$shippers,
                'suppliers'=>$suppliers,
                'notify'=>$notify,
                'ffws'=>$ffws,
                'voyages'=>$voyages,
                'ports'=>$ports,
                'equipmentTypes'=>$equipmentTypes,
                'bookings'=>$bookings,
                'charges'=>$charges,
        ]);
    }
        $bldrafts = BlDraft::findOrFail(request('bldraft_id'));

        $bl_id = request()->input('bldraft_id');
        if ($bl_id != null) {
            $bldraft = BlDraft::where('id', $bl_id)->with('blDetails')->first();
        } else {
            $bldraft = null;
        }
        $qty = $bldraft->blDetails->count();

        $voyages    = Voyages::with('vessel')->where('company_id',Auth::user()->company_id)->get();

        if(optional(optional(optional($bldraft)->booking)->quotation)->shipment_type == "Export"){

            $triffDetails = LocalPortTriff::where('port_id', $bldraft->load_port_id)
            ->where('validity_to', '>=', Carbon::now()->format("Y-m-d"))
            ->with(["triffPriceDetailes" => function($q) use($bldraft) {
                $q->where("is_import_or_export", 1)
                ->where(function($query) use($bldraft) {
                    $query->where("equipment_type_id", optional($bldraft->equipmentsType)->id)
                    ->orWhere('equipment_type_id', '100');
                });
            },'triffPriceDetailes.charge'])
            ->first();
        }else{

            $triffDetails = LocalPortTriff::where('port_id', $bldraft->discharge_port_id)
                ->where('validity_to', '>=', Carbon::now()->format("Y-m-d"))
                ->with(["triffPriceDetailes" => function($q) use($bldraft) {
                    $q->where("is_import_or_export", 0);
                    $q->where('standard_or_customise',1)
                    ->where(function($query) use($bldraft) {
                        $query->where("equipment_type_id", optional($bldraft->equipmentsType)->id)
                        ->orWhere('equipment_type_id', '100');
                    });
            },'triffPriceDetailes.charge'])
            ->first();
        }
        // id's of triff type export and import power charge
        $powerTriffs = [7,8];
        $cartData = json_decode(request('cart_data_for_invoice'));
        foreach ($cartData as $cart){
            if(in_array(Demurrage::where('id',$cart->triffValue)->pluck('tariff_type_id')->first(),$powerTriffs)){
                $cart->triffText = "Power Charge";
            }else{
                $cart->triffText = "Port Storage";
            }
        }
        return view('invoice.invoice.create_invoice', [
            'bldrafts' => $bldrafts,
            'cartData' => $cartData ?? null,
            'qty' => $qty,
            'bldraft' => $bldraft,
            'triffDetails' => $triffDetails,
            'voyages' => $voyages,
            'charges' => $charges,
        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,Invoice::class);
        if(request('bldraft_id') == "customize"){
            $ffws = Customers::where('company_id',Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
                return $query->where('role_id', 6);
            })->with('CustomerRoles.role')->get();
            $shippers = Customers::where('company_id',Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
                return $query->where('role_id', 1);
            })->with('CustomerRoles.role')->get();
            $suppliers = Customers::where('company_id',Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
                return $query->where('role_id', 7);
            })->with('CustomerRoles.role')->get();
            $notify = Customers::where('company_id',Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
                return $query->where('role_id', 3);

            })->with('CustomerRoles.role')->get();

            $voyages    = Voyages::with('vessel')->where('company_id',Auth::user()->company_id)->get();
            $ports = Ports::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
            $equipmentTypes = ContainersTypes::orderBy('id')->get();
            $bookings  = Booking::orderBy('id','desc')->where('company_id',Auth::user()->company_id)->get();

            return view('invoice.invoice.create_customize_debit',[
                'shippers'=>$shippers,
                'suppliers'=>$suppliers,
                'cartData' => $cartData ?? null,
                'notify'=>$notify,
                'ffws'=>$ffws,
                'voyages'=>$voyages,
                'ports'=>$ports,
                'equipmentTypes'=>$equipmentTypes,
                'bookings'=>$bookings,
            ]);
        }
        $bldrafts = BlDraft::findOrFail(request('bldraft_id'));

        $bl_id = request()->input('bldraft_id');
        if($bl_id != null){
            $bldraft = BlDraft::where('id',$bl_id)->with('blDetails')->first();
        }else{
            $bldraft = null;
        }
        $voyages    = Voyages::with('vessel')->where('company_id',Auth::user()->company_id)->get();
        $qty = $bldraft->blDetails->count();
        $cartData = json_decode(request('cart_data_for_invoice'));
        return view('invoice.invoice.create_debit',[
            'bldrafts'=>$bldrafts,
            'cartData' => $cartData ?? null,
            'qty'=>$qty,
            'bldraft'=>$bldraft,
            'voyages'=>$voyages,

        ]);
    }

    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,Invoice::class);

        if($request->bldraft_id == 'customize'){
            request()->validate([
                'customer' => ['required'],
                'customer_id' => ['required'],

            ]);
        }else{
            request()->validate([
                'bldraft_id' => ['required'],
                'customer' => ['required'],
                'customer_id' => ['required'],
            ]);
        }
        if($request->bldraft_id != 'customize'){

            $totalAmount = 0;
            foreach($request->input('invoiceChargeDesc',[])  as $desc){
                $totalAmount += $desc['total_amount'];
            }
            if($totalAmount == 0){
                return redirect()->back()->with('error','Invoice Total Amount Can not be Equal Zero')->withInput($request->input());
            }
        }
        $bldraft = BlDraft::where('id',$request->bldraft_id)->with('blDetails')->first();
        $blkind = str_split($request->bl_kind, 2);
        $blkind = $blkind[0];
        if($request->bldraft_id == 'customize'){
        $invoice = Invoice::create([
            'bldraft_id'=>$request->bldraft_id,
            'customer'=>$request->customer,
            'qty'=>$request->qty,
            'customer_id'=>$request->customer_id,
            'place_of_acceptence'=>$request->place_of_acceptence,
            'load_port'=>$request->load_port,
            'booking_ref'=>$request->booking_ref,
            'voyage_id'=>$request->voyage_id,
            'discharge_port'=>$request->discharge_port,
            'port_of_delivery'=>$request->port_of_delivery,
            'equipment_type'=>$request->equipment_type,
            'company_id'=>Auth::user()->company_id,
            'invoice_no'=>'',
            'date'=>$request->date,
            'rate'=>$request->exchange_rate,
            'add_egp'=>'false',
            'invoice_kind'=>'',
            'type'=>'debit',
            'invoice_status'=>$request->invoice_status,
            'notes'=>$request->notes,
            'activity_code'=>$request->activity_code,

        ]);
        }else{
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
                'add_egp'=>'false',
                'voyage_id'=>$request->voyage_id,
                'notes'=>$request->notes,
            ]);
        }
        $setting = Setting::find(1);
        if($invoice->invoice_status == "confirm"){
            $invoice_no = 'DN'.' '.'/'.' '.$setting->debit_confirm.' / 23';
            $setting->debit_confirm += 1;
        }else{
            $invoice_no = 'DRAFTD';
            $invoice_no = $invoice_no . str_pad( $setting->debit_draft, 4, "0", STR_PAD_LEFT );
            $setting->debit_draft += 1;
        }
        $invoice->invoice_no = $invoice_no;
        $invoice->save();
        $setting->save();

        if($request->bldraft_id != 'customize'){
            $qty = $bldraft->blDetails->count();
        }
        if($blkind == 40){
            foreach($request->input('invoiceChargeDesc',[])  as $chargeDesc){
                InvoiceChargeDesc::create([
                    'invoice_id' => $invoice->id,
                    'charge_description' => $chargeDesc['charge_description'],
                    'size_large' => $chargeDesc['size_small'],
                    'total_amount' => ($chargeDesc['size_small'] == $chargeDesc['total_amount'] && $qty > 1) ? $chargeDesc['size_small'] : $qty * $chargeDesc['size_small'],
                    // 'enabled'=>$chargeDesc['enabled'],
                ]);
            }
        }elseif($request->bldraft_id == 'customize'){
            foreach($request->input('invoiceChargeDesc',[])  as $chargeDesc){
                InvoiceChargeDesc::create([
                    'invoice_id'=>$invoice->id,
                    'charge_description'=>$chargeDesc['charge_description'],
                    'size_small'=>$chargeDesc['size_small'],
                    'total_amount'=>$chargeDesc['total_amount'],
                    'enabled'=>$chargeDesc['enabled'],
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

        if($request->bldraft_id != 'customize'){
        $bldrafts = BlDraft::where('id',$request->input('bldraft_id'))->first();
        $bldrafts->has_bl = 1;
        $bldrafts->save();
        }
        return redirect()->route('invoice.index')->with('success',trans('Invoice.created'));
    }

    public function storeInvoice(Request $request)
    {

        if($request->bldraft_id == 'customize'){
            request()->validate([
                'customer' => ['required'],
            ]);
        }else{
            request()->validate([
                'bldraft_id' => ['required'],
                'customer' => ['required'],
                'customer_id' => ['required'],
                'exchange_rate' => ['required'],
                'add_egp' => ['required'],
            ]);
        }

        $totalAmount = 0;
        foreach($request->input('invoiceChargeDesc',[])  as $desc){
            $totalAmount += $desc['total'];
        }
        if($totalAmount == 0){
            return redirect()->back()->with('error','Invoice Total Amount Can not be Equal Zero')->withInput($request->input());
        }
        if($request->invoice_status == "confirm"){
            if($request->add_egp == "true"){
                return redirect()->back()->with('error','You Must Choose EGP or USD in Confirmed Invoice')->withInput($request->input());
            }
        }
        if($request->bldraft_id == 'customize'){
            $invoice = Invoice::create([
                'bldraft_id'=>$request->bldraft_id,
                'tax_discount'=>$request->tax_discount,
                'customer'=>$request->customer,
                'qty'=>$request->qty,
                'customer_id'=>$request->customer_id,
                'place_of_acceptence'=>$request->place_of_acceptence,
                'load_port'=>$request->load_port,
                'booking_ref'=>$request->booking_ref,
                'voyage_id'=>$request->voyage_id,
                'discharge_port'=>$request->discharge_port,
                'port_of_delivery'=>$request->port_of_delivery,
                'equipment_type'=>$request->equipment_type,
                'company_id'=>Auth::user()->company_id,
                'invoice_no'=>'',
                'date'=>$request->date,
                'rate'=>$request->exchange_rate,
                'add_egp'=>$request->add_egp,
                'vat'=>$request->vat,
                'invoice_kind'=>'',
                'type'=>'invoice',
                'invoice_status'=>$request->invoice_status,
                'booking_status'=>$request->booking_status,
                'notes'=>$request->notes,
                'customize_exchange_rate'=>$request->customize_exchange_rate,
            ]);
        }else{
            $bldraft = BlDraft::where('id',$request->bldraft_id)->with('blDetails')->first();
            $blkind = str_split($request->bl_kind, 2);
            $blkind = $blkind[0];
            $invoice = Invoice::create([
                'bldraft_id'=>$request->bldraft_id,
                'tax_discount'=>$request->tax_discount,
                'customer'=>$request->customer,
                'customer_id'=>$request->customer_id,
                'company_id'=>Auth::user()->company_id,
                'invoice_no'=>'',
                'date'=>$request->date,
                'rate'=>$request->exchange_rate,
                'vat'=>$request->vat,
                'add_egp'=>$request->add_egp,
                'invoice_kind'=>$blkind,
                'type'=>'invoice',
                'invoice_status'=>$request->invoice_status,
                'notes'=>$request->notes,
            ]);
        }

        $setting = Setting::find(1);
        if($request->bldraft_id != 'customize'){
            if($invoice->invoice_status == "confirm"){
                if(optional($invoice->bldraft->booking->quotation)->shipment_type == "Export"){
                    $invoice->invoice_no = 'ALYEXP'.' '.'/'.' '.$setting->invoice_confirm.' / 23';
                }elseif(optional($invoice->bldraft->booking->quotation)->shipment_type == "Import"){
                    $invoice->invoice_no = 'ALYIMP'.' '.'/'.' '.$setting->invoice_confirm.' / 23';
                }
                $setting->invoice_confirm += 1;
            }else{
                $invoice_no = 'DRAFTV';
                $invoice_no = $invoice_no . str_pad( $setting->invoice_draft, 4, "0", STR_PAD_LEFT );
                $invoice->invoice_no = $invoice_no;
                $setting->invoice_draft += 1;
            }
        }else{
            if($request->booking_status == "import" && $invoice->invoice_status == "confirm"){
                $invoice->invoice_no = 'ALYIMP'.' '.'/'.' '.$setting->invoice_confirm.' / 23';
                $setting->invoice_confirm += 1;
            }elseif($request->booking_status == "export" && $invoice->invoice_status == "confirm"){
                $invoice->invoice_no = 'ALYEXP'.' '.'/'.' '.$setting->invoice_confirm.' / 23';
                $setting->invoice_confirm += 1;
            }
            elseif($invoice->invoice_status == "draft"){
            $invoice_no = 'DRAFTV';
            $invoice_no = $invoice_no . str_pad( $setting->invoice_draft, 4, "0", STR_PAD_LEFT );
            $invoice->invoice_no = $invoice_no;
            $setting->invoice_draft += 1;
            }
        }
        $setting->save();
        $invoice->save();
        // $egyrate = 0;
        // if($request->bldraft_id != 'customize'){
        //     if($request->exchange_rate == "eta"){
        //         $egyrate = optional($invoice->voyage)->exchange_rate;
        //     }elseif($request->exchange_rate == "etd"){
        //         $egyrate = optional($invoice->voyage)->exchange_rate_etd;
        //     }
        // }

        foreach($request->input('invoiceChargeDesc',[])  as $chargeDesc){
            InvoiceChargeDesc::create([
                'invoice_id'=>$invoice->id,
                'charge_description'=>$chargeDesc['charge_description'],
                'size_small'=>$chargeDesc['size_small'],
                'total_amount'=>$chargeDesc['total'],
                'total_egy'=>$chargeDesc['egy_amount'],
                'enabled'=>$chargeDesc['enabled'],
                'add_vat'=>$chargeDesc['add_vat'],
                'usd_vat'=>$chargeDesc['usd_vat'],
                'egp_vat'=>$chargeDesc['egp_vat'],
            ]);
        }

        if($request->bldraft_id != 'customize'){
            $bldrafts = BlDraft::where('id',$request->input('bldraft_id'))->first();
            $bldrafts->has_bl = 1;
            $bldrafts->save();
        }
        return redirect()->route('invoice.index')->with('success',trans('Invoice.created'));
    }

    public function show($id)
    {
        $invoice = Invoice::with('chargeDesc')->find($id);
        $firstVoyagePortdis =null;
        if(optional(optional(optional($invoice->bldraft)->booking)->quotation)->shipment_type == "Import"){
            $firstVoyagePortdis = VoyagePorts::where('voyage_id',optional($invoice->bldraft->booking)->voyage_id)
            ->where('port_from_name',optional($invoice->bldraft->booking->dischargePort)->id)->first();
        }

        $secondVoyagePortdis =null;
        if(optional(optional(optional($invoice->bldraft)->booking)->quotation)->shipment_type == "Import" && optional($invoice->bldraft->booking)->transhipment_port != null){
            $secondVoyagePortdis = VoyagePorts::where('voyage_id',optional($invoice->bldraft->booking)->voyage_id_second)
            ->where('port_from_name',optional($invoice->bldraft->booking->dischargePort)->id)->first();
        }

        if($invoice->bldraft_id == 0){
            $qty = $invoice->qty;
            if($invoice->booking != null){
                $firstVoyagePort = VoyagePorts::where('voyage_id',optional($invoice->booking)->voyage_id)
                ->where('port_from_name',optional($invoice->booking->loadPort)->id)->first();
            }else{
                $firstVoyagePort = null;
            }
        }else{
            $qty = $invoice->bldraft->blDetails->count();
            $firstVoyagePort = VoyagePorts::where('voyage_id',optional($invoice->bldraft->booking)->voyage_id)
            ->where('port_from_name',optional($invoice->bldraft->booking->loadPort)->id)->first();
        }
        $vat = $invoice->vat;
        $vat = $vat / 100;
        $total = 0;
        $total_eg = 0;
        $total_after_vat = 0;
        $total_before_vat = 0;
        $total_eg_after_vat = 0;
        $total_eg_before_vat = 0;
        $totalAftereTax = 0;
        $totalAftereTax_eg = 0;

        foreach($invoice->chargeDesc as $chargeDesc){
            $total += $chargeDesc->total_amount;
            $total_eg += $chargeDesc->total_egy;
            //Tax
            $totalAftereTax = (($total * $invoice->tax_discount)/100);
            $totalAftereTax_eg = (($total_eg * $invoice->tax_discount)/100);
            //End Tax
           if($chargeDesc->add_vat == 1){
                $total_after_vat += ($vat * $chargeDesc->total_amount);
                $total_eg_after_vat += ($vat * $chargeDesc->total_egy);
            }
        }
        $total_before_vat = $total;
        if($total_after_vat != 0){
            $total = $total + $total_after_vat;
        }
        $exp = explode('.', $total);
        $f = new \NumberFormatter("en_US", \NumberFormatter::SPELLOUT);
        if(count($exp) >1){
            $USD =  ucfirst($f->format($exp[0])) . ' and ' . ucfirst($f->format($exp[1]));

        }else{
            $USD =  ucfirst($f->format($exp[0]));
        }

        $total_eg_before_vat = $total_eg;
        if($total_eg_after_vat != 0){
            $total_eg = $total_eg + $total_eg_after_vat;
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
                'firstVoyagePortdis'=>$firstVoyagePortdis,
                'secondVoyagePortdis'=>$secondVoyagePortdis,
                'USD'=>$USD,
                'EGP'=>$EGP,

            ]);
        }else{
            $gross_weight = 0;
            $amount = 0;

            if($invoice->bldraft_id != 0){
                foreach($invoice->bldraft->blDetails as $bldetail){
                    $gross_weight += $bldetail->gross_weight;
                }
                $triffDetails = LocalPortTriff::where('port_id',optional($invoice->bldraft)->load_port_id)
                    ->where('validity_to','>=',Carbon::now()->format("Y-m-d"))
                    ->with(["triffPriceDetailes" => function($q) use($invoice){
                        $q->where("equipment_type_id", optional($invoice->bldraft->equipmentsType)->id);
                        $q->orwhere('equipment_type_id','100');
                    }])->first();
            }else{
                $triffDetails = LocalPortTriff::where('port_id',$invoice->load_port)
                    ->where('validity_to','>=',Carbon::now()->format("Y-m-d"))
                    ->with(["triffPriceDetailes" => function($q) use($invoice){
                        $q->where("equipment_type_id", optional($invoice->equipmentsType)->id);
                        $q->orwhere('equipment_type_id','100');
                    }])->first();
            }


            foreach($invoice->chargeDesc as $charge){
                $amount = $amount + ( (float)$charge->size_small);
            }


            return view('invoice.invoice.show_invoice',[
                'invoice'=>$invoice,
                'qty'=>$qty,
                'total'=>$total,
                'total_eg'=>$total_eg,
                'amount'=>$amount,
                'gross_weight'=>$gross_weight,
                'firstVoyagePort'=>$firstVoyagePort,
                'firstVoyagePortdis'=>$firstVoyagePortdis,
                'secondVoyagePortdis'=>$secondVoyagePortdis,
                'USD'=>$USD,
                'EGP'=>$EGP,
                'total_after_vat'=>$total_after_vat,
                'total_before_vat'=>$total_before_vat,
                'total_eg_after_vat'=>$total_eg_after_vat,
                'total_eg_before_vat'=>$total_eg_before_vat,
                'totalAftereTax'=>$totalAftereTax,
                'totalAftereTax_eg'=>$totalAftereTax_eg,
                'triffDetails'=>$triffDetails,
            ]);
        }

    }

    public function edit(Request $request, Invoice $invoice)
    {
        $charges = ChargesDesc::where('company_id',Auth::user()->company_id)->orderBy('id')->get();

        if($invoice->bldraft_id != 0){
            $bldraft = BlDraft::where('id',$invoice->bldraft_id)->with('blDetails')->first();
        }else{
            $bldraft = null;
            $ffws = Customers::where('company_id',Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
                return $query->where('role_id', 6);
            })->with('CustomerRoles.role')->get();
            $shippers = Customers::where('company_id',Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
                return $query->where('role_id', 1);
            })->with('CustomerRoles.role')->get();
            $suppliers = Customers::where('company_id',Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
                return $query->where('role_id', 7);
            })->with('CustomerRoles.role')->get();
            $notify = Customers::where('company_id',Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
                return $query->where('role_id', 3);

            })->with('CustomerRoles.role')->get();


            $voyages    = Voyages::with('vessel')->where('company_id',Auth::user()->company_id)->get();
            $ports = Ports::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
            $equipmentTypes = ContainersTypes::orderBy('id')->get();
            $bookings  = Booking::orderBy('id','desc')->where('company_id',Auth::user()->company_id)->get();
            $invoice_details = InvoiceChargeDesc::where('invoice_id',$invoice->id)->with('invoice')->get();
            $total = 0;
            $total_eg = 0;

            foreach($invoice->chargeDesc as $chargeDesc){
                $total += $chargeDesc->total_amount;
                $total_eg += $chargeDesc->total_egy;
            }

            return view('invoice.invoice.edit_customized_invoice',[
                'shippers'=>$shippers,
                'suppliers'=>$suppliers,
                'notify'=>$notify,
                'ffws'=>$ffws,
                'ports'=>$ports,
                'equipmentTypes'=>$equipmentTypes,
                'bookings'=>$bookings,
                'invoice'=>$invoice,
                'bldraft'=>$bldraft,
                'voyages'=>$voyages,
                'invoice_details'=>$invoice_details,
                'total'=>$total,
                'charges'=>$charges,
                'total_eg'=>$total_eg,
            ]);
        }
        $voyages    = Voyages::with('vessel')->where('company_id',Auth::user()->company_id)->get();
        $qty = $bldraft->blDetails->count();
        $invoice_details = InvoiceChargeDesc::where('invoice_id',$invoice->id)->with('invoice')->get();
        $total = 0;
        $total_eg = 0;

        foreach($invoice->chargeDesc as $chargeDesc){
            $total += $chargeDesc->total_amount;
            $total_eg += $chargeDesc->total_egy;
        }
        return view('invoice.invoice.edit',[
            'invoice'=>$invoice,
            'bldraft'=>$bldraft,
            'qty'=>$qty,
            'voyages'=>$voyages,
            'invoice_details'=>$invoice_details,
            'total'=>$total,
            'charges'=>$charges,
            'total_eg'=>$total_eg,
        ]);
    }
    public function update(Request $request, Invoice $invoice)
    {
        $this->authorize(__FUNCTION__,Invoice::class);
        if($invoice->bldraft_id == 0){
            $totalAmount = 0;
            foreach($request->input('invoiceChargeDesc',[])  as $desc){
                $totalAmount += $desc['total_amount'];
            }
            if($totalAmount == 0){
                return redirect()->back()->with('error','Invoice Total Amount Can not be equal zero')->withInput($request->input());
            }
        }
        if($request->invoice_status == "confirm"){
            if($request->add_egp == "true"){
                return redirect()->back()->with('error','You Must Choose EGP or USD in Confirmed Invoice')->withInput($request->input());
            }
        }
        $setting = Setting::find(1);
        $inputs = request()->all();
        unset($inputs['invoiceChargeDesc'],$inputs['_token'],$inputs['removed']);
        if($invoice->invoice_status == "draft" && $request->invoice_status == "confirm" && $invoice->type == "invoice"){
            $setting = Setting::find(1);
            // check if this invoice is customized
            if($invoice->bldraft_id != 0){
                if(optional($invoice->bldraft->booking->quotation)->shipment_type == "Export"){
                    $inputs['invoice_no'] = 'ALYEXP'.' '.'/'.' '.$setting->invoice_confirm.' / 23';
                }elseif(optional($invoice->bldraft->booking->quotation)->shipment_type == "Import"){
                    $inputs['invoice_no'] = 'ALYIMP'.' '.'/'.' '.$setting->invoice_confirm.' / 23';
                }
            }else{
                if($inputs['booking_status'] == "import"){
                    $inputs['invoice_no'] = 'ALYIMP'.' '.'/'.' '.$setting->invoice_confirm.' / 23';
                }elseif($inputs['booking_status'] == "export"){
                    $inputs['invoice_no'] = 'ALYEXP'.' '.'/'.' '.$setting->invoice_confirm.' / 23';
                }
            }
            $setting->invoice_confirm += 1;
        } elseif ($invoice->invoice_status == "draft" && $request->invoice_status == "confirm" && $invoice->type == "debit") {
            $setting = Setting::find(1);
            $inputs['invoice_no'] = 'DN' . ' ' . '/' . ' ' . $setting->debit_confirm . ' / 23';
            $setting->debit_confirm += 1;
        }
        $setting->save();
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

    public function invoiceJson($id){
        $invoice = Invoice::find($id);
        $invoiceModel = $invoice->getTaxInvoiceModel();
        return $invoiceModel->toJson();
    }
}

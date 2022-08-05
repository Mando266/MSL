<?php

namespace App\Http\Controllers\Quotations;
use App\Http\Controllers\Controller;
use App\Models\Quotations\Quotation;
use App\Models\Quotations\QuotationDes;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Currency;
use App\Models\Master\Customers;
use App\Models\Master\Ports;
use App\User as AppUser;
use App\Models\Master\Agents;
use App\Filters\Quotation\QuotationIndexFilter;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuotationsController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,Quotation::class);
        if(Auth::user()->is_super_admin){
            $quotations = Quotation::filter(new QuotationIndexFilter(request()))->orderBy('id','desc')->paginate(30);
            $quotation = Quotation::get();
        }
        else
        {
            $quotations = Quotation::where('agent_id',Auth::user()->id)->filter(new QuotationIndexFilter(request()))->orderBy('id','desc')->paginate(30);
            $quotation = Quotation::get();
        }
        return view('quotations.quotations.index',[
            'items'=>$quotations,
            'quotation'=>$quotation,

        ]);    
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,Quotation::class);
        $ports = Ports::orderBy('id')->get();
        $container_types = ContainersTypes::orderBy('id')->get();
        $currency = Currency::orderBy('id')->get();
        $customers = Customers::orderBy('id')->get();
        $isSuperAdmin = false;
        if(Auth::user()->is_super_admin){
            $isSuperAdmin = true;
            // $agents = AppUser::role('agent');
            $agents = Agents::get();
        }else{
            $agents = [];
        }

        return view('quotations.quotations.create',[
            'ports'=>$ports,
            'isSuperAdmin'=>$isSuperAdmin,
            'agents'=>$agents,
            'container_types'=>$container_types,
            'currency'=>$currency,
            'customers'=>$customers,

        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'validity_from' => ['required'], 
            'customer_id' => ['required'], 
            'place_of_acceptence_id' => ['required'], 
            'load_port_id' => ['required'], 
            'equipment_type_id' => ['required'], 
            'export_detention' => ['required'], 
            'import_detention' => ['required'], 
            'place_of_delivery_id' => ['required','different:place_of_acceptence_id'],
            'discharge_port_id' => ['required','different:load_port_id'],
            'validity_to' => ['required','after:validity_from'],
        ],[
            'validity_to.after'=>'Validaty To Should Be After Validaty From ',
            'place_of_delivery_id.different'=>'Place Of Delivery The Same  Place Of Acceptence',
            'discharge_port_id.different'=>'Load Port The Same  Discharge Port',
        ]);

        $user = Auth::user();
        $validityFrom = $request->validity_from;
        $validityFrom = str_replace('-', '', $validityFrom);
        $placeOfAcceptance = Ports::where('id',$request->place_of_acceptence_id)->pluck('code')->first();
        $placeOfDelivery = Ports::where('id',$request->place_of_delivery_id)->pluck('code')->first();
        $customerName = Customers::where('id', $request->customer_id)->pluck('name')->first();
        $customerName = substr($customerName, 0, 3);
        $refNo = $customerName.$validityFrom.$placeOfAcceptance.$placeOfDelivery;
        
        $rate_sh = false;
        $rate_cn = false;
        $rate_nt = false;
        $rate_fwd = false;
        switch ($request->rate) {
            case "rate_sh":
                $rate_sh = true;
                break;
            case "rate_cn":
                $rate_cn = true;
                break;
            case "rate_nt":
                $rate_nt = true;
                break;
            case "rate_fwd":
                $rate_fwd = true;
                break;                
    }
    if(isset($request->agent_id)){
        $quotations = Quotation::create([
            'ref_no'=> "",
            'agent_id'=>$request->agent_id,
            'validity_from'=> $request->input('validity_from'),
            'validity_to'=> $request->input('validity_to'),
            'rate_sh'=> $rate_sh,
            'rate_cn'=> $rate_cn,
            'rate_nt'=> $rate_nt,
            'rate_fwd'=> $rate_fwd,
            'customer_id'=> $request->input('customer_id'),
            'place_of_acceptence_id'=> $request->input('place_of_acceptence_id'),
            'place_of_delivery_id'=> $request->input('place_of_delivery_id'),
            'load_port_id'=> $request->input('load_port_id'),
            'discharge_port_id'=> $request->input('discharge_port_id'),
            'equipment_type_id'=> $request->input('equipment_type_id'),
            'export_detention'=> $request->input('export_detention'),
            'import_detention'=> $request->input('import_detention'),
            'status'=> "pending",
        ]);
        $refNo .=$quotations->id;
        $quotations->ref_no = $refNo;
        $quotations->save();
    }else{
        $quotations = Quotation::create([
            'ref_no'=> "",
            'agent_id'=>$user->agent_id,
            'validity_from'=> $request->input('validity_from'),
            'validity_to'=> $request->input('validity_to'),
            'rate_sh'=> $rate_sh,
            'rate_cn'=> $rate_cn,
            'rate_nt'=> $rate_nt,
            'rate_fwd'=> $rate_fwd,
            'customer_id'=> $request->input('customer_id'),
            'place_of_acceptence_id'=> $request->input('place_of_acceptence_id'),
            'place_of_delivery_id'=> $request->input('place_of_delivery_id'),
            'load_port_id'=> $request->input('load_port_id'),
            'discharge_port_id'=> $request->input('discharge_port_id'),
            'equipment_type_id'=> $request->input('equipment_type_id'),
            'export_detention'=> $request->input('export_detention'),
            'import_detention'=> $request->input('import_detention'),
            'status'=> "pending",
        ]);
        $refNo .=$quotations->id;
        $quotations->ref_no = $refNo;
        $quotations->save();
    }

        foreach($request->input('quotationDes',[]) as $quotationDes){
            QuotationDes::create([
                'quotation_id'=>$quotations->id,
                'charge_desc'=>$quotationDes['charge_desc'],
                'mode'=>$quotationDes['mode'],
                'currency'=>$quotationDes['currency'],
                'charge_unit'=>$quotationDes['charge_unit'],
            ]);
        }
        return redirect()->route('quotations.index')->with('success',trans('Quotation.created'));
    }

    public function show($id)
    {
        $quotation = Quotation::with('quotationDesc')->find($id);
        return view('quotations.quotations.show',[
            'quotation'=>$quotation,

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

    public function approve($id)
    {
        $quotation = Quotation::findOrFail($id);
        
        if($quotation) {
            $quotation->status = "approved";
            $quotation->save();
        }
        return back()->with('success',"$quotation->name approved Successfully");
    }

    public function reject($id)
    {
        $quotation = Quotation::findOrFail($id);
        
        if($quotation) {
            $quotation->status = "rejected";
            $quotation->save();
        }
        return back()->with('success',"$quotation->name rejected Successfully");
    }

    public function destroy($id)
    {
        $quotation = Quotation::find($id);
        QuotationDes::where('quotation_id',$id)->delete();
        $quotation->delete(); 
        return redirect()->route('quotations.index')->with('success',trans('Quotation.deleted.success'));
    }
}

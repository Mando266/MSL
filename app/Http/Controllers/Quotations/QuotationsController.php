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
use App\Models\Master\Country;
use App\Models\Master\Agents;
use App\Filters\Quotation\QuotationIndexFilter;
use App\Models\Master\Lines;
use App\Models\Quotations\QuotationLoad;
use App\Quotations\QuotationLoad as QuotationsQuotationLoad;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class QuotationsController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,Quotation::class);
        if(Auth::user()->is_super_admin){
            $quotations = Quotation::filter(new QuotationIndexFilter(request()))->orderBy('id','desc')->paginate(30);
            $exportQuotations = Quotation::select('ref_no','agent_id','customer_id','validity_from','validity_to','equipment_type_id','place_of_acceptence_id','place_of_delivery_id','load_port_id','discharge_port_id')->filter(new QuotationIndexFilter(request()))->orderBy('id','desc')->get();
            $quotation = Quotation::get();
            $customers = Customers::orderBy('id')->get();
            $ports = Ports::orderBy('id')->get();
        }
        else
        {
            $quotations = Quotation::where('agent_id',Auth::user()->agent_id)->filter(new QuotationIndexFilter(request()))->orderBy('id','desc')->paginate(30);
            $exportQuotations = Quotation::select('ref_no','agent_id','customer_id','validity_from','validity_to','equipment_type_id','place_of_acceptence_id','place_of_delivery_id','load_port_id','discharge_port_id')->where('agent_id',Auth::user()->agent_id)->filter(new QuotationIndexFilter(request()))->orderBy('id','desc')->get();
            $quotation = Quotation::get();
            $customers = Customers::orderBy('id')->get();
            $ports = Ports::orderBy('id')->get();
        }
        session()->flash('quotations',$exportQuotations);
        return view('quotations.quotations.index',[
            'items'=>$quotations,
            'quotation'=>$quotation,
            'ports'=>$ports,
            'customers'=>$customers,

        ]);    
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,Quotation::class);
        $ports = Ports::orderBy('id')->get();
        $container_types = ContainersTypes::orderBy('id')->get();
        $currency = Currency::orderBy('id')->get();
        $customers = Customers::orderBy('id')->with('CustomerRoles.role')->get();
        $country = Country::orderBy('name')->get();
        $equipment_types = ContainersTypes::orderBy('id')->get();
        $line = Lines::get();
        $isSuperAdmin = false;
        if(Auth::user()->is_super_admin){
            $isSuperAdmin = true;
            // $agents = AppUser::role('agent');
            $agents = [];
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
            'country'=>$country,
            'line'=>$line,
            'equipment_types'=>$equipment_types,
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
            'principal_name' => ['required'], 
            'vessel_name' => ['required'],
            'place_of_delivery_id' => ['required','different:place_of_acceptence_id'],
            'discharge_port_id' => ['required','different:load_port_id'],
            'validity_to' => ['required','after:validity_from'], 
            'ofr' => ['required'], 
            'commodity_des' =>['required'], 
        ],[
            'validity_to.after'=>'Validaty To Should Be After Validaty From ',
            'place_of_delivery_id.different'=>'Place Of Delivery The Same  Place Of Acceptence',
            'discharge_port_id.different'=>'Load Port The Same  Discharge Port',
        ]);

        $user = Auth::user();
        $validityFrom = Carbon::parse($request->validity_from)->format('m-Y');
        $validityFrom = str_replace('-', '', $validityFrom);
        $placeOfAcceptance = Ports::where('id',$request->place_of_acceptence_id)->pluck('code')->first();
        $placeOfAcceptance = substr($placeOfAcceptance, -3);
        $placeOfDelivery = Ports::where('id',$request->place_of_delivery_id)->pluck('code')->first();
        $placeOfDelivery = substr($placeOfDelivery, -3);
        $customerName = Customers::where('id', $request->customer_id)->pluck('name')->first();
        $customerName = substr($customerName, 0, 5);
        $refNo = $placeOfAcceptance.$placeOfDelivery.'-'.$customerName.$validityFrom.'-';
        // $rate_sh = false;
        // $rate_cn = false;
        // $rate_nt = false;
        // $rate_fwd = false;
        // switch ($request->rate) {
        //     case "rate_sh":
        //         $rate_sh = true;
        //         break;
        //     case "rate_cn":
        //         $rate_cn = true;
        //         break;
        //     case "rate_nt":
        //         $rate_nt = true;
        //         break;
        //     case "rate_fwd":
        //         $rate_fwd = true;
        //         break;                
        // }
        // Admin 
        if(isset($request->agent_id)){
            $oldQuotations = 
            Quotation::where("customer_id",$request->input('customer_id'))
            ->where("place_of_acceptence_id",$request->input('place_of_acceptence_id'))
            ->where("place_of_delivery_id",$request->input('place_of_delivery_id'))
            ->where("load_port_id",$request->input('load_port_id'))
            ->where("discharge_port_id",$request->input('discharge_port_id'))
            ->where("place_return_id",$request->input('place_return_id'))
            ->where("equipment_type_id",$request->input('equipment_type_id'))
            ->where("export_detention",$request->input('export_detention'))
            ->where("import_detention",$request->input('import_detention'))
            ->where("export_storage",$request->input('export_storage'))
            ->where("import_storage",$request->input('import_storage'))
            ->where("oog_dimensions",$request->input('oog_dimensions'))
            ->get();
            
            if($oldQuotations->count() >0){
                if($request->input('validity_from') < $oldQuotations[0]->validity_to){
                    return redirect()->back()->with('error', 'this quotation is dublicated with the same user in the same time');
                }    
            }
            //  $request->agent_id ==> Import Agent  ,  $request->discharge_agent_id   ==> Discharge Agent
            $quotations = Quotation::create([
                'ref_no'=> "",
                'agent_id'=>$request->agent_id,
                'quoted_by_id'=>$user->id,
                'discharge_agent_id'=> $request->input('discharge_agent_id'),
                'countryload'=> $request->input('countryload'),
                'countrydis'=> $request->input('countrydis'),
                'vessel_name'=> $request->input('vessel_name'),
                'principal_name'=> $request->input('principal_name'),
                'validity_from'=> $request->input('validity_from'),
                'validity_to'=> $request->input('validity_to'), 
                'soc'=>$request->soc ? $request->soc : 0,
                'imo'=>$request->imo ? $request->imo : 0,
                'oog'=>$request->oog ? $request->oog : 0,
                'rf'=>$request->rf ? $request->rf : 0,
                'customer_id'=> $request->input('customer_id'),
                'place_of_acceptence_id'=> $request->input('place_of_acceptence_id'),
                'place_of_delivery_id'=> $request->input('place_of_delivery_id'),
                'load_port_id'=> $request->input('load_port_id'),
                'discharge_port_id'=> $request->input('discharge_port_id'),
                'place_return_id'=> $request->input('place_return_id'),
                'equipment_type_id'=> $request->input('equipment_type_id'),
                'export_detention'=> $request->input('export_detention'),
                'import_detention'=> $request->input('import_detention'),
                'export_storage'=>$request->input('export_storage'),
                'import_storage'=>$request->input('import_storage'),
                'oog_dimensions'=>$request->input('oog_dimensions'),
                'commodity_code'=>$request->input('commodity_code'),
                'commodity_des'=>$request->input('commodity_des'),
                'pick_up_location'=>$request->input('pick_up_location'),
                'ofr'=>$request->input('ofr'),
                'show_import'=>$request->input('show_import'),
                'status'=> "pending",
            ]);
            $refNo .=$quotations->id;
            $quotations->ref_no = $refNo;
            $quotations->save();
        }else{
            $quotations = Quotation::create([
                'ref_no'=> "",
                'agent_id'=>$user->agent_id,
                'quoted_by_id'=>$user->id,
                'discharge_agent_id'=> $request->input('discharge_agent_id'),
                'countryload'=> $request->input('countryload'),
                'countrydis'=> $request->input('countrydis'),
                'vessel_name'=> $request->input('vessel_name'),
                'principal_name'=> $request->input('principal_name'),
                'validity_from'=> $request->input('validity_from'),
                'validity_to'=> $request->input('validity_to'),
                'soc'=>$request->soc ? $request->soc : 0,
                'imo'=>$request->imo ? $request->imo : 0,
                'oog'=>$request->oog ? $request->oog : 0,
                'rf'=>$request->rf ? $request->rf : 0,
                'customer_id'=> $request->input('customer_id'),
                'place_of_acceptence_id'=> $request->input('place_of_acceptence_id'),
                'place_of_delivery_id'=> $request->input('place_of_delivery_id'),
                'load_port_id'=> $request->input('load_port_id'),
                'discharge_port_id'=> $request->input('discharge_port_id'),
                'place_return_id'=> $request->input('place_return_id'),
                'equipment_type_id'=> $request->input('equipment_type_id'),
                'export_detention'=> $request->input('export_detention'),
                'import_detention'=> $request->input('import_detention'),
                'export_storage'=>$request->input('export_storage'),
                'import_storage'=>$request->input('import_storage'),
                'oog_dimensions'=>$request->input('oog_dimensions'),
                'commodity_code'=>$request->input('commodity_code'),
                'commodity_des'=>$request->input('commodity_des'),
                'pick_up_location'=>$request->input('pick_up_location'),
                'ofr'=>$request->input('ofr'),
                'show_import'=>$request->input('show_import'),
                'status'=> "pending",
            ]);
            $refNo .=$quotations->id;
            $quotations->ref_no = $refNo;
            $quotations->save();
        }
    
        foreach($request->input('quotationDis',[]) as $quotationDis){
            QuotationDes::create([
                'quotation_id'=>$quotations->id,
                'charge_type'=>$quotationDis['charge_type'],
                'currency'=>$quotationDis['currency'],
                'unit'=>$quotationDis['unit'],
                'selling_price'=>$quotationDis['selling_price'],
                'payer'=>$quotationDis['payer'],
                'equipment_type_id'=>$quotationDis['equipments_type'],
            ]);
        }
        foreach($request->input('quotationLoad',[]) as $quotationLoad){
            QuotationLoad::create([
                'quotation_id'=>$quotations->id,
                'charge_type'=>$quotationLoad['charge_type'],
                'currency'=>$quotationLoad['currency'],
                'unit'=>$quotationLoad['unit'],
                'selling_price'=>$quotationLoad['selling_price'],
                'payer'=>$quotationLoad['payer'],
                'equipment_type_id'=>$quotationLoad['equipments_type'],
            ]);
        }
        return redirect()->route('quotations.index')->with('success',trans('Quotation.created'));
    }

    public function show($id)
    {
        $quotation = Quotation::with('quotationDesc','quotationLoad','customer.CustomerRoles.role')->find($id);
        return view('quotations.quotations.show',[
            'quotation'=>$quotation,
        ]);
    }
    public function edit($id)
    {
        $this->authorize(__FUNCTION__,Quotation::class);
        $quotation = Quotation::with('quotationDesc','quotationLoad')->find($id);
        $ports = Ports::orderBy('id')->get();
        $container_types = ContainersTypes::orderBy('id')->get();
        $currency = Currency::orderBy('id')->get();
        $customers = Customers::orderBy('id')->get();
        $country = Country::orderBy('name')->get();
        $equipment_types = ContainersTypes::orderBy('id')->get();
        $line = Lines::get();
        $isSuperAdmin = false;
        if(Auth::user()->is_super_admin){
            $isSuperAdmin = true;
            // $agents = AppUser::role('agent');
            $agents = Agents::where('is_active',1)->get();
        }else{
            $agents = [];
        }

        return view('quotations.quotations.edit',[
            'quotation'=>$quotation,
            'ports'=>$ports,
            'isSuperAdmin'=>$isSuperAdmin,
            'agents'=>$agents,
            'container_types'=>$container_types,
            'currency'=>$currency,
            'customers'=>$customers,
            'line'=>$line,
            'equipment_types'=>$equipment_types,
            'country'=>$country,
        ]);
    }

    public function update(Request $request,$id)
    {
        
        $request->validate([
            'validity_from' => ['required'], 
            'customer_id' => ['required'], 
            'place_of_acceptence_id' => ['required'], 
            'load_port_id' => ['required'], 
            'equipment_type_id' => ['required'], 
            'export_detention' => ['required'], 
            'import_detention' => ['required'], 
            'principal_name' => ['required'], 
            'vessel_name' => ['required'],
            'place_of_delivery_id' => ['required','different:place_of_acceptence_id'],
            'discharge_port_id' => ['required','different:load_port_id'],
            'validity_to' => ['required','after:validity_from'],
            'rate' => ['required'], 
            'commodity_des' =>['required'], 
        ],[
            'validity_to.after'=>'Validaty To Should Be After Validaty From ',
            'place_of_delivery_id.different'=>'Place Of Delivery The Same  Place Of Acceptence',
            'discharge_port_id.different'=>'Load Port The Same  Discharge Port',
        ]);


        $this->authorize(__FUNCTION__,Quotation::class);
        $user = Auth::user();
        $quotation = Quotation::with('quotationDesc','quotationLoad')->find($id);
        // dd($quotation,$request->input());
        $input = [                    
            'validity_from' => $request->validity_from,
            'validity_to' => $request->validity_to,
            'customer_id' => $request->customer_id,
            'countryload'=> $request->countryload,
            'countrydis'=> $request->countrydis,
            'place_of_acceptence_id' => $request->place_of_acceptence_id,
            'place_of_delivery_id' => $request->place_of_delivery_id,
            'load_port_id' => $request->load_port_id,
            'discharge_port_id' => $request->discharge_port_id,
            'equipment_type_id' => $request->equipment_type_id,
            'place_return_id' => $request->place_return_id,
            'export_detention' => $request->export_detention,
            'import_detention' => $request->import_detention,
            'export_storage' => $request->export_storage,
            'import_storage' => $request->import_storage,
            'commodity_code' => $request->commodity_code,
            'commodity_des' => $request->commodity_des,
            'pick_up_location' => $request->pick_up_location,
            'ofr' => $request->ofr,
            'soc'=>$request->soc ? $request->soc : 0,
            'imo'=>$request->imo ? $request->imo : 0,
            'oog'=>$request->oog ? $request->oog : 0,
            'rf'=>$request->rf ? $request->rf : 0,
            'show_import'=>$request->show_import,
            'agent_id'=>$user->agent_id,
            'vessel_name'=> $request->vessel_name,
            'principal_name'=> $request->principal_name,
            'oog_dimensions'=>$request->oog_dimensions,
        ];
        if(isset($request->agent_id)){
            $input ['discharge_agent_id'] = $request->discharge_agent_id;
            $input ['status'] = "MSL count";
            $quotation->update($input);
        }else{
            if($quotation->status == 'pending'){
                $input ['discharge_agent_id'] = $user->discharge_agent_id;
                $input ['status'] = "pending";
                $quotation->update($input);
            }else{
                $input ['discharge_agent_id'] = $user->discharge_agent_id;
                $input ['status'] = "Agent count";
                $quotation->update($input);
            }
            
        }
        if($quotation->discharge_agent_id != $request->discharge_agent_id){
            $deleteOldDes = QuotationDes::where('quotation_id',$quotation->id)->get();
            foreach($deleteOldDes as $x){
                $x->delete();
            }
        }
        $quotation->createOrUpdateDesc($request->quotationDis);
        if($quotation->agent_id != $request->agent_id){
            $deleteOldLoad = QuotationLoad::where('quotation_id',$quotation->id)->get();
            foreach($deleteOldLoad as $x){
                $x->delete();
            }
        }
        $quotation->createOrUpdateLoad($request->quotationLoad);
        QuotationDes::destroy(explode(',',$request->removedDesc));
        QuotationLoad::destroy(explode(',',$request->removedLoad));
        return redirect()->route('quotations.index')->with('success',trans('Quotation.updated.success'));
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
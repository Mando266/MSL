<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Country;
use App\Models\Master\Vessels;
use App\Models\Master\VesselType;
use App\Models\Master\VesselOperators;
use Illuminate\Http\Request;
use App\Filters\Voyages\VoyagesIndexFilter;
use App\Models\Master\Lines;
use Illuminate\Support\Facades\Auth;

class VesselsController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,Vessels::class);

        if(Auth::user()->is_super_admin || is_null(Auth::user()->company_id)){
            $vessels = Vessels::filter(new VoyagesIndexFilter(request()))->orderBy('id')->paginate(30);
            $vessel = Vessels::orderBy('name')->get();
        }else{
            $vessels = Vessels::filter(new VoyagesIndexFilter(request()))->where('company_id',Auth::user()->company_id)->orderBy('id')->paginate(30);
            $vessel = Vessels::where('company_id',Auth::user()->company_id)->orderBy('name')->get();
        }

        return view('master.vessels.index',[
            'items'=>$vessels,
            'vessel'=>$vessel,

        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,Vessels::class);
        $countries = Country::orderBy('name')->get();
        $vessel_types = VesselType::orderBy('name')->get();
        $vesselOperators = Lines::orderBy('id')->get();
        return view('master.vessels.create',[
            'countries'=>$countries,
            'vessel_types'=>$vessel_types,
            'vesselOperators'=>$vesselOperators,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,Vessels::class);

        $user = Auth::user();
        $name = request()->input('name');
        $code = request()->input('code');
        $call_sign = request()->input('call_sign');
        $imo_number = request()->input('imo_number');

        $NameDublicate  = Vessels::where('company_id',$user->company_id)->where('name',$name)->first();
            if($NameDublicate != null){
                return back()->with('alert','This Vessel Name Already Exists');
            }

        $CodeDublicate  = Vessels::where('company_id',$user->company_id)->where('code',$code)->first();
            if($CodeDublicate != null){
                return back()->with('alert','This Vessel Code Already Exists');
            }
        
        $CallSignDublicate  = Vessels::where('company_id',$user->company_id)->where('call_sign',$call_sign)->first();
            if($CallSignDublicate != null){
                return back()->with('alert','This Vessel Call Sign Already Exists');
            }

        $ImoNumberDublicate  = Vessels::where('company_id',$user->company_id)->where('imo_number',$imo_number)->first();
            if($ImoNumberDublicate != null){
                return back()->with('alert','This Vessel Imo Number Already Exists');
            }
        $vessels = Vessels::create($request->except('_token'));
        $vessels->company_id = $user->company_id;
        $vessels->save();
        return redirect()->route('vessels.index')->with('success',trans('vessel.created'));
    }

    public function show(Vessels $vessel)
    {
        $this->authorize(__FUNCTION__,Vessels::class);
        $countries = Country::orderBy('name')->get();
        $vessel_types = VesselType::orderBy('name')->get();
        $vesselOperators = VesselOperators::orderBy('name')->get();
        return view('master.vessels.show',[
            'vessel'=>$vessel,
            'countries'=>$countries,
            'vessel_types'=>$vessel_types,
            'vesselOperators'=>$vesselOperators,
        ]);
    }

    public function edit(Vessels $vessel)
    {
        $this->authorize(__FUNCTION__,Vessels::class);
        $countries = Country::orderBy('name')->get();
        $vessel_types = VesselType::orderBy('name')->get();
        $vesselOperators = VesselOperators::orderBy('name')->get();
        return view('master.vessels.edit',[
            'vessel'=>$vessel,
            'countries'=>$countries,
            'vessel_types'=>$vessel_types,
            'vesselOperators'=>$vesselOperators,
        ]);
    }

    public function update(Request $request, Vessels $vessel)
    {
        $user = Auth::user();
        $name = request()->input('name');
        $code = request()->input('code');
        $call_sign = request()->input('call_sign');
        $imo_number = request()->input('imo_number');

        $NameDublicate  = Vessels::where('company_id',$user->company_id)->where('name',$name)->first();
            if($NameDublicate != null){
                return back()->with('alert','This Vessel Name Already Exists');
            }

        $CodeDublicate  = Vessels::where('company_id',$user->company_id)->where('code',$code)->first();
            if($CodeDublicate != null){
                return back()->with('alert','This Vessel Code Already Exists');
            }
        
        $CallSignDublicate  = Vessels::where('company_id',$user->company_id)->where('call_sign',$call_sign)->first();
            if($CallSignDublicate != null){
                return back()->with('alert','This Vessel Call Sign Already Exists');
            }

        $ImoNumberDublicate  = Vessels::where('company_id',$user->company_id)->where('imo_number',$imo_number)->first();
            if($ImoNumberDublicate != null){
                return back()->with('alert','This Vessel Imo Number Already Exists');
            }

        $this->authorize(__FUNCTION__,Vessels::class);
        $vessel->update($request->except('_token'));
        return redirect()->route('vessels.index')->with('success',trans('vessel.updated.success'));
    }

    public function destroy($id)
    {
        $vessel =Vessels::Find($id);
        $vessel->delete();

        return redirect()->route('vessels.index')->with('success',trans('vessel.deleted.success'));
    }
}

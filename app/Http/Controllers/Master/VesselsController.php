<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Country;
use App\Models\Master\Vessels;
use App\Models\Master\VesselType;
use App\Models\Master\VesselOperators;
use Illuminate\Http\Request;
use App\Filters\Voyages\VoyagesIndexFilter;

class VesselsController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,Vessels::class);
        $vessels = Vessels::filter(new VoyagesIndexFilter(request()))->orderBy('id')->paginate(10);
        $vessel = Vessels::orderBy('name')->get();

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
        $vesselOperators = VesselOperators::orderBy('name')->get();
        return view('master.vessels.create',[
            'countries'=>$countries,
            'vessel_types'=>$vessel_types,
            'vesselOperators'=>$vesselOperators,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,Vessels::class);
        $request->validate([
            'name' => 'required',
            'code' => 'required',
        ]);
        $vessels = Vessels::create($request->except('_token'));
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
        $request->validate([
            'name' => 'required',
            'code' => 'required',

        ]);
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

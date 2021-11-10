<?php

namespace App\Http\Controllers\Voyages;

use App\Http\Controllers\Controller;
use App\Models\Voyages\Voyages;
use App\Models\Master\Vessels;
use App\Models\Master\Lines;
use App\Models\Voyages\Legs;
use Illuminate\Http\Request; 

class VoyagesController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,Voyages::class);
        $voyages = Voyages::orderBy('id')->paginate(10);
        return view('voyages.voyages.index',[
            'items'=>$voyages,
        ]);   
     }

    public function create()
    {
        $this->authorize(__FUNCTION__,Voyages::class);
        $vessels = Vessels::orderBy('name')->get();
        $legs = Legs::orderBy('id')->get();
        $liens = Lines::orderBy('id')->get();
        return view('voyages.voyages.create',[
            'vessels'=>$vessels,
            'legs'=>$legs,
            'liens'=>$liens,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,Voyages::class);
        $request->validate([
            'voyage_no' => 'required',
            'vessel_id' => 'required',
        ]);
        $voyages = Voyages::create($request->except('_token'));
        return redirect()->route('voyages.index')->with('success',trans('voyage.created')); 
    }

    public function show($id)
    {
        //
    }

    public function edit(Voyages $voyage)
    {
        $this->authorize(__FUNCTION__,Voyages::class);
        $vessels = Vessels::orderBy('name')->get();
        $legs = Legs::orderBy('id')->get();
        return view('voyages.voyages.edit',[
            'voyage'=>$voyage,
            'vessels'=>$vessels,
            'legs'=>$legs,
        ]);
    }

    public function update(Request $request, Voyages $voyage)
    {
        $request->validate([
            'voyage_no' => 'required',
            'vessel_id' => 'required',
        ]);
        $this->authorize(__FUNCTION__,Voyages::class);
        $voyage->update($request->except('_token'));
        return redirect()->route('voyages.index')->with('success',trans('voyage.updated.success'));
    }

    public function destroy($id)
    {
        $voyage =Voyages::Find($id);
        $voyage->delete();

        return redirect()->route('voyages.index')->with('success',trans('voyage.deleted.success'));
    }
}

<?php

namespace App\Http\Controllers\Containers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Containers\Demurrage;
use App\Models\Containers\Period;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Ports;
use App\Models\Master\Country;
use App\Models\Containers\Bound;
use DB;

class DemurageController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,Demurrage::class);
        $demurrages = Demurrage::
        join('period', 'demurrage.id', '=', 'period.demurrage_id')
            ->select('demurrage.*', 'period.rate','period.period')->
        get();

        return view('containers.demurrage.index',[
            'items'=>$demurrages,
        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,Demurrage::class);
        $countries = Country::orderBy('id')->get();
        $bounds = Bound::orderBy('id')->get();
        $containersTypes = ContainersTypes::orderBy('id')->get();
        $ports = Ports::orderBy('id')->get();

        return view('containers.demurrage.create',[
            'countries'=>$countries,
            'bounds'=>$bounds,
            'containersTypes'=>$containersTypes,
            'ports'=>$ports,
        ]);
    }

    public function store(Request $request)
    {
        $demurrages = Demurrage::create([
            'country_id'=> $request->input('country_id'),
            'container_type_id'=> $request->input('container_type_id'),
            'port_id'=> $request->input('port_id'),
            'validity_from'=> $request->input('validity_from'),
            'validity_to'=> $request->input('validity_to'),
            'currency'=> $request->input('currency'),
            'bound_id'=> $request->input('bound_id'),
        ]);

        foreach($request->input('period',[]) as $period){
            Period::create([
                'demurrage_id'=>$demurrages->id,
                'rate'=>$period['rate'],
                'period'=>$period['period'],
            ]);
        }
        return redirect()->route('demurrage.index')->with('success',trans('Demurrage.created'));

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
        $demurrage = Demurrage::find($id);
        Period::where('demurrage_id',$id)->delete();
        $demurrage->delete();
        return redirect()->route('demurrage.index')->with('success',trans('Demurrage.deleted.success'));
    }
}

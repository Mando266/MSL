<?php

namespace App\Http\Controllers\Containers;

use App\Filters\Containers\ContainersIndexFilter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Containers\Demurrage;
use App\Models\Containers\Period;
use App\Models\Containers\Triff;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Ports;
use App\Models\Master\Country;
use App\Models\Master\Currency;
use App\Models\Containers\Bound;
use App\Models\Master\Terminals;
use Illuminate\Support\Facades\Auth;
use DB;

class DemurageController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,Demurrage::class);
        if(Auth::user()->is_super_admin || is_null(Auth::user()->company_id)){
            $demurrage = Demurrage::get();
            $demurrages = Demurrage::filter(new ContainersIndexFilter(request()))->get();
            $countries = Country::orderBy('name')->get();
        }else{
            $demurrage = Demurrage::where('company_id',Auth::user()->company_id)->get();
            $demurrages = Demurrage::where('company_id',Auth::user()->company_id)->filter(new ContainersIndexFilter(request()))->get();
            $countries = Country::orderBy('name')->get();
        }
        return view('containers.demurrage.index',[
            'countries'=>$countries,
            'items'=>$demurrages,
            'demurrage'=>$demurrage,
        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,Demurrage::class);
        $countries = Country::orderBy('id')->get();
        $bounds = Bound::orderBy('id')->get();
        $containersTypes = ContainersTypes::orderBy('id')->get();
        $ports = Ports::orderBy('id')->where('company_id',Auth::user()->company_id)->get();
        $triffs = Triff::get();
        $currency = Currency::all();
        $terminals = Terminals::where('company_id',Auth::user()->company_id)->get();
        return view('containers.demurrage.create',[
            'terminals'=>$terminals,
            'countries'=>$countries,
            'bounds'=>$bounds,
            'containersTypes'=>$containersTypes,
            'ports'=>$ports,
            'triffs'=>$triffs,
            'currency'=>$currency,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $demurrages = Demurrage::create([
            'country_id'=> $request->input('country_id'),
            'terminal_id'=>$request->input('terminal_id'),
            'container_type_id'=> $request->input('container_type_id'),
            'port_id'=> $request->input('port_id'),
            'validity_from'=> $request->input('validity_from'),
            'validity_to'=> $request->input('validity_to'),
            'currency'=> $request->input('currency'),
            'bound_id'=> $request->input('bound_id'),
            'tariff_id'=> $request->input('tariff_id'),
            'company_id'=>$user->company_id,
        ]);

        foreach($request->input('period',[]) as $period){
            Period::create([
                'demurrage_id'=>$demurrages->id,
                'rate'=>$period['rate'],
                'period'=>$period['period'],
                'number_off_dayes'=>$period['number_off_dayes'],
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
        $this->authorize(__FUNCTION__,Demurrage::class);
        $demurrages = Demurrage::find($id);
        $period = Period::where('demurrage_id',$id)->get();
        $demurrage = Demurrage::where('id',$id)->first();

        return view('containers.demurrage.show',[
            'demurrages'=>$demurrages,
            'period'=>$period,
            'demurrage'=>$demurrage,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Demurrage $demurrage)
    {
        $this->authorize(__FUNCTION__,Demurrage::class);
        $user = Auth::user();

        $demurrage = $demurrage->load('periods');
        $countries = Country::orderBy('id')->get();
        $bounds = Bound::orderBy('id')->get();
        $containersTypes = ContainersTypes::orderBy('id')->get();
        $ports = Ports::where('company_id',$user->company_id)->orderBy('id')->get();
        $triffs = Triff::all();
        $currency = Currency::all();
        $terminals = Terminals::where('company_id',$user->company_id)->all();
        return view('containers.demurrage.edit',[
            'terminals'=>$terminals,
            'demurrage' => $demurrage,
            'countries'=>$countries,
            'bounds'=>$bounds,
            'containersTypes'=>$containersTypes,
            'ports'=>$ports,
            'triffs'=>$triffs,
            'currency'=>$currency,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Demurrage $demurrage)
    {
        $this->authorize(__FUNCTION__,Demurrage::class);
        $demurrage = $demurrage->load('periods');
        $input = [                    
            'country_id' => $request->country_id,
            'terminal_id'=>$request->terminal_id,
            'port_id' => $request->port_id,
            'container_type_id' => $request->container_type_id,
            'bound_id' => $request->bound_id,
            'currency' => $request->currency,
            'validity_from' => $request->validity_from,
            'validity_to' => $request->validity_to,
            'tariff_id' => $request->tariff_id,
            'is_storge' => $request->is_storge,
        ];
        $demurrage->update($input);
        $demurrage->createOrUpdatePeriod($request->period);
        Period::destroy(explode(',',$request->removed));
        return redirect()->route('demurrage.index')->with('success',trans('Demurrage.updated.success'));
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

<?php

namespace App\Http\Controllers\Master;

use App\Filters\User\UserIndexFilter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Country;
use App\Models\Master\PortTypes;
use App\Models\Master\Ports;
use App\Models\Master\Terminals;
use App\Models\Master\Agents;
use Illuminate\Support\Facades\Auth;

class PortsController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,Ports::class);
        // if(Auth::user()->is_super_admin || is_null(Auth::user()->company_id)){
            $ports = Ports::filter(new UserIndexFilter(request()))->orderBy('id')->paginate(10);
            $port = Ports::get();

        // }
        // else
        // {
        //     $ports = Ports::where('company_id',Auth::user()
        //     ->company_id)->UserPorts()->orderBy('id')->paginate(10);
        // }
        return view('master.ports.index',[
            'items'=>$ports,
            'port'=>$port,

        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,Ports::class);
        $countries = Country::orderBy('name')->get();
        $port_types = PortTypes::orderBy('name')->get();
        $agents = Agents::orderBy('name')->get();
        $terminals = Terminals::orderBy('name')->get();
        return view('master.ports.create',[
            'countries'=>$countries,
            'port_types'=>$port_types,
            'agents'=>$agents,
            'terminals'=>$terminals,
        ]); 
    }

    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,Ports::class);
        $request->validate([
            'name' => 'required|unique:ports|max:255',
            'code' => 'required|unique:ports|max:255',

        ],[
            'name.unique'=>'This Port Name Already Exists ',
            'code.unique'=>'This Port Code Already Exists ',

        ]);
        $ports = Ports::create($request->except('_token'));
        return redirect()->route('ports.index')->with('success',trans('port.created')); 
    }

    public function show(Ports $port)
    {
        $this->authorize(__FUNCTION__,Ports::class);
        $countries = Country::orderBy('name')->get();
        $agents = Agents::orderBy('name')->get();
        $port_types = PortTypes::orderBy('name')->get();
        $terminals = Terminals::orderBy('name')->get();
        return view('master.ports.show',[
            'port'=>$port,
            'countries'=>$countries,
            'agents'=>$agents,
            'port_types'=>$port_types,
            'terminals'=>$terminals,
        ]);
    }

    public function edit(Ports $port)
    {
        $this->authorize(__FUNCTION__,Ports::class);
        $countries = Country::orderBy('name')->get();
        $agents = Agents::orderBy('name')->get();
        $port_types = PortTypes::orderBy('name')->get();
        $terminals = Terminals::orderBy('name')->get();
        return view('master.ports.edit',[
            'port'=>$port,
            'countries'=>$countries,
            'agents'=>$agents,
            'port_types'=>$port_types,
            'terminals'=>$terminals,
        ]); 
    }

    public function update(Request $request, Ports $port)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required',
        ]);
        $this->authorize(__FUNCTION__,Ports::class);
        $port->update($request->except('_token'));
        return redirect()->route('ports.index')->with('success',trans('Port.updated.success')); 
    }

    public function destroy($id)
    {
        $port =Ports::Find($id);
        $port->delete();

        return redirect()->route('ports.index')->with('success',trans('Port.deleted.success'));
    }
}

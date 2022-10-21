<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Country;
use App\Models\Master\Terminals;
use App\Models\Master\Ports;
use Illuminate\Http\Request;

class TerminalsController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,Terminals::class);
        $terminals = Terminals::orderBy('id')->paginate(10);

        return view('master.terminals.index',[
            'items'=>$terminals,
        ]);    }

    public function create()
    {
        $this->authorize(__FUNCTION__,Terminals::class);
        $countries = Country::orderBy('name')->get();
        $ports = Ports::orderBy('name')->get();
        return view('master.terminals.create',[
            'countries'=>$countries,
            'ports'=>$ports,
        ]); 
    }

    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,Terminals::class);
        $request->validate([
            'name' => 'required|unique:terminals|max:255',
            'code' => 'required|unique:terminals|max:255',
        ],[
            'name.unique'=>'This Terminal Name Already Exists ',
            'code.unique'=>'This Terminal Code Already Exists ',

        ]);
            Terminals::create($request->except('_token'));
        return redirect()->route('terminals.index')->with('success',trans('Terminal.created')); 
    }

    public function show($id)
    {
        //
    }

    public function edit(Terminals $terminal)
    {
        $this->authorize(__FUNCTION__,Terminals::class);
        $countries = Country::orderBy('name')->get();
        $ports = Ports::orderBy('name')->get();
        return view('master.terminals.edit',[
            'countries'=>$countries,
            'terminal'=>$terminal,
            'ports'=>$ports,

        ]); 
    }

    public function update(Request $request, Terminals $terminal)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required',
        ]);
        $this->authorize(__FUNCTION__,Terminals::class);
        $terminal->update($request->except('_token'));
        return redirect()->route('terminals.index')->with('success',trans('Terminal.updated.success')); 
    }

    public function destroy($id)
    {
        $terminal =Terminals::Find($id);
        $terminal->delete();
        return redirect()->route('terminals.index')->with('success',trans('Terminal.deleted.success')); 
    }

    public function getTerminals()
    {
        
    }
}

<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Country;
use App\Models\Master\Terminals;
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
        return view('master.terminals.create',[
            'countries'=>$countries,
        ]); 
    }

    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,Terminals::class);
        $request->validate([
            'name' => 'required',
        ]);
        $terminals = Terminals::create($request->except('_token'));
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
        return view('master.terminals.edit',[
            'countries'=>$countries,
            'terminal'=>$terminal,
        ]); 
    }

    public function update(Request $request, Terminals $terminal)
    {
        $request->validate([
            'name' => 'required',
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
}

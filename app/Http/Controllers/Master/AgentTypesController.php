<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\AgentTypes;
use Illuminate\Support\Facades\Auth;

class AgentTypesController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,AgentTypes::class);
        $agent_types = AgentTypes::orderBy('id')->paginate(10);

        return view('master.agent-types.index',[
            'items'=>$agent_types,
        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,AgentTypes::class);
        return view('master.agent-types.create');  
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        AgentTypes::create([      
        'name'=> $request->input('name'),
        ]);
        return redirect()->route('agent-types.index')->with('success',trans('Agent Types.created'));
    }

    public function show($id)
    {
        //
    }

    public function edit(AgentTypes $agent_type)
    {
        $this->authorize(__FUNCTION__,AgentTypes::class);
        return view('master.agent-types.edit',[
            'agent_type'=>$agent_type,
        ]);
    }

    public function update(Request $request, AgentTypes $agent_type)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $this->authorize(__FUNCTION__,AgentTypes::class);
        $agent_type->update($request->except('_token'));
        return redirect()->route('agent-types.index')->with('success',trans('Agent Type.updated.success'));      }

    public function destroy($id)
    {
        $agent_type =AgentTypes::Find($id);
        $agent_type->delete();

        return redirect()->route('agent-types.index')->with('success',trans('Agent Type.deleted.success'));
    }
}

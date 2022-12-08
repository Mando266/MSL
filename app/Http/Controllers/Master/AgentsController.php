<?php

namespace App\Http\Controllers\Master;
use App\Models\Master\Country;
use App\Models\Master\Agents;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentsController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,Agents::class);
        if(Auth::user()->is_super_admin || is_null(Auth::user()->company_id)){ 
            $agents = Agents::orderBy('id')->paginate(30);
        }
        else{
            $agents = Agents::where('company_id',Auth::user()->company_id)->orderBy('id')->paginate(30); 
        }
            return view('master.agents.index',[
            'items'=>$agents,
        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,Agents::class);
        $countries = Country::orderBy('name')->get();
        return view('master.agents.create',[
            'countries'=>$countries,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,Agents::class);
        $user = Auth::user();
        $name = request()->input('name');

        $NameDublicate  = Agents::where('company_id',$user->company_id)->where('name',$name)->first();

        if($NameDublicate != null){
            return back()->with('alert','This Port Name Already Exists');
        }
        $agents = Agents::create($request->except('_token'));
        $agents->company_id = $user->company_id;
        $agents->save();
        return redirect()->route('agents.index')->with('success',trans('agent.created'));
    }

    public function show($id)
    {
        //
    }

    public function edit(Agents $agent)
    {
        $this->authorize(__FUNCTION__,Agents::class);
        $countries = Country::orderBy('name')->get();
        return view('master.agents.edit',[
            'agent'=>$agent,
            'countries'=>$countries,
        ]); 
    }

    public function update(Request $request, Agents $agent)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $this->authorize(__FUNCTION__,Agents::class);
        $agent->update($request->except('_token'));
        return redirect()->route('agents.index')->with('success',trans('agent.updated.success'));
    }

    public function destroy($id)
    {
        $agent =Agents::Find($id);
        $agent->delete();
        return redirect()->route('agents.index')->with('success',trans('agent.deleted.success'));
    }
}

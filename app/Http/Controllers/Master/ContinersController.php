<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\LessorSeller;
use Illuminate\Http\Request;
use App\Models\Master\Containers;
use App\Models\Master\ContainersTypes;
use App\Models\Master\ContinerOwnership;
use App\Filters\Containers\ContainersIndexFilter;
use App\Models\Containers\Movements;
use Illuminate\Support\Facades\Auth;

class ContinersController extends Controller
{

    public function index()
    {
        $this->authorize(__FUNCTION__,Containers::class);
        
            $container = Containers::filter(new ContainersIndexFilter(request()))->where('company_id',Auth::user()->company_id)->orderBy('id')->paginate(30);
            $exportContainers = Containers::filter(new ContainersIndexFilter(request()))->where('company_id',Auth::user()->company_id)->orderBy('id')->get();
            $containers = Containers::where('company_id',Auth::user()->company_id)->get();
            $container_types = ContainersTypes::orderBy('id')->get();
            $container_ownership = ContinerOwnership::orderBy('id')->get();
            session()->flash('containers',$exportContainers);
        return view('master.containers.index',[
            'items'=>$container,
            'containers'=>$containers,
            'container_types'=>$container_types,
            'container_ownership'=>$container_ownership,
            'sellers' => LessorSeller::where('company_id',Auth::user()->company_id)->get()

        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,Containers::class);
        $container_types = ContainersTypes::orderBy('id')->get();
        $container_ownership = ContinerOwnership::orderBy('id')->get();

        return view('master.containers.create',[
            'container_types'=>$container_types,
            'container_ownership'=>$container_ownership,
            'sellers' => LessorSeller::where('company_id',Auth::user()->company_id)->get()
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,Containers::class);
        
        $request->validate([
            'container_type_id' =>'required',
            'code' => ['required','regex:/^[A-Z]{4}[0-9]{7}/','string','min:11','max:11'],
            'tar_weight' => 'integer|nullable',
            'max_payload' => 'integer|nullable',
            'production_year' => 'integer|nullable',
            'SOC_COC' => 'required|in:SOC,COC'
            ],[
            'code.regex'=>'Invalid Container Number Format', 
            'code.min'=>'Invalid Container Number Format', 
            'code.max'=>'Invalid Container Number Format', 
            'code.required'=>'Container Number Field is Required',

        ]);
        $user = Auth::user();
            
        $CodeDublicate  = Containers::where('company_id',$user->company_id)->where('code',$request->code)->first();
        if($CodeDublicate != null){
            return back()->with('alert','This Container Number Already Exists');
        }
        $container = Containers::create($request->input());
        $container->company_id = $user->company_id;
        $container->save();
        if($request->hasFile('certificat')){
            $path = $request->file('certificat')->getClientOriginalName();
            $request->certificat->move(public_path('certificat'), $path);
            $container->update(['certificat'=>"certificat/".$path]);
        }
        return redirect()->route('containers.index')->with('success',trans('container.created'));
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $this->authorize(__FUNCTION__,Containers::class);
        $container_types = ContainersTypes::orderBy('id')->get();
        $container_ownership = ContinerOwnership::orderBy('id')->get();
        $container = Containers::find($id);

        return view('master.containers.edit',[
            'container'=>$container,
            'container_types'=>$container_types,
            'container_ownership'=>$container_ownership,
            'sellers' => LessorSeller::where('company_id',Auth::user()->company_id)->get()
        ]);

    }

    public function update(Request $request, $id)
    {   
    $this->authorize(__FUNCTION__,Containers::class);
        $request->validate([
            'container_type_id' => 'required',
            'code' =>  ['required','regex:/^[A-Z]{4}[0-9]{7}/','string','min:11','max:11'],
            'tar_weight' => 'integer|nullable',
            'max_payload' => 'integer|nullable',
            'production_year' => 'integer|nullable',
        ],[
            'code.regex'=>'Invalid Container Number Format', 
            'code.min'=>'Invalid Container Number Format', 
            'code.max'=>'Invalid Container Number Format',
            'code.required'=>'Container Number Field is Required',  
        ]);
        $user = Auth::user();
            
        $CodeDublicate  = Containers::where('id','!=',$id)->where('company_id',$user->company_id)->where('code',$request->code)->count();
        if($CodeDublicate > 0){
            return back()->with('alert','This Container Number Already Exists');
        }
        $container = Containers::find($id);
        $container->update($request->except('_token'));
        if($request->hasFile('certificat')){
            $path = $request->file('certificat')->getClientOriginalName();
            $request->certificat->move(public_path('certificat'), $path);
            $container->update(['certificat'=>"certificat/".$path]);
        }
        return redirect()->route('containers.index')->with('success',trans('containers.updated.success'));
    }


    public function destroy($id)
    {
        $container =Containers::Find($id);
        Movements::where('container_id',$id)->delete();
        $container->delete();

        return redirect()->route('containers.index')->with('success',trans('Container.deleted.success'));
    }
}

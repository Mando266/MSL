<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
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
        if(Auth::user()->is_super_admin || is_null(Auth::user()->company_id)){
            $container = Containers::filter(new ContainersIndexFilter(request()))->orderBy('id')->paginate(30);
            $containers = Containers::get();
            $container_types = ContainersTypes::orderBy('id')->get();
            $container_ownership = ContinerOwnership::orderBy('id')->get();
        }else{
            $container = Containers::filter(new ContainersIndexFilter(request()))->where('company_id',Auth::user()->company_id)->orderBy('id')->paginate(30);
            $containers = Containers::where('company_id',Auth::user()->company_id)->get();
            $container_types = ContainersTypes::orderBy('id')->get();
            $container_ownership = ContinerOwnership::orderBy('id')->get();
        }

        return view('master.containers.index',[
            'items'=>$container,
            'containers'=>$containers,
            'container_types'=>$container_types,
            'container_ownership'=>$container_ownership,
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
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,Containers::class);
        $user = Auth::user();
        $code = request()->input('code');

        $request->validate([
            'container_type_id' =>'required',
            'code' => 'required',
            'tar_weight' => 'integer|nullable',
            'max_payload' => 'integer|nullable',
            'production_year' => 'integer|nullable',
        ]);

        $CodeDublicate  = Containers::where('company_id',$user->company_id)->where('code',$code)->first();
        if($CodeDublicate != null){
            return back()->with('alert','This Container Code Already Exists');
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
        ]);

    }

    public function update(Request $request, $id)
    {
        // dd($request->files);
        $user = Auth::user();
        $code = request()->input('code');
        
        $request->validate([
            'container_type_id' => 'required',
            'code' => 'required',
            'tar_weight' => 'integer|nullable',
            'max_payload' => 'integer|nullable',
            'production_year' => 'integer|nullable',
        ]);
        $CodeDublicate  = Containers::where('company_id',$user->company_id)->where('code',$code)->first();
        if($CodeDublicate != null){
            return back()->with('alert','This Container Code Already Exists');
        }
        $container = Containers::find($id);
        $container->update($request->except('_token'));
        if($request->hasFile('certificat')){
            $path = $request->file('certificat')->getClientOriginalName();
            $request->certificat->move(public_path('certificat'), $path);
            $container->update(['certificat'=>"certificat/".$path]);
        }

        $this->authorize(__FUNCTION__,Containers::class);
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

<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Containers;
use App\Models\Master\ContainersTypes;
use Illuminate\Support\Facades\Auth;
class ContinersController extends Controller
{

    public function index()
    {
        $this->authorize(__FUNCTION__,Containers::class);
            $containers = Containers::orderBy('id')->paginate(10);
        return view('master.containers.index',[
            'items'=>$containers,
        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,Containers::class);
        $container_types = ContainersTypes::orderBy('id')->get();
        return view('master.containers.create',[
            'container_types'=>$container_types,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,Containers::class);
        $request->validate([
            'container_type_id' => 'required',
            'code' => 'required',
        ]);
        $containers = Containers::create($request->except('_token'));
        return redirect()->route('containers.index')->with('success',trans('container.created'));
    }

    public function show($id)
    {
        //
    }

    public function edit(Containers $container)
    {
        $this->authorize(__FUNCTION__,Containers::class);
        $container_types = ContainersTypes::orderBy('id')->get();

        return view('master.containers.edit',[
            'container_types'=>$container_types,
        ]);

    }

    public function update(Request $request,Containers $container)
    {
        $request->validate([
            'container_type_id' => 'required',
            'code' => 'required',

        ]);
        $this->authorize(__FUNCTION__,Containers::class);
        $container->update($request->except('_token'));
        return redirect()->route('containers.index')->with('success',trans('Container.updated.success'));
    
    }

    public function destroy($id)
    {
        $container =Containers::Find($id);
        $container->delete();

        return redirect()->route('containers.index')->with('success',trans('Container.deleted.success'));
    }
}

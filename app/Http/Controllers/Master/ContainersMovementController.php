<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\ContainersMovement;
use App\Models\Master\StockTypes;
use App\Models\Master\ContainerStatus;
use Illuminate\Support\Facades\Auth;

class ContainersMovementController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,ContainersMovement::class);
        $containers_movement = ContainersMovement::orderBy('id')->paginate(10);

        return view('master.container-movement.index',[
            'items'=>$containers_movement,
        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,ContainersMovement::class);
        $container_stock = StockTypes::orderBy('id')->get();
        $container_status = ContainerStatus::orderBy('id')->get();

        return view('master.container-movement.create',[
            'container_stock'=>$container_stock,
            'container_status'=>$container_status,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required',
            'stock_type_id' => 'integer|nullable',
            'container_status_id' => 'integer|nullable',
        ]);
        ContainersMovement::create($request->except('_token'));
        return redirect()->route('container-movement.index')->with('success',trans('Container Movement.created'));
    }

    public function show($id)
    {
        //
    }

    public function edit(ContainersMovement $container_movement)
    {
        $this->authorize(__FUNCTION__,ContainersMovement::class);
        $container_stock = StockTypes::orderBy('id')->get();
        $container_status = ContainerStatus::orderBy('id')->get();

        return view('master.container-movement.edit',[
            'container_movement'=>$container_movement,
            'container_stock'=>$container_stock,
            'container_status'=>$container_status,

        ]);
    }

    public function update(Request $request, ContainersMovement $container_movement)
    {
        $request->validate([
            'name' => 'required',
            'stock_type_id' => 'integer|nullable',
            'container_statuss_id' => 'integer|nullable',

        ]);
        $this->authorize(__FUNCTION__,ContainersMovement::class);
        $container_movement->update($request->except('_token'));
        return redirect()->route('container-movement.index')->with('success',trans('Container Movement.updated.success'));
    }

    public function destroy($id)
    {
        $container_movement =ContainersMovement::Find($id);
        $container_movement->delete();

        return redirect()->route('container-movement.index')->with('success',trans('Container Movement.deleted.success'));
    }
}

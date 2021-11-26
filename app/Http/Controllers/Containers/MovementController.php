<?php

namespace App\Http\Controllers\Containers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\ContainersMovement;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Containers;
use App\Models\Master\Vessels;
use App\Models\Master\Ports;
use App\Models\Voyages\Voyages;
use App\Models\Containers\Movements;
use App\Filters\Containers\ContainersIndexFilter;

class MovementController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,Movements::class);
        $movements = Containers::filter(new ContainersIndexFilter(request()))->orderBy('id')->paginate(30);
        $containers = Containers::orderBy('id')->get();
        return view('containers.movements.index',[
            'items'=>$movements,
            'containers'=>$containers,

        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,Movements::class);
        $voyages = Voyages::orderBy('id')->get();
        $vessels = Vessels::orderBy('id')->get();
        $containers = Containers::orderBy('id')->get();
        $containersTypes = ContainersTypes::orderBy('id')->get();
        $containersMovements = ContainersMovement::orderBy('id')->get();
        $ports = Ports::orderBy('id')->get();

        return view('containers.movements.create',[
            'voyages'=>$voyages,
            'vessels'=>$vessels,
            'containers'=>$containers,
            'containersTypes'=>$containersTypes,
            'containersMovements'=>$containersMovements,
            'ports'=>$ports,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,Movements::class);
        $request->validate([
            'container_id' => 'required',
            'container_type_id' => 'required',
            'movement_id' => 'required',
            'movement_date' => 'required',
            'port_location_id' => 'required',
        ]);
        // foreach($request->input('movement',[]) as $movement){
        //     Movements::create([
        //         'container_id'=>$movement['container_id'],
        //         'container_type_id'=>$movement['container_type_id'],
        //         'movement_id'=>$movement['movement_id'],
        //         'movement_date'=>$movement['movement_date'],
        //         'port_location_id'=>$movement['port_location_id'],
        //         'pol_id'=>$movement['pol_id'],
        //         'pod_id'=>$movement['pod_id'],
        //         'vessel_id'=>$movement['vessel_id'],
        //         'voyage_id'=>$movement['voyage_id'],
        //         'terminal_id'=>$movement['terminal_id'],
        //         'booking_no'=>$movement['booking_no'],
        //         'bl_no'=>$movement['bl_no'],
        //         'remarkes'=>$movement['remarkes'],
        //     ]);
        // }
        Movements::create($request->except('_token'));
        return redirect()->route('movements.index')->with('success',trans('Movement.created'));
    }

    public function show($id)
    {
        $this->authorize(__FUNCTION__,Movements::class);
        $movement = Movements::find($id);
        $container = Containers::find($id);
        $movements = Movements::filter(new ContainersIndexFilter(request()))->where('container_id',$id)->orderBy('movement_date','asc')->orderBy('movement_id','asc')->get();
        $containers = Containers::where('id',$id)->first();

        return view('containers.movements.show',[
            'movement'=>$movement,
            'container'=>$container,
            'items'=>$movements,
            'containers'=>$containers,
        ]);
    }

    public function edit(Movements $movement)
    {
        $this->authorize(__FUNCTION__,Movements::class);
        $voyages = Voyages::orderBy('id')->get();
        $vessels = Vessels::orderBy('id')->get();
        $containers = Containers::orderBy('id')->get();
        $containersTypes = ContainersTypes::orderBy('id')->get();
        $containersMovements = ContainersMovement::orderBy('id')->get();
        $ports = Ports::orderBy('id')->get();

        return view('containers.movements.edit',[
            'movement'=>$movement,
            'voyages'=>$voyages,
            'vessels'=>$vessels,
            'containers'=>$containers,
            'containersTypes'=>$containersTypes,
            'containersMovements'=>$containersMovements,
            'ports'=>$ports,
        ]);
    }

    public function update(Request $request, Movements $movement)
    {
        $request->validate([
            'movement_id' => 'required',
            'movement_date' => 'required',
            'port_location_id' => 'required',
        ]);
        $this->authorize(__FUNCTION__,Movements::class);
        $movement->update($request->except('_token'));
        return redirect()->route('movements.index')->with('success',trans('Movement.updated.success'));
    }

    public function destroy($id)
    {
        $movement = Movements::find($id);
        $movement->delete();
        return redirect()->route('movements.index')->with('success',trans('Movement.deleted.success'));
    }
}

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
use App\Models\Master\Agents;
use App\Models\Master\ContainerStatus;
use App\Filters\Containers\ContainersIndexFilter;
use App\MovementImportErrors;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;
class MovementController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,Movements::class);
        $movementErrors = MovementImportErrors::all();
        $container_id =  request()->input('container_id');
        $movements = Movements::filter(new ContainersIndexFilter(request()))->orderBy('id')->groupBy('container_id')
        ->paginate(30);
        $plNo = request()->input('bl_no');
        if($plNo != null){
        //     $movements = Movements::filter(new ContainersIndexFilter(request()))->where('bl_no',$plNo)->orderBy('id')->groupBy('container_id')
        // ->paginate(30);
        
        }
        $containers = Containers::orderBy('id')->get();
        $ports = Ports::orderBy('id')->get();

        if($container_id == null){
            return view('containers.movements.index',[
                'items'=>$movements,
                'containers'=>$containers,
                'movementerrors' => $movementErrors,
                'ports'=>$ports,
            ]);
        }else{
            $movement = Movements::find($container_id);
            $container = Containers::find($container_id);
            $movements = Movements::filter(new ContainersIndexFilter(request()))->where('container_id',$container_id)->orderBy('movement_date','asc')->orderBy('id','asc')->paginate(30);
            // dd($container);
            $containers = Containers::where('id',$container_id)->first();
            $DCHF = $movements->where('movement_id',ContainersMovement::where('code','DCHF')->pluck('id')->first())->count();
            $RCVC = $movements->where('movement_id',ContainersMovement::where('code','RCVC')->pluck('id')->first())->count();
            return view('containers.movements.show',[
                'DCHF' => $DCHF,
                'RCVC' => $RCVC,
                'movement'=>$movement,
                'container'=>$container,
                'items'=>$movements,
                'containers'=>$containers,
            ]);
        }
        
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,Movements::class);
        $container_id =  request()->input('container_id');
        $voyages = Voyages::orderBy('id')->get();
        $vessels = Vessels::orderBy('id')->get();
        $containersTypes = ContainersTypes::orderBy('id')->get();
        $containersMovements = ContainersMovement::orderBy('id')->get();
        $ports = Ports::orderBy('id')->get();
        $agents = Agents::orderBy('id')->get();
        $containerstatus = ContainerStatus::orderBy('id')->get();

        if(isset($container_id)){
            $container = Containers::find($container_id);
            return view('containers.movements.create',[
                'voyages'=>$voyages,
                'vessels'=>$vessels,
                'container'=>$container,
                'containersTypes'=>$containersTypes,
                'containersMovements'=>$containersMovements,
                'ports'=>$ports,
                'agents'=>$agents,
                'containerstatus'=>$containerstatus,
            ]);
        }else{
            $containers = Containers::orderBy('id')->get();
            return view('containers.movements.create',[
                'voyages'=>$voyages,
                'vessels'=>$vessels,
                'containers'=>$containers,
                'containersTypes'=>$containersTypes,
                'containersMovements'=>$containersMovements,
                'ports'=>$ports,
                'agents'=>$agents,
                'containerstatus'=>$containerstatus,

            ]);
        }
        

        
    }

    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,Movements::class);

        $request->validate([
            'movement' => 'required',
            'container_type_id' => 'required',
            'movement_id' => 'required',
            'movement_date' => 'required',
            'port_location_id' => 'required',
        ]);
        foreach($request->movement as $move){
            $containerNo = Containers::where('id', $move['container_id'])->pluck('code')->first();
            $lastMove = Movements::where('container_id',$move['container_id'])->orderBy('movement_date')->pluck('movement_id')->last();
            $nextMoves = ContainersMovement::where('id',$lastMove)->pluck('next_move')->first();
            $nextMoves = explode(', ',$nextMoves);
            $moveCode = ContainersMovement::where('id',$request->movement_id)->pluck('code')->first();
            if(!$nextMoves[0] == null){
                if(!in_array($moveCode, $nextMoves)){
                    return redirect()->route('movements.create')->with('error','container number: '.$containerNo.' with movement: '
                    .$moveCode.' not allowed!, the allowed movements for this container is '.implode(", ", $nextMoves));
                }
            }
        
        }
        foreach($request->movement as $move){
            Movements::create([
                'container_id'=>$move['container_id'],
                'container_type_id'=> $request->input('container_type_id'),
                'movement_id'=> $request->input('movement_id'),
                'movement_date'=> $request->input('movement_date'),
                'port_location_id'=> $request->input('port_location_id'),
                'pol_id'=> $request->input('pol_id'),
                'pod_id'=> $request->input('pod_id'),
                'vessel_id'=> $request->input('vessel_id'),
                'voyage_id'=> $request->input('voyage_id'),
                'terminal_id'=> $request->input('terminal_id'),
                'booking_no'=> $request->input('booking_no'),
                'transshipment_port_id'=> $request->input('transshipment_port_id'),
                'booking_agent_id'=> $request->input('booking_agent_id'),
                'import_agent'=> $request->input('import_agent'),
                'container_status'=> $request->input('container_status'),
                'free_time'=> $request->input('free_time'),
                'free_time_origin'=> $request->input('free_time_origin'),
                'bl_no'=> $request->input('bl_no'),
                'remarkes'=> $request->input('remarkes'),
                ]);
        }
        
        // Movements::create($request->except('_token'));
        
        
        return redirect()->route('movements.index')->with('success',trans('Movement.created'));
    }

    public function show($id)
    {
        $this->authorize(__FUNCTION__,Movements::class);
        $movement = Movements::find($id);
        $container = Containers::find($id);
        $movements = Movements::filter(new ContainersIndexFilter(request()))->where('container_id',$id)->orderBy('movement_date','asc')->orderBy('id','asc')->paginate(30);
        $containers = Containers::where('id',$id)->first();
        $mytime = Carbon::now()->format('d-m-Y');
        $DCHF = $movements->where('movement_id',ContainersMovement::where('code','DCHF')->pluck('id')->first())->count();
        $RCVC = $movements->where('movement_id',ContainersMovement::where('code','RCVC')->pluck('id')->first())->count();
        return view('containers.movements.show',[
            'DCHF' => $DCHF,
            'RCVC' => $RCVC,
            'movement'=>$movement,
            'container'=>$container,
            'items'=>$movements,
            'containers'=>$containers,
            'mytime'=>$mytime,

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
        $agents = Agents::orderBy('id')->get();
        $containerstatus = ContainerStatus::orderBy('id')->get();
        return view('containers.movements.edit',[
            'movement'=>$movement,
            'voyages'=>$voyages,
            'vessels'=>$vessels,
            'containers'=>$containers,
            'containersTypes'=>$containersTypes,
            'containersMovements'=>$containersMovements,
            'ports'=>$ports,
            'agents'=>$agents,
            'containerstatus'=>$containerstatus,
        ]);
    }

    public function update(Request $request, Movements $movement)
    {
        $request->validate([
            'movement_id' => 'required',
            'movement_date' => 'required',
            'port_location_id' => 'required',
        //     'voyage_id' =>[
        //     'required', 
        //                     Rule::unique('movements')
        //                         ->where('vessel_id', $request->vessel_id)
        // ],
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
        return redirect()->back()->with('success',trans('Movement.deleted.success'));
    }
}

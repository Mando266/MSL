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
use Illuminate\Support\Carbon;
use App\Models\Containers\Demurrage;
use App\Models\Containers\Period;

class MovementController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,Movements::class);
        $movementErrors = MovementImportErrors::all();
        $container_id =  request()->input('container_id');
        $filteredData = Movements::filter(new ContainersIndexFilter(request()))->orderBy('id')->groupBy('container_id')->get();
        // $movements = Movements::where('movement_id', request('movement_id'))->paginate(30);
        $plNo = request()->input('bl_no');
        $movementsBlNo = Movements::select('bl_no')->distinct()->get()->pluck('bl_no');
        $temp = [];
        
        // remove element if last movement doesn't include movement_id or port_location_id
        if(request('movement_id') != null || request('port_location_id') != null){
            $filteredData = Movements::filter(new ContainersIndexFilter(request()))->orderBy('movement_date','desc')->groupBy('container_id')->get();
            if(request('movement_id') != null){
                foreach($filteredData as $key => $move){
                    $lastMove = Movements::where('container_id',$move->container_id)->orderBy('movement_date','desc')->orderBy('id','desc')->first();
                    // dump($lastMove->movement_id != request('movement_id'));
                    if($lastMove->movement_id != request('movement_id')){
                        unset($filteredData[$key]);
                    }
                }
            }
            if(request('port_location_id') != null){
                foreach($filteredData as $key =>$move){
                    $lastMove = Movements::where('container_id',$move->container_id)->orderBy('movement_date','desc')->orderBy('id','desc')->first();
                    if($lastMove->port_location_id != request('port_location_id')){
                        unset($filteredData[$key]);
                    }else{
                        $move = $lastMove;
                    }
                    
                }
            }
        }
        if(request('TillDate')){
            $tillDate = request('TillDate');
            $periods = Period::where('demurrage_id',request('Triff_id'))->get();
            $lastDCHF = $lastMove = Movements::where('container_id',$move->container_id)->where('movement_id',ContainersMovement::where('code','DCHF')->pluck('id')->first())->orderBy('movement_date','desc')->orderBy('id','desc')->first();
        }else{
            $tillDate = null;
            $periods = null;
            $lastDCHF = null;
        }
        
        foreach($filteredData as $element){
           
            $id = $element->id;
              array_push($temp,$id);
        }
        $movements = Movements::wherein('id',$temp)->orderBy('movement_date','desc')->orderBy('id','desc')->groupBy('container_id')->paginate(30);
        foreach($movements as $move){
            $lastMove = Movements::where('container_id',$move->container_id);
            if(request('bl_no') != null){
                $lastMove = $lastMove->where('bl_no',request('bl_no'));
            }
            if(request('voyage_id') != null){
                $lastMove = $lastMove->where('voyage_id',request('voyage_id'));
            }
            if(request('booking_no') != null){
                $lastMove = $lastMove->where('booking_no',request('booking_no'));
            }
            $lastMove = $lastMove->orderBy('movement_date','desc')->orderBy('id','desc')->first();
            
            $move->bl_no = $lastMove->bl_no;
            $move->port_location_id = $lastMove->port_location_id;
            $move->movement_date = $lastMove->movement_date;
            $move->movement_id = $lastMove->movement_id;
            $move->container_type_id = $lastMove->container_type_id;
            $move->pol_id = $lastMove->pol_id;
            $move->pod_id = $lastMove->pod_id;
            $move->vessel_id = $lastMove->vessel_id;
            $move->voyage_id = $lastMove->voyage_id;
            $move->terminal_id = $lastMove->terminal_id;
            $move->booking_no = $lastMove->booking_no;
            $move->remarkes = $lastMove->remarkes;
            $move->created_at = $lastMove->created_at;
            $move->updated_at = $lastMove->updated_at;
            $move->transshipment_port_id = $lastMove->transshipment_port_id;
            $move->booking_agent_id = $lastMove->booking_agent_id;
            $move->free_time = $lastMove->free_time;
            $move->container_status = $lastMove->container_status;
            $move->import_agent = $lastMove->import_agent;
            $move->free_time_origin = $lastMove->free_time_origin;
            
        }

        $containers = Containers::orderBy('id')->get();
        $ports = Ports::orderBy('id')->get();
        $voyages = Voyages::orderBy('id')->get();
        $containersMovements = ContainersMovement::orderBy('id')->get();
        
        if($movements->count() > 1){
            return view('containers.movements.index',[
                'items'=>$movements,
                'movementsBlNo'=>$movementsBlNo,
                'containers'=>$containers,
                'movementerrors' => $movementErrors,
                'plNo' => $plNo,
                'ports'=>$ports,
                'voyages'=>$voyages,
                'containersMovements'=>$containersMovements,
            ]);
        }else{
            if($container_id == null){
                if($movements->count() == 0){
                    return view('containers.movements.index',[
                        'items'=>$movements,
                        'movementsBlNo'=>$movementsBlNo,
                        'containers'=>$containers,
                        'movementerrors' => $movementErrors,
                        'plNo' => $plNo,
                        'ports'=>$ports,
                        'voyages'=>$voyages,
                        'containersMovements'=>$containersMovements,
                    ]);
                }
                // dd($movements);
                $container_id = Containers::where('id',$movements->first()->container_id)->pluck('id')->first();
            }
            $movement = Movements::find($container_id);
            $container = Containers::find($container_id);
            $movements = Movements::filter(new ContainersIndexFilter(request()))->where('container_id',$container_id);
            
            $movementId = false;
            $movementsArray = false;

            // Check if we have movement code or port_location_id to get latest movement or not
            if(request('movement_id') == null && request('port_location_id') == null){
                $movements = $movements->orderBy('movement_date','desc')->orderBy('id','desc')->paginate(30);
            }else{
                
                if(request('container_id') != null){
                    $movements = Movements::where('container_id',request('container_id'))->orderBy('movement_date','desc')->orderBy('id','desc')->first();
                    
                }else{
                    
                    $movements = Movements::where('container_id',$container_id)->orderBy('movement_date','desc')->first();
                    
                }
                
                
                if(request('movement_id') != null){
                    // dd($movements);
                    if($movements->movement_id != request('movement_id')){
                        $movementsArray = true;
                    }
                }
                if(request('port_location_id') != null){
                    if($movements->port_location_id != request('port_location_id')){
                        $movementsArray = true;
                    }
                }
                $movementId = true;
            }
            $containers = Containers::where('id',$container_id)->first();
            
            if($movementsArray == true){
                $movements = [];
                $DCHF = 0;
                $RCVC = 0;
            }else{
            $DCHF = $movements->where('movement_id',ContainersMovement::where('code','DCHF')->pluck('id')->first())->count();
            $RCVC = $movements->where('movement_id',ContainersMovement::where('code','RCVC')->pluck('id')->first())->count();
            }
            $demurrages = Demurrage::get();
            $mytime = Carbon::now()->format('d-m-Y');
            return view('containers.movements.show',[
                
                'movementsArray'=>$movementsArray,
                'movementId' =>$movementId,
                'periods'=>$periods,
                'container_id'=>$container_id,
                'tillDate'=>$tillDate,
                'lastDCHF'=>$lastDCHF,
                'id' =>$id,
                'DCHF' => $DCHF,
                'RCVC' => $RCVC,
                'movement'=>$movement,
                'container'=>$container,
                'items'=>$movements,
                'containers'=>$containers,
                'mytime'=>$mytime,
                'demurrages'=>$demurrages,
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
        if(isset($id)){
            $container_id = $id;
        }elseif(request('container_id') != null){
            $container_id = request('container_id');
        }else{
            $container_id = null;
        }
        $movement = Movements::find($id);
        $container = Containers::find($id);
        $movementId = false;
        $movementsArray = false;
        $demurrages = Demurrage::get();

        if(request('plNo') == null){
            $movements = Movements::filter(new ContainersIndexFilter(request()))->where('container_id',$id);
        }else{
            $movements = Movements::filter(new ContainersIndexFilter(request()))->where('container_id',$id)->where('bl_no',request('plNo'))->orderBy('movement_date','desc')->orderBy('id','desc');
        }
        
        if(request('voyage_id') != null){
            $movements = $movements->where('voyage_id',request('voyage_id'));
        }
        if(request('port_location_id') != null){
            $movements = $movements->where('port_location_id',request('port_location_id'));
        }
        if(request('movement_id') != null){
            $movements = $movements->where('movement_id',request('movement_id'));
            $movementId = true;
        }
        if(request('booking_no') != null){
            $movements = $movements->where('booking_no',request('booking_no'));
        }

        if(request('movement_id') == null && request('port_location_id') == null){
            $movements = $movements->orderBy('movement_date','desc')->orderBy('id','desc')->paginate(30);
        }else{
            $movements = Movements::where('container_id',$movements->first()->container_id)->orderBy('movement_date','desc')->orderBy('id','desc')->first();
            $container_id = $movements->first()->container_id;
            if(request('movement_id') != null){
                if($movements->movement_id != request('movement_id')){
                    $movements = [];
                    $movementsArray = true;
                }
            }
            if(request('port_location_id') != null){
                if($movements->port_location_id != request('port_location_id')){
                    $movements = [];
                    $movementsArray = true;
                }
            }
            
            $movementId = true;
        }
        if(request('container_id'))
        {
            $container_id = request('container_id');
        }
        
        if(request('TillDate')&&request('Triff_id')!=null){
            $tillDate = request('TillDate');
            $periods = Period::where('demurrage_id',request('Triff_id'))->get();
           
            $lastDCHF = $lastMove = Movements::where('container_id',$container_id)->where('movement_id',ContainersMovement::where('code','DCHF')->pluck('id')->first())->orderBy('movement_date','desc')->orderBy('id','desc')->first();
        }else{
            $tillDate = null;
            $periods = null;
            $lastDCHF = null;
        }
        
        $containers = Containers::where('id',$id)->first();
        $mytime = Carbon::now()->format('d-m-Y');
        if($movementsArray == true){
            $DCHF = 0;
            $RCVC = 0;
        }else{
        $DCHF = $movements->where('movement_id',ContainersMovement::where('code','DCHF')->pluck('id')->first())->count();
        $RCVC = $movements->where('movement_id',ContainersMovement::where('code','RCVC')->pluck('id')->first())->count();
        }
        
        return view('containers.movements.show',[
            'movementsArray'=>$movementsArray,
            'movementId' =>$movementId,
            'periods'=>$periods,
            'container_id'=>$container_id,
            'tillDate'=>$tillDate,
            'lastDCHF'=>$lastDCHF,
            'id' =>$id,
            'DCHF' => $DCHF,
            'RCVC' => $RCVC,
            'movement'=>$movement,
            'container'=>$container,
            'items'=>$movements,
            'containers'=>$containers,
            'mytime'=>$mytime,
            'demurrages'=>$demurrages,

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

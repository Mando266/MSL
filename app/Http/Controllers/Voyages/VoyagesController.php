<?php

namespace App\Http\Controllers\Voyages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Voyages\Voyages;
use App\Models\Master\Vessels;
use App\Models\Master\Lines;
use App\Models\Master\Terminals;
use App\Models\Master\Ports;
use App\Models\Voyages\Legs;
use App\Models\Voyages\VoyagePorts;
use App\Filters\Voyages\VoyagesIndexFilter;
use Illuminate\Support\Facades\Http;
use DB;
class VoyagesController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,Voyages::class);
        $where = [];
        $Port =  request()->input('Port');
        if ($Port) $where[] = ['port_from_name', $Port];
        $FromPort =  request()->input('From');
        // if ($FromPort) $where[] = ['port_from_name','>=', $FromPort];
        $ToPort = request()->input('To');
        // if ($ToPort) $where[] = ['port_from_name','<=', $ToPort];
        
        $voyages = Voyages::join('voyage_port', 'voyage_port.voyage_id' ,'=','voyages.id')
            ->select('voyages.*', 'voyage_port.port_from_name', 'voyage_port.terminal_name', 'voyage_port.road_no')
            ->with('voyagePorts')
            ->filter(new VoyagesIndexFilter(request()))
            ->groupBy('id')
            ->get();
            
            // SEARCH FROM PART
            if(isset($FromPort) && !isset($ToPort)){
                $voyages = Voyages::join('voyage_port', 'voyage_port.voyage_id' ,'=','voyages.id')
                ->select('voyages.*', 'voyage_port.port_from_name', 'voyage_port.terminal_name', 'voyage_port.road_no')
                ->with('voyagePorts')
                ->filter(new VoyagesIndexFilter(request()))
                ->where('port_from_name','>=', $FromPort)
                ->groupBy('id')
                ->get();
                foreach ($voyages as $key => $voyage) {
                    $tempPortsFrom = $voyage->voyagePorts->where('port_from_name', $FromPort)->pluck('id')->toArray();
                    if(!isset($tempPortsFrom[0])){
                        unset($voyages[$key]);
                    }else{
                        foreach($voyage->voyagePorts as $key => $voyagePort){
                            if(!in_array($voyagePort->id,$tempPortsFrom)){
                                unset($voyage->voyagePorts[$key]);
                            }
                        }
                    } 
                }
            }//check for filter from : to
            elseif(isset($FromPort) && isset($ToPort)){
                $voyages = Voyages::join('voyage_port', 'voyage_port.voyage_id' ,'=','voyages.id')
                ->select('voyages.*', 'voyage_port.port_from_name', 'voyage_port.terminal_name', 'voyage_port.road_no')
                ->with('voyagePorts')
                ->filter(new VoyagesIndexFilter(request()))
                ->where('port_from_name','>=', $FromPort)
                ->orWhere('port_from_name','<=', $ToPort)
                ->groupBy('id')
                ->get();
            foreach ($voyages as $key => $voyage) {
                $tempPortsFrom = $voyage->voyagePorts->where('port_from_name', $FromPort)->pluck('id')->toArray();
                $tempPortsTo = $voyage->voyagePorts->where('port_from_name', $ToPort)->pluck('id')->toArray();
                if(!isset($tempPortsFrom[0]) || !isset($tempPortsTo[0])){
                    unset($voyages[$key]);
                }
                    $voyageports = VoyagePorts::where('voyage_id',$voyage->id)
                    ->where(function ($query) use ($FromPort,$ToPort) {
                        $query->where('port_from_name', $FromPort)
                              ->orWhere('port_from_name', $ToPort);
                    })->get();
                    if($voyageports[0]->port_from_name != $FromPort){
                        unset($voyages[$key]);
                    }else{
                        $voyageports = $voyageports->pluck('id')->toArray();
                        foreach($voyage->voyagePorts as $key => $voyagePort){
                            if(!in_array($voyagePort->id,$voyageports)){
                                unset($voyage->voyagePorts[$key]);
                            }
                        }
                    }
            }
        }
        $vessels = Vessels::orderBy('name')->get();
        $ports = Ports::orderBy('name')->get();
        // dd($voyages);
        return view('voyages.voyages.index',[
            'items'=>$voyages,
            'vessels'=>$vessels,
            'ports'=>$ports,
            'FromPort'=> $FromPort,
            'ToPort'=> $ToPort
        ]);
     }

    public function create()
    {
        $this->authorize(__FUNCTION__,Voyages::class);
        $vessels = Vessels::orderBy('name')->get();
        $legs = Legs::orderBy('id')->get();
        $lines = Lines::orderBy('id')->get();
        $terminals = [];
        $ports = Ports::orderBy('id')->get();
        return view('voyages.voyages.create',[
            'vessels'=>$vessels,
            'legs'=>$legs,
            'lines'=>$lines,
            'terminals'=>$terminals,
            'ports'=>$ports,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'voyage_no' => 'required',
            'vessel_id' => 'required',
        ]);
        $voyages = Voyages::create([
            'vessel_id'=> $request->input('vessel_id'),
            'voyage_no'=> $request->input('voyage_no'),
            'leg_id'=> $request->input('leg_id'),
            'line_id'=> $request->input('line_id'),
        ]);

        foreach($request->input('voyageport',[]) as $voyageport){
            VoyagePorts::create([
                'voyage_id'=>$voyages->id,
                'port_from_name'=>$voyageport['port_from_name'],
                'terminal_name'=>$voyageport['terminal_name'],
                'road_no'=>$voyageport['road_no'],
                'eta'=>$voyageport['eta'],
                'etd'=>$voyageport['etd'],

            ]);
        }
        return redirect()->route('voyages.index')->with('success',trans('voyage.created'));
    }

    public function show($id,$from = null,$to = null)
    {
        $this->authorize(__FUNCTION__,Voyages::class);
        $voyage = Voyages::find($id);
        if($from == null && $to == null){
            $voyageports = VoyagePorts::where('voyage_id',$id)->get();
        // }elseif($from == null){
        //     $voyageports = VoyagePorts::where('voyage_id',$id)
        // ->where('port_from_name', $to)->get();
        }elseif($to == null){
            // Here is trips when we search with from just
            $tempPortsFrom = VoyagePorts::where('voyage_id',$id)->where('port_from_name', $from)->get();
            if(!isset($tempPortsFrom[0])){
                $voyageports = [];
            }else{
                $voyageports = VoyagePorts::where('voyage_id',$id)
                        ->where('port_from_name', $from)->get();
            }
            
        }else{
            // Here is trips when we search with from : to 
                // here we check if we found ports for this voyage from and to our search
                $tempPortsFrom = VoyagePorts::where('voyage_id',$id)->where('port_from_name', $from)->get();
                $tempPortsTo = VoyagePorts::where('voyage_id',$id)->where('port_from_name', $to)->get();
                
                // dd(!isset($tempPortsFrom[0]) || !isset($tempPortsTo[0]));
                
                if(!isset($tempPortsFrom[0]) || !isset($tempPortsTo[0])){
                    $voyageports = [];
                }else{
                    $voyageports = VoyagePorts::where('voyage_id',$id)
                    ->where(function ($query) use ($from,$to) {
                        $query->where('port_from_name', $from)
                              ->orWhere('port_from_name', $to);
                    })->get();
                }
                
            
        }
        
        $voyageport = Voyages::where('id',$id)->first();

        return view('voyages.voyages.show',[
            'voyage'=>$voyage,
            'voyageports'=>$voyageports,
            'voyageport'=>$voyageport,
        ]);
    }

    public function edit(VoyagePorts $voyage)
    {
        $this->authorize(__FUNCTION__,VoyagePorts::class);
        $vessels = Vessels::orderBy('name')->get();
        $legs = Legs::orderBy('id')->get();
        $lines = Lines::orderBy('id')->get();
        $terminals = Terminals::orderBy('id')->get();
        $ports = Ports::orderBy('id')->get();

        return view('voyages.voyages.edit',[
            'voyage'=>$voyage,
            'vessels'=>$vessels,
            'legs'=>$legs,
            'lines'=>$lines,
            'terminals'=>$terminals,
            'ports'=>$ports,

        ]);
    }

    public function update(Request $request, VoyagePorts $voyage)
    {
        $this->authorize(__FUNCTION__,VoyagePorts::class);
        $voyage->update($request->except('_token'));
        return redirect()->route('voyages.index')->with('success',trans('voyage.updated.success'));
    }

    public function destroy($id)
    {
        $voyage = Voyages::find($id);
        VoyagePorts::where('voyage_id',$id)->delete();
        $voyage->delete(); 
        return redirect()->route('voyages.index')->with('success',trans('voyage.deleted.success'));
    }
}

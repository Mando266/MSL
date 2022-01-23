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
        $FromPort =  request()->input('From');
        $ToPort = request()->input('To');
        $voyages = Voyages::join('voyage_port', 'voyage_port.voyage_id' ,'=','voyages.id')
            ->select('voyages.*', 'voyage_port.port_from_name', 'voyage_port.terminal_name', 'voyage_port.road_no', 'voyage_port.eta', 'voyage_port.etd')
            ->filter(new VoyagesIndexFilter(request()))
            ->whereBetween('port_from_name', [$FromPort, $ToPort])
            ->get();
        $vessels = Vessels::orderBy('name')->get();
        $ports = Ports::orderBy('name')->get();
        return view('voyages.voyages.index',[
            'items'=>$voyages,
            'vessels'=>$vessels,
            'ports'=>$ports,
        ]);
     }

    public function create()
    {
        $this->authorize(__FUNCTION__,Voyages::class);
        $vessels = Vessels::orderBy('name')->get();
        $legs = Legs::orderBy('id')->get();
        $lines = Lines::orderBy('id')->get();
        $terminals = Terminals::orderBy('id')->get();
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

    public function show($id)
    {
        $this->authorize(__FUNCTION__,Voyages::class);
        $voyage = Voyages::find($id);
        $voyageports = VoyagePorts::where('voyage_id',$id)->get();
        $voyageport = Voyages::where('id',$id)->first();

        return view('voyages.voyages.show',[
            'voyage'=>$voyage,
            'voyageports'=>$voyageports,
            'voyageport'=>$voyageport,
        ]);
    }

    public function edit(Voyages $voyage)
    {
        $this->authorize(__FUNCTION__,Voyages::class);
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

    public function update(Request $request, Voyages $voyage)
    {
        $request->validate([
            'voyage_no' => 'required',
            'vessel_id' => 'required',
        ]);
        $this->authorize(__FUNCTION__,Voyages::class);
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
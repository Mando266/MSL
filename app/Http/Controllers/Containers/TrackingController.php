<?php

namespace App\Http\Controllers\Containers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Containers\Movements;
use App\Models\Master\Containers;
use App\Filters\Containers\ContainersIndexFilter;
use DB;
class TrackingController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,Movements::class);
        // $movements = Movements::filter(new ContainersIndexFilter(request()))
        // ->get()->groupBy('container_id');
        $containers = Containers::orderBy('id')->get();
        $movements = Movements::filter(new ContainersIndexFilter(request()))->join('containers', 'movements.container_id', '=', 'containers.id')
            ->select('movements.*', 'containers.code')->orderBy('movement_date','asc')->orderBy('movement_id','asc')->orderBy('id','asc')
        ->get()->groupBy('code');

        return view('containers.tracking.index',[
            'items'=>$movements,
            'containers'=>$containers,

        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,Movements::class);
        $containers = Containers::orderBy('id')->get();

        return view('containers.tracking.create',[
            'containers'=>$containers,
        ]);
    }

    public function store()
    {

    }

}

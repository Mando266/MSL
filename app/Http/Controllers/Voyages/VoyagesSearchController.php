<?php

namespace App\Http\Controllers\Voyages;
use App\Http\Controllers\Controller;
use App\Models\Voyages\Voyages;
use App\Models\Master\Vessels;
use App\Models\Master\Lines;
use App\Models\Master\Ports;
use App\Models\Voyages\Legs;

class VoyagesSearchController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,Voyages::class);
        $vessels = Vessels::orderBy('name')->get();
        $legs = Legs::orderBy('id')->get();
        $lines = Lines::orderBy('id')->get();
        $ports = Ports::orderBy('id')->get();
        return view('voyages.voyagesearch.create',[
            'vessels'=>$vessels,
            'legs'=>$legs,
            'lines'=>$lines,
            'ports'=>$ports,
        ]);
    }

    public function store()
    {
        //
    }


    public function show($id)
    {

    }

    public function edit()
    {

    }

    public function update()
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}

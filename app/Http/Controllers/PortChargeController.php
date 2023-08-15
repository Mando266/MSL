<?php

namespace App\Http\Controllers;

use App\Models\PortCharge;
use Illuminate\Http\Request;

class PortChargeController extends Controller
{

    public function index()
    {
        $portCharges = PortCharge::paginate(10);
            
        return view('port_charge.index')
            ->with([
                'portCharges' => $portCharges
            ]);
    }


    public function create()
    {
    }


    public function store(Request $request)
    {
        $portCharge = PortCharge::create($request->all());
        $savedId = $portCharge->id;

        return response()->json(['id' => $savedId], 201);
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }

    public function editRow()
    {
//        dd(request()->all());
        $portCharge = PortCharge::find(request()->id);
        $data = request()->except('id');
        $portCharge->update([
            'thc_20ft' => $data[0],
            'thc_40ft' => $data[1],
            'storage_20ft' => $data[2],
            'storage_40ft_first_5' => $data[3],
            'storage_40ft_after_5' => $data[4],
            'power_20ft' => $data[5],
            'power_40ft' => $data[6],
            'shifting_20ft' => $data[7],
            'shifting_40ft' => $data[8],
            'disinf_20ft' => $data[9],
            'disinf_40ft' => $data[10],
            'hand_fes_em_20ft' => $data[11],
            'hand_fes_em_40ft' => $data[12],
            'gat_lift_off_inbnd_em_ft40_20ft' => $data[13],
            'gat_lift_off_inbnd_em_ft40_40ft' => $data[14],
            'gat_lift_on_inbnd_em_ft40_20ft' => $data[15],
            'gat_lift_on_inbnd_em_ft40_40ft' => $data[16],
            'pti_20ft' => $data[17],
            'pti_40ft_failed' => $data[18],
            'pti_40ft_pass' => $data[19],
            'wire_trnshp_20ft' => $data[20],
            'wire_trnshp_40ft' => $data[21],
        ]);
    }

    public function deleteRow()
    {
        PortCharge::find(request()->id)->delete();
    }
}

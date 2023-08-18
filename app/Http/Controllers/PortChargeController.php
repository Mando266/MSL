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

    public function store(Request $request)
    {
        $portCharge = PortCharge::create($request->all());
        $savedId = $portCharge->id;

        return response()->json(['id' => $savedId], 201);
    }

    public function create()
    {
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function editRow()
    {
        $portCharge = PortCharge::find(request()->id);
        $data = request()->except('id');
        $count = 0;
        $portCharge->update([
            'thc_20ft' => $data[$count++],
            'thc_40ft' => $data[$count++],
            'storage_free' => $data[$count++],
            'storage_slab1_period' => $data[$count++],
            'storage_slab1_20ft' => $data[$count++],
            'storage_slab1_40ft' => $data[$count++],
            'storage_slab2_period' => $data[$count++],
            'storage_slab2_20ft' => $data[$count++],
            'storage_slab2_40ft' => $data[$count++],
            'power_20ft' => $data[$count++],
            'power_40ft' => $data[$count++],
            'shifting_20ft' => $data[$count++],
            'shifting_40ft' => $data[$count++],
            'disinf_20ft' => $data[$count++],
            'disinf_40ft' => $data[$count++],
            'hand_fes_em_20ft' => $data[$count++],
            'hand_fes_em_40ft' => $data[$count++],
            'gat_lift_off_inbnd_em_ft40_20ft' => $data[$count++],
            'gat_lift_off_inbnd_em_ft40_40ft' => $data[$count++],
            'gat_lift_on_inbnd_em_ft40_20ft' => $data[$count++],
            'gat_lift_on_inbnd_em_ft40_40ft' => $data[$count++],
            'pti_failed' => $data[$count++],
            'pti_passed' => $data[$count++],
            'wire_trnshp_20ft' => $data[$count++],
            'wire_trnshp_40ft' => $data[$count++],
        ]);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function deleteRow()
    {
        PortCharge::find(request()->id)->delete();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Bl\BlDraft;
use App\Models\Master\Containers;
use App\Models\Master\ContainersMovement;
use App\Models\Master\Country;
use App\Models\Master\Lines;
use App\Models\Master\Ports;
use App\Models\Master\Vessels;
use App\Models\PortCharge;
use App\Models\Voyages\Voyages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function createInvoice()
    {
        $vessels = Vessels::where('company_id', Auth::user()->company_id)->orderBy('id')->get();
        $voyages = Voyages::where('company_id', Auth::user()->company_id)->orderBy('id')->get();
        $lines = Lines::where('company_id', Auth::user()->company_id)->orderBy('id')->get();
        $portCharges = PortCharge::paginate(10);
        $possibleMovements = ContainersMovement::where('name','like','%empty%')->get();
        $countries = Country::orderBy('name')->get();
        $ports = Ports::where('company_id',Auth::user()->company_id)->orderBy('id')->get();

        return view('port_charge.invoice', [
            'vessels' => $vessels,
            'voyages' => $voyages,
            'lines' => $lines,
            'portCharges' => $portCharges,
            'possibleMovements' => $possibleMovements,
            'countries' => $countries,
            'ports' => $ports
        ]);
    }

    public function getRefNo()
    {
//        $vessel = request()->input('vessel');
        $voyage = request()->input('voyage');

        $container = request()->input('container');
        $containerNo = Containers::firstWhere('code', $container)->id;

//        dd($containerNo, $voyage);
        $blDraft = BlDraft::where('voyage_id', $voyage)
            ->whereHas('blDetails', function ($q) use ($containerNo) {
                $q->where('container_id', $containerNo);
            })->first();
        $booking = $blDraft->booking;
        $quotation = $booking->quotation;
        if ($blDraft) {
            return response()->json([
                'status' => 'success',
                'ref_no' => $blDraft->ref_no,
                'is_ts' => $booking->is_transhipment ?? '',
                'shipment_type' => $quotation->shipment_type ?? '',
                'quotation_type' => $quotation->quotation_type ?? ''
            ], 201);
        }

        return response()->json(['status' => 'failed'], 404);
    }

//cost gate
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
            'power_free' => $data[$count++],
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
            'add_plan_20ft' => $data[$count++],
            'add_plan_40ft' => $data[$count++],
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

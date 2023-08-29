<?php

namespace App\Http\Controllers;

use App\Models\Bl\BlDraft;
use App\Models\ChargesMatrix;
use App\Models\Containers\Movements;
use App\Models\Master\Containers;
use App\Models\Master\ContainersMovement;
use App\Models\Master\Country;
use App\Models\Master\Lines;
use App\Models\Master\Ports;
use App\Models\Master\Vessels;
use App\Models\PortCharge;
use App\Models\Voyages\Voyages;
use Carbon\Carbon;
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

        return response()->json(['id' => $portCharge->id], 201);
    }

    public function create()
    {
    }

    public function createInvoice()
    {
        $userCompanyId = Auth::user()->company_id;
        $vessels = Vessels::where('company_id', $userCompanyId)->orderBy('id')->get();
        $voyages = Voyages::where('company_id', $userCompanyId)->orderBy('id')->get();
        $lines = Lines::where('company_id', $userCompanyId)->orderBy('id')->get();
        $portCharges = PortCharge::paginate(10);
        $possibleMovements = ContainersMovement::where('name', 'like', '%empty%')->get();
        $countries = Country::orderBy('name')->get();
        $ports = Ports::where('company_id', $userCompanyId)->orderBy('id')->get();

        return view(
            'port_charge.invoice',
            compact('vessels', 'voyages', 'lines', 'portCharges', 'possibleMovements', 'countries', 'ports')
        );
    }

    public function getRefNo()
    {
        $voyage = request()->input('voyage');
        $container = request()->input('container');
        $containerId = Containers::firstWhere('code', $container)->id;

        $blDraft = BlDraft::with(['booking.quotation'])->where('voyage_id', $voyage)->whereHas(
            'blDetails',
            fn($q) => $q->where('container_id', $containerId)
        )->first();
        if ($blDraft) {
            $booking = $blDraft->booking;
            $quotation = $booking->quotation;
            return response()->json([
                'status' => 'success',
                'ref_no' => $blDraft->ref_no,
                'is_ts' => $booking->is_transhipment ?? '',
                'shipment_type' => $quotation->shipment_type ?? '',
                'quotation_type' => $quotation->quotation_type ?? '',
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

    public function calculateInvoiceRow()
    {
//        dd(request()->all());
        $blNo = request()->bl_no;
        $chargeType = request()->charge_type;
        $containerNo = request()->container_no;
        $container = Containers::firstWhere('code', $containerNo);

        $chargeMatrix = ChargesMatrix::find($chargeType);
        $storage_from = request()->from ?? $chargeMatrix->storage_from;
        $storage_to = request()->to ?? $chargeMatrix->storage_to;
        $power_from = request()->from ?? $chargeMatrix->power_from;
        $power_to = request()->to ?? $chargeMatrix->power_to;

        $containerId = $container->id;
        $storageFromDate = $this->getMovementDate($containerId, $storage_from, $blNo);
        $storageToDate = $this->getMovementDate($containerId, $storage_to, $blNo);
        $storageDaysInPort = Carbon::parse($storageFromDate)->diffInDays(Carbon::parse($storageToDate)) + 1;
        $powerFromDate = $this->getMovementDate($containerId, $power_from, $blNo);
        $powerToDate = $this->getMovementDate($containerId, $power_to, $blNo);
        $powerDaysInPort = Carbon::parse($powerFromDate)->diffInDays(Carbon::parse($powerToDate)) + 1;

        $portCharge = $chargeMatrix->portCharge;
        $container_size = (int)$container->containersTypes->name;
        $storage_cost = $this->calculateStorageCost($storageDaysInPort, $container_size, $portCharge);
        $power_cost = $this->calculatePowerCost($powerDaysInPort, $container_size, $portCharge);
        $containerSizeSuffix = "{$container_size}ft";
        $chargeTypes = [
            'thc',
            'shifting',
            'disinf',
            'hand_fes_em',
            'gat_lift_off_inbnd_em_ft40',
            'gat_lift_on_inbnd_em_ft40',
            'add_plan'
        ];

        foreach ($chargeTypes as $chargeType) {
            $property = "{$chargeType}_{$containerSizeSuffix}";
            $costs[$chargeType] = $portCharge->$property;
        }

        $response = [
            'storage' => $storage_cost,
            'power' => $power_cost,
            'pti_failed' => $portCharge->pti_failed,
            'pti_passed' => $portCharge->pti_passed,
            'container_size' => $container_size
        ];

        foreach ($chargeTypes as $chargeType) {
            $response[$chargeType] = $costs[$chargeType];
        }


        return response()->json($response, 201);
    }

    public function getMovementDate($containerId, $movementCode, $blNo)
    {
        return Movements::where('container_id', $containerId)
            ->whereHas('movementcode', fn($q) => $q->where('code', $movementCode))
            ->where('bl_no', $blNo)->first()->movement_date;
    }

    public function calculateStorageCost($daysInPort, $container_size, $portCharge)
    {
        $free_days = $portCharge->storage_free;
        $slab1_period = $portCharge->storage_slab1_period;
        $slab1_20ft = $portCharge->storage_slab1_20ft;
        $slab1_40ft = $portCharge->storage_slab1_40ft;
        $slab2_20ft = $portCharge->storage_slab2_20ft;
        $slab2_40ft = $portCharge->storage_slab2_40ft;
        $cost = 0;
        if ($daysInPort > $free_days) {
            if ($daysInPort <= ($free_days + $slab1_period)) {
                $daysInSlab1 = $daysInPort - $free_days;
                $cost += $daysInSlab1 * ($container_size === 20 ? $slab1_20ft : $slab1_40ft);
            } else {
                $daysInSlab1 = $slab1_period;
                $cost += $daysInSlab1 * ($container_size === 20 ? $slab1_20ft : $slab1_40ft);

                $daysInSlab2 = $daysInPort - $free_days - $slab1_period;
                $cost += $daysInSlab2 * ($container_size === 20 ? $slab2_20ft : $slab2_40ft);
            }
        }
        return $cost;
    }

    public function calculatePowerCost($daysInPort, $container_size, $portCharge)
    {
        $free_days = $portCharge->power_free;
        $day_20ft = $portCharge->power_20ft;
        $day_40ft = $portCharge->power_40ft;

        $cost = 0;
        if ($daysInPort > $free_days) {
            $daysInSlab1 = $daysInPort - $free_days;
            $cost += $daysInSlab1 * ($container_size === 20 ? $day_20ft : $day_40ft);
        }
        return $cost;
    }
}

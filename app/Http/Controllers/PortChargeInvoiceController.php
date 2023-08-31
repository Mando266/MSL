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
use App\Models\PortChargeInvoice;
use App\Models\Voyages\Voyages;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortChargeInvoiceController extends Controller
{

    public function index()
    {
        $invoices = PortChargeInvoice::paginate(20);

        return view('port_charge.invoice.index')
            ->with([
                'invoices' => $invoices,
            ]);
    }

    public function store(Request $request)
    {
        $rows = $this->separateInputByIndex(request()->rows);
        $selectedCosts = request()->selected_costs;
        $identifiers = [
            "port_charge_id",
            "service",
            "bl_no",
            "container_no",
            "is_transhipment",
            "shipment_type",
            "quotation_type"
        ];
        $selectedItems = array_merge($selectedCosts, $identifiers);
        $rows->transform(fn($row) => $row->only($selectedItems));
        $invoiceData = request()->except('_token', 'rows');
        $invoiceData['selected_costs'] = implode(',', $invoiceData['selected_costs']);
        $portCharge = PortChargeInvoice::create($invoiceData);

        foreach ($rows as $row) {
            $portCharge->rows()->create($row->toArray());
        }
        return redirect()->route('port-charge-invoices.index');
    }



    public function create()
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
            'port_charge.invoice.create',
            compact('vessels', 'voyages', 'lines', 'portCharges', 'possibleMovements', 'countries', 'ports')
        );
    }

    public function show(PortChargeInvoice $portChargeInvoice)
    {
        return view('port_charge.invoice.show')
            ->with([
                'invoice' => $portChargeInvoice->load('rows'),
            ]);
    }

    public function edit(PortChargeInvoice $portChargeInvoice)
    {
        //
    }

    public function update(Request $request, PortChargeInvoice $portChargeInvoice)
    {
        //
    }

    public function destroy(PortChargeInvoice $portChargeInvoice)
    {
        $portChargeInvoice->delete();
        return redirect()->route('port-charge-invoices.index');
    }

    public function separateInputByIndex($data): \Illuminate\Support\Collection
    {
        $details = collect();

        for ($i = 0; $i < count(reset($data)); $i++) {
            $item = collect();

            foreach ($data as $key => $values) {
                $item[$key] = $values[$i] ?? null;
            }

            $details->push($item);
        }

        return $details;
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
}

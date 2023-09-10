<?php

namespace App\Http\Controllers;

use App\Models\Booking\Booking;
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
use Illuminate\Support\Collection;

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

    public function create()
    {
        $formViewData = $this->getFormViewData();

        return view('port_charge.invoice.create', $formViewData);
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_no' => 'required|unique:port_charge_invoices',
        ]);

        $rows = $this->prepareInvoiceRows(request()->rows);
        $invoiceData = $this->extractInvoiceData(request()->except('_token', 'rows'));

        $portChargeInvoice = PortChargeInvoice::create($invoiceData);

        foreach ($rows as $row) {
            $portChargeInvoice->rows()->create($row);
        }

        return redirect()->route('port-charge-invoices.index');
    }

    public function show(PortChargeInvoice $portChargeInvoice)
    {
        return view('port_charge.invoice.show', [
            'invoice' => $portChargeInvoice->load('rows'),
            'selected' => explode(',', $portChargeInvoice->selected_costs),
        ]);
    }

    public function edit(PortChargeInvoice $portChargeInvoice)
    {
        $formViewData = $this->getFormViewData();

        return view('port_charge.invoice.edit', $formViewData)
            ->with([
                'invoice' => $portChargeInvoice,
                'rows' => $portChargeInvoice->rows,
                'selected' => explode(',', $portChargeInvoice->selected_costs),
            ]);
    }

    public function update(PortChargeInvoice $portChargeInvoice)
    {
        $rows = $this->prepareInvoiceRows(request()->rows);
        $invoiceData = $this->extractInvoiceData(request()->except('_token', 'rows'));

        $portChargeInvoice->update($invoiceData);
        $portChargeInvoice->rows()->delete();

        foreach ($rows as $row) {
            $portChargeInvoice->rows()->create($row);
        }

        return redirect()->route('port-charge-invoices.index');
    }

    public function destroy(PortChargeInvoice $portChargeInvoice)
    {
        $portChargeInvoice->delete();
        return redirect()->route('port-charge-invoices.index');
    }

    public function prepareInvoiceRows($rawRows)
    {
        $rows = $this->separateInputByIndex($rawRows);
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
        return $rows->transform(fn($row) => $row->only($selectedItems))->toArray();
    }

    public function separateInputByIndex($data): Collection
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

    public function extractInvoiceData($invoiceData)
    {
        $invoiceData['selected_costs'] = implode(',', $invoiceData['selected_costs']);
        return $invoiceData;
    }

    public function getFormViewData()
    {
        $userCompanyId = auth()->user()->company_id;
        $vessels = Vessels::where('company_id', $userCompanyId)->orderBy('id')->get();
        $voyages = Voyages::where('company_id', $userCompanyId)->orderBy('id')->get();
        $lines = Lines::where('company_id', $userCompanyId)->orderBy('id')->get();
        $portCharges = PortCharge::paginate(10);
        $possibleMovements = ContainersMovement::all();
        $countries = Country::orderBy('name')->get();
        $ports = Ports::where('company_id', $userCompanyId)->orderBy('id')->get();

        return compact(
            'vessels',
            'voyages',
            'lines',
            'portCharges',
            'possibleMovements',
            'countries',
            'ports'
        );
    }

    public function calculateInvoiceRow()
    {
//        dd(request()->all());
        $blNo = request()->bl_no;
        $chargeType = request()->charge_type;
        $containerNo = request()->container_no;
        $quotationType = request()->quotation_type;
        $chargeMatrix = ChargesMatrix::find($chargeType);
        $storage_from = request()->from ?? $chargeMatrix->storage_from;
        $power_from = request()->from ?? $chargeMatrix->power_from;

        $container = Containers::firstWhere('code', $containerNo);
        $containerId = $container->id;
        $container_size = (int)$container->containersTypes->name;
        $portCharge = $chargeMatrix->portCharge;

        $storageDaysInPort = $storage_from === "Select" ?
            0 :
            $this->calculateDays($containerId, $storage_from, $blNo);
        $powerDaysInPort = $storage_from === "Select" ?
            0 :
            $this->calculateDays($containerId, $power_from, $blNo);

        [$storage_cost, $storage_cost_minus_one] = $this->calculateStorageCost(
            $storageDaysInPort,
            $container_size,
            $portCharge
        );

        [$power_cost, $power_cost_minus_one] = $quotationType === 'empty' ?
            [0, 0] :
            $this->calculatePowerCost($powerDaysInPort, $container_size, $portCharge);


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
            'storage_minus_one' => $storage_cost_minus_one,
            'power' => $power_cost,
            'power_minus_one' => $power_cost_minus_one,
            'pti_failed' => $portCharge->pti_failed,
            'pti_passed' => $portCharge->pti_passed,
            'container_size' => $container_size
        ];

        foreach ($chargeTypes as $chargeType) {
            $response[$chargeType] = $costs[$chargeType];
        }


        return response()->json($response, 201);
    }

    public function calculateDays($containerId, $storage_from, $blNo)
    {
//        dd($containerId, $storage_from, $blNo);

        $bookingId = Booking::where('ref_no', $blNo)->first()->id;

        $fromMovement = Movements::where('container_id', $containerId)
            ->whereHas('movementcode', fn($q) => $q->where('code', $storage_from))
            ->where('booking_no', $bookingId)->first();

        if ($fromMovement) {
            $toMovement = Movements::where('container_id', $containerId)
                ->whereDate('movement_date', '>', $fromMovement->movement_date)
                ->orderBy('movement_date')
                ->first();
        }

        if ($fromMovement && $toMovement) {
            $fromDate = Carbon::parse($fromMovement->movement_date);
            $toDate = Carbon::parse($toMovement->movement_date);

            return $fromDate->diffInDays($toDate) + 1;
        }

        return 0;
    }

    public function calculateStorageCost($daysInPort, $container_size, $portCharge, $isMinus = false)
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

        if ($isMinus) {
            return $cost;
        }
        $cost_minus_one = $this->calculateStorageCost($daysInPort - 1, $container_size, $portCharge, true);

        return [$cost, $cost_minus_one];
    }

    public function calculatePowerCost($daysInPort, $container_size, $portCharge, $isMinus = false)
    {
        $free_days = $portCharge->power_free;
        $day_20ft = $portCharge->power_20ft;
        $day_40ft = $portCharge->power_40ft;

        $cost = 0;
        if ($daysInPort > $free_days) {
            $daysInSlab1 = $daysInPort - $free_days;
            $cost += $daysInSlab1 * ($container_size === 20 ? $day_20ft : $day_40ft);
        }

        if ($isMinus) {
            return $cost;
        }
        $cost_minus_one = $this->calculatePowerCost($daysInPort - 1, $container_size, $portCharge, true);

        return [$cost, $cost_minus_one];
    }

    public function getMovementDate($containerId, $movementCode, $blNo)
    {
        return Movements::where('container_id', $containerId)
            ->whereHas('movementcode', fn($q) => $q->where('code', $movementCode))
            ->where('bl_no', $blNo)->first()->movement_date;
    }

    public function getRefNo()
    {
        $voyage = request()->input('voyage');
        $container = request()->input('container');
        $containerId = Containers::firstWhere('code', $container)->id;

        $booking = Booking::with(['quotation'])
            ->where(function ($query) use ($voyage, $containerId) {
                $query->where('voyage_id', $voyage)
                    ->orWhere(function ($subquery) use ($voyage, $containerId) {
                        $subquery->where('voyage_id_second', $voyage)
                            ->whereHas('quotation', fn($q) => $q->where('shipment_type', 'Import'));
                    });
            })
            ->whereHas('bookingContainerDetails', function ($q) use ($containerId) {
                $q->where('container_id', $containerId);
            })
            ->latest()
            ->first();

        if ($booking) {
            $quotation = $booking->quotation;
            return response()->json([
                'status' => 'success',
                'ref_no' => $booking->ref_no,
                'is_ts' => $booking->is_transhipment ?? '',
                'shipment_type' => $quotation->shipment_type ?? $booking->shipment_type ?? 'unknown',
                'quotation_type' => $quotation->quotation_type ?? $booking->booking_type ?? 'unkown',
            ], 201);
        }

        return response()->json(['status' => 'failed'], 412);
    }

}

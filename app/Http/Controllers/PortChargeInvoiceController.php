<?php

namespace App\Http\Controllers;

use App\Exports\PortChargeInvoiceExport;
use App\Models\Booking\Booking;
use App\Models\ChargesMatrix;
use App\Models\Master\Containers;
use App\Models\PortChargeInvoice;
use App\Services\PortChargeInvoiceService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PortChargeInvoiceController extends Controller
{
    private PortChargeInvoiceService $invoiceService;

    public function __construct()
    {
        $this->invoiceService = new PortChargeInvoiceService();
    }

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
        $formViewData = $this->invoiceService->getFormViewData();

        return view('port_charge.invoice.create', $formViewData);
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_no' => 'required|unique:port_charge_invoices',
        ]);

        $rows = $this->invoiceService->prepareInvoiceRows(request()->rows);
        $invoiceData = $this->invoiceService->extractInvoiceData(request()->except('_token', 'rows'));

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
        $formViewData = $this->invoiceService->getFormViewData();

        return view('port_charge.invoice.edit', $formViewData)
            ->with([
                'invoice' => $portChargeInvoice,
                'rows' => $portChargeInvoice->rows,
                'selected' => explode(',', $portChargeInvoice->selected_costs),
            ]);
    }

    public function update(PortChargeInvoice $portChargeInvoice)
    {
        $rows = $this->invoiceService->prepareInvoiceRows(request()->rows);
        $invoiceData = $this->invoiceService->extractInvoiceData(request()->except('_token', 'rows'));

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

    public function exportByDateView()
    {
        return view('port_charge.invoice.export-date');
    }

    public function doExportByDate()
    {
        $from = request()->from_date;
        $to = request()->to_date;

        $invoices = PortChargeInvoice::whereBetween('invoice_date', [$from, $to])->get()->load('rows');

        $rows = $invoices->pluck('rows')->collapse()->map(function ($row) {
            $invoice = $row->invoice;
            $booking = Booking::firstWhere('ref_no', $row->bl_no);
            $vesselName = $booking->vessel->name;
            $voyageName = $booking->voyage->voyage_no;
            $invoiceData = [
                'invoice_no' => $invoice->invoice_no,
                'invoice_date' => $invoice->invoice_date,
                'vessel' => $vesselName,
                'voyage' => $voyageName,
                'rate' => $invoice->exchange_rate,
                'port_charge_name' => $row->portCharge->name
            ];
            $rowData = $row->makeHidden([
                'rows',
                'invoice',
                'port_charge',
                'id',
                "port_charge_invoice_id",
                "port_charge_id",
                "created_at",
                "updated_at",
                "storage_days",
                "power_days",
                'pti_type',
            ])->toArray();
            unset($rowData['port_charge']); //for some reason makeHidden did not remove the port_charge :v
            return array_merge($invoiceData, $rowData);
        });

        $sums = $rows->reduce(function ($carry, $row) {
            $carry['total'] = 'Total';
            if (!isset($carry['total_usd'])) {
                $carry['total_usd'] = 0;
            }
            foreach (
                [
                    "thc",
                    "storage",
                    "power",
                    "shifting",
                    "disinf",
                    "hand_fes_em",
                    "gat_lift_off_inbnd_em_ft40",
                    "gat_lift_on_inbnd_em_ft40",
                    "pti",
                    "add_plan"
                ] as $key
            ) {
                if (!isset($carry[$key])) {
                    $carry[$key] = 0;
                }
                $carry[$key] += $row[$key];
                $carry['total_usd'] += $row[$key];
            }
//            dd($carry);
            return $carry;
        });
        $spacer = array_fill(0, 10, ''); // Fill with empty strings
        
        $sums = array_merge($spacer,$sums);
        $rows->push($sums);
        $q = new PortChargeInvoiceExport($rows);
//        dd(array_keys($rows->first()));
        return Excel::download($q, 'asd.xlsx');
    }


    public function calculateInvoiceRow(): \Illuminate\Http\JsonResponse
    {
//        dd(request()->all());
        $blNo = request()->bl_no;
        $chargeType = request()->charge_type;
        $containerNo = request()->container_no;
        $quotationType = request()->quotation_type;
        $chargeMatrix = ChargesMatrix::find($chargeType);
        $storage_from = request()->from ?? $chargeMatrix->storage_from;
        $storage_to = request()->to ?? $chargeMatrix->storage_to;
        $power_from = request()->from ?? $chargeMatrix->power_from;
        $power_to = request()->from ?? $chargeMatrix->power_to;

        $container = Containers::firstWhere('code', $containerNo);
        $containerId = $container->id;
        $container_size = (int)$container->containersTypes->name;
        $portCharge = $chargeMatrix->portCharge;

        $storageDaysInPort = $storage_from === "Select" ?
            0 :
            $this->invoiceService->calculateDays($containerId, $storage_from, $storage_to, $blNo);
        $powerDaysInPort = $storage_from === "Select" ?
            0 :
            $this->invoiceService->calculateDays($containerId, $power_from, $power_to, $blNo);

        [$storage_cost, $storage_cost_minus_one] = $this->invoiceService->calculateStorageCost(
            $storageDaysInPort,
            $container_size,
            $portCharge
        );

        [$power_cost, $power_cost_minus_one] = $quotationType === 'empty' ?
            [0, 0] :
            $this->invoiceService->calculatePowerCost($powerDaysInPort, $container_size, $portCharge);


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

    public function getRefNo(): \Illuminate\Http\JsonResponse
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

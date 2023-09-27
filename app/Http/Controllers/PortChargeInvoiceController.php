<?php

namespace App\Http\Controllers;

use App\Exports\PortChargeInvoiceExport;
use App\Models\Booking\Booking;
use App\Models\ChargesMatrix;
use App\Models\Master\Containers;
use App\Models\PortChargeInvoice;
use App\Models\Voyages\Voyages;
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

    public function index(Request $request)
    {

        $formViewData = $this->invoiceService->getFormViewData();

        $query = PortChargeInvoice::searchQuery($request);
        
        $invoices = $query->with(['voyages.vessel', 'line', 'port', 'country'])->latest()->paginate(20);

        return view('port_charge.invoice.index', $formViewData)
            ->with('invoices', $invoices);
    }


    public function searchJson(Request $request): \Illuminate\Http\JsonResponse
    {
        $invoices = PortChargeInvoice::searchQuery($request)->get()->load('vessels', 'voyages.leg');
        $invoices = $invoices->map(function ($invoice) {
            $invoice->uniqueVessels = $invoice->vessels->unique();
            $invoice->uniqueVoyages = $invoice->voyages->unique();
            return $invoice;
        });
        return response()->json($invoices);
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
        $invoiceData = $this->invoiceService->extractInvoiceData(request()->all());
        $voyages = Voyages::findMany($request->voyage_id);

        $voyageCosts = $request->voyage_costs;
        $portChargeInvoice = PortChargeInvoice::create($invoiceData);
        foreach ($voyages as $voyage) {
            $costs = $voyageCosts[$voyage->id] ?? [];
            $portChargeInvoice->createVoyageCosts($voyage, $costs);
        }

        foreach ($rows as $row) {
            $portChargeInvoice->rows()->create($row);
        }

        return redirect()->route('port-charge-invoices.index');
    }

    public function show(PortChargeInvoice $portChargeInvoice)
    {
        $wordsToRemove = ["power_days", "storage_days", "pti_type"];
        $selectedArray = explode(",", $portChargeInvoice->selected_costs);
        $filteredString = implode(", ", array_diff($selectedArray, $wordsToRemove));

        return view('port_charge.invoice.show', [
            'invoice' => $portChargeInvoice->load('rows'),
            'selected' => explode(',', $portChargeInvoice->selected_costs),
            'selectedCostsString' => $filteredString
        ]);
    }

    public function edit(PortChargeInvoice $portChargeInvoice)
    {
        $formViewData = $this->invoiceService->getFormViewData();
        $voyagesCosts = $portChargeInvoice->portChargeInvoiceVoyages->map(function($voyage){
            $data['id'] = $voyage->voyages_id;
            $data['empty'] = $voyage->empty_costs;
            $data['full'] = $voyage->full_costs;
            return $data;
        });

        return view('port_charge.invoice.edit', $formViewData)
            ->with([
                'invoice' => $portChargeInvoice,
                'rows' => $portChargeInvoice->rows,
                'selected' => explode(',', $portChargeInvoice->selected_costs),
                'voyagesCosts' => $voyagesCosts,
            ]);
    }

    public function update(PortChargeInvoice $portChargeInvoice)
    {
        $rows = $this->invoiceService->prepareInvoiceRows(request()->rows);
        $invoiceData = $this->invoiceService->extractInvoiceData(request()->all());

        $portChargeInvoice->update($invoiceData);
        $portChargeInvoice->rows()->delete();
        $portChargeInvoice->portChargeInvoiceVoyages()->delete();
        
        $voyageCosts = request()->voyage_costs;
        $voyages = Voyages::findMany(request()->voyage_id);
        foreach ($voyages as $voyage) {
            $costs = $voyageCosts[$voyage->id] ?? [];
            $portChargeInvoice->createVoyageCosts($voyage, $costs);
        }

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

    public function doExportInvoice(PortChargeInvoice $invoice)
    {
        $rows = $invoice->rows;

        return Excel::download(new PortChargeInvoiceExport($rows), "invoice_no_{$invoice->invoice_no}.xlsx");
    }

    public function doExportByDate()
    {
        $from = request()->from_date;
        $to = request()->to_date;

        $invoices = PortChargeInvoice::whereBetween('invoice_date', [$from, $to])->get()->load('rows');

        $rows = $invoices->pluck('rows')->collapse();

        if ($rows->isEmpty()) {
            return redirect()->back()->withErrors(['invoices' => 'No invoices found with this date.']);
        }
        return Excel::download(new PortChargeInvoiceExport($rows), "invoice_from_${from}_to_${to}.xlsx");
    }
    public function exportCurrent()
    {
        $query = PortChargeInvoice::searchQuery(request());
        $invoices = $query->get();

        $rows = $invoices->pluck('rows')->collapse();
        $now = now()->toDateString();

        if ($rows->isEmpty()) {
            return redirect()->back()->withErrors(['invoices' => 'No invoices found with this date.']);
        }
        return Excel::download(new PortChargeInvoiceExport($rows), "invoices_export_{$now}.xlsx");
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
        $voyageId = request()->input('voyage');
        $containerCode = request()->input('container');
        $container = Containers::firstWhere('code', $containerCode);
        $containerId = $container->id;
        $containerType = $container->containersTypes->name;

        $booking = Booking::query()->with(['quotation'])
            ->where(function ($q) use ($voyageId) {
                $q->whereIn('voyage_id', $voyageId)
                    ->orWhereIn('voyage_id_second', $voyageId);
            })->whereHas('bookingContainerDetails', fn($q) => $q->where('container_id', $containerId))
            ->latest()->first();

        if ($booking) {
            $quotation = $booking->quotation;
            $voyage = $booking->voyage;
            return response()->json([
                'status' => 'success',
                'ref_no' => $booking->ref_no,
                'is_ts' => $booking->is_transhipment ?? '',
                'shipment_type' => $quotation->shipment_type ?? $booking->shipment_type ?? 'unknown',
                'quotation_type' => $quotation->quotation_type ?? $booking->booking_type ?? 'unknown',
                'container_type' => $containerType ?? 'unknown',
                'voyage_name' => $voyage ? "{$voyage->voyage_no} - {$voyage->leg->name}" : 'unknown',
                'voyage_id' => $voyage->id ?? 'unknown',
            ], 201);
        }

        return response()->json(['status' => 'failed'], 412);
    }

}

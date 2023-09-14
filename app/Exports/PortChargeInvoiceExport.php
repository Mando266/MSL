<?php

namespace App\Exports;

use App\Models\Booking\Booking;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class PortChargeInvoiceExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{


    protected Collection $rows;

    /**
     * @param $rows
     */
    public function __construct($rows)
    {
        $this->rows = $rows;
    }

    public function headings(): array
    {
        return [
            "invoice_no",
            "invoice_date",
            "vessel",
            "voyage",
            "rate",
            "port_charge_name",
            "service",
            "bl_no",
            "container_no",
            "is_transhipment",
            "shipment_type",
            "quotation_type",
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
        ];
    }

    public function collection()
    {
        $rows = $this->rows;
        $rows->transform($this->processRowExport());

        $sums = $rows->reduce($this->calculateSumRowsExport());
        $spacer = array_fill(0, 11, ''); // Fill with empty strings
        $sums = array_merge($spacer, $sums);
        
        $total = array_sum($sums);
        $totalRow = array_merge($spacer, ['TOTAL USD', $total]);
        $rows->push($sums);
        $rows->push(['']);
        $rows->push($totalRow);
        return $rows;
    }


    public function styles(Worksheet $sheet)
    {
        $totalUsdRow = $this->rows->count() + 1;
        $totalsRow = $this->rows->count() - 1;
        
        $totalUsdRange = "L$totalUsdRow:V$totalUsdRow";
        $totalsRange = "L$totalsRow:V$totalsRow";

        $TotalUsdStyle = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
        ];
        $totalsStyle = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->getStyle($totalUsdRange)->applyFromArray($TotalUsdStyle);
        $sheet->getStyle($totalsRange)->applyFromArray($totalsStyle);
        $sheet->mergeCells("M$totalUsdRow:V$totalUsdRow");
    }
    
    public function processRowExport(): \Closure
    {
        return function ($row) {
            $invoice = $row->invoice;
            $booking = Booking::where('ref_no', $row->bl_no)->firstWhere('company_id', auth()->user()->company_id);
            $voyage = $booking->voyage;
            $voyageName = $voyage->voyage_no;
            $vesselName = $voyage->vessel->name;
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
            unset($rowData['port_charge']); // For some reason makeHidden did not remove the port_charge :v
            return array_merge($invoiceData, $rowData);
        };
    }

    public function calculateSumRowsExport(): \Closure
    {
        return function ($carry, $row) {
            $carry['Total'] = 'TOTAL';
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
            }
//            dd($carry);
            return $carry;
        };
    }

}

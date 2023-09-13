<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class PortChargeInvoiceExport implements FromCollection, WithHeadings, ShouldAutoSize
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
        return $this->rows;
    }

}

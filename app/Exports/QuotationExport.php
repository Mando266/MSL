<?php

namespace App\Exports;
use App\Models\Containers\Movements;
use App\Models\Master\Containers;
use App\Models\Master\ContainersMovement;
use App\Models\Master\ContainersTypes;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class QuotationExport implements FromCollection,WithHeadings
{
  
    public function headings(): array
    {
        return [
            "REF NO",
            "AGENT",
            "CUSTOMER",
            "VALIDITY FROM" ,
            "VALIDITY TO",
            "EQUIPMENT TYPE",
            "PLACE OF ACCEPTENCE",
            "PLACE OF DELIVERY" ,
            "LOAD PORT",
            "DISCHARGE PORT",
            "STATUS",
        ];
    }
    

    public function collection()
    {
       
        $quotations = session('quotations');
        
        foreach($quotations as $quotation){
            $quotation->agent_id = optional($quotation->agent)->name;
            $quotation->customer_id = optional($quotation->customer)->name;
            $quotation->equipment_type_id = optional($quotation->equipmentsType)->name;
            $quotation->place_of_acceptence_id = optional($quotation->placeOfAcceptence)->name;
            $quotation->place_of_delivery_id = optional($quotation->placeOfDelivery)->name;
            $quotation->load_port_id = optional($quotation->loadPort)->name;
            $quotation->discharge_port_id = optional($quotation->dischargePort)->name;
        }
        
        return $quotations;
    }
}
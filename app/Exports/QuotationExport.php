<?php

namespace App\Exports;
use App\Models\Quotations\Quotation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class QuotationExport implements FromCollection,WithHeadings
{
  
    public function headings(): array
    {
        return [
            "REF NO",
            "CUSTOMER",
            "VALIDITY FROM" ,
            "VALIDITY TO",
            "EQUIPMENT TYPE",
            "PLACE OF ACCEPTENCE",
            "PLACE OF DELIVERY" ,
            "LOAD PORT",
            "DISCHARGE PORT",
            "Main Line",
            'OFR',
            "STATUS",
        ];
    }


    public function collection()
    {
       
        $quotations = session('quotations');
        //$quotations = Quotation::all();

        foreach($quotations as $quotation){
            $quotation->customer_id = optional($quotation->customer)->name;
            $quotation->equipment_type_id = optional($quotation->equipmentsType)->name;
            $quotation->place_of_acceptence_id = optional($quotation->placeOfAcceptence)->name;
            $quotation->place_of_delivery_id = optional($quotation->placeOfDelivery)->name;
            $quotation->load_port_id = optional($quotation->loadPort)->name;
            $quotation->discharge_port_id = optional($quotation->dischargePort)->name;
            $quotation->principal_name = optional($quotation->principal)->name;
            //dump(optional($quotation->principal)->name);
        }
        
        return $quotations;
    }    
}

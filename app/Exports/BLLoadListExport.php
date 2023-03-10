<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BLLoadListExport implements FromCollection,WithHeadings
{
  
    public function headings(): array
    {
        return [
        "Booking No",
        "Bl No",
        "Shipper Name",
        "FORWARDER",
        "CONSIGNEE",
        "Notify",
        "Load Port",
        "Final Dest",
        "Line",
        "Vessel",
        "Voyage",
        "Container Type",
        "Container No",
        "OFR",
        "Seal No",
        "PACKS",
        "PACKS TYPE",
        "GROSS WEIGHT KGS",
        "NET WEIGHT KGS	",
        "MEASURE CBM",
        "Payment TYPE",
        ];
    }
    

    public function collection() 
    {
        $bldarfts = session('bldarft');
        $exportbls = collect();

        foreach($bldarfts ?? []  as $bldarft){
            foreach($bldarft->blDetails as $blDetail){

                    if($bldarft->bl_status == 1){
                        $bldarft->bl_status = "Confirm";
                    }else{
                        $bldarft->bl_status = "Draft";
                    }        
                    $tempCollection = collect([
                        'Booking No' => optional($bldarft->booking)->ref_no,
                        'Bl No' => $bldarft->ref_no,
                        'Shipper Name' => optional($bldarft->customer)->name,
                        'FORWARDER' => optional($bldarft->booking->forwarder)->name,
                        'CONSIGNEE' => optional($bldarft->booking->consignee)->name,
                        'Notify' => optional($bldarft->customerNotify)->name,
                        'Load Port' => optional($bldarft->loadPort)->code,
                        'Final Dest' => optional($bldarft->dischargePort)->code,
                        'line'=>optional($bldarft->booking->principal)->name,
                        'Vessel' => optional($bldarft->voyage)->vessel->name,
                        'Voyage' => optional($bldarft->voyage)->voyage_no,
                        'Container Type' => optional($bldarft->equipmentsType)->name,
                        'Container No' => optional($blDetail->container)->code,
                        'OFR' => optional($bldarft->booking->quotation)->ofr,
                        'Seal No' => $blDetail->seal_no,
                        'PACKS' => $blDetail->packs,
                        'PACKS TYPE' => $blDetail->pack_type,
                        'CARGO WEIGHT' => $blDetail->gross_weight,
                        'NET WEIGHT' => $blDetail->net_weight,
                        'MEASURE' => $blDetail->measurement,
                        'Payment' => $bldarft->payment_kind,

                    ]);
            $exportbls->add($tempCollection);

        }
    }
            return $exportbls;
    }
}

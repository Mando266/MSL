<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TruckerGateExport implements FromCollection,WithHeadings
{
  
    public function headings(): array
    {
        return [
            "BOOKING REF NO",
            "Certificate Type",
            "Shipper Name",
            "Truker Name",
            "Beneficiary Name",
            "Valid To",
            "Issue Date",
            "Inception Date",
            "Payment Date",
            "No Of Containers",
            "Container Type/Size",
            "Gross Permium",
            "Net Contribution",
            "Branch Name",
            "Shippment Typ",
            "Operator",
            "Release Location",
        ];
    }
    

    public function collection()
    {
       
        $truckergates = session('truckergates');
        $exportTruckergates = collect();
        foreach($truckergates ?? [] as $truckergate){
            $qty = 0;
            foreach($truckergate->booking->bookingContainerDetails as $bookingDetail){
                $qty += $bookingDetail->qty;
            }
            if($truckergate->shipment == 1){
                $truckergate->shipment = "Export";
            }elseif($truckergate->shipment == 2){
                $truckergate->shipment = "Import";
            }else{
                $truckergate->shipment = "Empty Move";
            }
                $tempCollection = collect([
                    'ref_no' => optional($truckergate->booking)->ref_no,
                    'certificate_type' =>$truckergate->certificate_type,
                    'Shipper_name' => optional($truckergate->booking->customer)->name,
                    'Truker_Name'=>optional($truckergate->trucker)->company_name,
                    'Beneficiary Name'=>$truckergate->beneficiry_name,
                    'valid_to'=>$truckergate->valid_to,
                    'issue_date'=>$truckergate->issue_date,
                    'inception_date'=>$truckergate->inception_date,
                    'payment_date'=>$truckergate->payment_date,
                    'qty' => $qty,
                    'Container_Size'=>optional($truckergate->booking->bookingContainerDetails[0]->containerType)->name,
                    'Gross_Permium'=>$truckergate->gross_premium * $qty,
                    'Net_Contribution'=>$truckergate->net_contribution * $qty,
                    'Branch Name'=>optional($truckergate->booking->loadPort)->name,
                    'Shippment'=>$truckergate->shipment,
                    'Operator'=>$truckergate->operator,
                    'Release Location'=>optional($truckergate->booking->pickUpLocation)->name,
                ]);
                $exportTruckergates->add($tempCollection);
        }
        return $exportTruckergates;
    }    
}

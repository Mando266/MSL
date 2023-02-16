<?php

namespace App\Exports;
use App\Models\Quotations\Quotation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BookingExport implements FromCollection,WithHeadings
{
  
    public function headings(): array
    {
        return [
            "QUOTATION NO",
            "BOOKING REF NO",
            "SHIPPER",
            "FORWARDER",
            "Vessel",
            "VOYAGE",
            "Main Line",
            "Vessel Operator",
            "PLACE OF Acceptence",
            "PLACE OF DELIVERY",
            "LOAD PORT",
            "DISCHARGE PORT",
            "Pick Up Location",
            "Place Of Return",
            "EQUIPMENT TYPE",
            "QTY",
            "SOC",
            "IMO",
            "OOG",
            "BOOKING CREATION",
            "BOOKING STATUS",
        ];
    }
    

    public function collection()
    {
       
        $bookings = session('bookings');
        $exportBookings = collect();
        
        foreach($bookings as $booking){
            $qty = 0;
            foreach($booking->bookingContainerDetails as $bookingDetail){
                $qty += $bookingDetail->qty;
            }
            if($booking->bookingContainerDetails->count() > 0){

                $tempCollection = collect([
                    'quotation_ref_no' => optional($booking->quotation)->ref_no,
                    'ref_no' => $booking->ref_no,
                    'customer_name' => optional($booking->customer)->name,
                    'forwarder_name' => optional($booking->forwarder)->name,
                    'vessel' => optional($booking->voyage)->vessel->name,
                    'voyage_id' => optional($booking->voyage)->voyage_no,
                    'main_line' => optional($booking->quotation->principal)->name,
                    'operator' => optional($booking->quotation->operator)->name,
                    'placeOfAcceptence' => optional($booking->placeOfAcceptence)->name,
                    'placeOfDelivery' => optional($booking->placeOfDelivery)->name,
                    'loadPort' => optional($booking->loadPort)->name,
                    'dischargePort' => optional($booking->dischargePort)->name,
                    'pickUpLocation' =>optional($booking->pickUpLocation)->name,
                    'placeOfReturn' =>optional($booking->placeOfReturn)->name,
                    'containerType' => optional($booking->bookingContainerDetails[0]->containerType)->name,
                    'qty' => $qty,
                    'soc' =>  $booking->soc == 1 ? "SOC":"",
                    'imo' =>  $booking->imo == 1 ? "IMO":"",
                    'oog' =>  $booking->oog == 1 ? "OOG":"",
                    'created_at' => $booking->created_at,
                    'booking_confirm' => $booking->booking_confirm == 1 ? "Confirm":"Draft",
                ]);
                $exportBookings->add($tempCollection);
        }
    }

        return $exportBookings;
    }    
}

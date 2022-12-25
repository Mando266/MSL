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
            "FORWARDER" ,
            "PLACE OF ACCEPTENCE",
            "PLACE OF DELIVERY",
            "LOAD PORT",
            "DISCHARGE PORT",
            "EQUIPMENT TYPE" ,
            "QTY",
            "BOOKING CREATION",
            "BOOKING STATUS",
        ];
    }
    

    public function collection()
    {
       
        $bookings = session('bookings');
        $exportBookings = collect();
        $qty = 0;
        foreach($bookings as $booking){
            foreach($booking->bookingContainerDetails as $bookingDetail){
                $qty += $bookingDetail->qty;
            }

                $tempCollection = collect([
                    'quotation_ref_no' => optional($booking->quotation)->ref_no,
                    'ref_no' => $booking->ref_no,
                    'customer_name' => optional($booking->customer)->name,
                    'forwarder_name' => optional($booking->forwarder)->name,
                    'placeOfAcceptence' => optional($booking->placeOfAcceptence)->name,
                    'placeOfDelivery' => optional($booking->placeOfDelivery)->name,
                    'loadPort' => optional($booking->loadPort)->name,
                    'dischargePort' => optional($booking->dischargePort)->name,
                    'containerType' => optional($booking->bookingContainerDetails[0]->containerType)->name,
                    'qty' => $qty,
                    'created_at' => $booking->created_at,
                    'booking_confirm' => $booking->booking_confirm == 1 ? "Confirm":"Draft",
                ]);
                $exportBookings->add($tempCollection);
        }
        
        return $exportBookings;
    }    
}

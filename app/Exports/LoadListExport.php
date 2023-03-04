<?php

namespace App\Exports;

use App\Models\Booking\Booking;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LoadListExport implements FromCollection,WithHeadings
{
  
    public function headings(): array
    {
        return [
        "Booking No",
        "Vesssel",
        "voyage No",
        "Load Port",
        "Final Dest",
        "Line", 
        "Pick Up Location", 
        "Place Of Return",
        "Container No",
        "Container Type",
        "Seal No",
        "VGM",
        "TARE",
        "CARGO WEIGHT",
        "TEMP / VENT / HUMIDTY",
        "Shipper",
        "Consignee",
        "Freight Forwarder",
        "Description",
        "BOOKING STATUS",
        ];
    }
    

    public function collection()
    {
        $bookings = session('bookings');
        $exportBookings = collect();

        foreach($bookings ?? []  as $booking){
            foreach($booking->bookingContainerDetails as $bookingContainerDetail){
                if($booking->bookingContainerDetails->count() > 0){
                    if($booking->booking_confirm == 1){
                        $booking->booking_confirm = "Confirm";
                    }elseif($booking->booking_confirm == 2){
                        $booking->booking_confirm = "Cancelled";
                    }else{
                        $booking->booking_confirm = "Draft";
                    }
                if($bookingContainerDetail->qty == 1){
                    if(optional($bookingContainerDetail->container)->code == null){
                        $containerNo = "Dummy";
                    }else{
                        $containerNo = optional($bookingContainerDetail->container)->code;
                    }
                
                    $tempCollection = collect([
                        'ref_no' => $booking->ref_no,
                        'Vessel' => optional($booking->voyage)->vessel->name,
                        'voyage No' => optional($booking->voyage)->voyage_no,
                        'Load Port' => optional($booking->loadPort)->code,
                        'Final Dest' => optional($booking->dischargePort)->code,
                        'line' => optional($booking->principal)->code,
                        'pickUpLocation' =>optional($booking->pickUpLocation)->name,
                        'placeOfReturn' =>optional($booking->placeOfReturn)->name,    
                        'Container No' => $containerNo,
                        'Container Type' => optional($bookingContainerDetail->containerType)->name,
                        'Seal No' => optional($bookingContainerDetail)->seal_no,
                        'VGM' => optional($bookingContainerDetail)->vgm,
                        'TARE' => optional($bookingContainerDetail->container)->tar_weight,
                        'CARGO WEIGHT' => optional($bookingContainerDetail)->weight,
                        'TEMP' => optional($bookingContainerDetail)->haz,
                        'Shipper' => optional($booking->customer)->name,
                        'Consignee' => optional($booking->consignee)->name,
                        'Forwarder' => optional($booking->forwarder)->name,
                        'Description' => optional($booking)->commodity_description,
                        'booking_confirm' => $booking->booking_confirm,
                    ]);
            $exportBookings->add($tempCollection);

                }elseif($bookingContainerDetail->container == 000 ){
                    for($i=0 ; $i < $bookingContainerDetail->qty ; $i++){
                        $tempCollection = collect([
                            'ref_no' => $booking->ref_no,
                            'Vessel' => optional($booking->voyage)->vessel->name,
                            'voyage No' => optional($booking->voyage)->voyage_no,
                            'Load Port' => optional($booking->loadPort)->code,
                            'Final Dest' => optional($booking->dischargePort)->code,
                            'line' => optional($booking->principal)->code,
                            'pickUpLocation' =>optional($booking->pickUpLocation)->name,
                            'placeOfReturn' =>optional($booking->placeOfReturn)->name,        
                            'Container No' => "Dummy",
                            'Container Type' => optional($bookingContainerDetail->containerType)->name,
                            'Seal No' => optional($bookingContainerDetail)->seal_no,
                            'VGM' => optional($bookingContainerDetail)->vgm,
                            'TARE' => optional($bookingContainerDetail->container)->tar_weight,
                            'CARGO WEIGHT' => optional($bookingContainerDetail)->weight,
                            'TEMP' => optional($bookingContainerDetail)->haz,
                            'Shipper' => optional($booking->customer)->name,
                            'Consignee' => optional($booking->consignee)->name,
                            'Forwarder' => optional($booking->forwarder)->name,
                            'Description' => optional($booking)->commodity_description,
                            'booking_confirm' => $booking->booking_confirm,
                        ]);
                        $exportBookings->add($tempCollection);
                    }
                        
                }
            }
        }
    }
            return $exportBookings;
    }
}

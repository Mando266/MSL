<?php

namespace App\Exports;
use App\Models\Quotations\Quotation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Voyages\VoyagePorts;

class BookingExport implements FromCollection,WithHeadings
{
  
    public function headings(): array
    {
        return [
            "QUOTATION NO",
            "BOOKING REF NO",
            "SHIPPER",
            "FORWARDER",
            "CONSIGNEE",
            "Vessel",
            "VOYAGE",
            "LEG",
            "ETA",
            "Shipment Type",
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
            "OFR",
            "SOC",
            "IMO",
            "OOG",
            "BOOKING CREATION",
            "BOOKING STATUS",
            "Bldraft Status",
            "Assigned",
            "UnAssigned",
        ];
    }
    

    public function collection()
    {
        
        $bookings = session('bookings');
        $exportBookings = collect();
        foreach($bookings ?? [] as $booking){
            $qty = 0;
            $assigned = 0;
            $unassigned = 0;
            $bldraftStatus = '';
            $bookingStatus = '';
            $shipping_status = '';
            if($booking->has_bl == 0){
                $bldraftStatus = 'unissued';
            }elseif(optional($booking->bldraft)->bl_status == 1){
                $bldraftStatus = 'confirm';
            }elseif(optional($booking->bldraft)->bl_status == 0){
                $bldraftStatus = 'draft';
            }
            foreach($booking->bookingContainerDetails as $bookingDetail){
                $qty += $bookingDetail->qty;
                if($bookingDetail->qty == 1 && $bookingDetail->container_id == "000"){
                    $unassigned += 1;
                }elseif($bookingDetail->qty == 1){
                    $assigned += 1;
                }else{
                    $unassigned += $bookingDetail->qty;
                }
            }
            if($booking->bookingContainerDetails->count() > 0){
                if($booking->booking_confirm == 1){
                    $bookingStatus = "Confirm";
                }elseif($booking->booking_confirm == 2){
                    $bookingStatus = "Cancelled";
                }else{
                    $bookingStatus = "Draft";
                }

                if($booking->is_transhipment == 1 && $booking->quotation_id == 0){
                    $shipping_status = 'Transhipment';
                }elseif($booking->is_transhipment == 0 && $booking->quotation_id == 0){
                    $shipping_status = 'Draft';
                }else{
                    $shipping_status = optional($booking->quotation)->shipment_type;
                }
                
                $loadPort = VoyagePorts::where('voyage_id',optional($booking->voyage)->id)->where('port_from_name',optional($booking->loadPort)->id)->first();
                $dischargePort = VoyagePorts::where('voyage_id',optional($booking->voyage)->id)->where('port_from_name',optional($booking->dischargePort)->id)->first();
                $tempCollection = collect([
                    'quotation_ref_no' => optional($booking->quotation)->ref_no,
                    'ref_no' => $booking->ref_no,
                    'customer_name' => optional($booking->customer)->name,
                    'forwarder_name' => optional($booking->forwarder)->name,
                    'consignee_name' => optional($booking->consignee)->name,
                    'vessel' => optional($booking->voyage)->vessel->name,
                    'voyage_id' => optional($booking->voyage)->voyage_no,
                    'leg' => optional($booking->voyage->leg)->name,
                    'eta' => $shipping_status == "Export"? optional($loadPort)->eta : optional($dischargePort)->eta,
                    'shipping_status' => $shipping_status,
                    'main_line' => optional($booking->principal)->name,
                    'operator' => optional($booking->operator)->name,
                    'placeOfAcceptence' => optional($booking->placeOfAcceptence)->name,
                    'placeOfDelivery' => optional($booking->placeOfDelivery)->name,
                    'loadPort' => optional($booking->loadPort)->name,
                    'dischargePort' => optional($booking->dischargePort)->name,
                    'pickUpLocation' =>optional($booking->pickUpLocation)->name,
                    'placeOfReturn' =>optional($booking->placeOfReturn)->name,
                    'containerType' => optional($booking->bookingContainerDetails[0]->containerType)->name,
                    'qty' => $qty,
                    'ofr'=>optional($booking->quotation)->ofr,
                    'soc' =>  $booking->soc == 1 ? "SOC":"",
                    'imo' =>  $booking->imo == 1 ? "IMO":"",
                    'oog' =>  $booking->oog == 1 ? "OOG":"",
                    'created_at' => $booking->created_at,
                    'booking_confirm' => $bookingStatus,
                    'bl_status'=>$bldraftStatus,
                    'assigned' => $assigned,
                    'unassigned' => $unassigned,
                ]);
                $exportBookings->add($tempCollection);
        }
    }

        return $exportBookings;
    }    
}

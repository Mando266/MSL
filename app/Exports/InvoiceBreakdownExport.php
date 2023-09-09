<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Voyages\VoyagePorts;

class InvoiceBreakdownExport implements FromCollection,WithHeadings
{

    public function headings(): array
    {
        return [
            "Invoice No",
            "Customer",
            "TAX NO",
            "BL NO",
            "Voyage",
            "Vessel",
            "ETA",
            "ETD",
            "Date Creation",
            "INVOICE TYPE",
            "PAYMENT KIND",
            "INVOICE STATUS",
            "Payment STATUS",
            "Shipment STATUS",
            "Receipts",
            "Charge Description",
            "Quantity",
            "Amount USD",
            "Total USD",
            "Total EGP",
            "Currency",
        ];
    }


    public function collection()
    {

        $invoices = session('invoice');
        $exportinvoices = collect();

        foreach($invoices  ?? [] as $invoice){

            $Curency = '';
            $Payment = '';

            if($invoice->bldraft_id == 0){
                if($invoice->booking != null){
                    $VoyagePort = VoyagePorts::where('voyage_id',optional($invoice->booking)->voyage_id)
                    ->where('port_from_name',optional($invoice->booking->loadPort)->id)->first();
                }else{
                    $VoyagePort = null;
                }
            }else{
                $VoyagePort = VoyagePorts::where('voyage_id',optional($invoice->bldraft->booking)->voyage_id)
                ->where('port_from_name',optional($invoice->bldraft->booking->loadPort)->id)->first();
            }


            if($invoice->bldraft_id == 0){
                if($invoice->booking != null){
                    $secondVoyagePortdis = VoyagePorts::where('voyage_id',optional($invoice->booking)->voyage_id_second)
                    ->where('port_from_name',optional($invoice->booking->dischargePort)->id)->first();
                }else{
                    $secondVoyagePortdis = null;
                }
            }else{
                $secondVoyagePortdis = VoyagePorts::where('voyage_id',optional($invoice->bldraft->booking)->voyage_id_second)
                ->where('port_from_name',optional($invoice->bldraft->booking->dischargePort)->id)->first();
            }

        if($invoice->invoice_status == 'confirm'){
            if($invoice->add_egp == 'false'){
                $Curency = 'USD';
            }elseif($invoice->add_egp == 'onlyegp'){
                $Curency = 'EGP';
            }
        }
        if($invoice->paymentstauts == '1'){
            $Payment = 'Paid';
        }elseif($invoice->receipts->count() != 0){
            $Payment = 'Partially Paid';
        }else{
            $Payment = 'UnPaid';
        }
        $receipts = '';
        if($invoice->receipts->count() != 0){
            foreach($invoice->receipts as $receipt){
                $receipts .= $receipt->receipt_no . "\n";
            }
        }
            $totalusd = 0;
            $totalegp = 0;
            foreach($invoice->chargeDesc as $invoiceDesc ){
                $totalusd = $totalusd + (float)$invoiceDesc->total_amount;
                $totalegp = $totalegp + (float)$invoiceDesc->total_egy;
            }
            foreach($invoice->chargeDesc as $desc ){
                $tempCollection = collect([
                    'invoice_no' => $invoice->invoice_no,
                    'customer' => $invoice->customer,
                    'tax no' => optional($invoice->customerShipperOrFfw)->tax_card_no,
                    'bl no' => $invoice->bldraft_id == 0 ? optional(optional($invoice->bldraft)->booking)->ref_no :optional($invoice->bldraft)->ref_no,
                    'voyage' => $invoice->bldraft_id == 0 ? optional($invoice->voyage)->voyage_no : optional($invoice->bldraft->voyage)->voyage_no,
                    'vessel' => $invoice->bldraft_id == 0 ? optional(optional($invoice->voyage)->vessel)->name : optional($invoice->bldraft->voyage->vessel)->name,
                    'eta' => optional(optional(optional($invoice->bldraft)->booking)->quotation)->shipment_type == "Import" && optional($invoice->bldraft->booking)->transhipment_port != null ? optional($secondVoyagePortdis)->eta : optional($VoyagePort)->eta,
                    'etd' => optional(optional(optional($invoice->bldraft)->booking)->quotation)->shipment_type == "Import" && optional($invoice->bldraft->booking)->transhipment_port != null ? optional($secondVoyagePortdis)->etd : optional($VoyagePort)->etd,
                    'date' => $invoice->date,
                    'type' => $invoice->type,
                    'payment_kind' => optional($invoice->bldraft)->payment_kind,
                    'STATUS' => $invoice->invoice_status,
                    'PaymentSTATUS' => $Payment,
                    'ShipmentStatus' => $invoice->bldraft_id == 0 ? $invoice->booking_status : optional(optional(optional($invoice->bldraft)->booking)->quotation)->shipment_type ,
                    'receipts' => $receipts,
                    'charge_desc' => $desc->charge_description,
                    'qty' => $invoice->qty,
                    'amount_usd' => $desc->size_small,
                    'total usd' => $desc->total_amount,
                    'total egp' => $desc->total_egy,
                    'Curency' =>$Curency,
                ]);

                $exportinvoices->add($tempCollection);
            }
        }

        return $exportinvoices;
    }
}

<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InvoiceListExport implements FromCollection,WithHeadings
{
  
    public function headings(): array
    {
        return [
            "Invoice No",
            "Customer",
            "TAX NO",
            "BL NO",
            "DATE",
            "INVOICE TYPE",
            "PAYMENT KIND",
            "TOTAL USD",
            "TOTAL EGP",
            "INVOICE STATUS",
        ];
    }
    

    public function collection()
    {
       
        $invoices = session('invoice');
        $exportinvoices = collect();

        foreach($invoices  ?? [] as $invoice){
            $totalusd = 0;
            $totalegp = 0;
            foreach($invoice->chargeDesc as $invoiceDesc ){
                $totalusd = $totalusd + (float)$invoiceDesc->total_amount;
                $totalegp = $totalegp + (float)$invoiceDesc->total_egy;
            }
                $tempCollection = collect([
                    'invoice_no' => $invoice->invoice_no,
                    'customer' => $invoice->customer,
                    'tax no' => optional($invoice->customerShipperOrFfw)->tax_card_no,
                    'bl no' => optional($invoice->bldraft)->ref_no,
                    'date' => $invoice->date,
                    'type' => $invoice->type,
                    'payment_kind' => optional($invoice->bldraft)->payment_kind,
                    'total usd' => $totalusd,
                    'total egp' => $totalegp,
                    'STATUS' => $invoice->invoice_status,
                ]);
                $exportinvoices->add($tempCollection);
        }
        
        return $exportinvoices;
    }    
}

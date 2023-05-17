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
            "Quantity",
            "Charge Description",
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
                    'qty' => $invoice->qty,
                    'charge_desc' => $desc->charge_description,
                    'amount_usd' => $desc->size_small,
                    'total usd' => $totalusd,
                    'total egp' => $totalegp,
                    'Curency' =>$Curency,
                ]);
                
                $exportinvoices->add($tempCollection);
            }
        }
        
        return $exportinvoices;
    }    
}

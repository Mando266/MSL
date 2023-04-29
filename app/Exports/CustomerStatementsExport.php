<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerStatementsExport implements FromCollection,WithHeadings
{
  
    public function headings(): array
    {
        return [
            "Customer",
            "Invoice NO",
            "Date Of Invoice",
            "Invoice Amount USD",
            "Invoice Amount EGP",
            "BL NO",
            "Receipt No",
            "Receipt Amount",
            "Balance",
            "Vessel",
            "Voyage",
            "Receipt Date",
        ];
    }
    

    public function collection()
    {
        
        $invoices = session('customerStatement');
        $exporRefunds = session('exporRefunds');
        $exportinvoices = collect();

        foreach($invoices  ?? [] as $invoice){
            $Curency = '';

        if($invoice->invoice_status == 'confirm'){
            if($invoice->add_egp == 'false'){
                $Curency = 'USD';
            }elseif($invoice->add_egp == 'onlyegp'){
                $Curency = 'EGP';
            }
        }

        $receipts = '';
        // $refunds = '';
        $totalreceipt = 0;
        $totalrefund = 0;
        $receiptDate = '';
        if($invoice->receipts->count() != 0){
            foreach($invoice->receipts as $receipt){
                $receiptDate = $receipt->created_at ;
                if($receipt->status == "valid"){
                    $receipts .= $receipt->receipt_no . "\n";
                    $totalreceipt += $receipt->paid;
                }
                // else{
                //     $refunds .= $receipt->receipt_no . "\n";
                //     $totalrefund += $receipt->paid;
                // }
            }
        }
            $totalusd = 0;
            $totalegp = 0;
            
            foreach($invoice->chargeDesc as $invoiceDesc ){
                $totalusd = $totalusd + (float)$invoiceDesc->total_amount;
                $totalegp = $totalegp + (float)$invoiceDesc->total_egy;
            }
            if($invoice->add_egp == "false"){
                $totalegp = 0;
            }
            if($invoice->add_egp == "onlyegp"){
                $totalusd = 0;
            }
                $tempCollection = collect([
                    'customer' => $invoice->customer,
                    'invoice_no' => $invoice->invoice_no,
                    'date' => $invoice->date,
                    'invoice_amount' => $totalusd,
                    'invoice_amount_egp' => $totalegp,
                    'bl no' => optional($invoice->bldraft)->ref_no,
                    'receipts' => $receipts,
                    'receipt_amount' => $totalreceipt,
                    'balance' =>  $Curency == 'USD' ? optional($invoice->customerShipperOrFfw)->credit : optional($invoice->customerShipperOrFfw)->credit_egp,
                    'vessel' => $invoice->bldraft_id == 0 ? optional(optional($invoice->voyage)->vessel)->name : optional($invoice->bldraft->voyage->vessel)->name,
                    'voyage' => $invoice->bldraft_id == 0 ? optional($invoice->voyage)->voyage_no : optional($invoice->bldraft->voyage)->voyage_no,
                    'created_at' => $receiptDate
                ]);
                
                $exportinvoices->add($tempCollection);
        }
        foreach($exporRefunds as $exporRefund){
            $tempCollection = collect([
                'customer' => optional($exporRefund->customer)->name,
                'invoice_no' => '',
                'date' => '',
                'invoice_amount' => '',
                'invoice_amount_egp' => '',
                'bl no' => '',
                'receipts' => $exporRefund->receipt_no,
                'receipt_amount' => $exporRefund->paid,
                'balance' =>  '',
                'vessel' => '',
                'voyage' => '',
                'created_at' => $exporRefund->created_at
            ]);
            
            $exportinvoices->add($tempCollection);
        }
        
        return $exportinvoices;
    }    
}

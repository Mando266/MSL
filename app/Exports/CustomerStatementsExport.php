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
            "Type",
            "Ref No",
            "Date",
            "Amount USD",
            "Amount EGP",
            "BL NO",
            "Receipt No",
            "Receipt Amount EGP",
            "Receipt Amount USD",
            "Balance USD",
            "Balance EGP",
            "Vessel / Voyage",
            "Receipt Date",
        ];
    }

    public function collection()
    {
        
        $customers = session('statements');
        $exportinvoices = collect();

        foreach($customers  ?? [] as $customer){
            foreach($customer->invoices as $invoice){
                $totalusd = null;
                $totalegp = null;
                $receipts = null;
                $totalreceipt = null;
                $blanceEgp = null;
                $blanceUSD = null; 
                if($invoice->receipts->count() != 0){
                    foreach($invoice->receipts as $receipt){
                        $receipts .= $receipt->receipt_no . "\n";
                        $totalreceipt += $receipt->paid;
                    }   
                }
                foreach($invoice->chargeDesc as $invoiceDesc ){
                    $totalusd = $totalusd + (float)$invoiceDesc->total_amount;
                    $totalegp = $totalegp + (float)$invoiceDesc->total_egy;
                }
                    $blanceEgp = $totalreceipt - $totalegp;
                    $blanceUSD = $totalreceipt - $totalusd;
                if($invoice->receipts->count() != 0){
                    foreach($invoice->receipts as $receipt){
                        $tempCollection = collect([
                            'customer' => $customer->name,
                            'type' => optional($invoice)->type,
                            'invoice_no' => optional($invoice)->invoice_no,
                            'date' => optional($invoice)->date,
                            'invoice_amount' => $invoice->add_egp != 'onlyegp' ? $totalusd : 0,
                            'invoice_amount_egp' => ($invoice->add_egp == 'true' || $invoice->add_egp == 'onlyegp') ? $totalegp : 0,
                            'bl no' => $invoice->bldraft_id == 0 ?  'Customize' : optional($invoice->bldraft)->ref_no,
                            'receipts' => $receipt->receipt_no,
                            'receipt_amount_egp' => ($invoice->add_egp == 'true' || $invoice->add_egp == 'onlyegp') ? $totalreceipt:0,
                            'receipt_amount_usd' => $invoice->add_egp != 'onlyegp' ? $totalreceipt : 0,
                            'balance_usd' =>  $invoice->add_egp != 'onlyegp' ? $blanceUSD : 0,
                            'balance_egp' =>  ($invoice->add_egp == 'true' || $invoice->add_egp == 'onlyegp') ? $blanceEgp : 0,
                            'vessel-voyage' => ($invoice->bldraft_id == 0 ? optional(optional($invoice->voyage)->vessel)->name : optional($invoice->bldraft->voyage->vessel)->name) .' / '. ($invoice->bldraft_id == 0 ? optional($invoice->voyage)->voyage_no : optional($invoice->bldraft->voyage)->voyage_no),
                            'created_at' => $receipt->created_at,
                        ]);
                        
                        $exportinvoices->add($tempCollection);
                    }
                }else{
                    $tempCollection = collect([
                        'customer' => $customer->name,
                        'type' => optional($invoice)->type,
                        'invoice_no' => optional($invoice)->invoice_no,
                        'date' => optional($invoice)->date,
                        'invoice_amount' => $invoice->add_egp != 'onlyegp' ? $totalusd : 0,
                        'invoice_amount_egp' => ($invoice->add_egp == 'true' || $invoice->add_egp == 'onlyegp') ? $totalegp : 0,
                        'bl no' => $invoice->bldraft_id == 0 ?  'Customize' : optional($invoice->bldraft)->ref_no,
                        'receipts' => '',
                        'receipt_amount_egp' => ($invoice->add_egp == 'true' || $invoice->add_egp == 'onlyegp') ? $totalreceipt:0,
                        'receipt_amount_usd' => $invoice->add_egp != 'onlyegp' ? $totalreceipt : 0,
                        'balance_usd' =>  $invoice->add_egp != 'onlyegp' ? $blanceUSD : 0,
                        'balance_egp' =>  ($invoice->add_egp == 'true' || $invoice->add_egp == 'onlyegp') ? $blanceEgp : 0,
                        'vessel-voyage' => ($invoice->bldraft_id == 0 ? optional(optional($invoice->voyage)->vessel)->name : optional($invoice->bldraft->voyage->vessel)->name) .' / '. ($invoice->bldraft_id == 0 ? optional($invoice->voyage)->voyage_no : optional($invoice->bldraft->voyage)->voyage_no),
                        'created_at' => ''
                    ]);
                    
                    $exportinvoices->add($tempCollection);
                }
                    
            }

            foreach($customer->creditNotes as $creditNote){
                $tempCollection = collect([
                    'customer' => $customer->name,
                    'type' => "Credit Note",
                    'invoice_no' => optional($creditNote)->credit_no,
                    'date' => optional($creditNote)->created_at,
                    'invoice_amount' => $creditNote->currency == "credit_usd" ? optional($creditNote)->total_amount : 0,
                    'invoice_amount_egp' => $creditNote->currency == "credit_egp" ? optional($creditNote)->total_amount : 0,
                    'bl no' => $creditNote->bl_no??'',
                    'receipts' => '',
                    'receipt_amount_egp' => 0,
                    'receipt_amount_usd' => 0,
                    'balance_usd' =>  0,
                    'balance_egp' =>  0,
                    'vessel-voyage' => '',
                    'created_at' => '',
                ]);
                
                $exportinvoices->add($tempCollection);
            }

            foreach($customer->refunds as $refund){
                $tempCollection = collect([
                    'customer' => $customer->name,
                    'type' => "Refund",
                    'invoice_no' => optional($refund)->receipt_no,
                    'date' => optional($refund)->created_at,
                    'invoice_amount' => $refund->status == "refund_usd" ? '-'.optional($refund)->paid : 0,
                    'invoice_amount_egp' => $refund->status == "refund_egp" ? '-'.optional($refund)->paid : 0,
                    'bl no' => '',
                    'receipts' => '',
                    'receipt_amount_egp' => 0,
                    'receipt_amount_usd' => 0,
                    'balance_usd' =>  0,
                    'balance_egp' =>  0,
                    'vessel-voyage' => '',
                    'created_at' => '',
                ]);
                
                $exportinvoices->add($tempCollection);
            }
            
        }

        return $exportinvoices;
    }    
}

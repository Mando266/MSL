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
            "Receipt Amount USD",
            "Receipt Amount EGP",
            "Tax Hold USD",
            "Tax Hold EGP",
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
            $total_invoice_amount = 0;
            $total_invoice_amount_egp = 0;
            $total_receipt_amount_usd = 0;
            $total_receipt_amount_egp = 0;
            $total_balance_usd = 0;
            $total_balance_egp = 0;
            $total_tax_hold_usd = 0;
            $total_tax_hold_egp = 0;
            foreach($customer->invoices as $invoice){
                $tax_hold_usd = 0;
                $tax_hold_egp = 0;
                $matching_usd = 0;
                $matching_egp = 0;
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
                if($invoice->add_egp != 'onlyegp'){
                    $tax_hold_usd = $totalusd * ($invoice->tax_discount/100);
                    $total_tax_hold_usd += $tax_hold_usd;
                }elseif($invoice->add_egp == 'true' || $invoice->add_egp == 'onlyegp'){
                    $tax_hold_egp = $totalusd * ($invoice->tax_discount/100);
                    $total_tax_hold_egp += $tax_hold_egp;
                }
                if($invoice->add_egp != 'onlyegp'){
                    $matching_usd = $invoice->matching;
                }elseif($invoice->add_egp == 'true' || $invoice->add_egp == 'onlyegp'){
                    $matching_egp = $invoice->matching;
                }
                    $blanceUSD = ($totalreceipt + $tax_hold_usd) - $totalusd;
                    $blanceEgp = ($totalreceipt + $tax_hold_egp) - $totalegp;
                    // dump($blanceUSD,$blanceEgp);
                    
                    //calculating total line for each customer
                    if($invoice->add_egp != 'onlyegp'){
                        $total_invoice_amount += $totalusd;
                    }elseif($invoice->add_egp == 'true' || $invoice->add_egp == 'onlyegp'){
                        $total_invoice_amount_egp += $totalegp;
                    }
                    if($invoice->add_egp != 'onlyegp'){
                        $total_receipt_amount_usd += $totalreceipt;
                    }elseif($invoice->add_egp == 'true' || $invoice->add_egp == 'onlyegp'){
                        $total_receipt_amount_egp += $totalreceipt;
                    }
                    
                if($invoice->receipts->count() != 0){
                    foreach($invoice->receipts as $receipt){
                        if($invoice->add_egp != 'onlyegp'){
                            $matching_usd = $receipt->matching;
                            $blanceUSD += $matching_usd;
                        }elseif($invoice->add_egp == 'true' || $invoice->add_egp == 'onlyegp'){
                            $matching_egp = $receipt->matching;
                            $blanceEgp += $matching_egp;
                        }
                        $tempCollection = collect([
                            'customer' => $customer->name,
                            'type' => optional($invoice)->type,
                            'invoice_no' => optional($invoice)->invoice_no,
                            'date' => optional($invoice)->date,
                            'invoice_amount' => $invoice->add_egp != 'onlyegp' ? $totalusd : '0',
                            'invoice_amount_egp' => ($invoice->add_egp == 'true' || $invoice->add_egp == 'onlyegp') ? $totalegp : '0',
                            'bl no' => $invoice->bldraft_id == 0 ?  'Customize' : optional($invoice->bldraft)->ref_no,
                            'receipts' => $receipt->receipt_no,
                            'receipt_amount_usd' => $invoice->add_egp != 'onlyegp' ? $totalreceipt : '0',
                            'receipt_amount_egp' => ($invoice->add_egp == 'true' || $invoice->add_egp == 'onlyegp') ? $totalreceipt:'0',
                            'tax_hold_usd' => $invoice->add_egp != 'onlyegp'? ($tax_hold_usd == 0? '0':$tax_hold_usd) : '0',
                            'tax_hold_egp' => ($invoice->add_egp == 'true' || $invoice->add_egp == 'onlyegp') ? ($tax_hold_egp == 0? '0':$tax_hold_egp) : '0',
                            'balance_usd' =>  $invoice->add_egp != 'onlyegp' ? ($blanceUSD == 0 ? '0' : $blanceUSD) : '0',
                            'balance_egp' =>  ($invoice->add_egp == 'true' || $invoice->add_egp == 'onlyegp') ? ($blanceEgp == 0 ? '0' : $blanceEgp) : '0',
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
                        'invoice_amount' => $invoice->add_egp != 'onlyegp' ? $totalusd : '0',
                        'invoice_amount_egp' => ($invoice->add_egp == 'true' || $invoice->add_egp == 'onlyegp') ? $totalegp : '0',
                        'bl no' => $invoice->bldraft_id == 0 ?  'Customize' : optional($invoice->bldraft)->ref_no,
                        'receipts' => '',
                        'receipt_amount_usd' => $invoice->add_egp != 'onlyegp' ? $totalreceipt : '0',
                        'receipt_amount_egp' => ($invoice->add_egp == 'true' || $invoice->add_egp == 'onlyegp') ? $totalreceipt:'0',
                        'tax_hold_usd' => $invoice->add_egp != 'onlyegp'? ($tax_hold_usd == 0? '0':$tax_hold_usd) : '0',
                        'tax_hold_egp' => ($invoice->add_egp == 'true' || $invoice->add_egp == 'onlyegp') ? ($tax_hold_egp == 0? '0':$tax_hold_egp) : '0',
                        'balance_usd' =>  $invoice->add_egp != 'onlyegp' ? ($blanceUSD == 0 ? '0' : $blanceUSD) : '0',
                        'balance_egp' =>  ($invoice->add_egp == 'true' || $invoice->add_egp == 'onlyegp') ? ($blanceEgp == 0 ? '0' : $blanceEgp) : '0',
                        'vessel-voyage' => ($invoice->bldraft_id == 0 ? optional(optional($invoice->voyage)->vessel)->name : optional($invoice->bldraft->voyage->vessel)->name) .' / '. ($invoice->bldraft_id == 0 ? optional($invoice->voyage)->voyage_no : optional($invoice->bldraft->voyage)->voyage_no),
                        'created_at' => ''
                    ]);
                    
                    $exportinvoices->add($tempCollection);
                }
                if($invoice->add_egp != 'onlyegp'){
                    $total_balance_usd += $blanceUSD;
                }elseif($invoice->add_egp == 'true' || $invoice->add_egp == 'onlyegp'){
                    $total_balance_egp += $blanceEgp;
                }
                    
            }

            foreach($customer->creditNotes as $creditNote){
                if($creditNote->currency == "credit_usd"){
                    $total_invoice_amount += optional($creditNote)->total_amount;
                }elseif($creditNote->currency == "credit_egp"){
                    $total_invoice_amount_egp += optional($creditNote)->total_amount;
                }
                $tempCollection = collect([
                    'customer' => $customer->name,
                    'type' => "Credit Note",
                    'invoice_no' => optional($creditNote)->credit_no,
                    'date' => optional($creditNote)->created_at,
                    'invoice_amount' => $creditNote->currency == "credit_usd" ? optional($creditNote)->total_amount : '0',
                    'invoice_amount_egp' => $creditNote->currency == "credit_egp" ? optional($creditNote)->total_amount : '0',
                    'bl no' => $creditNote->bl_no??'',
                    'receipts' => '',
                    'receipt_amount_usd' => '0',
                    'receipt_amount_egp' => '0',
                    'tax_hold_usd' => '0',
                    'tax_hold_egp' => '0',
                    'balance_usd' =>  '0',
                    'balance_egp' =>  '0',
                    'vessel-voyage' => '',
                    'created_at' => '',
                ]);
                
                $exportinvoices->add($tempCollection);
            }

            foreach($customer->refunds as $refund){
                if($refund->status == "refund_usd"){
                    $total_invoice_amount -= optional($refund)->paid;
                }elseif($refund->status == "refund_egp"){
                    $total_invoice_amount_egp -= optional($refund)->paid;
                }
                $tempCollection = collect([
                    'customer' => $customer->name,
                    'type' => "Refund",
                    'invoice_no' => optional($refund)->receipt_no,
                    'date' => optional($refund)->created_at,
                    'invoice_amount' => $refund->status == "refund_usd" ? '-'.optional($refund)->paid : '0',
                    'invoice_amount_egp' => $refund->status == "refund_egp" ? '-'.optional($refund)->paid : '0',
                    'bl no' => '',
                    'receipts' => '',
                    'receipt_amount_usd' => '0',
                    'receipt_amount_egp' => '0',
                    'tax_hold_usd' => '0',
                    'tax_hold_egp' => '0',
                    'balance_usd' =>  '0',
                    'balance_egp' =>  '0',
                    'vessel-voyage' => '',
                    'created_at' => '',
                ]);
                
                $exportinvoices->add($tempCollection);
            }

            //total calculations
            $tempCollection = collect([
                'customer' => '',
                'type' =>  '',
                'invoice_no' => '',
                'date' => 'Total',
                'invoice_amount' => $total_invoice_amount == 0 ? '0':$total_invoice_amount,
                'invoice_amount_egp' => $total_invoice_amount_egp == 0 ? '0':$total_invoice_amount_egp,
                'bl no' => '',
                'receipts' => '',
                'receipt_amount_usd' => $total_receipt_amount_usd == 0 ? '0':$total_receipt_amount_usd,
                'receipt_amount_egp' => $total_receipt_amount_egp == 0 ? '0':$total_receipt_amount_egp,
                'tax_hold_usd' => $total_tax_hold_usd == 0 ? '0':$total_tax_hold_usd,
                'tax_hold_egp' => $total_tax_hold_egp == 0 ? '0':$total_tax_hold_egp,
                'balance_usd' =>  $total_balance_usd == 0 ? '0':$total_balance_usd,
                'balance_egp' =>  $total_balance_egp == 0 ? '0':$total_balance_egp,
                'vessel-voyage' => '',
                'created_at' => '',
            ]);
            
            $exportinvoices->add($tempCollection);
            
        }

        return $exportinvoices;
    }    
}

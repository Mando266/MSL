<?php

namespace App\Filters\Invoice;
use App\Filters\AbstractFilter;
use App\Filters\Invoice\TypeFilter;

class InvoiceIndexFilter extends AbstractFilter{
    protected $filters = [
        'type'=>TypeFilter::class,
        'bldraft_id'=>BlDraftFilter::class,
        'customer_id'=>CustomerFilter::class,
        'invoice_no'=>InvoiceNoFilter::class,
        'payment_kind'=>PaymentFilter::class,
    ];
}

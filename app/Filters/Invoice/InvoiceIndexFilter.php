<?php

namespace App\Filters\Invoice;
use App\Filters\AbstractFilter;
use App\Filters\Invoice\TypeFilter;

class InvoiceIndexFilter extends AbstractFilter{
    protected $filters = [
        'type'=>TypeFilter::class,

    ];
}

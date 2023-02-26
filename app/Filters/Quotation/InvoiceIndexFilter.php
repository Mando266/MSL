<?php

namespace App\Filters\Quotation;
use App\Filters\AbstractFilter;
use App\Filters\Invoice\TypeFilter;

class InvoiceIndexFilter extends AbstractFilter{
    protected $filters = [
        'type'=>TypeFilter::class,

    ];
}

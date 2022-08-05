<?php

namespace App\Filters\Quotation;

use App\Filters\AbstractFilter;

class QuotationIndexFilter extends AbstractFilter{
    protected $filters = [
        'status'=>StatusFilter::class,
        'ref_no'=>RefNoFilter::class,
    ];
}

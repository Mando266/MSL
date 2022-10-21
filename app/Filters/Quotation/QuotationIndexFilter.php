<?php

namespace App\Filters\Quotation;

use App\Filters\AbstractFilter;

class QuotationIndexFilter extends AbstractFilter{
    protected $filters = [
        'status'=>StatusFilter::class,
        'ref_no'=>RefNoFilter::class,
        'triff_no'=>TriffPortNoFilter::class,
        'validity_from'=>ValidityFromFilter::class,
        'validity_to'=>ValidityToFilter::class,
        'customer_id'=>CustomerFilter::class,
        'place_of_acceptence_id'=>POLFilter::class,
        'place_of_delivery_id'=>PODFilter::class,
    ];
}

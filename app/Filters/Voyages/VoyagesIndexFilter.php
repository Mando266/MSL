<?php

namespace App\Filters\Voyages;

use App\Filters\AbstractFilter;

class VoyagesIndexFilter extends AbstractFilter{
    protected $filters = [
        'vessel_id'=>VesselFilter::class,
        'voyage_no'=>VoyageFilter::class,
    ];
}

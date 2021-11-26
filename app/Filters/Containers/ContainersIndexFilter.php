<?php

namespace App\Filters\Containers;

use App\Filters\AbstractFilter;

class ContainersIndexFilter extends AbstractFilter{
    protected $filters = [
        'code'=>ContainerNoFilter::class,
        'bl_no'=>BLNoFilter::class,
        'booking_no'=>BookingNoFilter::class,
    ];
}

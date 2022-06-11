<?php

namespace App\Filters\Containers;

use App\Filters\AbstractFilter;

class ContainersIndexFilter extends AbstractFilter{
    protected $filters = [
        'container_id'=>ContainerIdFilter::class,
        'code'=>ContainerNoFilter::class,
        'bl_no'=>BLNoFilter::class,
        'booking_no'=>BookingNoFilter::class,
        'movement_date'=>MovementDateFilter::class,
    ];
}

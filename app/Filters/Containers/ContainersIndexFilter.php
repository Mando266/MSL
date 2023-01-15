<?php

namespace App\Filters\Containers;

use App\Filters\AbstractFilter;

class ContainersIndexFilter extends AbstractFilter{
    protected $filters = [
        'is_storge'=>IsStorageFilter::class,
        'country_id'=>CountryFilter::class,
        'container_id'=>ContainerIdFilter::class,
        'code'=>ContainerNoFilter::class,
        'bl_no'=>BLNoFilter::class,
        'booking_no'=>BookingNoFilter::class,
        'movement_date'=>MovementDateFilter::class,
        'port_location_id'=>PortLocaionFilter::class,
        'voyage_id'=>VoayegeNoFilter::class,
        'movement_id'=>MovementIDFilter::class,
        'remarkes'=>RemarkesFilter::class,
        'description'=>DescriptionFilter::class,
        'id'=>IDFilter::class,
        'container_type_id'=>ContainerTypeFilter::class,
        'container_ownership_id'=>ContainerOwnershipFilter::class,
        'container_status'=>ContainerStatusFilter::class,
    ];
}

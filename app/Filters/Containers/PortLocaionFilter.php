<?php
namespace App\Filters\Containers;

use App\Filters\AbstractBasicFilter;

class PortLocaionFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('port_location_id',$value);
    }
}

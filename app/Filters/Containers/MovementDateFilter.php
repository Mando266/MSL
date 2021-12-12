<?php
namespace App\Filters\Containers;

use App\Filters\AbstractBasicFilter;

class MovementDateFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('movement_date',$value);
    }
}

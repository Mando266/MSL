<?php
namespace App\Filters\Containers;

use App\Filters\AbstractBasicFilter;

class MovementIDFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('movement_id',$value);
    }
}

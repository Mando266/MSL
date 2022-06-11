<?php
namespace App\Filters\Containers;

use App\Filters\AbstractBasicFilter;

class ContainerIdFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('container_id',$value);
    }
}

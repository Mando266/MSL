<?php
namespace App\Filters\Quotation;

use App\Filters\AbstractBasicFilter;

class ContainerFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->whereHas('container_id',$value);
    }
}

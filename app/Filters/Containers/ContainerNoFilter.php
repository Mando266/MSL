<?php
namespace App\Filters\Containers;

use App\Filters\AbstractBasicFilter;

class ContainerNoFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('code',$value);
    }
}

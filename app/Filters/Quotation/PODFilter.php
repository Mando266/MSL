<?php
namespace App\Filters\Quotation;

use App\Filters\AbstractBasicFilter;

class PODFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('place_of_delivery_id',$value);
    }
}

<?php
namespace App\Filters\Quotation;

use App\Filters\AbstractBasicFilter;

class POLFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('place_of_acceptence_id',$value);
    }
}

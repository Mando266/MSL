<?php
namespace App\Filters\Quotation;

use App\Filters\AbstractBasicFilter;

class BothVoyageFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('voyage_id_second',$value)->orwhere('voyage_id',$value);
    }
}

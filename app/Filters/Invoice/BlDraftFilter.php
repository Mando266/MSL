<?php

namespace App\Filters\Invoice;
use App\Filters\AbstractBasicFilter;

class BlDraftFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('bldraft_id',$value);
    }
}

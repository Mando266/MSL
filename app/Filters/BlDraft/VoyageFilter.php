<?php
namespace App\Filters\BlDraft;

use App\Filters\AbstractBasicFilter;

class VoyageFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('voyage_id',$value);
    }
}

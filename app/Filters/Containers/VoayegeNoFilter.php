<?php
namespace App\Filters\Containers;

use App\Filters\AbstractBasicFilter;

class VoayegeNoFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('voyage_id',$value);
    }
}

<?php
namespace App\Filters\Containers;

use App\Filters\AbstractBasicFilter;

class RemarkesFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('remarkes','like',"%{$value}%");
    }
}

<?php
namespace App\Filters\ManifestXml;

use App\Filters\AbstractBasicFilter;

class VoyageFilter extends AbstractBasicFilter{
    public function filter($value)
    {
        return $this->builder->Where('voyage_id',$value);
    }
}

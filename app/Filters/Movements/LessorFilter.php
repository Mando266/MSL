<?php

namespace App\Filters\Movements;

use App\Filters\AbstractBasicFilter;

class LessorFilter extends AbstractBasicFilter
{
    public function filter($value)
    {
        $lessor_id = (int) auth()->user()->lessor_id;
        if ($lessor_id != 0) {
            return $this->builder->whereHas('container', function ($q) use ($lessor_id) {
                $q->where('description', $lessor_id);
            });
        }
        return $this->builder->whereHas('container', function ($q) use ($value) {
            $q->where('description', $value);
        });
    }
}
 
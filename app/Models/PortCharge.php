<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortCharge extends Model
{
    protected $guarded = [];

    public function chargeMatrix()
    {
        return $this->belongsTo(ChargesMatrix::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortCharge extends Model
{
    protected $guarded = [];

    protected $appends = ['name'];
    public function chargeMatrix()
    {
        return $this->belongsTo(ChargesMatrix::class);
    }
    
    public function getNameAttribute()
    {
        return $this->chargeMatrix->name;
    }
}

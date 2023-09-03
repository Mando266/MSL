<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortChargeInvoiceRow extends Model
{
    protected $guarded = [];
    
    protected $with = ['portCharge'];

    public function invoice(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PortChargeInvoice::class);
    }

    public function portCharge(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PortCharge::class);
    }
}

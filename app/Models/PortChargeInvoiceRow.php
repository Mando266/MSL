<?php

namespace App\Models;

use App\Models\Booking\Booking;
use App\Models\Master\Containers;
use Illuminate\Database\Eloquent\Model;

class PortChargeInvoiceRow extends Model
{
    protected $guarded = [];

//    protected $with = ['portCharge', 'invoice'];

    public function invoice(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PortChargeInvoice::class, 'port_charge_invoice_id');
    }

    public function portCharge(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PortCharge::class);
    }

    public function container(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Containers::class, 'container_no', 'code');
    }

    public function booking(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Booking::class, 'bl_no', 'ref_no');
    }

    public function getServiceAttribute($value)
    {
        return $value === 'Select' ?
            '' :
            $value;
    }
}

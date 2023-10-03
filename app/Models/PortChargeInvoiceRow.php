<?php

namespace App\Models;

use App\Models\Booking\Booking;
use App\Models\Master\Containers;
use App\Models\Voyages\Voyages;
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
    
    public function voyage()
    {
        return $this->booking->voyage();
    }

    public function voyagePorts()
    {
        return $this->voyage->voyagePorts();
    }
    
    public function vessel()
    {
        return $this->booking->voyage->vessel();
    }

    public function getEtaAttribute()
    {
        return optional($this->voyagePorts->firstWhere('port_from_name', $this->invoice->port_id))->eta;
    }

    public function getServiceAttribute($value)
    {
        return $value === 'Select' ?
            '' :
            $value;
    }

    public function totalCosts()
    {
        return $this->thc +
            $this->storage +
            $this->power +
            $this->shifting +
            $this->disinf +
            $this->hand_fes_em +
            $this->gat_lift_off_inbnd_em_ft40 +
            $this->gat_lift_on_inbnd_em_ft40 +
            $this->pti +
            $this->add_plan +
            $this->additional_fees;
    }

}

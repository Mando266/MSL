<?php

namespace App\Models;

use App\Models\Master\Country;
use App\Models\Master\Ports;
use App\Models\Master\Vessels;
use App\Models\Voyages\Voyages;
use Illuminate\Database\Eloquent\Model;

class PortChargeInvoice extends Model
{
    protected $guarded = [];
    
    protected $with = [
        'country',
        'port',
        'vessel',
        'voyage'
    ];

    public function rows(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PortChargeInvoiceRow::class);
    }

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function port(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Ports::class, 'port_id');
    }

    public function vessel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Vessels::class, 'vessel_id');
    }

    public function voyage(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Voyages::class, 'voyage_id');
    }


}

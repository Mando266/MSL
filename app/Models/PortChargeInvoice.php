<?php

namespace App\Models;

use App\Models\Master\Country;
use App\Models\Master\Ports;
use App\Models\Voyages\Voyages;
use Illuminate\Database\Eloquent\Model;

class PortChargeInvoice extends Model
{
    protected $guarded = [];
    
    protected $with = [
        'country',
        'port'
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
    
    public function voyages()
    {
        return $this->belongsToMany(Voyages::class, PortChargeInvoiceVoyage::class);
    }

    public function voyagesNames(): string
    {
        return $this->voyages->isNotEmpty()
            ? $this->voyages->pluck('voyage_no')->implode(',')
            : '';
    }

    public function vesselsNames(): string
    {
        return $this->voyages->isNotEmpty()
            ? $this->voyages->pluck('vessel.name')->implode(',')
            : '';
    }


    public static function searchQuery($term): \Illuminate\Database\Eloquent\Builder
    {
        return static::query()
            ->where('invoice_no', 'like', "%{$term}%")
            ->orWhereHas('country', fn ($q) => $q->where('name', 'like', "%{$term}%"))
            ->orWhereHas('port', fn ($q) => $q->where('name', 'like', "%{$term}%"))
//            ->orWhereHas('vessel', fn ($q) => $q->where('name', 'like', "%{$term}%"))
//            ->orWhereHas('voyage', fn ($q) => $q->where('voyage_no', 'like', "%{$term}%"))
            ->latest();
    }

}

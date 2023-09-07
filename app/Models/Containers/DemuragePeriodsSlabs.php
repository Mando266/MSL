<?php

namespace App\Models\Containers;

use App\Traits\HasFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DemuragePeriodsSlabs extends Model
{
    use HasFilter;

    protected $table = 'demurage_periods_slabs';
    protected $guarded = [];

    public function demurrage()
    {
        return $this->belongsTo(Demurrage::class, 'demurage_id', 'id');
    }

    public function periods(): HasMany
    {
        return $this->hasMany(Period::class, 'slab_id', 'id');
    }
}
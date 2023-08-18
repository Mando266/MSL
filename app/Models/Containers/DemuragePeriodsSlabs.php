<?php

namespace App\Models\Containers;

use App\Traits\HasFilter;
use Illuminate\Database\Eloquent\Model;

class DemuragePeriodsSlabs extends Model
{
    use HasFilter;

    protected $table = 'demurage_periods_slabs';
    protected $guarded = [];

    public function demurrage()
    {
        return $this->belongsTo(Demurrage::class, 'demurage_id', 'id');
    }

    public function period()
    {
        return $this->belongsTo(Period::class, 'period_id', 'id');
    }
}

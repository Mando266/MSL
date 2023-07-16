<?php

namespace App;

use App\Models\Master\Customers;
use Illuminate\Database\Eloquent\Model;

class ContactPerson extends Model
{
    protected $guarded = [];

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customers::class);
    }
}

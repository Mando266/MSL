<?php

namespace App\Models\Voyages;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasFilter;

class VoyagePorts extends Model
{
    use HasFilter;
    protected $table = 'voyage_port';
    protected $guarded = [];

    public function voyage(){
        return $this->belongsTo(Voyages::class,'voyage_id','id');
    }

}

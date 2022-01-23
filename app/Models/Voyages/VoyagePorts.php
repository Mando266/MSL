<?php

namespace App\Models\Voyages;
use App\Models\Master\Ports;
use App\Models\Master\Vessels;
use App\Models\Master\Terminals;
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

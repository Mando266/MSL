<?php

namespace App\Models\Containers;

use Illuminate\Database\Eloquent\Model;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Traits\HasFilter;
use App\Models\Master\ContainersMovement;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Containers;
use App\Models\Master\Vessels;
use App\Models\Master\Ports;
use App\Models\Voyages\Voyages;

class Movements extends Model implements PermissionSeederContract
{
    use HasFilter;
    protected $table = 'movements';
    protected $guarded = [];
    
    use PermissionSeederTrait;
    public function getPermissionActions(){
        return config('permission_seeder.actions',[
            'List',
            'Create',
            'Edit',
            'Delete'
        ]);
    }
    public function movementcode(){
        return $this->belongsTo(ContainersMovement::class,'movement_id','id');
    }
    public function container(){
        return $this->belongsTo(Containers::class,'container_id','id');
    }

    public function containersType(){
        return $this->belongsTo(ContainersTypes::class,'container_type_id','id');
    }

    public function vessel(){
        return $this->belongsTo(Vessels::class,'vessel_id','id');
    }
    public function voyage(){
        return $this->belongsTo(Voyages::class,'voyage_id','id');
    }
    public function location(){
        return $this->belongsTo(Ports::class,'port_location_id','id');
    }
    public function pol(){
        return $this->belongsTo(Ports::class,'pol_id','id');
    }
    public function pod(){
        return $this->belongsTo(Ports::class,'pod_id','id');
    }
}

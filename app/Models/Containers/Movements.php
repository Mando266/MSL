<?php

namespace App\Models\Containers;

use Illuminate\Database\Eloquent\Model;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Traits\HasFilter;
use App\Models\Master\ContainersMovement;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Containers;

class Movements extends Model implements PermissionSeederContract
{
    use HasFilter;
    protected $table = 'movements';
    protected $guarded = [];

    protected $fillable = [
        'container_id',
        'container_type_id',
        'movement_id',
        'movement_date',
        'port_location_id',
        'pol_id',
        'pod_id',
        'vessel_id',
        'voyage_id',
        'terminal_id',
        'booking_no',
        'bl_no',
        'remarkes',
        'transshipment_port_id',
        'booking_agent_id',
        'free_time',
        'container_status',
        'import_agent',
        'free_time_origin',
    ];
    
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

}

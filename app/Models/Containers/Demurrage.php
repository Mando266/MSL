<?php

namespace App\Models\Containers;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Ports;
use App\Models\Master\Country;
use App\Models\Containers\Bound;

class Demurrage extends Model implements PermissionSeederContract
{
    protected $table = 'demurrage';
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
    public function containersType(){
        return $this->belongsTo(ContainersTypes::class,'container_type_id','id');
    }
    public function ports(){
        return $this->belongsTo(Ports::class,'port_id','id');
    }
    public function country(){
        return $this->belongsTo(Country::class,'country_id','id');
    }
    public function bound(){
        return $this->belongsTo(Bound::class,'bound_id','id');
    }
}
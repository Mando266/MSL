<?php

namespace App\Models\Voyages;

use App\Models\Master\Vessels;
use Illuminate\Database\Eloquent\Model;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Traits\HasFilter;

class Voyages extends Model implements PermissionSeederContract
{
    use HasFilter;
    protected $table = 'voyages';
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
    public function line(){
        return $this->belongsTo(Lines::class,'line_id','id');
    }
    public function leg(){
        return $this->belongsTo(Legs::class,'leg_id','id');
    }
    public function vessel(){
        return $this->belongsTo(Vessels::class,'vessel_id','id');
    }
    public function voyagePort()
    {
        return $this->hasMany(VoyagePorts::class ,'voyage_id','id');
    }
}

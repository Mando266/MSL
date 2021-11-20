<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Traits\HasFilter;
use Illuminate\Support\Facades\Auth;
use App\Models\Containers\Movements;

class Containers extends Model implements PermissionSeederContract
{
    use HasFilter;
    protected $table = 'containers';
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

    public function containersOwner (){
        return $this->belongsto(ContinerOwnership::class,'container_ownership_id','id');
    }
    public function containersTypes (){
        return $this->belongsto(ContainersTypes::class,'container_type_id','id');
    }
    public function company (){
        return $this->belongsto(Company::class,'company_id','id');
    }
    public function movement()
    {
        return $this->hasMany(Movements::class ,'movement_id','id');
    }

    public function scopeUserContainers($query){
        if(is_null(Auth::user()->company_id))
        {
            $query;
        }else{
            $query->where('company_id',Auth::user()->company_id);
        }

    }
}

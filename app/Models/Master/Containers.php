<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Containers extends Model
{
    protected $table = 'containers';
    protected $guarded = [];

    public function containersTypes (){
        return $this->belongsto(ContainersTypes::class,'container_type_id','id');
    }
    public function company (){
        return $this->belongsto(Company::class,'company_id','id');
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

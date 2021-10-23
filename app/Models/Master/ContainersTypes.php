<?php

namespace App\Models\Master;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;

class ContainersTypes extends Model
{
    protected $table = 'containers_type';
    protected $guarded = [];

    public function company (){
        return $this->belongsto(Company::class,'company_id','id');
    }
    
    public function scopeUserContainersTypes($query){
        if(is_null(Auth::user()->company_id))
        {
            $query;
        }else{
            $query->where('company_id',Auth::user()->company_id);
        }
    
    }
}

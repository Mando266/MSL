<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Lines extends Model
{
    protected $table = 'line';
    protected $guarded = [];

    public function LineTypes(){
        return $this->belongsTo(LineTypes::class,'line_type_id','id');
    }
    public function company (){
        return $this->belongsto(Company::class,'company_id','id');
    }

    public function scopeUserPorts($query){
        if(is_null(Auth::user()->company_id))
        {
            $query;
        }else{
            $query->where('company_id',Auth::user()->company_id);
        }
    
    }
}

<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Ports extends Model
{
    protected $table = 'ports';
    protected $guarded = [];

    public function country(){
        return $this->belongsTo(Country::class,'country_id','id');
    }
    public function PortTypes(){
        return $this->belongsTo(PortTypes::class,'port_type_id','id');
    }
    public function company (){
        return $this->belongsto(Company::class,'company_id','id');
    }
    public function Agent (){
        return $this->belongsto(Agents::class,'agent_id','id');
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

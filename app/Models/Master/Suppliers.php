<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Suppliers extends Model
{
    protected $table = 'suppliers';
    protected $guarded = [];

    public function country (){
        return $this->belongsto(Country::class,'country_id','id');
    }
    public function company (){
        return $this->belongsto(Company::class,'company_id','id');
    }
    public function scopeUserSuppliers($query){
        if(is_null(Auth::user()->company_id))
        {
            $query;
        }else{
            $query->where('company_id',Auth::user()->company_id);
        }
    
    }
}

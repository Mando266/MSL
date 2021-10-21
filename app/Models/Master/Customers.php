<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\User;

class Customers extends Model
{
    protected $table = 'customers';
    protected $guarded = [];

    public function CustomerRoles (){
        return $this->belongsto(CustomerRoles::class,'customer_role_id','id');
    }
    public function country(){
        return $this->belongsTo(Country::class,'country_id','id');
    }
    public function User(){
        return $this->belongsTo(User::class,'sales_person_id','id');
    }
    public function company (){
        return $this->belongsto(Company::class,'company_id','id');
    }

    public function scopeUserCustomers($query){
        if(is_null(Auth::user()->company_id))
        {
            $query;
        }else{
            $query->where('company_id',Auth::user()->company_id);
        }
    
    }
}

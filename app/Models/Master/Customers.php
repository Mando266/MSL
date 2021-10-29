<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Traits\HasFilter;
use App\User;

class Customers extends Model implements PermissionSeederContract
{
    protected $table = 'customers';
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

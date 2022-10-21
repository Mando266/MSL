<?php

namespace App\Models\Quotations;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Models\Master\ContainersTypes;
use App\Models\Quotations\QuotationDes;
use App\Models\Master\Ports;
use App\Models\Master\Customers;
use App\Traits\HasFilter;
use App\User as AppUser;
use App\User;
use App\Models\Master\Agents;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model implements PermissionSeederContract
{
    use HasFilter;
    protected $table = 'quotations';
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
    public function equipmentsType(){
        return $this->belongsTo(ContainersTypes::class,'equipment_type_id','id');
    }
    public function placeOfAcceptence(){
        return $this->belongsTo(Ports::class,'place_of_acceptence_id','id');
    }
    public function placeOfDelivery(){
        return $this->belongsTo(Ports::class,'place_of_delivery_id','id');
    }
    public function placeOfReturn(){
        return $this->belongsTo(Ports::class,'place_return_id','id');
    }
    public function loadPort(){
        return $this->belongsTo(Ports::class,'load_port_id','id');
    }
    public function dischargePort(){
        return $this->belongsTo(Ports::class,'discharge_port_id','id');
    }
    public function pickUpLocation(){
        return $this->belongsTo(Ports::class,'pick_up_location','id');
    }
    public function customer(){
        return $this->belongsTo(Customers::class,'customer_id','id');
    }
    public function agent(){
        return $this->belongsTo(Agents::class,'agent_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'quoted_by_id','id');
    }
    public function quotationDesc()
    {
        return $this->hasMany(QuotationDes::class ,'quotation_id','id');
    }

    public function createOrUpdateDesc($inputs)
    {
        foreach($inputs as $input){
            $input['quotation_id'] = $this->id;
            if( isset($input['id']) ){
                QuotationDes::find($input['id'])
                ->update($input);
            }
            else{
                QuotationDes::create($input);
            }
        }
    }

}

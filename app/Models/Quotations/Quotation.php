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
use App\Models\Master\Lines;
use App\Models\Booking\Booking;

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
    public function ffw(){
        return $this->belongsTo(Customers::class,'ffw_id','id');
    }
    public function agent(){
        return $this->belongsTo(Agents::class,'agent_id','id');
    }
    public function principal(){
        return $this->belongsTo(Lines::class,'principal_name','id');
    }
    public function operator(){
        return $this->belongsTo(Lines::class,'vessel_name','id');
    }
    public function disAgent(){
        return $this->belongsTo(Agents::class,'discharge_agent_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'quoted_by_id','id');
    }
    public function quotationDesc()
    {
        return $this->hasMany(QuotationDes::class ,'quotation_id','id');
    }
    public function quotationLoad()
    {
        return $this->hasMany(QuotationLoad::class ,'quotation_id','id');
    }
    public function booking()
    {
        return $this->hasMany(Booking::class ,'quotation_id','id');
    }
    public function createOrUpdateDesc($inputs)
    {
        if($inputs != null){
        foreach($inputs as $input){
            $input['quotation_id'] = $this->id;
            if( isset($input['id']) ){
                $des = QuotationDes::find($input['id']);
                // dd($des,$inputs);
                $des->update([
                    'quotation_id'=>$input['quotation_id'],
                    'charge_type'=>$input['charge_type'],
                    'currency'=>$input['currency'],
                    'unit'=>$input['unit'],
                    'selling_price'=>$input['selling_price'],
                    'payer'=>$input['payer'],
                    'equipment_type_id'=>$input['equipments_type'],
                ]);
            }
            else{
                QuotationDes::create([
                    'quotation_id'=>$input['quotation_id'],
                    'charge_type'=>$input['charge_type'],
                    'currency'=>$input['currency'],
                    'unit'=>$input['unit'],
                    'selling_price'=>$input['selling_price'],
                    'payer'=>$input['payer'],
                    'equipment_type_id'=>$input['equipments_type'],
                ]);
            }
        }
        }
    }
    public function createOrUpdateLoad($inputs)
    {
        if($inputs != null){
        foreach($inputs as $input){
            $input['quotation_id'] = $this->id;
            if( isset($input['id']) ){
                $load = QuotationLoad::find($input['id']);
                $load->update([
                    'quotation_id'=>$input['quotation_id'],
                    'charge_type'=>$input['charge_type'],
                    'currency'=>$input['currency'],
                    'unit'=>$input['unit'],
                    'selling_price'=>$input['selling_price'],
                    'payer'=>$input['payer'],
                    'equipment_type_id'=>$input['equipments_type'],
                ]);
            }
            else{
                QuotationLoad::create([
                    'quotation_id'=>$input['quotation_id'],
                    'charge_type'=>$input['charge_type'],
                    'currency'=>$input['currency'],
                    'unit'=>$input['unit'],
                    'selling_price'=>$input['selling_price'],
                    'payer'=>$input['payer'],
                    'equipment_type_id'=>$input['equipments_type'],
                ]);
            }
        }
    }
    }

}

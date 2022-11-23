<?php

namespace App\Models\Booking;

use App\Models\Master\Agents;
use App\Models\Master\Containers;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Models\Quotations\Quotation;
use App\Models\Master\Ports;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Customers;
use App\Models\Master\Terminals;
use App\Models\Voyages\Voyages;
use App\User;
use App\Traits\HasFilter;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model implements PermissionSeederContract
{
    use HasFilter;
    protected $table = 'booking';
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
    public function quotation(){
        return $this->belongsTo(Quotation::class,'quotation_id','id');
    }
    public function equipmentsType(){
        return $this->belongsTo(ContainersTypes::class,'equipment_type_id','id');
    }
    public function customer(){
        return $this->belongsTo(Customers::class,'customer_id','id');
    }
    public function forwarder(){
        return $this->belongsTo(Customers::class,'ffw_id','id');
    }
    public function voyage(){
        return $this->belongsTo(Voyages::class,'voyage_id','id');
    }
    public function secondvoyage(){
        return $this->belongsTo(Voyages::class,'voyage_id_second','id');
    }
    public function terminals(){
        return $this->belongsTo(Terminals::class,'terminal_id','id');
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
    public function bookedby(){
        return $this->belongsTo(User::class,'booked_by','id');
    }
    public function agent(){
        return $this->belongsTo(Agents::class,'bl_release','id');
    }
    public function container(){
        return $this->belongsTo(Containers::class,'container_id','id');
    }
    public function bookingContainerDetails()
    {
        return $this->hasMany(BookingContainerDetails::class ,'booking_id','id');
    }

    public function createOrUpdateContainerDetails($inputs)
    {
        if (is_array($inputs) || is_object($inputs)){
        foreach($inputs as $input){
            
            $input['booking_id'] = $this->id;

            if( isset($input['id']) ){
                BookingContainerDetails::find($input['id'])
                ->update($input);
            }
            else{
                BookingContainerDetails::create($input);
            }
        }
    }
 }
}
<?php

namespace App\Models\Invoice;

use App\Models\Bl\BlDraft;
use App\Traits\HasFilter;
use App\Models\Master\Ports;
use App\Models\Booking\Booking;
use App\Models\Master\ContainersTypes;
use App\Models\Voyages\Voyages;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Master\Customers;


class Invoice extends Model implements PermissionSeederContract
{
    use HasFilter;
    protected $table = 'invoice';
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
    public function bldraft(){
        return $this->belongsTo(BlDraft::class,'bldraft_id','id');
    }

    public function voyage(){
        return $this->belongsTo(Voyages::class,'voyage_id','id');
    }

    public function equipmentsType(){
        return $this->belongsTo(ContainersTypes::class,'equipment_type','id');
    }

    public function placeOfAcceptence(){
        return $this->belongsTo(Ports::class,'place_of_acceptence','id');
    }
    public function placeOfDelivery(){
        return $this->belongsTo(Ports::class,'port_of_delivery','id');
    }
    public function loadPort(){
        return $this->belongsTo(Ports::class,'load_port','id');
    } 
    public function dischargePort(){
        return $this->belongsTo(Ports::class,'discharge_port','id');
    }

    public function booking(){
        return $this->belongsTo(Booking::class,'booking_ref','id');
    }

    public function chargeDesc()
    {
        return $this->hasMany(InvoiceChargeDesc::class ,'invoice_id','id');
    }
    public function customerShipperOrFfw(){
        return $this->belongsTo(Customers::class,'customer_id','id');
    }

    public function createOrUpdateInvoiceChargeDesc($inputs)
    {

        if (is_array($inputs) || is_object($inputs)){
            foreach($inputs as $input){
                $input['invoice_id'] = $this->id;
                if( isset($input['id']) ){
                    InvoiceChargeDesc::find($input['id'])
                    ->update($input);
                }
                else{
                    InvoiceChargeDesc::create($input);
                }
            }
        }
    }

}

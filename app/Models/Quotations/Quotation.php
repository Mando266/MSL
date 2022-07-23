<?php

namespace App\Models\Quotations;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use App\Models\Master\ContainersTypes;
use App\Models\Quotations\QuotationDes;
use App\Models\Master\Ports;
use App\Traits\HasFilter;

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
    public function loadPort(){
        return $this->belongsTo(Ports::class,'load_port_id','id');
    }
    public function dischargePort(){
        return $this->belongsTo(Ports::class,'discharge_port_id','id');
    }
    public function quotationDesc()
    {
        return $this->hasMany(QuotationDes::class ,'quotation_id','id');
    }

}

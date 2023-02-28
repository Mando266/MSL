<?php

namespace App\Models\Invoice;

use App\Models\Bl\BlDraft;
use App\Traits\HasFilter;
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

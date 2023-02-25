<?php

namespace App\Models\Invoice;

use App\Models\Bl\BlDraft;
use App\Traits\HasFilter;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;
use Illuminate\Database\Eloquent\Model;

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
}

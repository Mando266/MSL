<?php

namespace App\Models\Receipt;

use App\Models\Bl\BlDraft;
use App\Models\Invoice\Invoice;
use Illuminate\Database\Eloquent\Model;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use App\Traits\HasFilter;
use App\User;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;

class Receipt extends Model
{
    use HasFilter;
    protected $table = 'receipts';
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

    public function invoice(){
        return $this->belongsTo(Invoice::class,'invoice_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}

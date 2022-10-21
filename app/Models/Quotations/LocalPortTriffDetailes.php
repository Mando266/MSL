<?php

namespace App\Models\Quotations;

use App\Http\Controllers\Quotations\LocalPortTriffDetailesController;
use Illuminate\Database\Eloquent\Model;
use App\Models\Master\ContainersTypes;

class LocalPortTriffDetailes extends Model
{

    protected $table = 'quotation_triff_details';
    protected $guarded = [];

    public function triffPrice(){
        return $this->belongsTo(TriffPrice::class,'quotation_triff_id','id');
    }
    public function equipmentsType(){
        return $this->belongsTo(ContainersTypes::class,'equipment_type_id','id');
    }
    
}

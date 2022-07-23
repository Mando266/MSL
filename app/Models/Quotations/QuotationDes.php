<?php

namespace App\Models\Quotations;

use Illuminate\Database\Eloquent\Model;

class QuotationDes extends Model
{
    protected $table = 'quotations_description';
    protected $guarded = [];

    public function quotation(){
        return $this->belongsTo(Quotations::class,'quotation_id','id');
    }
}

<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Terminals extends Model
{
    protected $table = 'terminals';
    protected $guarded = [];

    public function country(){
        return $this->belongsTo(Country::class,'country_id','id');
    }

}

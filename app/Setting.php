<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Bitwise\PermissionSeeder\PermissionSeederContract;
use Bitwise\PermissionSeeder\Traits\PermissionSeederTrait;

class Setting extends Model implements PermissionSeederContract
{
    protected $table = 'settings';
    protected $guarded = [];
    use PermissionSeederTrait;

    public function getPermissionActions(){
        return config('permission_seeder.actions',[
            'List',
            'Edit',
        ]);
    }
    public static function getByKey($key){
        $item = Setting::where('name',$key)->first();
        if(!is_null($item)){
            return $item->getValue();
        }
        return null;
    }

    protected function getValue(){
        $castType = $this->cast_type ?? 'string';
        switch($castType){
            case 'bool':
            case 'boolean':
                return (bool)$this->value;
            default:
            return $this->value;
        }
    }
}

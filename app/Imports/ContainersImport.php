<?php

namespace App\Imports;

use App\Models\Master\Containers;
use App\Models\Master\ContainersTypes;
use App\Models\Master\ContinerOwnership;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ContainersImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {        
        $containertype = $row['container_type_id'];
        $containerNumber= $row['code'];
        $user = Auth::user();

        $row['container_type_id'] = ContainersTypes::where('name',$row['container_type_id'])->pluck('id')->first();
        $row['container_ownership_id'] = ContinerOwnership::where('name',$row['container_ownership_id'])->pluck('id')->first();

        if(!$row['container_type_id']){
            
            return session()->flash('message',"This Container Type: {$containertype} Not found ");
        } 

        $containerdublicate  = Containers::where('code',$row['code'])->where('company_id',$user->company_id)->first();
        
        if($containerdublicate != null){
            return Session::flash('message', 'This Container Number: '.$containerNumber.' Already Exists!');
        }

            $ImportContainers = Containers::create([
                'code' => $row['code'],
                'iso' => $row['iso'],
                'container_type_id' => $row['container_type_id'],
                'description' => $row['description'],
                'tar_weight' => $row['tar_weight'],
                'max_payload' => $row['max_payload'],
                'container_ownership_id' => $row['container_ownership_id'],
                'production_year' => $row['production_year'],
            ]);
            $ImportContainers->company_id = $user->company_id;
            $ImportContainers->save();

        return $ImportContainers;
    }


    
}

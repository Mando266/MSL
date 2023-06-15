<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Containers\Demurrage;
use App\Models\Containers\Movements;
use App\Models\Master\Containers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class StorageContainersController extends Controller
{
    //
    public function getStorageBlContainers($id,$company_id)
    {
        $mov = Movements::where('bl_no', $id)->where('company_id',$company_id)->distinct()->get()->pluck('container_id')->toarray();

        $containers = Containers::whereIn('id',$mov)->get();

        return Response::json([
            'containers' => $containers
        ],200);
    }

    public function getStorageTriffs($service,$company_id)
    {
        $now = Carbon::now()->format('Y-m-d');
        if($service == "power charges"){
            $is_torage = "power charges";
            $status = 1;
        }elseif($service == "Export Full" || $service == "Export Empty"){ // full 1 empty 2 
            $is_torage = "Export";
            if($service == "Export Full"){
                $status = 1;
            }else{
                $status = 2;
            }
        }else{
            $is_torage = "Import";
            if($service == "Import Full"){
                $status = 1;
            }else{
                $status = 2;
            }
        }
        $triffs = Demurrage::where('company_id',$company_id)->where('validity_to','>=',$now)
                    ->where('is_storge',$is_torage)->where('container_status',$status)->get();

        return Response::json([
            'triffs' => $triffs
        ],200);
    }
}

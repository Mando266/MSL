<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Containers\Movements;
use App\Models\Master\Containers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class StorageContainersController extends Controller
{
    //
    public function getStorageBlContainers($id,$company_id)
    {
        $containers = Containers::whereHas('movement', function ($query) use ($id,$company_id) {
                            $query->where('bl_no', $id)->where('company_id',$company_id);
                        })->get();
        
        return Response::json([
            'containers' => $containers
        ],200);
    }
}

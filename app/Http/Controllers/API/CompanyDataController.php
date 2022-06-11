<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Finance\AccountPeriod;
use App\Models\Finance\OperationRate;
use App\Models\Master\Company;
use App\Models\Voyages\Voyages;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class CompanyDataController extends Controller
{
    public function getVesselVoyages($id)
    {
        $voyages = Voyages::where('vessel_id',$id)->select('voyage_no')->get();
        
        return Response::json([
            'voyages' => $voyages,
        ],200);
    }
    

}

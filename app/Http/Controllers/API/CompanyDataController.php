<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Finance\AccountPeriod;
use App\Models\Finance\OperationRate;
use App\Models\Master\Company;
use App\Models\Master\Customers;
use App\Models\Master\Ports;
use App\Models\Voyages\Voyages;
use Illuminate\Http\Request;
use App\Models\Master\Terminals;
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
    
    public function portsCountry($id)
    {
        $ports = Ports::where('country_id',$id)->select('name','id')->get();
        
        return Response::json([
            'ports' => $ports,
        ],200);
    }

    public function terminalsPorts($id)
    {
        $terminals = Terminals::where('port_id',$id)->select('name','id','code')->get();
        
        return Response::json([
            'terminals' => $terminals,
        ],200);
    }

    public function customer($id)
    {
        $customer = Customers::where('id',$id)->select('id','name','phone','address','email')->get();
        
        return Response::json([
            'customer' => $customer,
        ],200);
    }
}

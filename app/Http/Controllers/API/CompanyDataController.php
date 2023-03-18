<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Finance\AccountPeriod;
use App\Models\Finance\OperationRate;
use App\Models\Invoice\Invoice;
use App\Models\Master\Customers;
use App\Models\Master\Ports;
use App\Models\Voyages\Voyages;
use App\Models\Master\Terminals;
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
    
    public function portsCountry($id,$company_id)
    {
        $ports = Ports::where('company_id',$company_id)->where('country_id',$id)->select('name','id')->get();
        
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
        $customer = Customers::where('id',$id)->get();
        
        return Response::json([
            'customer' => $customer,
        ],200);
    }

    public function blinvoice($id)
    {
        $invoices = Invoice::where('invoice_status','confirm')->where('bldraft_id',$id)->get();
        
        return Response::json([
            'invoices' => $invoices,
        ],200);
    }

    public function customerInvoice($id)
    {
        $invoices = Invoice::where('invoice_status','confirm')->where('customer_id',$id)->get();
        
        return Response::json([
            'invoices' => $invoices,
        ],200);
    }
}

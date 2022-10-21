<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Quotations\LocalPortTriff;
use App\Models\Quotations\LocalPortTriffDetailes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PriceController extends Controller
{
    //
    public function getLoadAgentPrice($id)
    {
        $agentTriff = LocalPortTriff::where('agent_id',$id)->pluck('id')->first();
        $agentTriff = LocalPortTriffDetailes::where('quotation_triff_id',$agentTriff)
            ->where('is_import_or_export', 0)->get();
    
        return Response::json([
            'agentTriff' => $agentTriff
        ],200);
    }
    public function getDischargeAgentPrice($id)
    {
        $agentTriff = LocalPortTriff::where('agent_id',$id)->pluck('id')->first();
        $agentTriff = LocalPortTriffDetailes::where('quotation_triff_id',$agentTriff)
            ->where('is_import_or_export', 1)->get();
        
        return Response::json([
            'agentTriff' => $agentTriff
        ],200);
    }
    
}

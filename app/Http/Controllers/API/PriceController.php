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
    public function getLoadAgentPrice($id,$equipment_id = null)
    {
        
        $agentTriff = LocalPortTriff::where('agent_id',$id)->pluck('id')->first();
        if($equipment_id != null){
            $agentTriff = LocalPortTriffDetailes::where('quotation_triff_id',$agentTriff)
            ->where('is_import_or_export', 0)->where('add_to_quotation', 1)->where(function ($query) use($equipment_id){
                $query->where('equipment_type_id', $equipment_id)
                      ->orWhere('equipment_type_id', 100);
            })->with('equipmentsType')->get();
        }else{
            $agentTriff = LocalPortTriffDetailes::where('quotation_triff_id',$agentTriff)
            ->where('is_import_or_export', 0)->where('add_to_quotation', 1)->with('equipmentsType')->get();
        }
        
        return Response::json([
            'agentTriff' => $agentTriff
        ],200);
    }
    public function getDischargeAgentPrice($id,$equipment_id = null)
    {
        $agentTriff = LocalPortTriff::where('agent_id',$id)->pluck('id')->first();
        if($equipment_id != null){
            $agentTriff = LocalPortTriffDetailes::where('quotation_triff_id',$agentTriff)
            ->where('is_import_or_export', 1)->where('add_to_quotation', 1)->where(function ($query) use($equipment_id){
                $query->where('equipment_type_id', $equipment_id)
                      ->orWhere('equipment_type_id', 100);
            })->with('equipmentsType')->get();
        }else{
            $agentTriff = LocalPortTriffDetailes::where('quotation_triff_id',$agentTriff)
            ->where('is_import_or_export', 1)->where('add_to_quotation', 1)->with('equipmentsType')->get();
        }
        
        return Response::json([
            'agentTriff' => $agentTriff
        ],200);
    }
    
}


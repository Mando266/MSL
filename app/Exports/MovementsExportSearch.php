<?php

namespace App\Exports;

use App\Filters\Containers\ContainersIndexFilter;
use App\Models\Containers\Movements;
use App\Models\Master\Containers;
use App\Models\Master\ContainersMovement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Master\ContainersTypes;

class MovementsExportSearch implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */  
    // use Exportable;

    public function headings(): array
    {
        return [
            "id",
            "container_id",
            "container_type_id",
            "movement_id",
            "movement_date" ,
            "port_location_id",
            "pol_id",
            "pod_id",
            "vessel_id" ,
            "voyage_id",
            "terminal_id",
            "booking_no",
            "bl_no",
            "remarkes",
            "created_at",
            "updated_at",
            "transshipment_port_id",
            "booking_agent_id",
            "free_time",
            "container_status",
            "import_agent",
            "free_time_origin",
        ];
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        
        $movements = Movements::filter(new ContainersIndexFilter(request()))->orderBy('id')->groupBy('container_id')
        ->get();
        if(request('movement_id') != null || request('port_location_id') != null){
            $movements = Movements::filter(new ContainersIndexFilter(request()))->orderBy('movement_date','desc')->groupBy('container_id')->get();
            if(request('movement_id') != null){
                foreach($movements as $key => $move){
                    $lastMove = Movements::where('container_id',$move->container_id)->orderBy('movement_date','desc')->orderBy('id','desc')->first();
                    if($lastMove->movement_id != request('movement_id')){
                        unset($movements[$key]);
                    }
                }
            }
            if(request('port_location_id') != null){
                foreach($movements as $key =>$move){
                    $lastMove = Movements::where('container_id',$move->container_id)->orderBy('movement_date','desc')->orderBy('id','desc')->first();
                    if($lastMove->port_location_id != request('port_location_id')){
                        unset($movements[$key]);
                    }
                }
            }
            
        }
        foreach($movements as $move){
            $lastMove = Movements::where('container_id',$move->container_id)->orderBy('movement_date','desc')->orderBy('id','desc')->first();
            
            $move->bl_no = $lastMove->bl_no;
            $move->port_location_id = $lastMove->port_location_id;
            $move->movement_date = $lastMove->movement_date;
            $move->movement_id = $lastMove->movement_id;
            $move->container_type_id = $lastMove->container_type_id;
            $move->pol_id = $lastMove->pol_id;
            $move->pod_id = $lastMove->pod_id;
            $move->vessel_id = $lastMove->vessel_id;
            $move->voyage_id = $lastMove->voyage_id;
            $move->terminal_id = $lastMove->terminal_id;
            $move->booking_no = $lastMove->booking_no;
            $move->remarkes = $lastMove->remarkes;
            $move->created_at = $lastMove->created_at;
            $move->updated_at = $lastMove->updated_at;
            $move->transshipment_port_id = $lastMove->transshipment_port_id;
            $move->booking_agent_id = $lastMove->booking_agent_id;
            $move->free_time = $lastMove->free_time;
            $move->container_status = $lastMove->container_status;
            $move->import_agent = $lastMove->import_agent;
            $move->free_time_origin = $lastMove->free_time_origin;
            
        }
        if($movements != null && $movements->count() > 0){
            foreach($movements as $movement){
                $movement->container_id = Containers::where('id',$movement->container_id)->pluck('code')->first();
                $movement->movement_id = ContainersMovement::where('id',$movement->movement_id)->pluck('code')->first();
                $movement->container_type_id = ContainersTypes::where('id',$movement->container_type_id)->pluck('name')->first();
            }
        }else{
            $movements = [];
        }
        // dd($movements);
        return $movements;
    }
}

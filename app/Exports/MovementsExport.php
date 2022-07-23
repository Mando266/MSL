<?php

namespace App\Exports;
use App\Models\Containers\Movements;
use App\Models\Master\Containers;
use App\Models\Master\ContainersMovement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Master\ContainersTypes;

class MovementsExport implements FromCollection,WithHeadings
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
        $container_id =  request()->input('container_id');
        $port_location_id =  request()->input('port_location_id');
        $voyage_id =  request()->input('voyage_id');
        $movement_id =  request()->input('movement_id');
        $bl_no =  request()->input('bl_no');
        $booking_no =  request()->input('booking_no');
        $movements = Movements::where('container_id',$container_id);
        $movementsArray = false;
        $oneMovement = false;
        
        
        if($voyage_id != null){
            $movements = $movements->where('voyage_id',$voyage_id);
        }
        if($bl_no != null){
            $movements = $movements->where('bl_no',$bl_no);
        }
        if($booking_no != null){
            $movements = $movements->where('booking_no',$booking_no);
        }
        $movements = $movements->get();
        
        if($port_location_id != null || $movement_id != null){
            $movements = Movements::where('container_id',request('container_id'))->orderBy('movement_date','desc')->first();
                $oneMovement = true;
                if($movement_id != null){
                    if($movements->movement_id != $movement_id){
                        $movementsArray = true;
                    }
                }
                if($port_location_id != null){
                    if($movements->port_location_id != $port_location_id){
                        $movementsArray = true;
                    }
                }
                if($movementsArray == true){
                    $movements = [];
                }
        }
        if($movementsArray == false){
            if($oneMovement == false){
                foreach($movements as $movement){
                    $movement->container_id = Containers::where('id',$movement->container_id)->pluck('code')->first();
                    $movement->movement_id = ContainersMovement::where('id',$movement->movement_id)->pluck('code')->first();
                    $movement->container_type_id = ContainersTypes::where('id',$movement->container_type_id)->pluck('name')->first();
                }
            }else{
                $movements->container_id = Containers::where('id',$movements->container_id)->pluck('code')->first();
                $movements->movement_id = ContainersMovement::where('id',$movements->movement_id)->pluck('code')->first();
                $movements->container_type_id = ContainersTypes::where('id',$movements->container_type_id)->pluck('name')->first();
                $temp = $movements;
                $movements = collect();
                $movements->push($temp);
            }
        }
        
        return $movements;
    }
}

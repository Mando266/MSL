<?php

namespace App\Exports;
use App\Models\Containers\Movements;
use App\Models\Master\Agents;
use App\Models\Master\Containers;
use App\Models\Master\ContainersMovement;
use App\Models\Master\ContainerStatus;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Vessels;

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
            "company_id",
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
        $movements = session('items');
        // dd($movements);
                foreach($movements as $movement){
                    $movement->container_id = Containers::where('id',$movement->container_id)->pluck('code')->first();
                    $movement->movement_id = ContainersMovement::where('id',$movement->movement_id)->pluck('code')->first();
                    $movement->container_type_id = ContainersTypes::where('id',$movement->container_type_id)->pluck('name')->first();
                    $movement->container_status = ContainerStatus::where('id',$movement->container_status)->pluck('name')->first();
                    $movement->vessel_id = Vessels::where('id',$movement->vessel_id)->pluck('name')->first();
                    $movement->booking_agent_id = Agents::where('id',$movement->booking_agent_id)->pluck('name')->first();
                    $movement->import_agent = Agents::where('id',$movement->import_agent)->pluck('name')->first();
                }
            
        
        return $movements;
    }
}

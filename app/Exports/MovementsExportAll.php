<?php

namespace App\Exports;
use App\Models\Containers\Movements;
use App\Models\Master\Containers;
use App\Models\Master\ContainersMovement;
use App\Models\Master\ContainersTypes;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MovementsExportAll implements FromCollection,WithHeadings
{
  
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
    

    public function collection()
    {
        $movements = Movements::where('company_id',Auth::user()->company_id)->get();

        foreach($movements as $movement){
            $movement->container_id = Containers::where('id',$movement->container_id)->pluck('code')->first();
            $movement->movement_id = ContainersMovement::where('id',$movement->movement_id)->pluck('code')->first();
            $movement->container_type_id = ContainersTypes::where('id',$movement->container_type_id)->pluck('name')->first();
        }
        
        return $movements;
    }
}

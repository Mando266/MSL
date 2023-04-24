<?php

namespace App\Exports;

use App\Models\Master\Containers;
use App\Models\Master\ContainerStatus;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Vessels;

class AgentsExportSearch implements FromCollection,WithHeadings
{

    public function headings(): array
    {
        return [
 
            "DATE" ,
            "Container #",
            "Size/Type",
            "Containers Ownership",
            "Full/Empty",
            "BL REF. #",
            "VSL NAME" ,
            "VOY",
            "POL",
            "F.POD",
            "CONDITION",
        ];
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $movements = session('items');

        $exportmovements = collect();
        foreach($movements  ?? [] as $movement){
                $tempCollection = collect([
                    'movement_date' => $movement->movement_date,
                    $movement->container_id = Containers::where('id',$movement->container_id)->pluck('code')->first(),
                    $movement->container_type_id = ContainersTypes::where('id',$movement->container_type_id)->pluck('name')->first(),
                    $movement->containersOwner = optional($movement->container->containersOwner)->name,
                    $movement->container_status = ContainerStatus::where('id',$movement->container_status)->pluck('name')->first(),
                    'bl_no' => $movement->bl_no,
                    $movement->vessel_id = Vessels::where('id',$movement->vessel_id)->pluck('name')->first(),
                    'voyage_id'=> $movement->voyage_id,
                    'pol_id'=> $movement->pol_id,
                    'pod_id'=> $movement->pod_id,
                ]);
                $exportmovements->add($tempCollection);
        }
        
        return $exportmovements;
    }

}

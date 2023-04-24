<?php

namespace App\Exports;
use App\Models\Quotations\Quotation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VoyageExport implements FromCollection,WithHeadings
{
  
    public function headings(): array
    {
        return [
            "Vessel Code",
            "Vessel Name",
            "Voyage No",
            "Leg" ,
            "PORT",
            "ETA",
            "ETD",
            "Terminal Name" ,
            "Road NO",
        ];
    }
    

    public function collection()
    {
       
        $voyages = session('voyages');
        $exportVoyages = collect();
        foreach($voyages  ?? [] as $voyage){
            foreach($voyage->voyagePorts as $voyagePort){
                $tempCollection = collect([
                    'code' => optional($voyage->vessel)->code,
                    'name' => optional($voyage->vessel)->name,
                    'voyage_no'=> $voyage->voyage_no,
                    'leg'=> optional($voyage->leg)->name,
                    'port'=> optional($voyagePort->port)->name,
                    'eta'=> $voyagePort->eta,
                    'etd'=> $voyagePort->etd,
                    'terminal'=> optional($voyagePort->terminal)->name,
                    'road_no'=> $voyagePort->road_no,
                ]);
                $exportVoyages->add($tempCollection);
            }
        }
        
        return $exportVoyages;
    }    
}

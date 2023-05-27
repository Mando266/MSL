<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ContainersExport implements FromCollection,WithHeadings
{
  
    public function headings(): array
    {
        return [
            "NUMBER",
            "ISO",
            "TYPE",
            "OWNERSHIP",
            "TAR WEIGHT" ,
            "MAX PAYLOAD",
            "PRODUCTION YEAR",
            "LESSOR/SELLER REFRENCE",
            "Transhipment OR Not"
        ];
    }
    

    public function collection()
    {
       
        $containers = session('containers');
        $exportContainers = collect();
        foreach($containers  ?? [] as $container){
            if($container->is_transhipment == 0){
                $transhipment = 'No';

            }else{
                $transhipment = 'Yes';
            }
                $tempCollection = collect([
                    'code' => $container->code,
                    'iso' => $container->iso,
                    'type' => optional($container->containersTypes)->name,
                    'ownership'=> optional($container->containersOwner)->name,
                    'tar_weight'=> $container->tar_weight,
                    'max_payload'=> $container->max_payload,
                    'prod_year'=> $container->production_year,
                    'lessor'=> $container->description,
                    'is_transhipment'=>$transhipment,
                ]);
                $exportContainers->add($tempCollection);
        }
        
        return $exportContainers;
    }    
}

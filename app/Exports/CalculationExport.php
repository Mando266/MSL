<?php

namespace App\Exports;

use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CalculationExport implements FromCollection, WithHeadings
{

    public function headings(): array
    {
        return [
            "SR",
            "CONTAINER NO",
            "CONTAINER TYPE",
            "GATE IN MOVE CODE",
            "GATE IN MOVE DATE",
            "NEXT MOVE CODE",
            "FREE DAYS",
            "FREE TIME END DATE",
            "STORAGE TILL DATE",
            "CHARGABLE DAYS",
            "RATE",
            "AMOUNT",
            "CURRENCY",
        ];
    }


    public function collection()
    {
        $calculations = session()->pull('calculations');
        $calculationexport = collect();
        $count = 1;
        $freeTime = $calculations['freetime'];
            foreach ($calculations['calculation']['containers'] as $container) {
                $fromTime = Carbon::parse($container['from']);
                // dd($fromTime);
                $toTime = Carbon::parse($container['to']);
                if ($freeTime == 0) {
                    $freeTime = $container['periods'][0]['days'];
                }
                $totalDays = Carbon::parse($toTime)->diffInDays($fromTime); // total days
                $chargableDays = $totalDays-$freeTime + 1; // chargable days
                $freeTimeTillDate = $fromTime->addDays(($freeTime - 1));
                $tempCollection = collect([
                    "SR" => $count,
                    "CONTAINER NO" => $container['container_no'],
                    "CONTAINER TYPE" => $container['container_type'],
                    "GATE IN MOVE CODE" => $container['from_code'],
                    "GATE IN MOVE DATE" => $fromTime->format('Y-m-d'),
                    "NEXT MOVE CODE" => $container['to_code'],
                    "FREE DAYS" => $calculations['freetime'],
                    "FREE TIME END DATE" => $freeTimeTillDate->format('Y-m-d'),
                    "STORAGE TILL DATE" => $toTime->format('Y-m-d'),
                    "CHARGABLE DAYS" => $chargableDays,
                    "RATE" => 1,
                    "AMOUNT" => $container['total'],
                    "CURRENCY" => $calculations['calculation']['currency'],
                ]);
                $calculationexport->add($tempCollection);
            $count++;
            }

        $tempCollection = collect([
            "SR" => '',
            "CONTAINER NO" => '',
            "CONTAINER TYPE" => '',
            "GATE IN MOVE CODE" => '',
            "GATE IN MOVE DATE" => '',
            "NEXT MOVE CODE" => '',
            "FREE DAYS" => '',
            "FREE TIME END DATE" => '',
            "STORAGE TILL DATE" => '',
            "CHARGABLE DAYS" => '',
            "RATE" => 'Total: ',
            "AMOUNT" => $calculations['calculation']['grandTotal'],
            "CURRENCY" => '',
        ]);
        $calculationexport->add($tempCollection);

        return $calculationexport;
    }
}

<?php

namespace App\Imports;

use Illuminate\Support\Facades\Session;
use App\Models\Containers\Movements;
use App\Models\Master\Containers;
use App\Models\Master\ContainersMovement;
use App\Models\Master\ContainersTypes;
use App\MovementImportErrors;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MovementsImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $containerId = $row['container_id'];
        // dd($containerId);
        $row['container_id'] = Containers::where('code',$row['container_id'])->pluck('id')->first();
        $lastMove = Movements::where('container_id',$row['container_id'])->latest()->pluck('movement_id')->first();
        $lastMoveCode = ContainersMovement::where('id',$lastMove)->pluck('code')->first();
        $nextMoves = ContainersMovement::where('id',$lastMove)->pluck('next_move')->first();
        $nextMoves = explode(', ',$nextMoves);
        $movementCode = $row['movement_id'];
        
        $row['movement_id'] =  ContainersMovement::where('code',$row['movement_id'])->pluck('id')->first();
        $row['container_type_id'] = ContainersTypes::where('name',$row['container_type_id'])->pluck('id')->first();
        $row['movement_date'] = Date::excelToDateTimeObject($row['movement_date']);

        // dd([$movementCode,$nextMoves]);
        if(in_array($movementCode,$nextMoves)){
            $movement = Movements::create([
                'container_id' => $row['container_id'],
                'container_type_id' => $row['container_type_id'],
                'movement_id' => $row['movement_id'],
                'movement_date' => $row['movement_date'],
                'port_location_id' => $row['port_location_id'],
                'pol_id' => $row['pol_id'],
                'pod_id' => $row['pod_id'],
                'vessel_id' => $row['vessel_id'],
                'voyage_id' => $row['voyage_id'],
                'terminal_id' => $row['terminal_id'],
                'booking_no' => $row['booking_no'],
                'bl_no' => $row['bl_no'],
                'remarkes' => $row['remarkes'],
                'transshipment_port_id' => $row['transshipment_port_id'],
                'booking_agent_id' => $row['booking_agent_id'],
                'free_time' => $row['free_time'],
                'container_status' => $row['container_status'],
                'import_agent' => $row['import_agent'],
                'free_time_origin' => $row['free_time_origin'],
            ]);
        }elseif($nextMoves[0] == ""){
            $movement = Movements::create([
                'container_id' => $row['container_id'],
                'container_type_id' => $row['container_type_id'],
                'movement_id' => $row['movement_id'],
                'movement_date' => $row['movement_date'],
                'port_location_id' => $row['port_location_id'],
                'pol_id' => $row['pol_id'],
                'pod_id' => $row['pod_id'],
                'vessel_id' => $row['vessel_id'],
                'voyage_id' => $row['voyage_id'],
                'terminal_id' => $row['terminal_id'],
                'booking_no' => $row['booking_no'],
                'bl_no' => $row['bl_no'],
                'remarkes' => $row['remarkes'],
                'transshipment_port_id' => $row['transshipment_port_id'],
                'booking_agent_id' => $row['booking_agent_id'],
                'free_time' => $row['free_time'],
                'container_status' => $row['container_status'],
                'import_agent' => $row['import_agent'],
                'free_time_origin' => $row['free_time_origin'],
            ]);
        }else{
            // return '<script type="text/javascript">alert("hello!");</script>';
            // $movement = new Movements();
            // return $movement;
            MovementImportErrors::create([
                'container_id' => $containerId,
                'error_code' => $lastMoveCode,
                'allowed_code' => implode(", ", $nextMoves)
            ]);
            return Session::flash('message', 'Error in Allowed Next Movement');
        }
        

        return $movement;
    }


    
}

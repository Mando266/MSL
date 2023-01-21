<?php

namespace App\Imports;

use Illuminate\Support\Facades\Session;
use App\Models\Containers\Movements;
use App\Models\Master\Containers;
use App\Models\Master\ContainersMovement;
use App\Models\Master\ContainersTypes;
use App\MovementImportErrors;
use Exception;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class MovementsImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // CHECK if nll
        $a = collect($row);
        $z = $a->filter(fn($v)=>$v != null)->toArray();
        if(empty($z))
            return null;

        $containerId = $row['container_id'];
        $containertype = $row['container_type_id'];

        $row['container_id'] = Containers::where('company_id',Auth::user()->company_id)->where('code',$row['container_id'])->pluck('id')->first();
        
        // Validation
        if($row['port_location_id'] == null || $row['movement_date'] == null || $row['movement_id'] == null){
            return Session::flash('message', "This Container Number: {$containerId} Must have Movement Code and Activity location and Movement Date");
        }
        if(!$row['container_id']){
            
            return session()->flash('message',"This container Number: {$containerId} Not found ");
        }
        if(!$row['container_type_id']){
            
            return session()->flash('message',"This Container Type: {$containertype} Not found ");
        } 
        
        try {
            // code that triggers a pdo exception
            $dateConvertion = Date::excelToDateTimeObject($row['movement_date'])->format('Y-m-d');
          } catch (Exception $e) {
            $dateConvertion = $row['movement_date'];
          }
          $row['movement_date'] = $dateConvertion;
        
        // Get All movements and sort it and get the last movement before this movement 

        $movements = Movements::where('container_id',$row['container_id'])->orderBy('movement_date','desc')->with('movementcode')->get();
                
        $new = $movements;
        $new = $new->groupBy('movement_date');
        
        foreach($new as $key => $move){
            $move = $move->sortByDesc('movementcode.sequence');
            $new[$key] = $move;
        }
        $new = $new->collapse();
        
        $movements = $new;
        $lastMove = $movements->where('movement_date','<',$row['movement_date'])->pluck('movement_id')->first();
        // End Get All movements and sort it and get the last movement before this movement
        
        $lastMoveCode = ContainersMovement::where('id',$lastMove)->pluck('code')->first();
        $nextMoves = ContainersMovement::where('id',$lastMove)->pluck('next_move')->first();
        $nextMoves = explode(', ',$nextMoves);
        $movementCode = $row['movement_id'];
        
        // dd($lastMoveCode);
        $row['movement_id'] =  ContainersMovement::where('code',$row['movement_id'])->pluck('id')->first();
        $row['container_type_id'] = ContainersTypes::where('name',$row['container_type_id'])->pluck('id')->first();
        $row['container_status'] = ContainersMovement::where('id',$row['movement_id'])->pluck('container_status_id')->first();
        
        
        if($movements->first() != null){
            $moveType = $movements->first()->container_type_id;
            // Check same move type
            if(  $row['container_type_id'] != $moveType){
            return session()->flash('message',"This Container Type: {$containertype} Must be Same as Movements Container Type ");
            }
        }

        // Check same move type
        if(  $row['vessel_id'] == null){
            return session()->flash('message',"You Must Enter a Vessel No");
        }

        $movementdublicate  = Movements::where('container_id',$row['container_id'])->where('movement_id',$row['movement_id'])->where('movement_date',$row['movement_date'])->first();
        
        if($containerId == null){
            return Session::flash('stauts', 'Cannot Container Number be Null please check Excel Sheet');
        }

        if($movementdublicate != null){
            return Session::flash('message', 'This Container Number: '.$containerId.' With This Movement Code: '.$movementCode.' Already Exists!');
        }
        $user = Auth::user();
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
            $movement->company_id = $user->company_id;
            $movement->save();
             
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
            $movement->company_id = $user->company_id;
            $movement->save();
        }else{
   
            MovementImportErrors::create([
                'container_id' => $containerId,
                'date' => $row['movement_date'],
                'error_code' => $movementCode,
                'allowed_code' => implode(", ", $nextMoves)
            ]);
            return Session::flash('message', 'Error in Allowed Next Movement');
        }
        

        return $movement;
    }


    
}

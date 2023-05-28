<?php

namespace App\Imports;

use App\Models\Booking\BookingContainerDetails;
use Illuminate\Support\Facades\Session;
use App\Models\Master\Containers;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Ports;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class BookingImport implements ToModel,WithHeadingRow
{
    private $booking_id;

    public function setExtraParameter($booking_id)
    {
        $this->booking_id = $booking_id;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // CHECK if null
        $a = collect($row);
        $z = $a->filter(fn($v)=>$v != null)->toArray();
        if(empty($z))
            return null;

        $containerId = $row['container_id'];
        $booking_id = $this->booking_id;
        $row['container_id'] = Containers::where('company_id',Auth::user()->company_id)->where('code',$row['container_id'])->pluck('id')->first();
        $row['activity_location'] = Ports::where('company_id',Auth::user()->company_id)->where('code',$row['activity_location'])->pluck('id')->first();
        $row['container_type'] = ContainersTypes::where('company_id',Auth::user()->company_id)->where('name',$row['container_type'])->pluck('id')->first();
        $containerDuplicate = BookingContainerDetails::where('booking_id',$booking_id)->where('container_id',$row['container_id'])->count();
        // Validation
        if(!$row['container_id']){
            return session()->flash('message',"This container Number: {$containerId} Not found and containers before this container imported successfully");
        }
        if($containerDuplicate > 0 ){
            return Session::flash('message', "This Container Number: {$containerId} Already in this booking and containers before this container imported successfully");
        }
        
        
        $bookingDetail = BookingContainerDetails::create([
            'booking_id' => $booking_id,
            'seal_no' => $row['seal_no'],
            'qty' => $row['qty'],
            'activity_location_id' => $row['activity_location'],
            'container_id' => $row['container_id'],
            'container_type' => $row['container_type'],
            'haz' => $row['haz'],
            'weight' => $row['weight'],
            'vgm' => $row['vgm'],
        ]);

        return $bookingDetail;
    }


    
}

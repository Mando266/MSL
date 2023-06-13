<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use App\Models\Bl\BlDraft;
use Illuminate\Http\Request;
use App\Models\Containers\Demurrage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Master\ContainersMovement;
use App\Models\Containers\Movements;
use App\Models\Master\Containers;

class StorageController extends Controller
{

    public function index()
    {
        $now = Carbon::now()->format('Y-m-d');
        $movementsBlNo = BlDraft::where('company_id',Auth::user()->company_id)->get();
        $containers = [];
        $demurrages = Demurrage::where('company_id',Auth::user()->company_id)
        ->where('is_storge','!=','Detention')->where('validity_to','>=',$now)
        ->get();
        $movementsCode = ContainersMovement::orderBy('id')->get();
        // dd(request()->input('calculation'));

        return view('storage.index',[
            'movementsBlNo'=>$movementsBlNo,
            'containers' =>$containers,
            'demurrages' =>$demurrages,
            'movementsCode' =>$movementsCode,
        ]);
    }


    public function create()
    {

    }

    
    public function store(Request $request)
    {
        $rules = [
            'to' => 'required_without_all:date',
            'date' => 'required_without_all:to',
        ];
        
        $request->validate($rules);
        
        $triff = Demurrage::where('id', $request->Triff_id)->with('periods')->first();
        
        $containerCalc = collect();

        if($request->container_code == "all"){
            // Getting All containers for this Bl 
            $mov = Movements::where('bl_no', $request->bl_no)->where('company_id',Auth::user()->company_id)->distinct()->get()->pluck('container_id')->toarray();
            $containers = Containers::whereIn('id',$mov)->get();
            // Searching in container movements For the begining movement to the end move to get the difference in days 
            $fromMoveId = $request->from == 'RCVS' ? 3 : 5;
            $grandTotal = 0;
            foreach($containers as $container){
                $periodCalc = collect();
                // Calculation of each Container
                $containerTotal = 0;
                $fromMovement = Movements::where('container_id',$container->id)->where('movement_id', $fromMoveId)
                                        ->where('bl_no', $request->bl_no)->first();
                if($request->date == null){
                    $toMovement = Movements::where('container_id',$container->id)->where('movement_id', $request->to)
                                            ->where('bl_no', $request->bl_no)->first();
                }else{
                    $toMovement = $request->date;
                }
                if($toMovement != null){
                    if(optional($toMovement)->movement_date != null){
                        $daysCount = Carbon::parse($toMovement->movement_date)->diffInDays($fromMovement->movement_date);
                    }else{
                        $daysCount = Carbon::parse($toMovement)->diffInDays($fromMovement->movement_date);
                    }
                }else{
                    // if there is no till movement
                    $now = Carbon::now()->format('Y-m-d');
                    $daysCount = Carbon::parse($now)->diffInDays($fromMovement->movement_date);
                }
                    $daysCount ++;
                    $tempDaysCount = $daysCount;
                    // Calculation of each period
                    foreach($triff->periods as $period){
                        if($tempDaysCount != 0){
                            if($period->number_off_dayes < $tempDaysCount){
                                // remaining days more than period days
                                $tempDaysCount = $tempDaysCount - $period->number_off_dayes;
                                $periodtotal = $period->rate * $period->number_off_dayes;
                                $containerTotal = $containerTotal + $periodtotal;
                                $tempCollection = collect([
                                    'name' => $period->period,
                                    'days' => $period->number_off_dayes,
                                    'rate' => $period->rate,
                                    'total' => $periodtotal,
                                ]);
                                // Adding period
                                $periodCalc->add($tempCollection);
                            }else{
                                // remaining days less than period days
                                $periodtotal = $period->rate * $tempDaysCount;
                                $containerTotal = $containerTotal + $periodtotal;
                                $tempCollection = collect([
                                    'name' => $period->period,
                                    'days' => $tempDaysCount,
                                    'rate' => $period->rate,
                                    'total' => $periodtotal,
                                ]);
                                // Adding period
                                $periodCalc->add($tempCollection);
                                $tempDaysCount = 0;
                            }
                        }
                        
                    }
                
                // Adding Container with periods
                $grandTotal = $grandTotal + $containerTotal;
                $tempCollection = collect([
                    'container_no' => $container->code,
                    'from' => $fromMovement->movement_date,
                    'to' => $toMovement != null ? (optional($toMovement)->movement_date != null ? $toMovement->movement_date : $toMovement) : $now,
                    'total' => $containerTotal,
                    'periods' => $periodCalc,
                ]);
                $containerCalc->add($tempCollection);
            }
        }else{
            
        }
                $calculation = collect([
                    'grandTotal' => $grandTotal,
                    'currency' => $triff->currency,
                    'containers' => $containerCalc,
                ]);
        // return redirect()->back()->with(['calculation'=>$calculation])->withInput($request->input());
        return redirect()->route('storage.index')->with([
            'calculation' => $calculation,
            'input' => $request->input()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

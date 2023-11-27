<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use App\Models\Bl\BlDraft;
use App\Models\Containers\Demurrage;
use App\Models\Containers\Movements;
use App\Models\Master\Containers;
use App\Models\Master\ContainersMovement;
use App\TariffType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StorageController extends Controller
{
// for storage & power calc
    public function index()
    {
        $now = Carbon::now()->format('Y-m-d');
        $movementsBlNo = BlDraft::where('company_id', Auth::user()->company_id)->get();
        $containers = [];
        $demurrages = Demurrage::where('company_id', Auth::user()->company_id)
            ->where('is_storge', '!=', 'Detention')->where('validity_to', '>=', $now)
            ->get();
        $movementsCode = ContainersMovement::orderBy('id')->get();
        $tariffType = ['ESTO', 'ISTO', 'IEST', 'EEST', 'PCEX', 'PCIM'];
        $services = TariffType::whereIn('code', $tariffType)->get();
        // dd(request()->input('calculation'));

        return view('storage.index', [
            'movementsBlNo' => $movementsBlNo,
            'containers' => $containers,
            'demurrages' => $demurrages,
            'movementsCode' => $movementsCode,
            'services' => $services,
        ]);
    }

// testing for demurrage calc
    public function create()
    {
        $now = Carbon::now()->format('Y-m-d');
        $movementsBlNo = BlDraft::where('company_id', Auth::user()->company_id)->get();
        $containers = [];
        $demurrages = Demurrage::where('company_id', Auth::user()->company_id)
            ->where('is_storge', '!=', 'Detention')->where('validity_to', '>=', $now)
            ->get();
        $movementsCode = ContainersMovement::orderBy('id')->get();
        $tariffType = ['EDET', 'IDET'];
        $services = TariffType::whereIn('code', $tariffType)->get();

        return view('storage.demurrage-index', [
            'movementsBlNo' => $movementsBlNo,
            'containers' => $containers,
            'demurrages' => $demurrages,
            'movementsCode' => $movementsCode,
            'services' => $services,
        ]);
    }


    public function store(Request $request)
    {
        $rules = [
            'from' => 'required',
//            'to' => 'nullable_without_all:date',
//            'date' => 'nullable_without_all:to',
        ];

        $request->validate($rules);
        $route = 'storage.index';

        if(in_array($request->service,[1,3])){
            $route = 'storage.create';
        }
        $bl_no = BlDraft::where('id', $request->bl_no)->pluck('ref_no')->first();
        $bldraft = BlDraft::where('id', $request->bl_no)->with('booking.quotation')->first();
        $triff = Demurrage::where('id', $request->Triff_id)->with('slabs.periods')->first();
        $containerCalc = collect();
        if (count(request()->container_code) == 1) {
            if (request()->container_code[0] == "all") {
                // Getting All containers for this Bl
                $mov = Movements::where('bl_no', $bl_no)->where('company_id', Auth::user()->company_id)->distinct()->get()->pluck('container_id')->toarray();
                $containers = Containers::whereIn('id', $mov)->get();
                // Searching in container movements For the begining movement to the end move to get the difference in days

                $grandTotal = 0;
                foreach ($containers as $container) {
                    $periodCalc = collect();
                    // Calculation of each Container
                    $containerTotal = 0;
                    $fromMovement = Movements::where('container_id', $container->id)->where('movement_id', request()->from)
                        ->where('bl_no', $bl_no)->first();
                    if (!isset($fromMovement)) {
                        return redirect()->route($route)->with([
                            'error', 'there is No From Movement for Container No ' . $container->code,
                            'input' => $request->input()
                        ]);
                    }
                    if ($request->date == null && $request->to == null) {
                        $toMovement = Movements::where('container_id', $container->id)
                            ->where('movement_date', '>', $fromMovement->movement_date)->oldest()->first();
                    } elseif ($request->date == null) {
                        $toMovement = Movements::where('container_id', $container->id)->where('movement_id', $request->to)
                            ->where('movement_date', '>', $fromMovement->movement_date)->oldest()->first();
                    } else {
                        $toMovement = Movements::where('container_id', $container->id)
                            ->where('movement_date', '>', $fromMovement->movement_date)->oldest()->first();
                        if ($toMovement == null) {
                            $toMovement = $request->date;
                        } elseif ($request->date < $toMovement->movement_date) {
                            $toMovement = $request->date;
                        }
                    }
                    if ($toMovement != null) {
                        if (optional($toMovement)->movement_date != null) {
                            $daysCount = Carbon::parse($toMovement->movement_date)->diffInDays($fromMovement->movement_date);
                        } else {
                            $daysCount = Carbon::parse($toMovement)->diffInDays($fromMovement->movement_date);
                        }
                    } else {
                        // if there is no till movement
                        $now = Carbon::now()->format('Y-m-d');
                        $daysCount = Carbon::parse($now)->diffInDays($fromMovement->movement_date);
                    }
                    $daysCount = $daysCount + 1;
                    $tempDaysCount = $daysCount;
                    if (request()->service == 3) {
                        $quotationFreeTime = $bldraft->booking->quotation->import_detention;
                        $exportFreeTime = $quotationFreeTime;
                    } elseif (request()->service == 1) {
                        $quotationFreeTime = $bldraft->booking->quotation->export_detention;
                        $exportFreeTime = $quotationFreeTime;
                    }
                    // Calculation of each period
                    foreach ($triff->slabs as $slab) {
                        if ($slab->container_type_id == $container->container_type_id) {
                            foreach ($slab->periods as $period) {
                                //for Detention just export and import
                                if (request()->service == 3 || request()->service == 1) {
                                    //we are in the free time period
                                    if ($quotationFreeTime > $period->number_off_dayes) {
                                        if ($tempDaysCount != 0) {
                                            if ($period->number_off_dayes < $tempDaysCount) {
                                                // remaining days more than period days
                                                $tempDaysCount = $tempDaysCount - $period->number_off_dayes;
                                                $quotationFreeTime = $quotationFreeTime - $period->number_off_dayes;

                                                $periodtotal = 0 * $period->number_off_dayes;
                                                $containerTotal = $containerTotal + $periodtotal;
                                                $tempCollection = collect([
                                                    'name' => $period->period,
                                                    'days' => $period->number_off_dayes,
                                                    'rate' => $period->rate,
                                                    'total' => $periodtotal,
                                                ]);
                                                // Adding period
                                                $periodCalc->add($tempCollection);
                                            } else {
                                                // remaining days less than period days
                                                $periodtotal = 0 * $tempDaysCount;
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
                                    } else {
                                        if ($tempDaysCount != 0) {
                                            if ($period->number_off_dayes < $tempDaysCount) {
                                                // remaining days more than period days
                                                $tempDaysCount = $tempDaysCount - $period->number_off_dayes;
                                                $days = $period->number_off_dayes - $quotationFreeTime;
                                                $periodtotal = (0 * $quotationFreeTime) + ($period->rate * $days);
                                                $containerTotal = $containerTotal + $periodtotal;
                                                $tempCollection = collect([
                                                    'name' => $period->period,
                                                    'days' => $period->number_off_dayes,
                                                    'rate' => $period->rate,
                                                    'total' => $periodtotal,
                                                ]);
                                                // Adding period
                                                $periodCalc->add($tempCollection);
                                                $quotationFreeTime = 0;
                                            } else {
                                                // remaining days less than period days
                                                $days = $tempDaysCount - $quotationFreeTime;
                                                $periodtotal = (0 * $quotationFreeTime) + ($period->rate * $days);
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
                                                $quotationFreeTime = 0;
                                            }
                                        }
                                    }
                                } else {
                                    if ($tempDaysCount != 0) {
                                        if ($period->number_off_dayes < $tempDaysCount) {
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
                                        } else {
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

                            }
                        }
                    }


                    // Adding Container with periods
                    $grandTotal = $grandTotal + $containerTotal;
                    $tempCollection = collect([
                        'container_no' => $container->code,
                        'container_type' => $container->containersTypes->name,
                        'from' => $fromMovement->movement_date,
                        'to' => $toMovement != null ? (optional($toMovement)->movement_date != null ? $toMovement->movement_date : $toMovement) : $now,
                        'from_code'=>$fromMovement->movementcode->code,
                        'to_code'=>$toMovement != null ? (optional($toMovement)->movement_date != null ? $toMovement->movementcode->code : $toMovement) : $now,
                        'total' => $containerTotal,
                        'periods' => $periodCalc,
                    ]);
                    $containerCalc->add($tempCollection);
                }
            } else {
                $containers = Containers::whereIn('id', request()->container_code)->get();
                // Searching in container movements For the begining movement to the end move to get the difference in days
                $grandTotal = 0;
                foreach ($containers as $container) {
                    $periodCalc = collect();
                    // Calculation of each Container
                    $containerTotal = 0;
                    $fromMovement = Movements::where('container_id', $container->id)->where('movement_id', request()->from)
                        ->where('bl_no', $bl_no)->first();
                    if (!isset($fromMovement)) {
                        return redirect()->route($route)->with([
                            'error', 'there is No From Movement for Container No ' . $container->code,
                            'input' => $request->input()
                        ]);
                    }
                    if ($request->date == null && $request->to == null) {
                        $toMovement = Movements::where('container_id', $container->id)
                            ->where('movement_date', '>', $fromMovement->movement_date)->oldest()->first();
                    } elseif ($request->date == null) {
                        $toMovement = Movements::where('container_id', $container->id)->where('movement_id', $request->to)
                            ->where('movement_date', '>', $fromMovement->movement_date)->oldest()->first();
                    } else {
                        $toMovement = Movements::where('container_id', $container->id)
                            ->where('movement_date', '>', $fromMovement->movement_date)->oldest()->first();
                        if ($toMovement == null) {
                            $toMovement = $request->date;
                        } elseif ($request->date < $toMovement->movement_date) {
                            $toMovement = $request->date;
                        }
                    }
                    if ($toMovement != null) {
                        if (optional($toMovement)->movement_date != null) {
                            $daysCount = Carbon::parse($toMovement->movement_date)->diffInDays($fromMovement->movement_date);
                        } else {
                            $daysCount = Carbon::parse($toMovement)->diffInDays($fromMovement->movement_date);
                        }
                    } else {
                        // if there is no till movement
                        $now = Carbon::now()->format('Y-m-d');
                        $daysCount = Carbon::parse($now)->diffInDays($fromMovement->movement_date);
                    }
                    $daysCount = $daysCount + 1;
                    $tempDaysCount = $daysCount;
                    // Calculation of each period
                    if (request()->service == 3) {
                        $quotationFreeTime = $bldraft->booking->quotation->import_detention;
                        $exportFreeTime = $quotationFreeTime;
                    } elseif (request()->service == 1) {
                        $quotationFreeTime = $bldraft->booking->quotation->export_detention;
                        $exportFreeTime = $quotationFreeTime;
                    }
                    foreach ($triff->slabs as $slab) {
                        if ($slab->container_type_id == $container->container_type_id) {
                            foreach ($slab->periods as $period) {
                                if (request()->service == 3 || request()->service == 1) {

                                    //we are in the free time period
                                    if ($quotationFreeTime > $period->number_off_dayes) {
                                        if ($tempDaysCount != 0) {
                                            if ($period->number_off_dayes < $tempDaysCount) {
                                                // remaining days more than period days
                                                $tempDaysCount = $tempDaysCount - $period->number_off_dayes;
                                                $quotationFreeTime = $quotationFreeTime - $period->number_off_dayes;
                                                $periodtotal = 0 * $period->number_off_dayes;
                                                $containerTotal = $containerTotal + $periodtotal;
                                                $tempCollection = collect([
                                                    'name' => $period->period,
                                                    'days' => $period->number_off_dayes,
                                                    'rate' => $period->rate,
                                                    'total' => $periodtotal,
                                                ]);
                                                // Adding period
                                                $periodCalc->add($tempCollection);
                                            } else {
                                                // remaining days less than period days
                                                $periodtotal = 0 * $tempDaysCount;
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
                                    } else {
                                        if ($tempDaysCount != 0) {
                                            if ($period->number_off_dayes < $tempDaysCount) {
                                                // remaining days more than period days
                                                $tempDaysCount = $tempDaysCount - $period->number_off_dayes;
                                                $days = $period->number_off_dayes - $quotationFreeTime;
                                                $periodtotal = (0 * $quotationFreeTime) + ($period->rate * $days);
                                                $containerTotal = $containerTotal + $periodtotal;
                                                $tempCollection = collect([
                                                    'name' => $period->period,
                                                    'days' => $period->number_off_dayes,
                                                    'rate' => $period->rate,
                                                    'total' => $periodtotal,
                                                ]);
                                                // Adding period
                                                $periodCalc->add($tempCollection);
                                                $quotationFreeTime = 0;
                                            } else {
                                                // remaining days less than period days
                                                $days = $tempDaysCount - $quotationFreeTime;
                                                $periodtotal = (0 * $quotationFreeTime) + ($period->rate * $days);
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
                                                $quotationFreeTime = 0;
                                            }
                                        }
                                    }
                                } else {
                                    if ($tempDaysCount != 0) {
                                        if ($period->number_off_dayes < $tempDaysCount) {
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
                                        } else {
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
                            }
                        }
                    }

                    // Adding Container with periods
                    $grandTotal = $grandTotal + $containerTotal;
                    $tempCollection = collect([
                        'container_no' => $container->code,
                        'container_type' => $container->containersTypes->name,
                        'from' => $fromMovement->movement_date,
                        'to' => $toMovement != null ? (optional($toMovement)->movement_date != null ? $toMovement->movement_date : $toMovement) : $now,
                        'from_code'=>$fromMovement->movementcode->code,
                        'to_code'=>$toMovement != null ? (optional($toMovement)->movement_date != null ? $toMovement->movementcode->code : $toMovement) : $now,
                        'total' => $containerTotal,
                        'periods' => $periodCalc,
                    ]);
                    $containerCalc->add($tempCollection);
                }
            }
        }else{
            if(count(request()->container_code) > 0 ){
                $containers = Containers::whereIn('id',request()->container_code)->get();
                // Searching in container movements For the begining movement to the end move to get the difference in days
                $grandTotal = 0;
                foreach ($containers as $container) {
                    $periodCalc = collect();
                    // Calculation of each Container
                    $containerTotal = 0;
                    $fromMovement = Movements::where('container_id', $container->id)->where('movement_id', request()->from)
                        ->where('bl_no', $bl_no)->first();
                    if (!isset($fromMovement)) {
                        return redirect()->route($route)->with([
                            'error', 'there is No From Movement for Container No ' . $container->code,
                            'input' => $request->input()
                        ]);
                    }
                    if ($request->date == null && $request->to == null) {
                        $toMovement = Movements::where('container_id', $container->id)
                            ->where('movement_date', '>', $fromMovement->movement_date)->oldest()->first();
                    } elseif ($request->date == null) {
                        $toMovement = Movements::where('container_id', $container->id)->where('movement_id', $request->to)
                            ->where('movement_date', '>', $fromMovement->movement_date)->oldest()->first();
                    } else {
                        $toMovement = Movements::where('container_id', $container->id)
                            ->where('movement_date', '>', $fromMovement->movement_date)->oldest()->first();
                        if ($toMovement == null) {
                            $toMovement = $request->date;
                        } elseif ($request->date < $toMovement->movement_date) {
                            $toMovement = $request->date;
                        }
                    }
                    if ($toMovement != null) {
                        if (optional($toMovement)->movement_date != null) {
                            $daysCount = Carbon::parse($toMovement->movement_date)->diffInDays($fromMovement->movement_date);
                        } else {
                            $daysCount = Carbon::parse($toMovement)->diffInDays($fromMovement->movement_date);
                        }
                    } else {
                        // if there is no till movement
                        $now = Carbon::now()->format('Y-m-d');
                        $daysCount = Carbon::parse($now)->diffInDays($fromMovement->movement_date);
                    }
                    $daysCount = $daysCount + 1;
                    $tempDaysCount = $daysCount;
                    // Calculation of each period
                    if (request()->service == 3) {
                        $quotationFreeTime = $bldraft->booking->quotation->import_detention;
                        $exportFreeTime = $quotationFreeTime;
                    } elseif (request()->service == 1) {
                        $quotationFreeTime = $bldraft->booking->quotation->export_detention;
                        $exportFreeTime = $quotationFreeTime;
                    }
                    foreach ($triff->slabs as $slab) {
                        if ($slab->container_type_id == $container->container_type_id) {
                            foreach ($slab->periods as $period) {
                                if (request()->service == 3 || request()->service == 1) {

                                    //we are in the free time period
                                    if ($quotationFreeTime > $period->number_off_dayes) {
                                        if ($tempDaysCount != 0) {
                                            if ($period->number_off_dayes < $tempDaysCount) {
                                                // remaining days more than period days
                                                $tempDaysCount = $tempDaysCount - $period->number_off_dayes;
                                                $quotationFreeTime = $quotationFreeTime - $period->number_off_dayes;
                                                $periodtotal = 0 * $period->number_off_dayes;
                                                $containerTotal = $containerTotal + $periodtotal;
                                                $tempCollection = collect([
                                                    'name' => $period->period,
                                                    'days' => $period->number_off_dayes,
                                                    'rate' => $period->rate,
                                                    'total' => $periodtotal,
                                                ]);
                                                // Adding period
                                                $periodCalc->add($tempCollection);
                                            } else {
                                                // remaining days less than period days
                                                $periodtotal = 0 * $tempDaysCount;
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
                                    } else {
                                        if ($tempDaysCount != 0) {
                                            if ($period->number_off_dayes < $tempDaysCount) {
                                                // remaining days more than period days
                                                $tempDaysCount = $tempDaysCount - $period->number_off_dayes;
                                                $days = $period->number_off_dayes - $quotationFreeTime;
                                                $periodtotal = (0 * $quotationFreeTime) + ($period->rate * $days);
                                                $containerTotal = $containerTotal + $periodtotal;
                                                $tempCollection = collect([
                                                    'name' => $period->period,
                                                    'days' => $period->number_off_dayes,
                                                    'rate' => $period->rate,
                                                    'total' => $periodtotal,
                                                ]);
                                                // Adding period
                                                $periodCalc->add($tempCollection);
                                                $quotationFreeTime = 0;
                                            } else {
                                                // remaining days less than period days
                                                $days = $tempDaysCount - $quotationFreeTime;
                                                $periodtotal = (0 * $quotationFreeTime) + ($period->rate * $days);
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
                                                $quotationFreeTime = 0;
                                            }
                                        }
                                    }
                                } else {
                                    if ($tempDaysCount != 0) {
                                        if ($period->number_off_dayes < $tempDaysCount) {
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
                                        } else {
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
                            }
                        }
                    }

                    // Adding Container with periods
                    $grandTotal = $grandTotal + $containerTotal;
                    $tempCollection = collect([
                        'container_no' => $container->code,
                        'container_type' => $container->containersTypes->name,
                        'from' => $fromMovement->movement_date,
                        'to' => $toMovement != null ? (optional($toMovement)->movement_date != null ? $toMovement->movement_date : $toMovement) : $now,
                        'from_code'=>$fromMovement->movementcode->code,
                        'to_code'=>$toMovement != null ? (optional($toMovement)->movement_date != null ? $toMovement->movementcode->code : $toMovement) : $now,
                        'total' => $containerTotal,
                        'periods' => $periodCalc,
                    ]);
                    $containerCalc->add($tempCollection);
                }
            }
        }
        $calculation = collect([
            'grandTotal' => $grandTotal,
            'currency' => $triff->currency,
            'containers' => $containerCalc,
        ]);
        // return redirect()->back()->with(['calculation'=>$calculation])->withInput($request->input());
        $data = [
            'calculation' => $calculation,
            'freetime' => (request()->service == 3 || request()->service == 1) ? $exportFreeTime : 0,
            'input' => $request->input()
        ];
        session(['calculations'=> $data]);
        return redirect()->route($route)->with($data);
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

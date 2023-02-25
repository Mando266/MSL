<?php

namespace App\Http\Controllers\Trucker;

use App\Http\Controllers\Controller;
use App\Filters\Trucker\TruckerIndexFilter;
use App\Models\Trucker\TruckerGates;
use App\Models\Booking\Booking;
use App\Models\Trucker\Trucker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Mail;

class TruckerGateController extends Controller
{

    public function index()
    {
        $this->authorize(__FUNCTION__,TruckerGates::class);
            $truckerGates = TruckerGates::with('booking.bookingContainerDetails')->filter(new TruckerIndexFilter(request()))->paginate(30);
            $exportTruckerGate = TruckerGates::with('booking.bookingContainerDetails')->filter(new TruckerIndexFilter(request()))->get();
           // dd($truckerGates);
            $booking = Booking::where('booking_confirm',1)->get();
            $truckers = Trucker::get();
            session()->flash('truckergates',$exportTruckerGate);
            
            return view('trucker.truckergate.index',[
                'items'=>$truckerGates,
                'booking'=>$booking,
                'truckers'=>$truckers,
                'exportTruckerGate'=>$exportTruckerGate,
            ]);  
        }

    public function create()
    {
        $this->authorize(__FUNCTION__,TruckerGates::class);
            $booking = Booking::where('booking_confirm',1)->get();
            $truckers = Trucker::get();
            return view('trucker.truckergate.create',[
                'booking'=>$booking,
                'truckers'=>$truckers,
            ]);    
    }

    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,TruckerGates::class);
        // $BookingDublicate  = TruckerGates::where('company_id',$user->company_id)->where('booking_id',$request->booking_id)->first();
        // if($BookingDublicate != null){
        //     return back()->with('alert','This Booking No Already Exists');
        // }
        $user = Auth::user();

        $certificate_type = 'PL-100-61-6004-2023-84/';
        $truckerGate = TruckerGates::create($request->except('_token'));
        $certificate_type .=$truckerGate->id;
        $truckerGate->certificate_type = $certificate_type;
        $truckerGate->company_id = $user->company_id;
        $truckerGate->save();

        return redirect()->route('truckergate.index')->with('success',trans('Trucker Gate.Created'));
    }

    public function show($id)
    {
        //
    }

    public function edit(TruckerGates $truckergate)
    {
        $this->authorize(__FUNCTION__,TruckerGates::class);
        $booking = Booking::where('booking_confirm',1)->get();
        $truckers = Trucker::get();
        return view('trucker.truckergate.edit',[
            'booking'=>$booking,
            'truckers'=>$truckers,
            'truckergate'=>$truckergate,
        ]);
    }

   
    public function update(Request $request, TruckerGates $truckergate)
    {
        $this->authorize(__FUNCTION__,TruckerGates::class);

        // $user = Auth::user();
        // $BookingDublicate  = TruckerGates::where('id','!=',$truckerGates->id)->where('company_id',$user->company_id)->where('booking_id',$request->booking_id)->count();
        // if($BookingDublicate > 0){
        //     return back()->with('alert','This Booking No Already Exists');
        // }

        $truckergate->update($request->except('_token'));
        return redirect()->route('truckergate.index')->with('success',trans('Trucker Gate.Updated'));

    }

    public function destroy($id)
    {
        $truckergate = TruckerGates::find($id);
        $truckergate->delete();
        return back()->with('Success',trans('Trucker Gate.Deleted'));    }
}

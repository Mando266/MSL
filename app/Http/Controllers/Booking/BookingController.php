<?php

namespace App\Http\Controllers\Booking;

use App\Filters\Quotation\QuotationIndexFilter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingContainerDetails;
use App\Models\Booking\BookingRefNo;
use App\Models\Master\Agents;
use App\Models\Master\Containers;
use App\Models\Master\ContainersTypes;
use App\Models\Master\CustomerRoles;
use App\Models\Quotations\Quotation;
use App\Models\Master\Customers;
use App\Models\Master\Ports;
use App\Models\Master\Terminals;
use App\Models\Master\Vessels;
use App\Models\Voyages\VoyagePorts;
use App\Models\Voyages\Voyages;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,Booking::class);
        $booking = Booking::filter(new QuotationIndexFilter(request()))->orderBy('id','desc')->where('company_id',Auth::user()->company_id)->with('bookingContainerDetails')->paginate(30);
        $exportbooking = Booking::filter(new QuotationIndexFilter(request()))->orderBy('id','desc')->where('company_id',Auth::user()->company_id)->with('bookingContainerDetails')->get();
        //  dd($exportbooking);
        $voyages    = Voyages::with('vessel')->where('company_id',Auth::user()->company_id)->get();
        $bookingNo = Booking::where('company_id',Auth::user()->company_id)->get();
        $quotation = Quotation::where('company_id',Auth::user()->company_id)->get();
        $ports = Ports::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
        $customers = Customers::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
        session()->flash('bookings',$exportbooking);
        return view('booking.booking.index',[
            'items'=>$booking,
            'bookingNo'=>$bookingNo,
            'voyages'=>$voyages,
            'quotation'=>$quotation,
            'ports'=>$ports,
            'customers'=>$customers,
        ]); 
    }
    public function selectQuotation()
    {
        $quotation  = Quotation::where('company_id',Auth::user()->company_id)->where('status','approved')->with('customer','equipmentsType')->get();
        return view('booking.booking.selectQuotation',[
            'quotation'=>$quotation,
        ]);
    }
    public function create()
    {
        $this->authorize(__FUNCTION__,Booking::class);
        request()->validate([
            'quotation_id' => ['required'],
        ]);
        $ffw = Customers::where('company_id',Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
            return $query->where('role_id', 6);
        })->with('CustomerRoles.role')->get();
        $customers  = Customers::where('company_id',Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
            return $query->where('role_id', '!=', 6);
        })->with('CustomerRoles.role')->get();
        $terminals = Terminals::where('company_id',Auth::user()->company_id)->get();
        if(request('quotation_id') == 'draft'){
            $quotation = new Quotation();
        }else{
            $quotation = Quotation::findOrFail(request('quotation_id'));
            $terminals = Terminals::where('company_id',Auth::user()->company_id)->where('port_id',$quotation->discharge_port_id)->get();
        }
        // dd($quotation);
        $agents = Agents::where('company_id',Auth::user()->company_id)->where('is_active',1)->get();
        $equipmentTypes = ContainersTypes::orderBy('id')->get();
        $terminal   = Terminals::where('company_id',Auth::user()->company_id)->get();
        $vessels    = Vessels::where('company_id',Auth::user()->company_id)->get();
        $voyages    = Voyages::with('vessel')->where('company_id',Auth::user()->company_id)->get();
        $ports = Ports::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
        $containers = Containers::where('company_id',Auth::user()->company_id)->get();
        return view('booking.booking.create',[
            'ffw'=>$ffw,
            'containers'=>$containers,
            'agents'=>$agents,
            'terminals'=>$terminals,
            'equipmentTypes'=>$equipmentTypes,
            'ports'=>$ports,
            'quotation'=>$quotation,
            'terminal'=>$terminal,
            'customers'=>$customers,
            'vessels'=>$vessels,
            'voyages'=>$voyages,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'place_of_delivery_id' => ['required','different:place_of_acceptence_id'],
            'discharge_port_id' => ['required','different:load_port_id'],
        ],[
            'place_of_delivery_id.different'=>'Place Of Delivery The Same  Place Of Acceptence',
            'discharge_port_id.different'=>'Load Port The Same  Discharge Port',
            ]);
            // Validate Containers Unique
            $uniqueContainers = array();
            foreach($request->containerDetails as $container){
                if(!in_array($container['container_id'],$uniqueContainers) ){
                    if($container['container_id'] != "000"){
                        array_push($uniqueContainers,$container['container_id']);
                    }
                }else{
                    return redirect()->back()->with('error','Container Numbers Must be unique')->withInput($request->input());
                }
            }
            // Validate Expiration Date
            $quotation = Quotation::find($request->quotation_id);
            $etaDate = VoyagePorts::where('voyage_id',$request->voyage_id)->where('port_from_name',$request->load_port_id)->pluck('eta')->first();
            if($etaDate > $quotation->validity_to || $etaDate < $quotation->validity_from){
                return redirect()->back()->with('error','Invalid Date '.$etaDate.' Date Must Be Between '.$quotation->validity_from.' and '.$quotation->validity_to)
                ->withInput($request->input());
            }
            $user = Auth::user();

            $booking = Booking::create([
                'ref_no'=> "",
                'booked_by'=>$user->id,
                'company_id'=>$user->company_id,
                'quotation_id'=> $request->input('quotation_id'),
                'customer_id'=> $request->input('customer_id'),
                'equipment_type_id'=> $request->input('equipment_type_id'),
                'bl_release'=> $request->input('bl_release'),
                'place_of_acceptence_id'=> $request->input('place_of_acceptence_id'),
                'load_port_id'=> $request->input('load_port_id'),
                'shipper_ref_no'=> $request->input('shipper_ref_no'),
                'place_of_delivery_id'=> $request->input('place_of_delivery_id'),
                'discharge_port_id'=> $request->input('discharge_port_id'),
                'forwarder_ref_no'=> $request->input('forwarder_ref_no'),
                'voyage_id'=> $request->input('voyage_id'),
                'voyage_id_second'=> $request->input('voyage_id_second'),
                'terminal_id'=> $request->input('terminal_id'),
                'agent_id'=>$request->input('agent_id'),
                'discharge_etd'=>$request->input('discharge_etd'),
                'load_port_cutoff'=>$request->input('load_port_cutoff'),
                'load_port_dayes'=>$request->input('load_port_dayes'),
                'tariff_service'=>$request->input('tariff_service'),
                'commodity_code'=>$request->input('commodity_code'),
                'commodity_description'=>$request->input('commodity_description'),
                'soc'=>$request->input('soc') != null? 1 : 0,
                'imo'=>$request->input('imo') != null? 1 : 0,
                'rf'=>$request->input('rf') != null? 1 : 0,
                'oog'=>$request->input('oog') != null? 1 : 0,
                'ffw_id'=>$request->input('ffw_id'),
                'booking_confirm'=>$request->input('booking_confirm'), 
                'notes'=>$request->input('notes'), 
            ]);

        foreach($request->input('containerDetails',[]) as $details){
            BookingContainerDetails::create([
                'seal_no'=>$details['seal_no'],
                'qty'=>$details['qty'],
                'container_id'=>$details['container_id'],
                'booking_id'=>$booking->id,
                'container_type'=>$booking->equipment_type_id,
                'haz'=>$details['haz'],
            ]);
        }
        $booking = $booking->load('loadPort');
        $bookingCounter = BookingRefNo::where('company_id',$user->company_id)->where('port_of_load_id',$booking->load_port_id)->first();
        if(!isset($bookingCounter)){
            $bookingCounter = BookingRefNo::create([
                'company_id'=>$user->company_id,
                'port_of_load_id'=>$booking->load_port_id,
                'counter'=>0
            ]);
        }
        $bookingCounter->counter++;
        $bookingCounter->save();
        $booking->ref_no = $booking->loadPort->code . sprintf('%05u', $bookingCounter->counter);
        $booking->save();

        if($request->hasFile('certificat')){
            $path = $request->file('certificat')->getClientOriginalName();
            $request->certificat->move(public_path('certificat'), $path);
            $booking->update(['certificat'=>"certificat/".$path]);
        }

        return redirect()->route('booking.index')->with('success',trans('Booking.created'));
    }
    
    public function showShippingOrder($id)
    {
        $booking = Booking::with('bookingContainerDetails.containerType','bookingContainerDetails.container','voyage.vessel','secondvoyage.vessel')->find($id);
        $firstVoyagePort = VoyagePorts::where('voyage_id',$booking->voyage_id)->where('port_from_name',optional($booking->loadPort)->id)->first();
        $secondVoyagePort = VoyagePorts::where('voyage_id',$booking->voyage_id_second)->where('port_from_name',optional($booking->loadPort)->id)->first();
        
        return view('booking.booking.showShippingOrder',[
            'booking'=>$booking,
            'firstVoyagePort'=>$firstVoyagePort,
            'secondVoyagePort'=>$secondVoyagePort
        ]);
    }
    public function show($id)
    {
        $booking = Booking::with('bookingContainerDetails.containerType','bookingContainerDetails.container','voyage.vessel','secondvoyage.vessel')->find($id);
        $firstVoyagePort = VoyagePorts::where('voyage_id',$booking->voyage_id)->where('port_from_name',optional($booking->loadPort)->id)->first();
        $secondVoyagePort = VoyagePorts::where('voyage_id',$booking->voyage_id_second)->where('port_from_name',optional($booking->loadPort)->id)->first();
        
        //dd($booking);
        return view('booking.booking.show',[
            'booking'=>$booking,
            'firstVoyagePort'=>$firstVoyagePort,
            'secondVoyagePort'=>$secondVoyagePort
        ]);
    }

    public function edit(Booking $booking)
    {
        $this->authorize(__FUNCTION__,Booking::class);
        $booking_details = BookingContainerDetails::where('booking_id',$booking->id)->get();
        $ffw = Customers::where('company_id',Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
            return $query->where('role_id', 6);
        })->with('CustomerRoles.role')->get();
        $customers  = Customers::where('company_id',Auth::user()->company_id)->whereHas('CustomerRoles', function ($query) {
            return $query->where('role_id', '!=', 6);
        })->with('CustomerRoles.role')->get();

        if(request('quotation_id') == 'draft'){
            $quotation = new Quotation();
        }else{
            $quotation = Quotation::findOrFail(request('quotation_id'));
            $terminals = Terminals::where('company_id',Auth::user()->company_id)->where('port_id',$quotation->discharge_port_id)->get();
        }
        $agents = Agents::where('company_id',Auth::user()->company_id)->where('is_active',1)->get();
        $equipmentTypes = ContainersTypes::orderBy('id')->get();
        $terminal   = Terminals::where('company_id',Auth::user()->company_id)->get();
        $vessels    = Vessels::where('company_id',Auth::user()->company_id)->get();
        $voyages    = Voyages::where('company_id',Auth::user()->company_id)->with('vessel')->get();
        $ports = Ports::where('company_id',Auth::user()->company_id)->orderBy('id')->get();
        $containers = Containers::where('company_id',Auth::user()->company_id)->get();

        return view('booking.booking.edit',[
            'booking_details'=>$booking_details,
            'booking'=>$booking,
            'ffw'=>$ffw,
            'containers'=>$containers,
            'agents'=>$agents,
            'terminals'=>$terminals,
            'equipmentTypes'=>$equipmentTypes,
            'ports'=>$ports,
            'terminal'=>$terminal,
            'customers'=>$customers,
            'vessels'=>$vessels,
            'voyages'=>$voyages,
            'quotation'=>$quotation,
        ]);
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'voyage_id' => ['required'],
            'commodity_description' =>['required'],
            'bl_release' =>['required'],
            'customer_id' => ['required'], 
        ]);
        $user = Auth::user();
        $ReferanceNumber  = Booking::where('id','!=',$booking->id)->where('company_id',$user->company_id)->where('ref_no',$request->ref_no)->count();

        if($ReferanceNumber > 0){
            return back()->with('error','The Booking Refrance Number Already Exists');
        }
        $this->authorize(__FUNCTION__,Booking::class);
        $inputs = request()->all();
        unset($inputs['containerDetails'],$inputs['_token'],$inputs['removed']);
        $booking->update($inputs);
        BookingContainerDetails::destroy(explode(',',$request->removed));

        $booking->createOrUpdateContainerDetails($request->containerDetails);
        if($request->hasFile('certificat')){
            $path = $request->file('certificat')->getClientOriginalName();
            $request->certificat->move(public_path('certificat'), $path);
            $booking->update(['certificat'=>"certificat/".$path]);
        }
        return redirect()->route('booking.index')->with('success',trans('Booking.Updated.Success'));

    }

    public function destroy($id)
    {
        $bookings = Booking::find($id);
        BookingContainerDetails::where('booking_id',$id)->delete();
        $bookings->delete(); 
        return back()->with('success',trans('Booking.Deleted.Success'));
    }
}

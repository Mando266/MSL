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
        $booking = Booking::filter(new QuotationIndexFilter(request()))->orderBy('id','desc')->with('bookingContainerDetails')->paginate(30);
        // dd($booking);
        $bookingNo = Booking::get();
        $quotation = Quotation::get();
        $ports = Ports::orderBy('id')->get();
        $customers = Customers::orderBy('id')->get();
        return view('booking.booking.index',[
            'items'=>$booking,
            'bookingNo'=>$bookingNo,
            'quotation'=>$quotation,
            'ports'=>$ports,
            'customers'=>$customers,
        ]); 
    }
    public function selectQuotation()
    {
        $this->authorize(__FUNCTION__,Booking::class);
        $quotation  = Quotation::where('status','approved')->with('customer','equipmentsType')->get();
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
        $ffw = Customers::whereHas('CustomerRoles', function ($query) {
            return $query->where('role_id', 6);
        })->with('CustomerRoles.role')->get();
        $customers  = Customers::whereHas('CustomerRoles', function ($query) {
            return $query->where('role_id', '!=', 6);
        })->with('CustomerRoles.role')->get();
        $terminals = Terminals::all();
        if(request('quotation_id') == 'draft'){
            $quotation = new Quotation();
        }else{
            $quotation = Quotation::findOrFail(request('quotation_id'));
            $terminals = Terminals::where('port_id',$quotation->discharge_port_id)->get();
        }
        // dd($quotation);
        $agents = Agents::where('is_active',1)->get();
        $equipmentTypes = ContainersTypes::orderBy('id')->get();
        $terminal   = Terminals::get();
        $vessels    = Vessels::get();
        $voyages    = Voyages::with('vessel')->get();
        $ports = Ports::orderBy('id')->get();
        $containers = Containers::all();
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
            'customer_id' => ['required'], 
            'place_of_acceptence_id' => ['required'], 
            'load_port_id' => ['required'], 
            'place_of_delivery_id' => ['required','different:place_of_acceptence_id'],
            'discharge_port_id' => ['required','different:load_port_id'],
            'voyage_id' => ['required'],
            'commodity_description' =>['required'],
            'bl_release' =>['required'],
            'booking_confirm' =>['required'],

        ],[
            'place_of_delivery_id.different'=>'Place Of Delivery The Same  Place Of Acceptence',
            'discharge_port_id.different'=>'Load Port The Same  Discharge Port',
        ]);
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
        return redirect()->route('booking.index')->with('success',trans('Booking.created'));
    }

    public function show($id)
    {
        $booking = Booking::with('bookingContainerDetails.containerType','bookingContainerDetails.container','voyage.vessel','secondvoyage.vessel')->find($id);
        $firstVoyagePort = VoyagePorts::where('voyage_id',$booking->voyage_id)->where('port_from_name','like',optional($booking->dischargePort)->name)->first();
        $secondVoyagePort = VoyagePorts::where('voyage_id',$booking->voyage_id_second)->where('port_from_name','like',optional($booking->dischargePort)->name)->first();
        
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
        $ffw = Customers::whereHas('CustomerRoles', function ($query) {
            return $query->where('role_id', 6);
        })->with('CustomerRoles.role')->get();
        $customers  = Customers::whereHas('CustomerRoles', function ($query) {
            return $query->where('role_id', '!=', 6);
        })->with('CustomerRoles.role')->get();

        if(request('quotation_id') == 'draft'){
            $quotation = new Quotation();
        }else{
            $quotation = Quotation::findOrFail(request('quotation_id'));
            $terminals = Terminals::where('port_id',$quotation->discharge_port_id)->get();
        }
        $agents = Agents::where('is_active',1)->get();
        $equipmentTypes = ContainersTypes::orderBy('id')->get();
        $terminal   = Terminals::get();
        $vessels    = Vessels::get();
        $voyages    = Voyages::with('vessel')->get();
        $ports = Ports::orderBy('id')->get();
        $containers = Containers::all();

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
        ]);
        
        $this->authorize(__FUNCTION__,Booking::class);
        $inputs = request()->all();
        unset($inputs['containerDetails'],$inputs['_token'],$inputs['removed']);
        $booking->update($inputs);
        BookingContainerDetails::destroy(explode(',',$request->removed));
        $booking->createOrUpdateContainerDetails($request->containerDetails);

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
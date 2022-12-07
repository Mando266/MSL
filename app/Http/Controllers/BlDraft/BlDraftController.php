<?php

namespace App\Http\Controllers\BlDraft;

use App\Filters\Quotation\QuotationIndexFilter;
use App\Http\Controllers\Controller;
use App\Models\Bl\BlDraft;
use App\Models\Bl\BlDraftDetails;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingContainerDetails;
use App\Models\Master\Containers;
use App\Models\Master\ContainersTypes;
use App\Models\Master\Customers;
use App\Models\Master\Ports;
use App\Models\Voyages\Voyages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlDraftController extends Controller
{

    public function index()
    {
        $this->authorize(__FUNCTION__,BlDraft::class);
        $blDrafts = BlDraft::filter(new QuotationIndexFilter(request()))->orderBy('id','desc')->with('blDetails')->paginate(30);
        //dd($blDrafts);
        $blDraftNo = BlDraft::get();
        $ports = Ports::orderBy('id')->get();
        $customers = Customers::orderBy('id')->get();
        return view('bldraft.bldraft.index',[
            'items'=>$blDrafts,
            'blDraftNo'=>$blDraftNo,
            'ports'=>$ports,
            'customers'=>$customers,
        ]); 
    }

    public function selectBooking()
    {
        $this->authorize(__FUNCTION__,Booking::class);
        $booking  = Booking::orderBy('id','desc')->where('booking_confirm',1)->with('customer')->get();
        return view('bldraft.bldraft.selectBooking',[
            'booking'=>$booking,
        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,BlDraft::class);
        request()->validate([
            'booking_id' => ['required'],
        ]);
        $customershipper  = Customers::whereHas('CustomerRoles', function ($query) {
            return $query->where('role_id', '=', 1);
        })->with('CustomerRoles.role')->get();
        $customersConsignee  = Customers::whereHas('CustomerRoles', function ($query) {
            return $query->where('role_id', '=', 2);
        })->with('CustomerRoles.role')->get();
        $customersNotifiy = Customers::whereHas('CustomerRoles', function ($query) {
            return $query->whereIn('role_id', [2,3]);
        })->with('CustomerRoles.role')->get();
        $booking = Booking::findOrFail(request('booking_id'));
        $equipmentTypes = ContainersTypes::orderBy('id')->get();
        $ports = Ports::orderBy('id')->get();
        $containers = Containers::orderBy('id')->get();
        $voyages    = Voyages::with('vessel')->get();
        $booking_qyt = BookingContainerDetails::where('booking_id',$booking->id)->where('container_id',000)->sum('qty');
        $booking_containers = BookingContainerDetails::where('booking_id',$booking->id)->where('container_id','!=',000)->with('container')->get();
        // dd($booking_containers);
        return view('bldraft.bldraft.create',[
            'booking_containers'=>$booking_containers,
            'customershipper'=>$customershipper,
            'customersConsignee'=>$customersConsignee,
            'customersNotifiy'=>$customersNotifiy,
            'booking'=>$booking,
            'equipmentTypes'=>$equipmentTypes,
            'ports'=>$ports,
            'containers'=>$containers,
            'voyages'=>$voyages,
            'booking_qyt'=>$booking_qyt,
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
        ],[
            'place_of_delivery_id.different'=>'Place Of Delivery The Same  Place Of Acceptence',
            'discharge_port_id.different'=>'Load Port The Same  Discharge Port',
        ]);
        $user = Auth::user();
        $blDraft = BlDraft::create([
            'booking_id'=> $request->input('booking_id'),
            'company_id'=>$user->company_id,
            'ref_no'=> "",
            'customer_id'=> $request->input('customer_id'),
            'customer_consignee_details'=> $request->input('customer_phone'),
            'customer_notifiy_details'=> $request->input('customer_address'),
            'customer_shipper_details'=> $request->input('customer_shipper_details'),
            'descripions'=> $request->input('descripions'),
            'customer_consignee_id'=> $request->input('customer_consignee_id'),
            'customer_notifiy_id'=> $request->input('customer_notifiy_id'),
            'place_of_acceptence_id'=> $request->input('place_of_acceptence_id'),
            'place_of_delivery_id'=> $request->input('place_of_delivery_id'),
            'load_port_id'=> $request->input('load_port_id'),
            'discharge_port_id'=> $request->input('discharge_port_id'),
            'equipment_type_id'=> $request->input('equipment_type_id'),
            'voyage_id'=> $request->input('voyage_id'),
            'number_of_original'=> $request->input('number_of_original'),
            'declerd_value'=> $request->input('declerd_value'),
            'bl_kind'=> $request->input('bl_kind'),
            'movement'=> $request->input('movement'),
            'date_of_issue'=> $request->input('date_of_issue'),
        ]);

        foreach($request->input('blDraftdetails',[]) as $blDraftdetails){
            BlDraftDetails::create([
                'bl_id'=>$blDraft->id,
                'container_id'=>$blDraftdetails['container_id'],
                'packs'=>$blDraftdetails['packs'],
                'seal_no'=>$blDraftdetails['seal_no'],
                'description'=>$blDraftdetails['description'],
                'gross_weight'=>$blDraftdetails['gross_weight'],
                'measurement'=>$blDraftdetails['measurement'],
            ]);
        }
        return redirect()->route('bldraft.index')->with('success',trans('Bl Draft.Created'));
    }

    public function show($id)
    {
        $blDraft = BlDraft::where('id',$id)->with('blDetails')->first();
        return view('bldraft.bldraft.show',[
            'blDraft'=>$blDraft
            ]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $bldraft = BlDraft::find($id);
        BlDraftDetails::where('bl_id',$id)->delete();
        $bldraft->delete(); 
        return back()->with('success',trans('BlDraft.Deleted.Success'));
    }
}

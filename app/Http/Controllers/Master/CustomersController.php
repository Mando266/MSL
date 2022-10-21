<?php

namespace App\Http\Controllers\Master;

use App\Models\Master\Country;
use App\Models\Master\CustomerRoles;
use App\Models\Master\Customers;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class CustomersController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,Customers::class);

        $customers = Customers::orderBy('id')->paginate(10);
        return view('master.customers.index',[
            'items'=>$customers,
        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,Customers::class);
        $countries = Country::orderBy('name')->get();
        $customer_roles = CustomerRoles::orderBy('id')->get();
        $users = User::orderBy('id')->get();
        return view('master.customers.create',[
            'countries'=>$countries,
            'customer_roles'=>$customer_roles,
            'users'=>$users,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,Customers::class);
        $request->validate([
            'name' => 'required',
        ]);
        $country = Country::where('id',$request->country_id)->pluck('prefix')->first();
        $code = $country.$request->input('name').'-';
        $customers = Customers::create([
            'code'=> "",
            'name'=> $request->input('name'),
            'tax_card_no'=> $request->input('tax_card_no'),
            'phone'=> $request->input('phone'),
            'city'=> $request->input('city'),
            'email'=> $request->input('email'),
            'country_id'=> $request->input('country_id'),
            'sales_person_id'=> $request->input('sales_person_id'),
            'customer_role_id'=> $request->input('customer_role_id'),
            'company_id'=> $request->input('company_id'),
            'contact_person'=>$request->input('contact_person'),
        ]);
        $code .=$customers->id;
        $customers->code = $code;
        $customers->save();
        return redirect()->route('customers.index')->with('success',trans('Customer.created'));
    }

    public function show(Customers $customer)
    {
        $this->authorize(__FUNCTION__,Customers::class);
        $customer = $customer->load('country');
        return view('master.customers.show',[
            'customer'=>$customer,
        ]);    
    }

    public function edit(Customers $customer)
    {
        $this->authorize(__FUNCTION__,Customers::class);
        $countries = Country::orderBy('name')->get();
        $customer_roles = CustomerRoles::orderBy('id')->get();
        $users = User::orderBy('id')->get();
        return view('master.customers.edit',[
            'customer'=>$customer,
            'countries'=>$countries,
            'customer_roles'=>$customer_roles,
            'users'=>$users,
        ]); 
    }

    public function update(Request $request, Customers $customer)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $this->authorize(__FUNCTION__,Customers::class);
        $customer->update($request->except('_token'));
        return redirect()->route('customers.index')->with('success',trans('customer.updated.success'));
    }

    public function destroy($id)
    {
        $customer =Customers::Find($id);
        $customer->delete();
        return redirect()->route('customers.index')->with('success',trans('customer.deleted.success'));
    }
}

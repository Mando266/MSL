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
        $customers = Customers::create($request->except('_token'));
        return redirect()->route('customers.index')->with('success',trans('Customer.created'));    }

    public function show($id)
    {
        //
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

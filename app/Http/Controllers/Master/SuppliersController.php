<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Country;
use App\Models\Master\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuppliersController extends Controller
{
    public function index()
    {
        $this->authorize(__FUNCTION__,Suppliers::class);

            $suppliers = Suppliers::where('company_id',Auth::user()->company_id)->orderBy('id')->paginate(30);
            
        return view('master.suppliers.index',[
            'items'=>$suppliers,
        ]);
    }

    public function create()
    {
        $this->authorize(__FUNCTION__,Suppliers::class);
        $countries = Country::orderBy('name')->get();
        return view('master.suppliers.create',[
            'countries'=>$countries,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize(__FUNCTION__,Suppliers::class);
        $user = Auth::user();

        $NameDublicate  = Suppliers::where('company_id',$user->company_id)->where('name',$request->name)->first();
        if($NameDublicate != null){
            return back()->with('alert','This Supplier Name Already Exists');
        }

        $suppliers = Suppliers::create($request->except('_token'));
        $suppliers->company_id = $user->company_id;
        $suppliers->save();
        return redirect()->route('suppliers.index')->with('success',trans('Suppliers.created'));
    }


    public function show($id)
    {
        //
    }

    public function edit(Suppliers $supplier)
    {
        $this->authorize(__FUNCTION__,Suppliers::class);
        $countries = Country::orderBy('name')->get();
        return view('master.suppliers.edit',[
            'supplier'=>$supplier,
            'countries'=>$countries,
        ]);
    }

    public function update(Request $request,Suppliers $supplier)
    {
        $request->validate([ 
            'name' => 'required', 
        ]);
        $user = Auth::user();

        $NameDublicate  = Suppliers::where('id','!=',$supplier->id)->where('company_id',$user->company_id)->where('name',$request->name)->count();
        if($NameDublicate > 0){
            return back()->with('alert','This Supplier Name Already Exists');
        }

        $this->authorize(__FUNCTION__,Suppliers::class);
        $supplier->update($request->except('_token'));
        return redirect()->route('suppliers.index')->with('success',trans('supplier.updated.success'));
    }

 
    public function destroy($id)
    {
        $supplier =Suppliers::Find($id);
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success',trans('Supplier.deleted.success'));
    }
}

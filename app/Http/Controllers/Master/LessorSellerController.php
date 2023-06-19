<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Country;
use App\Models\Master\LessorSeller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LessorSellerController extends Controller
{

    protected $countries;

    public function __construct()
    {
        $this->countries = Country::orderBy('name')->get();
    }

    public function index()
    {
        $this->authorize(__FUNCTION__, LessorSeller::class);

        $items = LessorSeller::paginate(30);

        return view('master.lessor-seller.index')
            ->with([
                'items' => $items
            ]);
    }


    public function create()
    {
        return view('master.lessor-seller.create')
            ->with([
                'countries' => $this->countries
            ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());
        LessorSeller::create($validated);
        
        return redirect()->route('seller.index');
    }


    public function show($id)
    {
        //
    }


    public function edit(LessorSeller $seller)
    {
        return view('master.lessor-seller.edit')
            ->with([
                'countries' => $this->countries,
                'seller' => $seller
            ]);
    }


    public function update(Request $request, LessorSeller $seller)
    {
        $validated = $request->validate($this->rules());
        $seller->update($validated);
        
        return redirect()->route('seller.index');
    }



    public function destroy(LessorSeller $seller)
    {
        $seller->delete();
        return redirect()->route('seller.index');
    }


    public function rules()
    {
        return [
            "name" => ['required', Rule::unique('lessor_sellers')->ignore(request()->name,'name')],
            "country_id" => 'required|integer',
            "city" => '',
            "phone" => '',
            "email" => '',
            "tax" => '',
        ];
    }
}

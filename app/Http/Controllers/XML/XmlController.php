<?php

namespace App\Http\Controllers\XML;

use App\Filters\ManifestXml\ManifestXmlIndexFilter;
use App\Http\Controllers\Controller;
use App\Models\Bl\BlDraft;
use App\Models\Voyages\Voyages;
use App\Models\Xml\Xml;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class XmlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize(__FUNCTION__,Xml::class);

        $xmls = Xml::filter(new ManifestXmlIndexFilter(request()))->orderBy('id','desc')->where('company_id',Auth::user()->company_id)->with('bldraft','voyage','port')->paginate(30);
        $blDraftNo = BlDraft::where('company_id',Auth::user()->company_id)->get();
        $voyages    = Voyages::with('vessel')->where('company_id',Auth::user()->company_id)->get();

        return view('bldraft.manifestXml.index',[
            'items'=>$xmls,
            'blDraftNo'=>$blDraftNo,
            'voyages'=>$voyages,
        ]); 
    }
    
    public function selectManifest()
    {
        $bldrafts  = BlDraft::where('company_id',Auth::user()->company_id)->with('customer')->get();
        return view('bldraft.manifestXml.selectManifest',[
            'bldrafts'=>$bldrafts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

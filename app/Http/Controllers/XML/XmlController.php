<?php

namespace App\Http\Controllers\XML;

use App\Filters\ManifestXml\ManifestXmlIndexFilter;
use App\Http\Controllers\Controller;
use App\Models\Bl\BlDraft;
use App\Models\Voyages\VoyagePorts;
use App\Models\Voyages\Voyages;
use App\Models\Xml\Xml;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

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
        $voyages  = Voyages::where('company_id',Auth::user()->company_id)->get();
        return view('bldraft.manifestXml.selectManifest',[
            'voyages'=>$voyages,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $voyage = Voyages::where('id',request()->voyage_id)->with('bldrafts')->first();
        $bldraft = $voyage->bldrafts->first();
        if($voyage->bldrafts->count() == 0){
            return back()->with('error','there is no bl on this voyage');
        }
        return redirect()->route('bldraft.serviceManifest',[
            'bldraft'=>$bldraft->id,
            'voyage'=>$voyage,
            'xml'=>true
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $xmlContent = '<?xml version="1.0" encoding="UTF-8"?>
            <ManifestData>
                <GeneralInfo>
                    <MNFSTArrivalDate>05/02/2003 11:04:36</MNFSTArrivalDate>
                    <MNFSTVoyageNumber>3</MNFSTVoyageNumber>
                    <MNFSTPassengerIndicator>0</MNFSTPassengerIndicator>
                    <MNFSTWarehouse>22</MNFSTWarehouse>
                    <MNFSTCarrierCallSign>9VIE6</MNFSTCarrierCallSign>
                    <MNFSTCarrierName>ALEX EXPRESS</MNFSTCarrierName>
                    <MNFSTCarrierCountry>GR</MNFSTCarrierCountry>
                    <MNFSTBillsOfLadingCount>1</MNFSTBillsOfLadingCount>
                </GeneralInfo>
                <CargoData>
                    <BillOfLading>
                        <BOLNumber>TUTADAP(3042</BOLNumber>
                        <BOLLoadingDate>01/02/2003</BOLLoadingDate>
                        <BOLTransitIndicator>0</BOLTransitIndicator>
                        <BOLConsolidation>2</BOLConsolidation>
                        <BOLConsigneeName>MIND WARE CAIRO EGYPT</BOLConsigneeName>
                        <BOLConsigneeAddress>LTD. ORIGINAL B/L NO. TUTADAP(03042</BOLConsigneeAddress>
                        <BOLNotifyPartyName>ARAMEX ALY 15, MOAASKAR ROMANY ST</BOLNotifyPartyName>
                        <BOLNOotifyPartyAddress>6TH DISTRICT-NASR CITY-CAIRO-EGYPT</BOLNOotifyPartyAddress>
                        <BOLCargoDesc>STC:195 CARTONS 1 X 20 STCNE 24/1 VISCOSE RING SPUNYARN"FRE</BOLCargoDesc>
                        <BOLDestinationCustoms>407</BOLDestinationCustoms>
                        <BOLLoadingPort>CMB</BOLLoadingPort>
                        <BOLLoadingCountry>LK</BOLLoadingCountry>
                        <BOLUnLoadingPort>SUE</BOLUnLoadingPort>
                        <BOLUnLoadingCountry>JB</BOLUnLoadingCountry>
                        <BOLShippingAgent>202484203</BOLShippingAgent>
                        <BOLWarehouse>22</BOLWarehouse>
                        <BOLItemsCount>2</BOLItemsCount>
                        <Item>
                            <ItemShipmentType>2</ItemShipmentType>
                            <ItemContainerNO>PCIU9863152</ItemContainerNO>
                            <ItemContainerType>1</ItemContainerType>
                            <ItemContainerVolume>20</ItemContainerVolume>
                            <ItemShipingSeal>D159223</ItemShipingSeal>
                            <ItemCargoDesc>S.T.C.COMPUTER PARTS 71-10494-012424 PCS15" CRT/KX562UL AC10</ItemCargoDesc>
                            <ItemExpQuantity>808</ItemExpQuantity>
                            <ItemExpQTYUOM>CNTS</ItemExpQTYUOM>
                            <ItemExpGrossWeight>11312</ItemExpGrossWeight>
                            <ItemExpGWUOM>K GM</ItemExpGWUOM>
                            <ItemContentPackagesQuantity>808</ItemContentPackagesQuantity>
                            <ItemContentQTYUOM>CNTS</ItemContentQTYUOM>
                            <ItemContentPackagesWeight>11312</ItemContentPackagesWeight>
                        </Item>
                        <Item>
                            <ItemShipmentType>2</ItemShipmentType>
                            <ItemContainerNO>PCIU9863390</ItemContainerNO>
                            <ItemContainerType>1</ItemContainerType>
                            <ItemContainerVolume>40</ItemContainerVolume>
                            <ItemShipingSeal>D159224</ItemShipingSeal>
                            <ItemCargoDesc>S.T.C.COMPUTER PARTS 71-10494-012424 PCS15" CRT/KX562UL AC10</ItemCargoDesc>
                            <ItemExpQuantity>904</ItemExpQuantity>
                            <ItemExpQTYUOM>CNTS</ItemExpQTYUOM>
                            <ItemExpGrossWeight>12844.8</ItemExpGrossWeight>
                            <ItemExpGWUOM>K GM</ItemExpGWUOM>
                            <ItemContentPackagesQuantity>904</ItemContentPackagesQuantity>
                            <ItemContentQTYUOM>CNTS</ItemContentQTYUOM>
                            <ItemContentPackagesWeight>12844.8</ItemContentPackagesWeight>
                        </Item>
                    </BillOfLading>
                </CargoData>
            </ManifestData>';

        // Fetch the data from the database or any other source
        $bldraft = BlDraft::where('id',$request->blDraft_id)->with('booking.quotation','voyage.vessel')->first();
        $xmlData = $bldraft;

        // Create a new XML document
        $xmlDoc = new \DOMDocument('1.0', 'UTF-8');
        $xmlDoc->formatOutput = true;

        // Create the root element <ManifestData>
        $manifestData = $xmlDoc->createElement('ManifestData');
        $xmlDoc->appendChild($manifestData);
        
        //getting arrival date
        if(optional($bldraft->booking)->shipment_type == "Export" || 
            optional($bldraft->booking->quotation)->shipment_type == "Export"){
                if(optional($bldraft->booking)->shipment_type == "Export"){
                    $etaDate = VoyagePorts::where('voyage_id',$bldraft->voyage_id)->where('port_from_name',optional($bldraft->booking)->load_port_id)->pluck('eta')->first();
                }else{
                    $etaDate = VoyagePorts::where('voyage_id',$bldraft->voyage_id)->where('port_from_name',optional($bldraft->booking->quotation)->load_port_id)->pluck('eta')->first();
                }
            }else{
                if(optional($bldraft->booking)->shipment_type == "Import"){
                    $etaDate = VoyagePorts::where('voyage_id',$bldraft->voyage_id)->where('port_from_name',optional($bldraft->booking)->discharge_port_id)->pluck('eta')->first();
                }else{
                    $etaDate = VoyagePorts::where('voyage_id',$bldraft->voyage_id)->where('port_from_name',optional($bldraft->booking->quotation)->discharge_port_id)->pluck('eta')->first();
                }
            }
        
        // Create the <GeneralInfo> element and add child elements
        $generalInfo = $xmlDoc->createElement('GeneralInfo');
        // $MNFSTArrivalDate = $xmlDoc->createElement('MNFSTArrivalDate',$etaDate);
        // $generalInfo->appendChild($MNFSTArrivalDate);
        
        $this->addItemToElement($xmlDoc, $generalInfo, $etaDate, 'MNFSTArrivalDate');
        $this->addItemToElement($xmlDoc, $generalInfo, $xmlData->voyage->voyage_no, 'MNFSTVoyageNumber');
        $this->addItemToElement($xmlDoc, $generalInfo, 0, 'MNFSTPassengerIndicator');
        $this->addItemToElement($xmlDoc, $generalInfo, 22, 'MNFSTWarehouse');
        $this->addItemToElement($xmlDoc, $generalInfo, $xmlData->voyage->vessel->call_sign, 'MNFSTCarrierCallSign');
        $manifestData->appendChild($generalInfo);
        // dd(optional($xmlData->voyage)->voyage_no);
        dd($xmlDoc);
        $this->addDataToElement($xmlDoc, $generalInfo, $xmlData['generalInfo']);

        // Create the <CargoData> element
        $cargoData = $xmlDoc->createElement('CargoData');
        $manifestData->appendChild($cargoData);

        // Iterate over the cargo items in the collection
        foreach ($xmlData['cargoItems'] as $cargoItem) {
            // Create the <BillOfLading> element and add child elements
            $billOfLading = $xmlDoc->createElement('BillOfLading');
            $this->addDataToElement($xmlDoc, $billOfLading, $cargoItem);
            $cargoData->appendChild($billOfLading);
        }

        // Generate the XML content
        $xmlContent = $xmlDoc->saveXML();

        // Set appropriate headers for download
        $headers = [
            'Content-Type' => 'application/xml',
            'Content-Disposition' => 'attachment; filename="manifest.xml"',
        ];

        // Return the XML content as a download response
        return response($xmlContent, 200, $headers);
    }

    private function addDataToElement(\DOMDocument $xmlDoc, \DOMElement $parentElement, array $data)
{
        foreach ($data as $key => $value) {
            // Create the element and set its value
            $element = $xmlDoc->createElement($key, $value);

            // Append the element to the parent element
            $parentElement->appendChild($element);
        }
    }

    private function addItemToElement(\DOMDocument $xmlDoc, \DOMElement $parentElement, $data , $key)
{
            // Create the element and set its value
            $element = $xmlDoc->createElement($key, $data);

            // Append the element to the parent element
            $parentElement->appendChild($element);
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

<?php

namespace App\Http\Controllers\BlDraft;

use App\Http\Controllers\Controller;
use App\Models\Bl\BlDraft;
use App\Models\Voyages\VoyagePorts;
use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;
use TCPDF;

class PDFController extends Controller
{
    public function showPDF(Request $request)
    {
        $blDraft = BlDraft::where('id',$request->bldraft)->with('blDetails')->first();
        $etdvoayege = VoyagePorts::where('voyage_id',$blDraft->voyage_id)->where('port_from_name',optional($blDraft->loadPort)->id)->first();
        
        // Get the path to the PDF form template
        $templatePath = public_path('bl_template.pdf');

        // Create a new instance of FPDI
        $pdf = new \setasign\Fpdi\Fpdi();

        // Set the source file
        $pdf->setSourceFile($templatePath);

        // Add a new page
        $pdf->AddPage();
        // Import the first page of the template
        $tplId = $pdf->importPage(1);

        // Use the imported page as the template for the new page
        $pdf->useTemplate($tplId);

        // Set the font
        $pdf->SetFont('Helvetica');

        // Set the font size
        $pdf->setFontSize(6);

        // Fill in the form fields with data
        $pdf->SetXY(5, 35);   // shipper
        $pdf->MultiCell(100, 3, optional($blDraft->customer)->name, 0, 'L');

        $pdf->SetXY(5, 45);   // shipper Details
        $pdf->MultiCell(100, 3, $blDraft->customer_shipper_details, 0, 'L');

        $pdf->SetXY(5, 61);   // consignee
        $pdf->MultiCell(100, 3, optional($blDraft->customerConsignee)->name, 0, 'L');

        $pdf->SetXY(5, 71);   // consignee Details
        $pdf->MultiCell(100, 3, $blDraft->customer_consignee_details, 0, 'L');

        $pdf->SetXY(5, 85);   // notify
        $pdf->MultiCell(100, 3, optional($blDraft->customerNotify)->name, 0, 'L');

        $pdf->SetXY(5, 95);   // notify Details
        $pdf->MultiCell(100, 3, $blDraft->customer_notifiy_details, 0, 'L');

        $pdf->SetXY(114, 50);   // bl no 
        $pdf->MultiCell(100, 3, $blDraft->ref_no, 0, 'L');

        $pdf->SetXY(165, 50);   // ref no
        $pdf->MultiCell(100, 3, optional($blDraft->booking)->ref_no, 0, 'L');

        $pdf->SetXY(140, 61);   // vessel voyage
        $pdf->MultiCell(100, 3, optional($blDraft->voyage->vessel)->name .'  '. optional($blDraft->voyage)->voyage_no, 0, 'L');

        $pdf->SetXY(140, 76);   // port of load
        $pdf->MultiCell(100, 3, optional($blDraft->loadPort)->name, 0, 'L');

        $pdf->SetXY(140, 82);   // port of discharge
        $pdf->MultiCell(100, 3, optional($blDraft->dischargePort)->name, 0, 'L');

        $pdf->SetXY(140, 82);   // port of discharge
        $pdf->MultiCell(100, 3, optional($blDraft->dischargePort)->name, 0, 'L');

        // Output the PDF as a string
        $pdfContent = $pdf->Output('filled_form.pdf', 'S', 'UTF-8');
        header('Content-Type: application/pdf; charset=utf-8');
        // header('Content-Disposition: attachment; filename="filled_form.pdf"');
        // header('Content-Length: ' . strlen($pdfContent));
        // Return a view that displays the PDF in an iframe
        echo $pdfContent;
        return view('bldraft.pdf.show', ['pdfContent' => $pdfContent]);
    }
}

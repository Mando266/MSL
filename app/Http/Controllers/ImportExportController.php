<?php

namespace App\Http\Controllers;

use App\Exports\BookingExport;
use App\Exports\ContainersExport;
use App\Exports\CustomerExport;
use App\Exports\LoadListExport;
use App\Imports\ContainersImport;
use App\Exports\LocalPortTriffShowExport;
use Illuminate\Http\Request;
use App\Exports\MovementsExport;
use App\Exports\MovementsExportAll;
use App\Exports\MovementsExportSearch;
use App\Exports\QuotationExport;
use App\Exports\VoyageExport;
use App\Imports\MovementsImport;
use App\Imports\MovementsOvewriteImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportExportController extends Controller
{
    public function importExportView()
    {
       return view('importexport');
    }
    public function import() 
    {
        Excel::import(new MovementsImport,request()->file('file'));
        return back();
    }
    public function overwrite() 
    {
        Excel::import(new MovementsOvewriteImport,request()->file('file'));
        return back();
    }
    public function export() 
    {
        return Excel::download(new MovementsExport, 'Movements.xlsx');
    }
    public function exportAll() 
    {
        return Excel::download(new MovementsExportAll, 'Movements.xlsx');
    }
    public function exportQuotation() 
    {
        return Excel::download(new QuotationExport, 'Quotations.xlsx');
    }
    public function exportContainers() 
    {
        return Excel::download(new ContainersExport, 'Containers.xlsx');
    }
    public function importContainers() 
    {
        Excel::import(new ContainersImport,request()->file('file'));
        return back();
    }
    public function exportCustomers() 
    {
        return Excel::download(new CustomerExport, 'Customers.xlsx');
    }
    public function LocalPortTriffShow() 
    {
        return Excel::download(new LocalPortTriffShowExport, 'LocalPortTriff.xlsx');
    }
    public function exportBooking() 
    {
        return Excel::download(new BookingExport, 'Bookings.xlsx');
    }
    public function loadlistBooking() 
    {
        return Excel::download(new LoadListExport, 'Loadlist.xlsx');
    }
    public function exportVoyages() 
    {
        return Excel::download(new VoyageExport, 'Voyages.xlsx');
    }
    public function exportSearch() 
    {
        return Excel::download(new MovementsExportSearch, 'Movements.xlsx');
    }
}

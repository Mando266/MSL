<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\MovementsExport;
use App\Exports\MovementsExportAll;
use App\Exports\MovementsExportSearch;
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
    public function exportSearch() 
    {
        return Excel::download(new MovementsExportSearch, 'Movements.xlsx');
    }
}

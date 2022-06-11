<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\MovementsExport;
use App\Imports\MovementsImport;
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
    public function export() 
    {
        return Excel::download(new MovementsExport, 'Movements.xlsx');
    }
}

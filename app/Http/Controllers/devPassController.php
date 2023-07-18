<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class devPassController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('dev_tools.dev-pass');
    }
}

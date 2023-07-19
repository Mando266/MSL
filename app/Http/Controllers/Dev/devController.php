<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class devController extends Controller
{

    private $devPass = 414141;

    public function __construct()
    {
//        dd(request()->all());
        if (request()->dev_pass != $this->devPass) {
            abort(401);
        }
        view()->composer('dev_tools/*', fn($v) => $v->with("devPass", request()->dev_pass));
    }

    public function index()
    {
        return view('dev_tools.index');
    }

    public function selectSqlForm()
    {
        return view('dev_tools.select-sql');
    }

    public function selectSqlPost()
    {
        return DB::select(request()->sql);
    }

    public function migrateTables()
    {
        Artisan::call('migrate');
        dd('migrations done!');
    }
}

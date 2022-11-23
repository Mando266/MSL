<?php

use App\Http\Controllers\API\AgentCountry;
use App\Http\Controllers\API\CompanyDataController;
use App\Http\Controllers\API\PriceController;
use App\Models\Master\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('get-company-users',function(){
        $copmany =  Company::find(request()->input('company_id'));
        if(!is_null($copmany)){
            return $copmany->users()->get(['users.id','users.name']);
        }
        return [];
    });


//});
Route::get('vessel/voyages/{id}', [CompanyDataController::class, 'getVesselVoyages']);
Route::get('master/ports/{id}', [CompanyDataController::class, 'portsCountry']);
Route::get('master/customers/{id}', [CompanyDataController::class, 'customer']);
Route::get('master/terminals/{id}', [CompanyDataController::class, 'terminalsPorts']);
Route::get('agent/loadPrice/{id}/{equipment_id?}', [PriceController::class, 'getLoadAgentPrice']);
Route::get('agent/dischargePrice/{id}/{equipment_id?}', [PriceController::class, 'getDischargeAgentPrice']);
Route::get('agent/agentCountry/{id}', [AgentCountry::class, 'getAgentCountry']);
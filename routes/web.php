<?php

use App\Models\ViewModel\RootMenuNode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@index')->name('home');
    /*
    |-------------------------------------------
    | admin routes
    |--------------------------------------------
    */
    Route::prefix('admin')->namespace('Admin')->group(function () {
        Route::resource('roles', 'RoleController');
        Route::resource('users', 'UserController');
        Route::get('profile', 'UserController@showProfile')->name('profile');
        Route::get('reset-password', 'ResetPasswordController@edit')->name('user.reset-password');
        Route::put('reset-password', 'ResetPasswordController@update');

    });
     /*
    |-------------------------------------------
    | master routes
    |--------------------------------------------
    */
    Route::prefix('master')->namespace('Master')->group(function () {
        Route::resource('company', 'CompanyController');
        Route::resource('countries', 'CountryController');
        Route::resource('port-types', 'PortTyepsController');
        Route::resource('ports', 'PortsController');
        Route::resource('agents', 'AgentsController');
        Route::resource('terminals', 'TerminalsController');
        Route::resource('line-types', 'LinTypeController');
        Route::resource('lines', 'LinesController');
        Route::resource('suppliers', 'SuppliersController');
        Route::resource('customers', 'CustomersController');
        Route::resource('vessel-types', 'VesselTypeController');
        Route::resource('vessels', 'VesselsController');
        Route::resource('container-types', 'ContinersTypeController');
        Route::resource('containers', 'ContinersController');
        Route::resource('container-movement', 'ContainersMovementController');
        Route::resource('stock-types', 'StockTypesController');
    });
    /*
    |-------------------------------------------
    | Voyage routes
    |--------------------------------------------
    */
    Route::prefix('voyages')->namespace('Voyages')->group(function () {
        Route::resource('voyages', 'VoyagesController');
    });

    /*
    |-------------------------------------------
    | Containers routes
    |--------------------------------------------
    */
    Route::prefix('containers')->namespace('Containers')->group(function () {
        Route::resource('movements', 'MovementController');
        Route::resource('tracking', 'TrackingController');
        Route::resource('demurrage', 'DemurageController');

    });

});
Auth::routes(['register' => false]);


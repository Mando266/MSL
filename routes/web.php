<?php

use App\Http\Controllers\Quotations\QuotationsController;
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
        Route::get('voyages/{voyage}/{FromPort?}/{ToPort?}','VoyagesController@show')->name('voyages.show');
        Route::resource('voyageports', 'VoyageportsController');

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
        Route::resource('movementerrors', 'MovementImportErrorsController');
        Route::get('detentionView','DetentionController@showDetentionView')->name('detention.view');
        Route::post('calculateDetention','DetentionController@calculateDetention')->name('detention.calculation');
        Route::get('detention/{id}/{detention}/{dchfDate}/{rcvcDate?}','DetentionController@showTriffSelectWithBlno')->name('detention.showTriffSelectWithBlno');
        Route::post('detention','DetentionController@showDetention')->name('detention.showDetention');
    });
    
    /*Excel import export*/
    Route::get('export', 'ImportExportController@export')->name('export');
    Route::get('exportAll', 'ImportExportController@exportAll')->name('export.all');
    Route::get('exportSearch', 'ImportExportController@exportSearch')->name('export.search');
    Route::get('importExportView', 'ImportExportController@importExportView');
    Route::post('import', 'ImportExportController@import')->name('import');

        /*
    |-------------------------------------------
    | Quotations routes
    |--------------------------------------------
    */
    Route::prefix('quotations')->namespace('Quotations')->group(function () {
        Route::resource('quotations', 'QuotationsController');
        Route::get('{quotation}/approve',[QuotationsController::class,'approve'])->name('quotation.approve');
        Route::get('{quotation}/reject',[QuotationsController::class,'reject'])->name('quotation.reject');
    });

});
Auth::routes(['register' => false]);


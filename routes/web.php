<?php

use App\Http\Controllers\BlDraft\BlDraftController;
use App\Http\Controllers\Booking\BookingController;
use App\Http\Controllers\Quotations\LocalPortTriffDetailesController;
use App\Http\Controllers\Quotations\QuotationsController;
use App\Models\Containers\Movements;
use App\Models\Master\Containers;
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
        Route::resource('supplierPrice', 'SupplierPriceController');
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
    Route::get('exportQuotation', 'ImportExportController@exportQuotation')->name('export.quotation');
    Route::get('exportCustomers', 'ImportExportController@exportCustomers')->name('export.customers');
    Route::get('exportLocalporttriffshow', 'ImportExportController@LocalPortTriffShow')->name('export.Localportshow');
    Route::get('exportBooking', 'ImportExportController@exportBooking')->name('export.booking');
    Route::get('exportVoyages', 'ImportExportController@exportVoyages')->name('export.voyages');
    Route::get('exportSearch', 'ImportExportController@exportSearch')->name('export.search');
    Route::get('importExportView', 'ImportExportController@importExportView');
    Route::post('import', 'ImportExportController@import')->name('import');
    Route::post('overwrite', 'ImportExportController@overwrite')->name('overwrite');
    Route::post('importContainers', 'ImportExportController@importContainers')->name('importContainers');
    Route::get('exportContainers', 'ImportExportController@exportContainers')->name('export.container');


    /*
    |-------------------------------------------
    | Quotations routes
    |--------------------------------------------
    */
    Route::prefix('quotations')->namespace('Quotations')->group(function () {
        Route::resource('quotations', 'QuotationsController');
        Route::get('{quotation}/approve',[QuotationsController::class,'approve'])->name('quotation.approve');
        Route::get('{quotation}/reject',[QuotationsController::class,'reject'])->name('quotation.reject');
        Route::resource('localporttriff', 'LocalPortTriffController');
        Route::get('localporttriffdetailes/{id}',[LocalPortTriffDetailesController::class,'destroy'])->name('LocalPortTriffDetailes.destroy');
    });
    /*
    |-------------------------------------------
    | Booking routes
    |--------------------------------------------
    */
        Route::prefix('booking')->namespace('Booking')->group(function () {
        Route::resource('booking','BookingController');
        Route::get('selectQuotation',[BookingController::class,'selectQuotation'])->name('booking.selectQuotation');
        Route::get('showShippingOrder/{booking}',[BookingController::class,'showShippingOrder'])->name('booking.showShippingOrder');
        Route::get('showGateIn/{booking}',[BookingController::class,'showGateIn'])->name('booking.showGateIn');
        Route::get('showGateOut/{booking}',[BookingController::class,'showGateOut'])->name('booking.showGateOut');
    });
    /*
    |-------------------------------------------
    | BL routes
    |--------------------------------------------
    */
    Route::prefix('bldraft')->namespace('BlDraft')->group(function () {
        Route::resource('bldraft','BlDraftController');
        Route::get('selectBooking',[BlDraftController::class,'selectBooking'])->name('bldraft.selectbooking');
    });
    /*
    |-------------------------------------------
    | Manual Updates
    |--------------------------------------------
    */
    Route::get('/update/manual',function (){
        $movements = Movements::orderBy('movement_date','desc')->with('movementcode.containerstock')->get();
                        
            $new = $movements;
            $new = $new->groupBy('movement_date');
            
            foreach($new as $key => $move){
                $move = $move->sortByDesc('movementcode.sequence');
                $new[$key] = $move;
            }
            $new = $new->collapse();
            
            $movements = $new;
            $filteredData = $movements->unique('container_id');
            foreach($filteredData as $key => $move){
                // Get All movements and sort it and get the last movement before this movement 
                $tempMovements = Movements::where('container_id',$move->container_id)->orderBy('movement_date','desc')->with('movementcode.containerstock')->get();
                            
                $new = $tempMovements;
                $new = $new->groupBy('movement_date');
                
                foreach($new as $k => $move){
                    $move = $move->sortByDesc('movementcode.sequence');
                    $new[$k] = $move;
                }
                $new = $new->collapse();
                
                $tempMovements = $new;
                $lastMove = $tempMovements->first();
                // End Get All movements and sort it and get the last movement before this movement 
                if($lastMove->container_status == 1){
                    $container = Containers::where('id',$lastMove->container_id)->first();
                    $container->update(['status'=>$lastMove->container_status]);
                }elseif($lastMove->container_status == 2 && $lastMove->movementcode->containerstock->code == "NOT AVAILABLE"){
                    $container = Containers::where('id',$lastMove->container_id)->first();
                    $container->update(['status'=>1]);
                }else{
                    $container = Containers::where('id',$lastMove->container_id)->first();
                    $container->update(['status'=>$lastMove->container_status]);
                }
                
                // dd($lastMove);
            }
        // $movements = Movements::where('container_status',2)->orderbyDesc('created_at')->groupBy('container_id')->get()->pluck('container_id');
        // $movements = Movements::where('container_status',1)->orderbyDesc('created_at')->first();
        return redirect()->route('movements.index')->with('success',"CONTAINERS UPDATED SUCCESSFULLY");
    })->name('containerRefresh');
});
Auth::routes(['register' => false]);


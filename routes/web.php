<?php

use App\Http\Controllers\BlDraft\BlDraftController;
use App\Http\Controllers\BlDraft\PDFController;
use App\Http\Controllers\Booking\BookingController;
use App\Http\Controllers\ImportExportController;
use App\Http\Controllers\Invoice\InvoiceController;
use App\Http\Controllers\Invoice\ReceiptController;
use App\Http\Controllers\Invoice\RefundController;
use App\Http\Controllers\Master\LessorSellerController;
use App\Http\Controllers\Quotations\LocalPortTriffDetailesController;
use App\Http\Controllers\Quotations\QuotationsController;
use App\Http\Controllers\Storage\StorageController;
use App\Http\Controllers\Trucker\TruckerGateController;
use App\Http\Controllers\Update\RefreshController;
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
        Route::resource('settings', 'SettingController');
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
    Route::get('exportTruckerGate', 'ImportExportController@exportTruckerGate')->name('export.TruckerGate'); 
    Route::get('loadlistBooking', 'ImportExportController@loadlistBooking')->name('export.loadList');
    Route::get('loadlistBl', 'ImportExportController@loadlistBl')->name('export.BLloadList');
    Route::get('Bllist', 'ImportExportController@Bllist')->name('export.BLExport');
    Route::get('exportVoyages', 'ImportExportController@exportVoyages')->name('export.voyages');
    Route::get('exportSearch', 'ImportExportController@exportSearch')->name('export.search');
    Route::get('agentSearch', 'ImportExportController@agentSearch')->name('export.agent');
    Route::get('importExportView', 'ImportExportController@importExportView');
    Route::post('import', 'ImportExportController@import')->name('import');
    Route::post('overwrite', 'ImportExportController@overwrite')->name('overwrite');
    Route::post('importContainers', 'ImportExportController@importContainers')->name('importContainers');
    Route::get('exportContainers', 'ImportExportController@exportContainers')->name('export.container');
    Route::get('invoiceList', 'ImportExportController@invoiceList')->name('export.invoice');
    Route::get('invoiceBreakdown', 'ImportExportController@invoiceBreakdown')->name('export.invoice.breakdown');
    Route::get('receiptExport', 'ImportExportController@receiptExport')->name('export.receipt');
    Route::get('customerStatementsExport', 'ImportExportController@customerStatementsExport')->name('export.statements');

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
        Route::get('selectGateOut/{booking}',[BookingController::class,'selectGateOut'])->name('booking.selectGateOut');
        Route::get('showShippingOrder/{booking}',[BookingController::class,'showShippingOrder'])->name('booking.showShippingOrder');
        Route::get('deliveryOrder/{booking}',[BookingController::class,'deliveryOrder'])->name('booking.deliveryOrder');
        Route::get('showGateIn/{booking}',[BookingController::class,'showGateIn'])->name('booking.showGateIn');
        Route::get('showGateInImport/{booking}',[BookingController::class,'showGateInImport'])->name('booking.showGateInImport');
        Route::get('selectGateInImport/{booking}',[BookingController::class,'selectGateInImport'])->name('booking.selectGateInImport');
        Route::get('showGateOut/{booking}',[BookingController::class,'showGateOut'])->name('booking.showGateOut');
        Route::get('showGateOutImport/{booking}',[BookingController::class,'showGateOutImport'])->name('booking.showGateOutImport');
        Route::get('referManifest',[BookingController::class,'referManifest'])->name('booking.referManifest');
        Route::get('selectBooking',[BookingController::class,'selectBooking'])->name('booking.selectBooking');
        Route::post('importBooking', [ImportExportController::class,'importBooking'])->name('importBooking');
    });
    /*
    |-------------------------------------------
    | BL routes
    |--------------------------------------------
    */
    Route::prefix('bldraft')->namespace('BlDraft')->group(function () {
        Route::resource('bldraft','BlDraftController');
        Route::get('selectBooking',[BlDraftController::class,'selectBooking'])->name('bldraft.selectbooking');
        Route::get('manifest/{bldraft}',[BlDraftController::class,'manifest'])->name('bldraft.manifest');
        Route::get('serviceManifest/{bldraft}',[BlDraftController::class,'serviceManifest'])->name('bldraft.serviceManifest');
        Route::get('pdf',[PDFController::class,'showPDF'])->name('bldraft.showPDF');
    });

    /*
    |-------------------------------------------
    | statements routes
    |--------------------------------------------
    */
    Route::prefix('statements')->namespace('Statements')->group(function () {
        Route::resource('statements','CustomerStatementController');
    });
    /*
    |-------------------------------------------
    | Trucker
    |--------------------------------------------
    */
    Route::prefix('trucker')->namespace('Trucker')->group(function () {
        Route::resource('trucker','TruckerController');
        Route::resource('truckergate','TruckerGateController');
        Route::get('basic_email',[TruckerGateController::class,'basic_email'])->name('trucker.basic_email');
    });
    /*
    |-------------------------------------------
    | BL routes
    |--------------------------------------------
    */
    Route::prefix('invoice')->namespace('Invoice')->group(function () {
        Route::resource('invoice','InvoiceController');
        Route::get('selectBL',[InvoiceController::class,'selectBL'])->name('invoice.selectBL');
        Route::get('selectBLinvoice',[InvoiceController::class,'selectBLinvoice'])->name('invoice.selectBLinvoice');
        Route::get('create_invoice',[InvoiceController::class,'create_invoice'])->name('invoice.create_invoice');
        Route::post('create_invoice',[InvoiceController::class,'storeInvoice'])->name('invoice.store_invoice');
        Route::resource('receipt','ReceiptController');
        Route::get('selectinvoice',[ReceiptController::class,'selectinvoice'])->name('receipt.selectinvoice');
        Route::resource('refund','RefundController');
        Route::resource('creditNote','CreditController');

    });
    /*
    |-------------------------------------------
    | Manual Updates
    |--------------------------------------------
    */
    Route::get('/update/manual',[RefreshController::class,'updateContainers'])->name('containerRefresh');
    Route::get('/update/quotation',[RefreshController::class,'updateQuotation'])->name('updateQuotation');
    Route::get('/update/booking/containers/{id?}',[RefreshController::class,'updateBookingContainers'])->name('bookingContainersRefresh');
    /*
    |-------------------------------------------
    | Storage routes
    |--------------------------------------------
    */
    Route::prefix('storage')->namespace('Storage')->group(function () {
        Route::resource('storage','StorageController');

        // Route::get('storage',[StorageController::class,'index'])->name('storage.index');
    });
    Route::prefix('lessor')->namespace('Master')->group(function () {
        Route::resource('seller','LessorSellerController');
    });
});
Auth::routes(['register' => false]);


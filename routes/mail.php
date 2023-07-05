<?php

use App\Http\Controllers\MailController;

Route::prefix('booking')->namespace('Booking')->group(function () {
    Route::post('temperatureDiscrepancy/sendMailToCustomer', [MailController::class, 'sendMailToCustomer'])
        ->name('temperature-discrepancy.send-customer');
    Route::post('temperatureDiscrepancy/sendMailToProvider', [MailController::class, 'sendMailToProvider'])
        ->name('temperature-discrepancy.send-provider');
});
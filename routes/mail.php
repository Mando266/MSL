<?php

use App\Http\Controllers\MailController;

Route::prefix('booking')->namespace('Booking')->group(function () {
    Route::post('temperatureDiscrepancy/sendMail', [MailController::class, 'sendTemperatureDiscrepancyMail'])
        ->name('temperature-discrepancy.send');
});
<?php

use App\Http\Controllers\Dev\DevController;
use App\Http\Controllers\SeedingController;

Route::get('dev', 'Dev\DevPassController')->middleware(['auth', 'dev.tools']);

Route::prefix('devtools')->name('devtools.')->middleware(['auth', 'dev.tools'])->group(function () {
    Route::get('', [DevController::class, 'index'])->name('index');
    Route::prefix('sql')->name('sql.')->group(function () {
        Route::get('select', [DevController::class, 'selectSqlForm'])->name('select-form');
        Route::post('select-post', [DevController::class, 'selectSqlPost'])->name('select-post');
    });
    Route::get('migrate-tables', [DevController::class, 'migrateTables'])->name('migrate-tables');
    Route::get('seed-charges-matrices', [SeedingController::class, 'seedChargesMatrices'])
        ->name('seed-charges-matrices');
    Route::get('seed', function () {
        Artisan::call('db:seed');
        return "Seeded!";
    })->name('seed');
});


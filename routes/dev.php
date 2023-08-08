<?php

use App\Http\Controllers\Dev\DevController;

Route::get('dev', 'Dev\DevPassController')->middleware(['auth', 'dev.tools']);

Route::prefix('devtools')->name('devtools.')->middleware(['auth', 'dev.tools'])->group(function () {
    Route::get('', [DevController::class, 'index'])->name('index');
    Route::prefix('sql')->name('sql.')->group(function () {
        Route::get('select', [DevController::class, 'selectSqlForm'])->name('select-form');
        Route::post('select-post', [DevController::class, 'selectSqlPost'])->name('select-post');
    });
    Route::get('migrate-tables', [DevController::class, 'migrateTables'])->name('migrate-tables');
    Route::get('seed-charges-matrices', function () {
        Artisan::call('db:seed', ['--class' => 'ChargesMatricesSeeder']);
        return "ChargesMatricesSeeder has been seeded!";
    })->name('seed-charges-matrices');
    Route::get('seed', function () {
        Artisan::call('db:seed');
        return "Seeded!";
    })->name('seed');
});


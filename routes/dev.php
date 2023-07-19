<?php

use App\Http\Controllers\Dev\devController;

Route::get('dev', 'Dev\devPassController')->middleware(['auth', 'dev.tools']);

Route::prefix('devtools')->name('devtools.')->middleware(['auth', 'dev.tools'])->group(function () {
    Route::get('', [devController::class, 'index'])->name('index');
    Route::prefix('sql')->name('sql.')->group(function () {
        Route::get('select', [devController::class, 'selectSqlForm'])->name('select-form');
        Route::post('select-post', [devController::class, 'selectSqlPost'])->name('select-post');
    });
    Route::get('migrate-tables', [devController::class, 'migrateTables'])->name('migrate-tables');
});


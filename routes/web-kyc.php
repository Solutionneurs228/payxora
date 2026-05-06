<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\KycController;

// ... [autres routes conservees] ...

/*
|--------------------------------------------------------------------------
| KYC (Auth)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/kyc', [KycController::class, 'show'])->name('kyc');
    Route::get('/kyc/show', [KycController::class, 'show'])->name('kyc.show');
    Route::post('/kyc', [KycController::class, 'store'])->name('kyc.store');
    Route::get('/kyc/verification', [KycController::class, 'verification'])->name('kyc.verification');
    Route::get('/kyc/document/{type}/{id}', [KycController::class, 'document'])->name('kyc.document');
});

<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ContractHistoryController;
use App\Http\Controllers\ContractItemController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource(
        'clients',
        ClientController::class
    );

    Route::apiResource(
        'services',
        ServiceController::class
    );

    Route::apiResource(
        'contracts',
        ContractController::class
    );

    Route::patch(
        'contracts/{contract}/cancel',
        [ContractController::class, 'cancel']
    );

    Route::post(
        'contracts/{contract}/items',
        [ContractItemController::class, 'store']
    );

    Route::put(
        'contract-items/{contractItem}',
        [ContractItemController::class, 'update']
    );

    Route::delete(
        'contract-items/{contractItem}',
        [ContractItemController::class, 'destroy']
    );

    Route::get(
        'contracts/{contract}/histories',
        [ContractHistoryController::class, 'index']
    );
});

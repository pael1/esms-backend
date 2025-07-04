<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AwardeeController;
use App\Http\Controllers\Api\ChildrenController;
use App\Http\Controllers\Api\LedgerController;
use App\Http\Controllers\Api\OpController;
use App\Http\Controllers\Api\ParameterController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [Authcontroller::class, 'logout'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResources([
        'users' => UserController::class,
        'awardees' => AwardeeController::class,
        'ledgers' => LedgerController::class,
        'childrens' => ChildrenController::class,
        'parameters' => ParameterController::class,
        'ops' => OpController::class,
    ]);

    Route::prefix('awardees')->group(function () {
        Route::get('/ledger/test', [AwardeeController::class, 'get_ledger']);
        Route::get('/childrens/{id}', [AwardeeController::class, 'get_childrens']);
        Route::get('/transactions/{id}', [AwardeeController::class, 'get_transactions']);
        Route::get('/files/{id}', [AwardeeController::class, 'get_files']);
        Route::get('/employees-data/{id}', [AwardeeController::class, 'get_employees_data']);
        Route::get('/generate/current-bill', [AwardeeController::class, 'current_billing']);
    });

    Route::prefix('ledgers')->controller(LedgerController::class)->group(function () {
        Route::get('data/arrears', 'arrears');
    });
});

Route::get('/sales-data', function () {
    return response()->json([
        'categories' => ['Jan', 'Feb', 'Mar', 'Apr'],
        'series' => [
            ['name' => 'Sales', 'data' => [120, 200, 150, 300]],
        ],
    ]);
});

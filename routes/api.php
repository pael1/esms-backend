<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OpController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\StallController;
use App\Http\Controllers\Api\LedgerController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SyncOpController;
use App\Http\Controllers\Api\AwardeeController;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\Api\ChildrenController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\ParameterController;
use App\Http\Controllers\Api\RentalController;
use App\Http\Controllers\Api\StallOwnerController;

//webhook routes
Route::post('/webhook/receiver', [WebhookController::class, 'receiver']);
Route::post('/webhook/subscribe', [WebhookController::class, 'subscribe']);

// auth routes
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [Authcontroller::class, 'logout'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResources([
        'users' => UserController::class,
        'awardees' => AwardeeController::class,
        'reports' => ReportController::class,
        'ledgers' => LedgerController::class,
        'childrens' => ChildrenController::class,
        'parameters' => ParameterController::class,
        'ops' => OpController::class,
        'sync' => SyncOpController::class,
        'stalls' => StallController::class,
        'employees' => EmployeeController::class,
        'files' => FileController::class,
        'stallowner' => StallOwnerController::class,
        'rentals' => RentalController::class,
    ]);

    // awardee endpoints
    Route::prefix('awardees')->group(function () {
        Route::get('/childrens/{id}', [AwardeeController::class, 'get_childrens']);
        Route::get('/transactions/{id}', [AwardeeController::class, 'get_transactions']);
        Route::get('/employees-data/{id}', [AwardeeController::class, 'get_employees_data']);
        Route::get('/generate/current-bill', [AwardeeController::class, 'current_billing']);
    });

    // reports endpoints
    Route::prefix('reports')->group(function () {
        Route::get('/print/masterlist', [ReportController::class, 'masterlist_print']);
    });

    //stall owner endpoints
    Route::prefix('stallowner')->group(function () {
        Route::get('/{id}/details/{rentalId}', [StallOwnerController::class, 'owner']);
        Route::get('/{searchName}/names', [StallOwnerController::class, 'ownerNames']);
    });

    //stalls endpoints
    Route::prefix('stalls')->group(function () {
        Route::get('/{id}/description/{rentalId}', [StallController::class, 'description']);
        Route::get('/find-stall/description', [StallController::class, 'findStall']);
        Route::get('/find-stall/stall-no-id', [StallController::class, 'findStallNoId']);
    });

    //rentals endpoints
    Route::prefix('rentals')->group(function () {
        Route::put('/cancel/{id}', [RentalController::class, 'cancelRental']);
    });

    //ledgers endpoints
    Route::prefix('ledgers')->controller(LedgerController::class)->group(function () {
        Route::get('data/arrears', 'arrears');
    });

    //sync operations for awardees and ledgers data update from external system to local system 
    Route::prefix('sync')->controller(SyncOpController::class)->group(function () {
        Route::get('data/arrears', 'arrearsMonth');
    });

    //parameters endpoints
    Route::prefix('parameters')->controller(ParameterController::class)->group(function () {
        Route::get('sub-section/list', 'subSection');
    });
});

// test routes
Route::put('/test', function () {
    return response()->json(['ok' => true]);
});
// test routes for sales data dashboard
Route::get('/sales-data', function () {
    return response()->json([
        'categories' => ['Jan', 'Feb', 'Mar', 'Apr'],
        'series' => [
            ['name' => 'Sales', 'data' => [120, 200, 150, 300]],
        ],
    ]);
});

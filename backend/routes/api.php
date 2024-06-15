<?php

use App\Http\Controllers\api\ExpenseController;
use App\Http\Controllers\Api\HouseController;
use App\Http\Controllers\Api\MaintenancePaymentController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ResidentController;
use App\Http\Controllers\Api\ResidentHouseController;
use App\Http\Controllers\Api\ResidentialStatusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('/residents', ResidentController::class);
Route::apiResource('/houses', HouseController::class);
Route::apiResource('/residential-statuses', ResidentialStatusController::class);
Route::apiResource('/resident-house', ResidentHouseController::class);
Route::apiResource('/expenses', ExpenseController::class)->only('index', 'store');
Route::apiResource('/maintenance-payments', MaintenancePaymentController::class);
Route::get('/annual-report', [ReportController::class, 'annual']);
Route::get('/report/{reportId}/{month?}', [ReportController::class, 'pdf']);
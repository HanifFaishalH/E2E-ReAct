<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\FeedbackApiController;
use App\Http\Controllers\Api\LaporanApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Semua route di bawah ini otomatis diawali dengan prefix "/api"
| Contoh: https://reportaction.dbsnetwork.my.id/api/login
|
*/

// ✅ Route untuk mendapatkan data user yang sedang login
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ✅ AUTH ROUTES
Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthApiController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    // 🔹 API Laporan
    Route::get('/laporan', [LaporanApiController::class, 'index']);
    Route::get('/laporan/{id}', [LaporanApiController::class, 'show']);
    Route::post('/laporan', [LaporanApiController::class, 'store']);
    Route::put('/laporan/{id}', [LaporanApiController::class, 'update']);
    Route::delete('/laporan/{id}', [LaporanApiController::class, 'destroy']);

    // 🔹 API Feedback
    Route::get('/feedback', [FeedbackApiController::class, 'index']);
    Route::get('/feedback/available', [FeedbackApiController::class, 'availableReports']);
    Route::post('/feedback', [FeedbackApiController::class, 'store']);
});
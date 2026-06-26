<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorDataController;
use App\Http\Controllers\Api\ThresholdApiController;

/*
|--------------------------------------------------------------------------
| API Routes — Si-Tetas IoT
|--------------------------------------------------------------------------
| Semua route di sini di-prefix dengan /api secara otomatis oleh Laravel.
| Endpoint ini tidak memerlukan autentikasi sesi (stateless).
*/

// ── ESP32 Data Endpoint ────────────────────────────────────────────────────
// POST /api/kirim-data
// Body JSON: { "suhu": 37.5, "kelembaban": 62.0 }
Route::post('/kirim-data', [SensorDataController::class, 'kirimData'])
    ->name('sensor.kirim-data');

// ── ESP32 Threshold Endpoints ──────────────────────────────────────────────
Route::get('/get-threshold', [ThresholdApiController::class, 'getThreshold'])->name('api.get-threshold');
Route::post('/update-threshold', [ThresholdApiController::class, 'updateThreshold'])->name('api.update-threshold');

// ── Health-check (opsional, untuk tes koneksi dari browser/Serial Monitor) ──
Route::get('/ping', function () {
    return response()->json([
        'status'  => 'ok',
        'message' => 'Si-Tetas API aktif.',
        'time'    => now()->toDateTimeString(),
    ]);
})->name('api.ping');


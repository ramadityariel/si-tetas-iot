<?php

namespace App\Http\Controllers;

use App\Models\SensorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Controller untuk menerima dan memproses data sensor dari ESP32.
 * Endpoint: POST /api/kirim-data
 * 
 * Header yang diperlukan:
 * - X-API-KEY: 815171f9b522f1cd4cd95cb1d4410311
 * 
 * Body JSON yang dikirim ESP32:
 * {
 *     "suhu": 36.5,
 *     "kelembaban": 60,
 *     "timestamp": 0
 * }
 */
class SensorDataController extends Controller
{
    /**
     * API Key yang valid untuk ESP32
     */
    private const VALID_API_KEY = '815171f9b522f1cd4cd95cb1d4410311';

    /**
     * Menerima data sensor dari ESP32, validasi API Key,
     * validasi data, simpan ke database, dan kembalikan respon.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function kirimData(Request $request)
    {
        // ── 1. Validasi X-API-KEY dari Header ─────────────────────────────────
        $apiKey = $request->header('X-API-KEY');
        
        if (!$apiKey) {
            Log::warning('[ESP32] Request tanpa X-API-KEY header', [
                'ip' => $request->ip(),
            ]);
            
            return response()->json([
                'status'  => 'error',
                'message' => 'X-API-KEY header tidak ditemukan.',
                'suhu_max' => 0,
                'kelembaban_min' => 0,
            ], 401);
        }

        if ($apiKey !== self::VALID_API_KEY) {
            Log::warning('[ESP32] X-API-KEY tidak valid', [
                'api_key' => $apiKey,
                'ip'      => $request->ip(),
            ]);
            
            return response()->json([
                'status'  => 'error',
                'message' => 'X-API-KEY tidak valid.',
                'suhu_max' => 0,
                'kelembaban_min' => 0,
            ], 403);
        }

        // ── 2. Validasi Data Input dari ESP32 ──────────────────────────────────
        try {
            $validated = $request->validate([
                'suhu'       => ['required', 'numeric', 'between:-50,150'],
                'kelembaban' => ['required', 'numeric', 'between:0,100'],
                'water_level'=> ['nullable', 'numeric', 'between:0,100'],
                'timestamp'  => ['nullable', 'integer'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('[ESP32] Validasi data gagal', [
                'errors' => $e->errors(),
                'ip'     => $request->ip(),
            ]);
            
            return response()->json([
                'status'  => 'error',
                'message' => 'Validasi data gagal.',
                'errors'  => $e->errors(),
                'suhu_max' => 0,
                'kelembaban_min' => 0,
            ], 422);
        }

        // ── 3. Simpan Data ke Tabel sensor_logs ────────────────────────────────
        try {
            $ruleStatus = \App\Helpers\MonitoringHelper::getStatusLabel($validated['suhu'], $validated['kelembaban']);

            $sensorLog = SensorLog::create([
                'temperature'       => $validated['suhu'],
                'humidity'          => $validated['kelembaban'],
                'fan_status'        => 0,
                'lamp_status'       => 0,
                'humidifier_status' => 0,
                'water_level'       => $validated['water_level'] ?? null,
                'rule_status'       => $ruleStatus,
            ]);

            // Call FastAPI to get ML predictions
            try {
                $recent_logs = SensorLog::orderBy('id', 'desc')->take(15)->get()->reverse()->values();
                $payload = [];
                foreach ($recent_logs as $log) {
                    $payload[] = [
                        'temperature' => (float)$log->temperature,
                        'humidity'    => (float)$log->humidity,
                    ];
                }

                $response = \Illuminate\Support\Facades\Http::timeout(3)->post('http://127.0.0.1:8000/predict/advanced_combined', [
                    'data' => $payload
                ]);

                if ($response->successful()) {
                    $mlResult = $response->json();
                    $sensorLog->update([
                        'rf_prediction' => $mlResult['rf_prediction'] ?? null,
                        'if_prediction' => $mlResult['if_prediction'] ?? null,
                        'if_anomaly_score' => $mlResult['if_anomaly_score'] ?? null,
                    ]);
                }
            } catch (\Exception $e) {
                Log::warning('[ESP32] Gagal memanggil endpoint ML: ' . $e->getMessage());
            }

            Log::info('[ESP32] Data sensor berhasil disimpan', [
                'id'         => $sensorLog->id,
                'temperature' => $sensorLog->temperature,
                'humidity'    => $sensorLog->humidity,
                'rule_status' => $ruleStatus,
                'ip'         => $request->ip(),
                'timestamp'  => now()->toDateTimeString(),
            ]);

        } catch (\Exception $e) {
            Log::error('[ESP32] Gagal menyimpan data ke database', [
                'error' => $e->getMessage(),
                'ip'    => $request->ip(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan data ke database.',
                'suhu_max' => 0,
                'kelembaban_min' => 0,
            ], 500);
        }

        // ── 4. Ambil Respon Feedback (suhu_max dan kelembaban_min) ────────────
        // Ambil nilai terbaru: suhu tertinggi dari semua data dan kelembaban terendah
        $feedback = DB::table('sensor_logs')
            ->selectRaw('MAX(temperature) as suhu_max, MIN(humidity) as kelembaban_min')
            ->first();

        $suhuMax = $feedback ? (float)$feedback->suhu_max : 0;
        $kelembabanMin = $feedback ? (float)$feedback->kelembaban_min : 0;

        // ── 5. Respon Sukses ke ESP32 ─────────────────────────────────────────
        return response()->json([
            'status'  => 'success',
            'message' => 'Data sensor berhasil disimpan.',
            'suhu_max' => $suhuMax,
            'kelembaban_min' => $kelembabanMin,
            'data'    => [
                'id'          => $sensorLog->id,
                'temperature' => $sensorLog->temperature,
                'humidity'    => $sensorLog->humidity,
                'created_at'  => $sensorLog->created_at->toDateTimeString(),
            ],
        ], 200);
    }
}

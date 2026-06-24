<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Controller untuk menerima data sensor dari perangkat ESP32.
 * Endpoint: POST /api/kirim-data
 */
class SensorDataController extends Controller
{
    /**
     * Terima data sensor dari ESP32, validasi, simpan ke database,
     * dan kembalikan respon JSON.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function kirimData(Request $request)
    {
        // ── Validasi ──────────────────────────────────────────────────────────
        $validated = $request->validate([
            'suhu'       => ['required', 'numeric', 'between:-50,150'],
            'kelembaban' => ['required', 'numeric', 'between:0,100'],
        ]);

        // ── Simpan ke database ────────────────────────────────────────────────
        $sensorData = SensorData::create([
            'suhu'       => $validated['suhu'],
            'kelembaban' => $validated['kelembaban'],
        ]);

        // ── Log untuk debugging ───────────────────────────────────────────────
        Log::info('[ESP32] Data sensor diterima', [
            'id'         => $sensorData->id,
            'suhu'       => $sensorData->suhu,
            'kelembaban' => $sensorData->kelembaban,
            'ip'         => $request->ip(),
        ]);

        // ── Respon sukses ─────────────────────────────────────────────────────
        return response()->json([
            'status'  => 'success',
            'message' => 'Data sensor berhasil disimpan.',
            'data'    => [
                'id'         => $sensorData->id,
                'suhu'       => $sensorData->suhu,
                'kelembaban' => $sensorData->kelembaban,
                'timestamp'  => $sensorData->created_at->toDateTimeString(),
            ],
        ], 200);
    }
}

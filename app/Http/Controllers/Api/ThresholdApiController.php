<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ThresholdSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ThresholdApiController extends Controller
{
    /**
     * Get the latest threshold configuration
     */
    public function getThreshold(Request $request)
    {
        // Simple API Key check
        $apiKey = $request->header('X-API-KEY');
        $expectedKey = env('ESP32_API_KEY', '815171f9b522f1cd4cd95cb1d4410311');
        
        if ($apiKey !== $expectedKey) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $threshold = ThresholdSetting::getActive();
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'temp_min_ideal' => $threshold->temp_min_ideal,
                'temp_max_ideal' => $threshold->temp_max_ideal,
                'hum_min_ideal' => $threshold->hum_min_ideal,
                'hum_max_ideal' => $threshold->hum_max_ideal,
                'temp_min_ekstrem' => $threshold->temp_min_ekstrem,
                'temp_max_ekstrem' => $threshold->temp_max_ekstrem,
                'hum_min_ekstrem' => $threshold->hum_min_ekstrem,
                'hum_max_ekstrem' => $threshold->hum_max_ekstrem,
            ]
        ]);
    }

    /**
     * Update the threshold configuration from ESP32 Keypad
     */
    public function updateThreshold(Request $request)
    {
        // Simple API Key check
        $apiKey = $request->header('X-API-KEY');
        $expectedKey = env('ESP32_API_KEY', '815171f9b522f1cd4cd95cb1d4410311');
        
        if ($apiKey !== $expectedKey) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'temp_min_ideal' => 'required|numeric',
            'temp_max_ideal' => 'required|numeric',
            'hum_min_ideal' => 'required|numeric',
            'hum_max_ideal' => 'required|numeric',
            'temp_min_ekstrem' => 'required|numeric',
            'temp_max_ekstrem' => 'required|numeric',
            'hum_min_ekstrem' => 'required|numeric',
            'hum_max_ekstrem' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $temp_min_ideal = (float) $request->temp_min_ideal;
        $temp_max_ideal = (float) $request->temp_max_ideal;
        $hum_min_ideal = (float) $request->hum_min_ideal;
        $hum_max_ideal = (float) $request->hum_max_ideal;
        $temp_min_ekstrem = (float) $request->temp_min_ekstrem;
        $temp_max_ekstrem = (float) $request->temp_max_ekstrem;
        $hum_min_ekstrem = (float) $request->hum_min_ekstrem;
        $hum_max_ekstrem = (float) $request->hum_max_ekstrem;

        if ($temp_max_ideal <= $temp_min_ideal || 
            $hum_max_ideal <= $hum_min_ideal || 
            $temp_min_ekstrem >= $temp_min_ideal ||
            $temp_max_ekstrem <= $temp_max_ideal ||
            $hum_min_ekstrem >= $hum_min_ideal ||
            $hum_max_ekstrem <= $hum_max_ideal) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nilai batas tidak valid (berpotongan atau terbalik).'
            ], 422);
        }

        $threshold = ThresholdSetting::create([
            'temp_min_ideal' => $temp_min_ideal,
            'temp_max_ideal' => $temp_max_ideal,
            'hum_min_ideal' => $hum_min_ideal,
            'hum_max_ideal' => $hum_max_ideal,
            'temp_min_ekstrem' => $temp_min_ekstrem,
            'temp_max_ekstrem' => $temp_max_ekstrem,
            'hum_min_ekstrem' => $hum_min_ekstrem,
            'hum_max_ekstrem' => $hum_max_ekstrem,
            'updated_by' => 'keypad',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Threshold 3-Tier updated successfully via ESP32',
            'data' => $threshold
        ]);
    }
}

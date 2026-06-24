<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnomalyLog extends Model
{
    protected $fillable = [
        'temperature',
        'humidity',
        'anomaly_type',
        'description',
    ];

    /**
     * Detect anomaly for a SensorLog and save it if anomalous.
     *
     * @param \App\Models\SensorLog $sensorLog
     * @return bool Returns true if anomaly was detected and saved, false otherwise.
     */
    public static function detectAndSave($sensorLog)
    {
        // Check if an anomaly log already exists for this exact timestamp
        if (self::where('created_at', $sensorLog->created_at)->exists()) {
            return false;
        }

        try {
            // Call FastAPI Isolation Forest endpoint
            $response = \Illuminate\Support\Facades\Http::timeout(2)->post('http://127.0.0.1:8000/predict/anomaly', [
                'temperature' => (float)$sensorLog->temperature,
                'humidity'    => (float)$sensorLog->humidity,
                'fan_on'      => $sensorLog->fan_status ? 1 : 0,
                'heater_on'   => $sensorLog->lamp_status ? 1 : 0,
                'turner_on'   => $sensorLog->humidifier_status ? 1 : 0,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                if (isset($result['is_anomaly']) && $result['is_anomaly']) {
                    $temp = (float)$sensorLog->temperature;
                    $hum = (float)$sensorLog->humidity;
                    
                    // Simple rule-based descriptions
                    $descriptions = [];
                    if ($temp > 38.5) {
                        $descriptions[] = "Suhu terlalu tinggi di luar batas wajar ({$temp}°C)";
                    } elseif ($temp < 36.5) {
                        $descriptions[] = "Suhu terlalu rendah di luar batas wajar ({$temp}°C)";
                    }
                    
                    if ($hum > 70) {
                        $descriptions[] = "Kelembapan terlalu tinggi di luar batas wajar ({$hum}%)";
                    } elseif ($hum < 50) {
                        $descriptions[] = "Kelembapan turun drastis secara mendadak ({$hum}%)";
                    }
                    
                    if (empty($descriptions)) {
                        $descriptions[] = "Parameter sensor berada di luar batas distribusi normal (Isolation Forest anomaly)";
                    }
                    
                    $desc = implode(', ', $descriptions);

                    // Insert anomaly log matching the original sensor log timestamps
                    $anomaly = new self();
                    $anomaly->temperature = $temp;
                    $anomaly->humidity = $hum;
                    $anomaly->anomaly_type = 'Anomaly Detected';
                    $anomaly->description = $desc;
                    $anomaly->created_at = $sensorLog->created_at;
                    $anomaly->updated_at = $sensorLog->updated_at;
                    $anomaly->save();

                    return true;
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning("FastAPI anomaly detection failed for sensor log ID {$sensorLog->id}: " . $e->getMessage());
        }

        return false;
    }
}

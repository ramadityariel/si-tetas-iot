<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Exception;

class SensorLogController extends Controller
{
    /**
     * Display Firebase Sensor Logs
     */
    public function index()
    {
        $sensor_logs = [];
        $credentials_missing = false;

        try {
            $firebaseCredentials = base_path(env('FIREBASE_CREDENTIALS', 'storage/app/firebase-credentials.json'));
            $firebaseDbUrl = env('FIREBASE_DATABASE_URL', 'https://si-tetas-default-rtdb.firebaseio.com/');

            if (!file_exists($firebaseCredentials)) {
                $credentials_missing = true;
            } else {
                $factory = (new Factory)
                    ->withServiceAccount($firebaseCredentials)
                    ->withDatabaseUri($firebaseDbUrl);

                $database = $factory->createDatabase();
                
                // 1. AMBIL DATA THRESHOLD DINAMIS DARI FIREBASE
                $thresholds = $database->getReference('settings/threshold')->getValue() ?: [
                    'suhu_bawah'  => 26.0, // Default cadangan jika di firebase kosong
                    'suhu_atas'   => 32.0,
                    'humid_bawah' => 50.0,
                    'humid_atas'  => 65.0
                ];

                // 2. Ambil data dari node 'log_sensor'
                $rawLogs = $database->getReference('log_sensor')->getValue() ?: [];
                
                $processedLogs = [];
                foreach ($rawLogs as $key => $log) {
                    if (!is_array($log)) continue;

                    $temp = isset($log['suhu']) ? round((float)$log['suhu'], 1) : 0.0;
                    $humid = isset($log['kelembaban']) ? round((float)$log['kelembaban'], 1) : 0.0;
                    $timestamp = isset($log['timestamp']) ? (int)$log['timestamp'] : time();

                    $formattedTime = date('H:i:s', $timestamp);

                    // 3. HITUNG STATUS MENGGUNAKAN THRESHOLD DARI FIREBASE
                    $status = $this->determineStatus($temp, $humid, $thresholds);

                    $processedLogs[] = [
                        'time' => $formattedTime,
                        'temperature' => $temp,
                        'humidity' => $humid,
                        'status' => $status,
                        'timestamp' => $timestamp
                    ];
                }

                if (!empty($processedLogs)) {
                    usort($processedLogs, function ($a, $b) {
                        return $b['timestamp'] <=> $a['timestamp'];
                    });

                    $sensor_logs = array_slice($processedLogs, 0, 15);
                }
            }
        } catch (Exception $e) {
            logger()->error('Firebase Connection Error: ' . $e->getMessage());
            $credentials_missing = true;
        }

        return view('sensor_logs', compact('sensor_logs', 'credentials_missing'));
    }

    /**
     * Export Firebase Sensor Logs to Excel (CSV)
     */
    public function exportExcel()
    {
        $sensor_logs = [];

        try {
            $firebaseCredentials = base_path(env('FIREBASE_CREDENTIALS', 'storage/app/firebase-credentials.json'));
            $firebaseDbUrl = env('FIREBASE_DATABASE_URL', 'https://si-tetas-default-rtdb.firebaseio.com/');

            if (file_exists($firebaseCredentials)) {
                $factory = (new Factory)
                    ->withServiceAccount($firebaseCredentials)
                    ->withDatabaseUri($firebaseDbUrl);

                $database = $factory->createDatabase();

                // AMBIL DATA THRESHOLD UNTUK EXPORT EXCEL
                $thresholds = $database->getReference('settings/threshold')->getValue() ?: [
                    'suhu_bawah'  => 26.0,
                    'suhu_atas'   => 32.0,
                    'humid_bawah' => 50.0,
                    'humid_atas'  => 65.0
                ];

                $rawLogs = $database->getReference('log_sensor')->getValue() ?: [];
                
                $processedLogs = [];
                foreach ($rawLogs as $key => $log) {
                    if (!is_array($log)) continue;

                    $temp = isset($log['suhu']) ? round((float)$log['suhu'], 1) : 0.0;
                    $humid = isset($log['kelembaban']) ? round((float)$log['kelembaban'], 1) : 0.0;
                    $timestamp = isset($log['timestamp']) ? (int)$log['timestamp'] : time();

                    $processedLogs[] = [
                        'time' => date('H:i:s', $timestamp),
                        'temperature' => $temp,
                        'humidity' => $humid,
                        'status' => $this->determineStatus($temp, $humid, $thresholds),
                        'timestamp' => $timestamp
                    ];
                }

                if (!empty($processedLogs)) {
                    usort($processedLogs, function ($a, $b) {
                        return $b['timestamp'] <=> $a['timestamp'];
                    });
                    $sensor_logs = array_slice($processedLogs, 0, 15);
                }
            }
        } catch (Exception $e) {
            logger()->error('Firebase Export Error: ' . $e->getMessage());
        }

        $filename = 'Log-Sensor-Firebase-' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0'
        ];

        $columns = ['Waktu', 'Suhu (°C)', 'Kelembapan (%)', 'Status (3-Tier)'];

        $callback = function() use($sensor_logs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($sensor_logs as $log) {
                fputcsv($file, [
                    $log['time'],
                    number_format($log['temperature'], 1, '.', ''),
                    number_format($log['humidity'], 1, '.', ''),
                    $log['status']
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Helper untuk menentukan status 3-Tier secara dinamis berdasarkan data Firebase
     */
    private function determineStatus(float $suhu, float $kelembaban, array $thresholds): string
    {
        // Ambil batasan dari array thresholds Firebase, jika tidak ada gunakan fallback angka lama
        $suhuBawah  = $thresholds['suhu_bawah'] ?? 26.0;
        $suhuAtas   = $thresholds['suhu_atas'] ?? 32.0;
        $humidBawah = $thresholds['humid_bawah'] ?? 50.0;
        $humidAtas  = $thresholds['humid_atas'] ?? 65.0;

        // Kondisi Critical: jika suhu atau kelembaban berada DI LUAR rentang ideal bawah dan atas
        if ($suhu < $suhuBawah || $suhu > $suhuAtas || $kelembaban < $humidBawah || $kelembaban > $humidAtas) {
            return 'Critical';
        }
        
        return 'Normal';
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Exception;

class AnomalyLogController extends Controller
{
    /**
     * Menampilkan Halaman Histori Data Anomali dari Firebase
     * Hanya data dengan status "Critical" yang ditampilkan.
     */
    public function index()
    {
        $anomaly_logs        = [];
        $credentials_missing = false;

        try {
            $firebaseCredentials = base_path(env('FIREBASE_CREDENTIALS', 'storage/app/firebase-credentials.json'));
            $firebaseDbUrl       = env('FIREBASE_DATABASE_URL', 'https://si-tetas-default-rtdb.firebaseio.com/');

            if (!file_exists($firebaseCredentials)) {
                $credentials_missing = true;
            } else {
                $factory = (new Factory)
                    ->withServiceAccount($firebaseCredentials)
                    ->withDatabaseUri($firebaseDbUrl);

                $database = $factory->createDatabase();

                // ── 1. AMBIL THRESHOLD DINAMIS dari Firebase ──────────────────────────
                $thresholds = $database->getReference('settings/threshold')->getValue() ?: [
                    'suhu_bawah'  => 37.0,
                    'suhu_atas'   => 38.0,
                    'humid_bawah' => 55.0,
                    'humid_atas'  => 60.0,
                ];

                // Pastikan semua kunci tersedia dengan nilai fallback
                $thresholds = array_merge([
                    'suhu_bawah'  => 37.0,
                    'suhu_atas'   => 38.0,
                    'humid_bawah' => 55.0,
                    'humid_atas'  => 60.0,
                ], (array)$thresholds);

                // ── 2. AMBIL DATA RAW SENSOR dari node 'log_sensor' ───────────────────
                $rawLogs = $database->getReference('log_sensor')->getValue() ?: [];

                $processedAnomalies = [];
                foreach ($rawLogs as $key => $log) {
                    if (!is_array($log)) continue;

                    // Baca dan bulatkan nilai sensor
                    $temp      = isset($log['suhu'])       ? round((float)$log['suhu'], 1)       : 0.0;
                    $humid     = isset($log['kelembaban'])  ? round((float)$log['kelembaban'], 1)  : 0.0;
                    $timestamp = isset($log['timestamp'])   ? (int)$log['timestamp']               : 0;

                    // ── 3. FILTER KETAT: Hanya data Critical yang masuk ───────────────
                    $anomalyDetails = $this->checkAnomaly($temp, $humid, $thresholds);

                    if ($anomalyDetails['status'] === 'Critical') {
                        $processedAnomalies[] = [
                            'time'         => $timestamp > 0 ? date('H:i:s', $timestamp) : '-',
                            'date'         => $timestamp > 0 ? date('d M Y', $timestamp) : '-',
                            'temperature'  => $temp,
                            'humidity'     => $humid,
                            'anomaly_type' => $anomalyDetails['type'],
                            'status'       => 'Critical',
                            'timestamp'    => $timestamp,
                        ];
                    }
                }

                // ── 4. URUTKAN: Timestamp descending (terbaru di atas) ────────────────
                // ── 5. BATASI: Ambil maksimal 15 data anomali terbaru ────────────────
                if (!empty($processedAnomalies)) {
                    usort($processedAnomalies, fn($a, $b) => $b['timestamp'] <=> $a['timestamp']);
                    $anomaly_logs = array_slice($processedAnomalies, 0, 15);
                }
            }
        } catch (Exception $e) {
            logger()->error('[AnomalyLog] Firebase Error: ' . $e->getMessage());
            $credentials_missing = true;
        }

        return view('anomaly_logs', compact('anomaly_logs', 'credentials_missing'));
    }

    /**
     * Export Anomaly Logs ke CSV
     */
    public function exportExcel()
    {
        $anomaly_logs = [];

        try {
            $firebaseCredentials = base_path(env('FIREBASE_CREDENTIALS', 'storage/app/firebase-credentials.json'));
            $firebaseDbUrl       = env('FIREBASE_DATABASE_URL', 'https://si-tetas-default-rtdb.firebaseio.com/');

            if (file_exists($firebaseCredentials)) {
                $factory = (new Factory)
                    ->withServiceAccount($firebaseCredentials)
                    ->withDatabaseUri($firebaseDbUrl);

                $database = $factory->createDatabase();

                $thresholds = array_merge([
                    'suhu_bawah'  => 37.0,
                    'suhu_atas'   => 38.0,
                    'humid_bawah' => 55.0,
                    'humid_atas'  => 60.0,
                ], (array)($database->getReference('settings/threshold')->getValue() ?: []));

                $rawLogs            = $database->getReference('log_sensor')->getValue() ?: [];
                $processedAnomalies = [];

                foreach ($rawLogs as $key => $log) {
                    if (!is_array($log)) continue;

                    $temp      = isset($log['suhu'])      ? round((float)$log['suhu'], 1)      : 0.0;
                    $humid     = isset($log['kelembaban']) ? round((float)$log['kelembaban'], 1) : 0.0;
                    $timestamp = isset($log['timestamp'])  ? (int)$log['timestamp']              : 0;

                    $anomalyDetails = $this->checkAnomaly($temp, $humid, $thresholds);

                    if ($anomalyDetails['status'] === 'Critical') {
                        $processedAnomalies[] = [
                            'date'         => $timestamp > 0 ? date('d M Y H:i:s', $timestamp) : '-',
                            'temperature'  => $temp,
                            'humidity'     => $humid,
                            'anomaly_type' => $anomalyDetails['type'],
                            'status'       => 'Critical',
                            'timestamp'    => $timestamp,
                        ];
                    }
                }

                if (!empty($processedAnomalies)) {
                    usort($processedAnomalies, fn($a, $b) => $b['timestamp'] <=> $a['timestamp']);
                    $anomaly_logs = $processedAnomalies;
                }
            }
        } catch (Exception $e) {
            logger()->error('[AnomalyLog] Export Error: ' . $e->getMessage());
        }

        $filename = 'Log-Anomali-Firebase-' . date('Y-m-d_H-i-s') . '.csv';
        $headers  = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $columns  = ['Waktu', 'Suhu (°C)', 'Kelembapan (%)', 'Jenis Anomali', 'Status'];
        $callback = function () use ($anomaly_logs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($anomaly_logs as $log) {
                fputcsv($file, [
                    $log['date'],
                    number_format($log['temperature'], 1, '.', ''),
                    number_format($log['humidity'], 1, '.', ''),
                    $log['anomaly_type'],
                    $log['status'],
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ─────────────────────────────────────────────────────────────────────────
    /**
     * Helper: Memeriksa apakah data sensor menyimpang dari threshold.
     * Mengembalikan status 'Critical' dan keterangan jenis penyimpangannya.
     *
     * Logika penggabungan: Jika keduanya menyimpang, hasilkan teks gabungan
     * dengan separator ' & ' (contoh: "Suhu Tinggi & Kelembaban Rendah").
     */
    private function checkAnomaly(float $suhu, float $kelembaban, array $thresholds): array
    {
        $suhuBawah  = (float)($thresholds['suhu_bawah']  ?? 37.0);
        $suhuAtas   = (float)($thresholds['suhu_atas']   ?? 38.0);
        $humidBawah = (float)($thresholds['humid_bawah'] ?? 55.0);
        $humidAtas  = (float)($thresholds['humid_atas']  ?? 60.0);

        $reasons = [];

        // Deteksi penyimpangan suhu
        if ($suhu > $suhuAtas) {
            $reasons[] = 'Suhu Tinggi';
        } elseif ($suhu < $suhuBawah) {
            $reasons[] = 'Suhu Rendah';
        }

        // Deteksi penyimpangan kelembaban
        if ($kelembaban > $humidAtas) {
            $reasons[] = 'Kelembaban Tinggi';
        } elseif ($kelembaban < $humidBawah) {
            $reasons[] = 'Kelembaban Rendah';
        }

        if (!empty($reasons)) {
            return [
                'status' => 'Critical',
                'type'   => implode(' & ', $reasons),
            ];
        }

        return [
            'status' => 'Normal',
            'type'   => 'Normal',
        ];
    }
}

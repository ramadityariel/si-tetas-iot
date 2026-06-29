<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;

class MonitoringController extends Controller
{
    public function index()
    {
        $table_logs = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        $anomaly_logs = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        $chart_labels = [];
        $temp_data = [];
        $humid_data = [];
        $latest_sensor = null;

        try {
            $firebaseCredentials = base_path(env('FIREBASE_CREDENTIALS', 'storage/app/firebase-credentials.json'));
            $firebaseDbUrl       = env('FIREBASE_DATABASE_URL', 'https://si-tetas-default-rtdb.firebaseio.com/');

            if (file_exists($firebaseCredentials)) {
                $factory = (new \Kreait\Firebase\Factory)
                    ->withServiceAccount($firebaseCredentials)
                    ->withDatabaseUri($firebaseDbUrl);

                $database = $factory->createDatabase();

                $thresholds = array_merge([
                    'suhu_bawah'  => 37.0,
                    'suhu_atas'   => 38.0,
                    'humid_bawah' => 55.0,
                    'humid_atas'  => 60.0,
                ], (array)($database->getReference('settings/threshold')->getValue() ?: []));

                $rawLogs = $database->getReference('log_sensor')->getValue() ?: [];
                
                $processedLogs = [];
                $processedAnomalies = [];
                
                foreach ($rawLogs as $key => $log) {
                    if (!is_array($log)) continue;
                    
                    $temp      = isset($log['suhu']) ? round((float)$log['suhu'], 1) : 0.0;
                    $humid     = isset($log['kelembaban']) ? round((float)$log['kelembaban'], 1) : 0.0;
                    $timestamp = isset($log['timestamp']) ? (int)$log['timestamp'] : 0;
                    if ($timestamp === 0) continue;

                    $createdAt = \Carbon\Carbon::createFromTimestamp($timestamp)->timezone('Asia/Jakarta');

                    // Logic for Status (Sensor Log)
                    $status = 'Normal';
                    if ($temp < $thresholds['suhu_bawah'] || $temp > $thresholds['suhu_atas'] || $humid < $thresholds['humid_bawah'] || $humid > $thresholds['humid_atas']) {
                        $status = 'Critical';
                    }
                    if ($temp >= 36.5 && $temp <= 38.5 && $humid >= 50 && $humid <= 70) {
                        $statusPrediction = 'Optimal';
                    } elseif ($temp >= 35.0 && $temp <= 39.0 && $humid >= 40 && $humid <= 80) {
                        $statusPrediction = 'Warning';
                    } else {
                        $statusPrediction = 'Critical';
                    }

                    $sensorObj = (object)[
                        'created_at' => $createdAt,
                        'temperature' => $temp,
                        'humidity' => $humid,
                        'status_prediction' => $statusPrediction,
                        'timestamp' => $timestamp,
                        'fan_status' => isset($log['fan_status']) ? (bool)$log['fan_status'] : false,
                        'lamp_status' => isset($log['lamp_status']) ? (bool)$log['lamp_status'] : false,
                        'humidifier_status' => isset($log['humidifier_status']) ? (bool)$log['humidifier_status'] : false,
                    ];
                    $processedLogs[] = $sensorObj;

                    // Logic for Anomaly
                    $reasons = [];
                    if ($temp > $thresholds['suhu_atas']) $reasons[] = 'Suhu Tinggi';
                    elseif ($temp < $thresholds['suhu_bawah']) $reasons[] = 'Suhu Rendah';
                    if ($humid > $thresholds['humid_atas']) $reasons[] = 'Kelembaban Tinggi';
                    elseif ($humid < $thresholds['humid_bawah']) $reasons[] = 'Kelembaban Rendah';

                    if (!empty($reasons)) {
                        $processedAnomalies[] = (object)[
                            'created_at'   => $createdAt,
                            'temperature'  => $temp,
                            'humidity'     => $humid,
                            'anomaly_type' => implode(' & ', $reasons),
                            'description'  => 'Terdeteksi ' . strtolower(implode(' & ', $reasons)),
                            'timestamp'    => $timestamp,
                        ];
                    }
                }

                // Sort descending
                usort($processedLogs, fn($a, $b) => $b->timestamp <=> $a->timestamp);
                usort($processedAnomalies, fn($a, $b) => $b->timestamp <=> $a->timestamp);

                $latest_sensor = $processedLogs[0] ?? null;

                // Paginate Sensor Logs
                $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
                $perPage = 10;
                $currentSensorItems = array_slice($processedLogs, ($currentPage - 1) * $perPage, $perPage);
                $table_logs = new \Illuminate\Pagination\LengthAwarePaginator($currentSensorItems, count($processedLogs), $perPage, $currentPage, [
                    'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()
                ]);

                // Paginate Anomaly Logs
                $currentAnomalyItems = array_slice($processedAnomalies, ($currentPage - 1) * $perPage, $perPage);
                $anomaly_logs = new \Illuminate\Pagination\LengthAwarePaginator($currentAnomalyItems, count($processedAnomalies), $perPage, $currentPage, [
                    'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()
                ]);

                // Charts Data
                $range = request('range', 'today');
                $now = \Carbon\Carbon::now('Asia/Jakarta');
                $startTime = $range === 'week' ? $now->copy()->startOfWeek() : $now->copy()->startOfDay();

                $filteredChartLogs = array_filter($processedLogs, function($log) use ($startTime) {
                    return $log->created_at->greaterThanOrEqualTo($startTime);
                });

                // Reverse for chronological order
                $filteredChartLogs = array_reverse($filteredChartLogs);
                
                // Downsample to max 60 points
                $totalPoints = count($filteredChartLogs);
                $maxPoints = 60;
                $chartLogs = [];
                
                if ($totalPoints > $maxPoints) {
                    $step = $totalPoints / $maxPoints;
                    for ($i = 0; $i < $maxPoints; $i++) {
                        $index = (int) floor($i * $step);
                        if (isset($filteredChartLogs[$index])) {
                            $chartLogs[] = $filteredChartLogs[$index];
                        }
                    }
                } else {
                    $chartLogs = $filteredChartLogs;
                }
                
                foreach ($chartLogs as $cl) {
                    if ($range === 'week') {
                        $chart_labels[] = $cl->created_at->format('D H:i');
                    } else {
                        $chart_labels[] = $cl->created_at->format('H:i');
                    }
                    $temp_data[] = $cl->temperature;
                    $humid_data[] = $cl->humidity;
                }
            }
        } catch (\Exception $e) {
            logger()->error('[Monitoring] Firebase Error: ' . $e->getMessage());
        }

        return view('monitoring', compact('latest_sensor', 'chart_labels', 'temp_data', 'humid_data', 'table_logs', 'anomaly_logs'));
    }

    public function exportPDF()
    {
        $logs = \App\Models\SensorLog::latest()->take(20)->get();
        
        if (ob_get_length()) {
            ob_end_clean();
        }
        
        $pdf = Pdf::loadView('admin.monitoring.report_pdf', compact('logs'))
                  ->setPaper('a4', 'portrait');
        
        return $pdf->stream('Laporan-SiTetas.pdf');
    }

    public function exportExcel()
    {
        $logs = \App\Models\SensorLog::latest()->get();
        
        $filename = 'Laporan-Sensor-SiTetas-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0'
        ];
        
        $columns = ['ID', 'Suhu (°C)', 'Kelembapan (%)', 'Status Kipas', 'Waktu Tercatat'];
        
        $callback = function() use($logs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->temperature,
                    $log->humidity,
                    $log->fan_status ? 'Aktif' : 'Mati',
                    $log->created_at->format('Y-m-d H:i:s')
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}

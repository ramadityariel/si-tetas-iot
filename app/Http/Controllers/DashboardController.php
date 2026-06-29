<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $latest_sensor = null;
        $chart_labels = [];
        $chart_data = [];
        $published_articles_count = \App\Models\Article::where('status', 'published')->count();
        
        try {
            $firebaseCredentials = base_path(env('FIREBASE_CREDENTIALS', 'storage/app/firebase-credentials.json'));
            $firebaseDbUrl       = env('FIREBASE_DATABASE_URL', 'https://si-tetas-default-rtdb.firebaseio.com/');

            if (file_exists($firebaseCredentials)) {
                $factory = (new \Kreait\Firebase\Factory)
                    ->withServiceAccount($firebaseCredentials)
                    ->withDatabaseUri($firebaseDbUrl);

                $database = $factory->createDatabase();

                $rawLogs = $database->getReference('log_sensor')->getValue() ?: [];
                
                $processedLogs = [];
                
                foreach ($rawLogs as $key => $log) {
                    if (!is_array($log)) continue;
                    
                    $temp      = isset($log['suhu']) ? round((float)$log['suhu'], 1) : 0.0;
                    $humid     = isset($log['kelembaban']) ? round((float)$log['kelembaban'], 1) : 0.0;
                    $timestamp = isset($log['timestamp']) ? (int)$log['timestamp'] : 0;
                    if ($timestamp === 0) continue;

                    // Apply the local timezone +07:00
                    $createdAt = \Carbon\Carbon::createFromTimestamp($timestamp)->timezone('Asia/Jakarta');

                    $sensorObj = (object)[
                        'created_at' => $createdAt,
                        'temperature' => $temp,
                        'humidity' => $humid,
                        'timestamp' => $timestamp,
                    ];
                    $processedLogs[] = $sensorObj;
                }

                if (!empty($processedLogs)) {
                    usort($processedLogs, fn($a, $b) => $b->timestamp <=> $a->timestamp);
                    $latest_sensor = $processedLogs[0];
                    
                    $range = request('range', '24h');
                    $now = \Carbon\Carbon::now('Asia/Jakarta');
                    $startTime = $range === '7d' ? $now->copy()->subDays(7) : $now->copy()->subHours(24);
                    
                    $filteredLogs = array_filter($processedLogs, function($log) use ($startTime) {
                        return $log->created_at->greaterThanOrEqualTo($startTime);
                    });
                    
                    // Reverse for chronological order (oldest to newest for chart)
                    $filteredLogs = array_reverse($filteredLogs);
                    
                    // Downsample to max 60 points to prevent browser crash/lag
                    $totalPoints = count($filteredLogs);
                    $maxPoints = 60;
                    $historical_sensors = [];
                    
                    if ($totalPoints > $maxPoints) {
                        $step = $totalPoints / $maxPoints;
                        for ($i = 0; $i < $maxPoints; $i++) {
                            $index = (int) floor($i * $step);
                            if (isset($filteredLogs[$index])) {
                                $historical_sensors[] = $filteredLogs[$index];
                            }
                        }
                    } else {
                        $historical_sensors = $filteredLogs;
                    }
                    
                    foreach ($historical_sensors as $cl) {
                        // If 7 days, show Date + Time, else just Time
                        if ($range === '7d') {
                            $chart_labels[] = $cl->created_at->format('d M H:i');
                        } else {
                            $chart_labels[] = $cl->created_at->format('H:i');
                        }
                        $chart_data[] = $cl->temperature;
                    }
                }
            }
        } catch (\Exception $e) {
            logger()->error('[Dashboard] Firebase Error: ' . $e->getMessage());
        }

        return view('dashboard', compact('latest_sensor', 'chart_labels', 'chart_data', 'published_articles_count'));
    }
}

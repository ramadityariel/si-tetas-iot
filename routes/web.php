<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    $articles = \App\Models\Article::query()
        ->where('status', 'published')
        ->latest()
        ->take(3)
        ->get();
    return view('welcome', compact('articles'));
})->name('home');

Route::middleware('web')->group(function () {
    Route::get('/lang/{locale}', function ($locale) {
        if (in_array($locale, ['id', 'en'])) {
            session(['locale' => $locale]);
        }
        return redirect()->back();
    })->name('lang.switch');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public Blog Routes
Route::get('/blog', function (\Illuminate\Http\Request $request) {
    $query = \App\Models\Article::query()->where('status', 'published');
    if ($search = $request->get('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('subtitle', 'like', "%{$search}%");
        });
    }
    $articles = $query->latest()->get();
    return view('blog.index', compact('articles'));
})->name('blog.index');

Route::get('/blog/{slug}', [\App\Http\Controllers\BlogController::class, 'showPublic'])->name('blog.read');

use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\AIMonitoringController;

// Admin Routes protected by auth middleware
Route::middleware(['auth', \App\Http\Middleware\SetLocale::class])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // AI Monitoring Dashboard
    Route::get('/ai-monitoring', [AIMonitoringController::class, 'index'])->name('ai-monitoring');
    Route::get('/ai-monitoring/api/daily-status', [AIMonitoringController::class, 'getDailyStatusDistribution'])->name('ai-monitoring.daily-status');
    Route::get('/ai-monitoring/api/realtime-zone', [AIMonitoringController::class, 'getRealtimeZoneChart'])->name('ai-monitoring.realtime-zone');
    Route::get('/ai-monitoring/api/summary', [AIMonitoringController::class, 'getStatusSummary'])->name('ai-monitoring.summary');
    Route::get('/ai-monitoring/api/hourly-trend', [AIMonitoringController::class, 'getHourlyTrend'])->name('ai-monitoring.hourly-trend');

    // Threshold Settings
    Route::get('/settings/threshold', [\App\Http\Controllers\ThresholdSettingController::class, 'index'])->name('settings.threshold');
    Route::post('/settings/threshold', [\App\Http\Controllers\ThresholdSettingController::class, 'store'])->name('settings.threshold.store');

    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring');
    Route::get('/monitoring/export-pdf', [MonitoringController::class, 'exportPDF'])->name('monitoring.export-pdf');
    Route::get('/monitoring/export-excel', [MonitoringController::class, 'exportExcel'])->name('monitoring.export-excel');

    // Log Sensor & Log Anomali (separated)
    Route::get('/sensor-logs', [\App\Http\Controllers\SensorLogController::class, 'index'])->name('sensor-logs');
    Route::get('/sensor-logs/export-excel', [\App\Http\Controllers\SensorLogController::class, 'exportExcel'])->name('sensor-logs.export-excel');
    
    Route::get('/anomaly-logs', [\App\Http\Controllers\AnomalyLogController::class, 'index'])->name('anomaly-logs');
    Route::get('/anomaly-logs/export-excel', [\App\Http\Controllers\AnomalyLogController::class, 'exportExcel'])->name('anomaly-logs.export-excel');

    Route::get('/prediksi', [\App\Http\Controllers\PredictionController::class, 'index'])->name('prediksi');
    Route::post('/prediksi/snapshot', [\App\Http\Controllers\PredictionController::class, 'snapshot'])->name('prediksi.snapshot');
    Route::get('/prediksi/export-pdf', [\App\Http\Controllers\PredictionController::class, 'exportPDF'])->name('prediksi.export-pdf');
    Route::get('/prediksi/export-csv/{id}', [\App\Http\Controllers\PredictionController::class, 'exportData'])->name('prediksi.export-data');
    Route::delete('/prediksi/destroy/{id}', [\App\Http\Controllers\PredictionController::class, 'destroy'])->name('prediksi.destroy');

    Route::middleware(['super_admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // Admin Blog CRUD
    Route::get('/blog', [BlogController::class, 'index'])->name('admin.blog');
    Route::get('/blog/create', [BlogController::class, 'create'])->name('blog.create');
    Route::post('/blog', [BlogController::class, 'store'])->name('blog.store');
    Route::get('/blog/{article}/edit', [BlogController::class, 'edit'])->name('blog.edit');
    Route::put('/blog/{article}', [BlogController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{article}', [BlogController::class, 'destroy'])->name('blog.destroy');
});

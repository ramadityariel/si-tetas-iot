# 🤖 AI Monitoring Implementation Guide

## 📋 Overview
Halaman **AI Monitoring** telah berhasil diimplementasikan untuk memantau kondisi inkubator secara realtime dengan visualisasi 3-tier status menggunakan Chart.js.

## 🎯 Fitur Utama

### 1. **3-Tier Status System**
- **Baik (Green)**: Suhu 37.0°C - 38.0°C **AND** Humidity 55% - 60%
- **Perhatian (Yellow)**: Rentang transisi di luar kondisi Baik
- **Critical (Red)**: Suhu < 36.0°C atau > 39.0°C, atau Humidity < 50% atau > 65%

### 2. **Chart Visualizations**

#### a. Daily Status Distribution (Donut Chart)
- Menampilkan persentase data sensor dengan status Baik, Perhatian, Critical untuk hari ini
- Data diperbarui setiap 30 detik
- File: `AIMonitoringController::getDailyStatusDistribution()`

#### b. Hourly Trend (Stacked Bar Chart)
- Menunjukkan jumlah reading sensor setiap jam dalam 24 jam terakhir
- Dipisah berdasarkan 3 status
- File: `AIMonitoringController::getHourlyTrend()`

#### c. Temperature Zone Chart (Line Chart)
- Visualisasi tren suhu realtime dengan color-coded points
- Menampilkan zone banding untuk rentang Baik dan Perhatian
- Menggunakan 48 data terakhir
- File: `AIMonitoringController::getRealtimeZoneChart()`

#### d. Humidity Zone Chart (Line Chart)
- Visualisasi tren kelembapan realtime dengan color-coded points
- Menampilkan zone banding untuk rentang Baik dan Perhatian
- Menggunakan 48 data terakhir
- File: `AIMonitoringController::getRealtimeZoneChart()`

### 3. **Summary Cards**
- Today's Logs: Jumlah data sensor hari ini
- Latest Status: Status terkini dengan temperature & humidity
- Today's Anomalies: Jumlah anomali yang terdeteksi hari ini
- Week Anomalies: Jumlah anomali dalam 7 hari terakhir

## 🔧 Setup Instructions

### Step 1: Run Migration
```bash
php artisan migrate
```

### Step 2: Seed Threshold Settings
```bash
php artisan db:seed --class=ThresholdSettingSeeder
```

Atau jalankan semua seeder:
```bash
php artisan db:seed
```

### Step 3: Update composer.json (jika belum)
Library Chart.js sudah di-include via CDN di view, tidak perlu npm install.

### Step 4: Akses Dashboard
Buka menu **AI Monitoring** di sidebar admin (ikon auto_awesome)
URL: `http://localhost:8000/admin/ai-monitoring`

## 📁 File Structure

### Models
- `app/Models/ThresholdSetting.php` - Model untuk menyimpan threshold settings
- `app/Models/SensorLog.php` - Sudah ada, tidak ada perubahan
- `app/Models/AnomalyLog.php` - Sudah ada, tidak ada perubahan

### Helpers
- `app/Helpers/MonitoringHelper.php` - Fungsi helper untuk status logic
  - `getStatusLabel($temp, $humidity)` - Menentukan status berdasarkan data
  - `getStatusColor($status)` - Mengembalikan hex color untuk status
  - `getStatusBadgeClass($status)` - Mengembalikan Tailwind classes
  - `getStatusIcon($status)` - Mengembalikan material icon name

### Controllers
- `app/Http/Controllers/AIMonitoringController.php`
  - `index()` - Menampilkan dashboard
  - `getDailyStatusDistribution()` - API endpoint untuk donut chart
  - `getRealtimeZoneChart()` - API endpoint untuk zone charts
  - `getStatusSummary()` - API endpoint untuk summary cards
  - `getHourlyTrend()` - API endpoint untuk hourly trend chart

### Views
- `resources/views/ai_monitoring.blade.php` - Main dashboard view dengan Chart.js scripts

### Routes
File: `routes/web.php`
```php
Route::get('/ai-monitoring', [AIMonitoringController::class, 'index'])->name('ai-monitoring');
Route::get('/ai-monitoring/api/daily-status', [AIMonitoringController::class, 'getDailyStatusDistribution']);
Route::get('/ai-monitoring/api/realtime-zone', [AIMonitoringController::class, 'getRealtimeZoneChart']);
Route::get('/ai-monitoring/api/summary', [AIMonitoringController::class, 'getStatusSummary']);
Route::get('/ai-monitoring/api/hourly-trend', [AIMonitoringController::class, 'getHourlyTrend']);
```

### Migrations
- `database/migrations/2026_06_26_000000_create_threshold_settings_table.php`

### Database Seeders
- `database/seeders/ThresholdSettingSeeder.php`

### Language Files
- `lang/id/admin.php` - Strings bahasa Indonesia (section: ai_monitoring)
- `lang/en/admin.php` - Strings bahasa Inggris (section: ai_monitoring)

## 🔄 Data Flow

```
ESP32 Sensor
    ↓
SensorLog (database)
    ↓
AIMonitoringController
    ↓
MonitoringHelper::getStatusLabel()
    ↓
3-Tier Status (Baik/Perhatian/Critical)
    ↓
Chart.js Visualization
    ↓
AI Monitoring Dashboard
```

## 🔐 Security & Performance

### Optimizations
- Pagination untuk data besar
- Query optimization dengan `limit()` dan `latest()`
- Chart auto-refresh setiap 30 detik (dapat diubah di view)
- Zone chart hanya menggunakan 48 data terakhir (lightweight)

### Access Control
- Protected by `auth` middleware
- Hanya admin yang bisa akses

## 📊 Threshold Customization

Untuk mengubah threshold, edit record di table `threshold_settings`:

```php
// Programmatically update threshold
$threshold = ThresholdSetting::where('is_active', true)->first();
$threshold->update([
    'temp_good_min' => 36.5,
    'temp_good_max' => 38.5,
    'humidity_good_min' => 54.0,
    'humidity_good_max' => 61.0,
]);
```

Atau langsung di database:
```sql
UPDATE threshold_settings SET 
  temp_good_min = 36.5,
  temp_good_max = 38.5
WHERE is_active = true;
```

## 🎨 Customization

### Ubah Warna Zone
Edit di `AIMonitoringController` method `getRealtimeZoneChart()`:
```php
'#10b981', // Green - Baik
'#f59e0b', // Amber - Perhatian  
'#ef4444', // Red - Critical
```

### Ubah Refresh Interval
Di `resources/views/ai_monitoring.blade.php`, cari:
```javascript
setInterval(function() { ... }, 30000); // 30000ms = 30 detik
```

### Ubah Jumlah Data Points
Di `AIMonitoringController` method `getRealtimeZoneChart()`:
```php
$logs = SensorLog::latest()->limit(48)->get(); // Ubah 48 ke nilai lain
```

## 🧪 Testing

### Manual Test API Endpoints
```bash
# Dapatkan daily status
curl http://localhost:8000/admin/ai-monitoring/api/daily-status

# Dapatkan realtime zone data
curl http://localhost:8000/admin/ai-monitoring/api/realtime-zone

# Dapatkan summary
curl http://localhost:8000/admin/ai-monitoring/api/summary

# Dapatkan hourly trend
curl http://localhost:8000/admin/ai-monitoring/api/hourly-trend
```

### Insert Test Data
```php
// Di tinker atau seeder
use App\Models\SensorLog;
for ($i = 0; $i < 100; $i++) {
    SensorLog::create([
        'temperature' => rand(350, 390) / 10,
        'humidity' => rand(500, 700) / 10,
        'fan_status' => rand(0, 1),
    ]);
}
```

## 📱 Browser Compatibility
- Chrome/Edge: ✅ Fully supported
- Firefox: ✅ Fully supported
- Safari: ✅ Fully supported
- IE11: ❌ Not supported (Chart.js 4.4)

## 🐛 Troubleshooting

### Chart tidak muncul
1. Buka browser console (F12)
2. Cek error message
3. Pastikan Chart.js CDN tersedia
4. Pastikan data API endpoint mengembalikan response yang valid

### Data tidak update
1. Buka Network tab di DevTools
2. Lihat apakah API endpoint diakses
3. Pastikan ada data di database `sensor_logs`
4. Cek `MonitoringHelper::getStatusLabel()` logic

### Threshold tidak berubah
1. Pastikan record di `threshold_settings` dengan `is_active = true`
2. Cek `ThresholdSetting::getActive()` mengembalikan record yang benar
3. Refresh halaman setelah update threshold

## 📞 Support

Untuk pertanyaan atau issue:
1. Cek logs di `storage/logs/laravel.log`
2. Jalankan `php artisan tinker` untuk debug
3. Cek database records di `threshold_settings` dan `sensor_logs`

---

**Last Updated**: 26 Juni 2026
**Version**: 1.0
**Status**: Production Ready ✅

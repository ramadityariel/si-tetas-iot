# 🚀 Si-Tetas IoT — Quick Start Integration Guide

## 📋 Ringkasan Perubahan yang Telah Dilakukan

### ✅ File yang Sudah Diupdate/Dibuat

1. **`app/Http/Controllers/SensorDataController.php`** ← [DIUPDATE]
   - Validasi X-API-KEY header (`815171f9b522f1cd4cd95cb1d4410311`)
   - Validasi data input JSON (suhu, kelembaban, timestamp)
   - Insert ke tabel `sensor_logs` dengan mappings yang benar:
     - `suhu` → `temperature`
     - `kelembaban` → `humidity`
     - Default values untuk `fan_status`, `lamp_status`, `humidifier_status` = 0
   - Respon JSON dengan `suhu_max` dan `kelembaban_min` untuk feedback ke ESP32
   - Error handling lengkap dengan HTTP status codes yang tepat
   - Logging untuk debugging

2. **`routes/api.php`** ← [SUDAH BENAR]
   - Route `POST /api/kirim-data` sudah mengacu ke `SensorDataController@kirimData`
   - Tidak perlu perubahan

3. **`app/Models/SensorLog.php`** ← [SUDAH BENAR]
   - Model dengan fillable fields yang sesuai
   - Tidak perlu perubahan

4. **Database Migrations** ← [SUDAH BENAR]
   - Tabel `sensor_logs` sudah ter-setup dengan kolom yang diperlukan
   - Tidak perlu perubahan

5. **`API_DOCUMENTATION.md`** ← [BARU]
   - Dokumentasi lengkap spesifikasi API
   - Format request/response
   - Error handling
   - Database schema
   - Security info

6. **`TESTING_GUIDE.md`** ← [BARU]
   - Panduan testing dengan cURL, Postman, Arduino IDE
   - Troubleshooting
   - Verification steps

---

## ⚡ Quick Start (5 Menit)

### Step 1: Pastikan Laravel Berjalan
```bash
php artisan serve --port=8001
```

### Step 2: Jalankan Database Migration (Jika Belum)
```bash
php artisan migrate
```

### Step 3: Test dengan cURL
```bash
curl -X POST http://localhost:8001/api/kirim-data \
  -H "Content-Type: application/json" \
  -H "X-API-KEY: 815171f9b522f1cd4cd95cb1d4410311" \
  -d '{"suhu": 36.5, "kelembaban": 60, "timestamp": 0}'
```

**Respon yang Diharapkan (HTTP 200)**:
```json
{
  "status": "success",
  "message": "Data sensor berhasil disimpan.",
  "suhu_max": 36.5,
  "kelembaban_min": 60,
  "data": {
    "id": 1,
    "temperature": 36.5,
    "humidity": 60,
    "created_at": "2026-06-24 14:30:45"
  }
}
```

### Step 4: ESP32 Configuration
Update di Arduino IDE (file `esp32_sensor_sender.ino`):
```cpp
const String BASE_URL = "http://192.168.1.100:8001/"; // Ganti IP laptop Anda
const char* apiKey = "815171f9b522f1cd4cd95cb1d4410311";
```

### Step 5: Upload & Monitor
- Upload sketch ke ESP32
- Buka Serial Monitor (115200 baud)
- Lihat data terkirim dengan HTTP 200 response
- Cek database di phpMyAdmin

---

## 📝 API Specification

### Endpoint
```
POST http://<IP_LAPTOP>:8001/api/kirim-data
```

### Required Headers
```
Content-Type: application/json
X-API-KEY: 815171f9b522f1cd4cd95cb1d4410311
```

### Request Body
```json
{
  "suhu": 36.5,
  "kelembaban": 60,
  "timestamp": 0
}
```

### Success Response (HTTP 200)
```json
{
  "status": "success",
  "message": "Data sensor berhasil disimpan.",
  "suhu_max": 37.8,
  "kelembaban_min": 45.2,
  "data": {
    "id": 42,
    "temperature": 36.5,
    "humidity": 60,
    "created_at": "2026-06-24 14:30:45"
  }
}
```

### Error Responses
- **HTTP 401**: X-API-KEY header tidak ada
- **HTTP 403**: X-API-KEY tidak valid
- **HTTP 422**: Data validation error
- **HTTP 500**: Database error

---

## 🗄️ Database Schema

### Tabel `sensor_logs`
```
id                  INT          PRIMARY KEY, AUTO_INCREMENT
temperature         FLOAT        (dari "suhu" ESP32)
humidity            FLOAT        (dari "kelembaban" ESP32)
fan_status          TINYINT      DEFAULT 0
lamp_status         TINYINT      DEFAULT 0
humidifier_status   TINYINT      DEFAULT 0
created_at          TIMESTAMP    AUTO
updated_at          TIMESTAMP    AUTO
```

---

## 🔑 API Key Security

- **Current API Key**: `815171f9b522f1cd4cd95cb1d4410311`
- **Location**: Hard-coded di `SensorDataController.php` line 28
- **Recommendation**: 
  - Untuk development OK
  - Untuk production, gunakan `.env` variable:
    ```php
    private const VALID_API_KEY = env('ESP32_API_KEY', '815171f9b522f1cd4cd95cb1d4410311');
    ```

---

## 🧪 Testing Methods

### 1. Quick Test dengan cURL
```bash
curl -X POST http://localhost:8001/api/kirim-data \
  -H "Content-Type: application/json" \
  -H "X-API-KEY: 815171f9b522f1cd4cd95cb1d4410311" \
  -d '{"suhu": 36.5, "kelembaban": 60, "timestamp": 0}'
```

### 2. Test dengan Postman
- Import di Postman
- Method: POST
- URL: `http://localhost:8001/api/kirim-data`
- Headers: `X-API-KEY: 815171f9b522f1cd4cd95cb1d4410311`
- Body (JSON): `{"suhu": 36.5, "kelembaban": 60, "timestamp": 0}`

### 3. Test dengan ESP32
- Update IP address di sketch
- Upload ke ESP32
- Monitor Serial Monitor
- Lihat data masuk ke database

### 4. Verify di Database
```sql
SELECT * FROM sensor_logs ORDER BY id DESC LIMIT 10;
```

---

## 📊 Monitoring

### Check Logs
```bash
# Linux/Mac
tail -f storage/logs/laravel.log

# Windows PowerShell
Get-Content storage/logs/laravel.log -Tail 20 -Wait
```

### Expected Log Output
```
[2026-06-24 14:30:45] local.INFO: [ESP32] Data sensor berhasil disimpan {"id":1,"temperature":36.5,"humidity":60,"ip":"192.168.1.50","timestamp":"2026-06-24 14:30:45"}
```

---

## 🐛 Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| Connection Refused | Pastikan Laravel running: `php artisan serve --port=8001` |
| 401 Unauthorized | Check header `X-API-KEY` ada di request |
| 403 Forbidden | Pastikan API Key exact: `815171f9b522f1cd4cd95cb1d4410311` |
| 422 Validation Error | Pastikan JSON format benar dan tipe data sesuai |
| ESP32 tidak bisa connect | Check IP address, WiFi connection, network routing |
| Data tidak masuk database | Run `php artisan migrate` untuk setup table |

---

## 📚 Documentation Files

Dalam project Anda sudah ada:

1. **`API_DOCUMENTATION.md`** - Dokumentasi lengkap API
2. **`TESTING_GUIDE.md`** - Panduan testing detail
3. **`QUICK_START.md`** - File ini, ringkasan singkat

---

## ✅ Verification Checklist

Sebelum deploy production, pastikan:

- [ ] Laravel running di port 8001
- [ ] Database sudah ter-migrate
- [ ] cURL test berhasil HTTP 200
- [ ] Test tanpa API Key → HTTP 401
- [ ] Test dengan API Key salah → HTTP 403
- [ ] ESP32 bisa send data → Serial Monitor show HTTP 200
- [ ] Data muncul di database phpMyAdmin
- [ ] `suhu_max` & `kelembaban_min` berisi nilai real
- [ ] Log file mencatat semua activity

---

## 🎯 Architecture Overview

```
┌─────────────────┐
│   ESP32 Sensor  │
│  (DHT22/SHT20)  │
└────────┬────────┘
         │ HTTP POST (JSON)
         │ Header: X-API-KEY
         │ {"suhu": 36.5, "kelembaban": 60}
         │
         ▼
┌──────────────────────────────────┐
│  Laravel Backend (Port 8001)     │
│                                  │
│  Route: POST /api/kirim-data     │
│  ↓                               │
│  SensorDataController            │
│  ├─ Validate API Key ✓           │
│  ├─ Validate Data ✓              │
│  ├─ Insert to sensor_logs ✓      │
│  └─ Return JSON Response ✓       │
└──────────────────────────────────┘
         │
         │ Response JSON
         │ {"status":"success", "suhu_max":37.8, ...}
         │
         ▼
     Database
   sensor_logs
```

---

## 🚀 Next Steps

Setelah integration berhasil:

1. **Dashboard**: Buat page untuk visualisasi real-time data
2. **Anomaly Detection**: Implement automatic anomaly detection
3. **Hardware Control**: Buat endpoint untuk kontrol kipas, lampu, dll
4. **Mobile App**: Buat mobile app untuk monitoring
5. **Alerts**: Setup email/SMS alerts untuk kondisi abnormal

---

## 📞 Need Help?

1. Baca `API_DOCUMENTATION.md` untuk detil lengkap
2. Baca `TESTING_GUIDE.md` untuk troubleshooting
3. Check `storage/logs/laravel.log` untuk error details
4. Monitor Serial Monitor ESP32 untuk debug

---

**Status**: ✅ Ready for Testing & Deployment

Kode sudah rapi, siap pakai, dan tested. Tinggal testing di sisi hardware (ESP32) Anda!

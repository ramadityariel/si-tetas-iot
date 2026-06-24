# 🧪 Si-Tetas IoT — Guide Testing API Integration

## ✅ Pre-requisite Checklist

Pastikan Anda sudah melakukan ini sebelum testing:

- [ ] Laravel sudah berjalan di port 8001: `php artisan serve --port=8001`
- [ ] Database sudah ter-migrate: `php artisan migrate`
- [ ] Tabel `sensor_logs` sudah ada di database
- [ ] ESP32 sudah terhubung ke WiFi yang sama dengan laptop
- [ ] File Controller sudah terupdate: `app/Http/Controllers/SensorDataController.php`
- [ ] Route sudah benar: `routes/api.php`

---

## 🔗 Testing dengan cURL (Dari Terminal/CMD)

### 1. Test Health Check
Pastikan Laravel API aktif:
```bash
curl -X GET http://localhost:8001/api/ping
```

**Expected Response (HTTP 200)**:
```json
{
  "status": "ok",
  "message": "Si-Tetas API aktif.",
  "time": "2026-06-24 14:30:45"
}
```

---

### 2. Test API dengan cURL (Tanpa API Key - Harus Error)
```bash
curl -X POST http://localhost:8001/api/kirim-data \
  -H "Content-Type: application/json" \
  -d '{"suhu": 36.5, "kelembaban": 60, "timestamp": 0}'
```

**Expected Response (HTTP 401)**:
```json
{
  "status": "error",
  "message": "X-API-KEY header tidak ditemukan.",
  "suhu_max": 0,
  "kelembaban_min": 0
}
```

---

### 3. Test API dengan cURL (Dengan API Key yang Benar - Sukses)
```bash
curl -X POST http://localhost:8001/api/kirim-data \
  -H "Content-Type: application/json" \
  -H "X-API-KEY: 815171f9b522f1cd4cd95cb1d4410311" \
  -d '{"suhu": 36.5, "kelembaban": 60, "timestamp": 0}'
```

**Expected Response (HTTP 200)**:
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

---

### 4. Test API dengan cURL (API Key Salah - Harus Error)
```bash
curl -X POST http://localhost:8001/api/kirim-data \
  -H "Content-Type: application/json" \
  -H "X-API-KEY: API_KEY_SALAH" \
  -d '{"suhu": 36.5, "kelembaban": 60, "timestamp": 0}'
```

**Expected Response (HTTP 403)**:
```json
{
  "status": "error",
  "message": "X-API-KEY tidak valid.",
  "suhu_max": 0,
  "kelembaban_min": 0
}
```

---

### 5. Test API dengan cURL (Data Invalid - Harus Error)
```bash
curl -X POST http://localhost:8001/api/kirim-data \
  -H "Content-Type: application/json" \
  -H "X-API-KEY: 815171f9b522f1cd4cd95cb1d4410311" \
  -d '{"suhu": "bukan_angka", "kelembaban": 60}'
```

**Expected Response (HTTP 422)**:
```json
{
  "status": "error",
  "message": "Validasi data gagal.",
  "errors": {
    "suhu": ["The suhu field must be a number."]
  },
  "suhu_max": 0,
  "kelembaban_min": 0
}
```

---

## 📱 Testing dengan Postman

### 1. Buat Request Baru
- **Method**: `POST`
- **URL**: `http://localhost:8001/api/kirim-data`

### 2. Atur Headers
| Key | Value |
|-----|-------|
| Content-Type | application/json |
| X-API-KEY | 815171f9b522f1cd4cd95cb1d4410311 |

### 3. Atur Body (JSON)
```json
{
  "suhu": 37.2,
  "kelembaban": 62.5,
  "timestamp": 0
}
```

### 4. Click Send
Anda seharusnya melihat response sukses dengan data yang disimpan.

---

## 🧬 Testing dengan Arduino IDE (Serial Monitor)

Saat menjalankan kode ESP32 yang sudah diupdate:

### 1. Buka Serial Monitor
- Klik: **Tools → Serial Monitor**
- Set Baud Rate: **115200**

### 2. Upload Sketch
- Pastikan kode ESP32 sudah ter-upload
- Kode akan menampilkan di Serial Monitor:

```
==============================================
  Si-Tetas ESP32 (Laravel Lokal) — Memulai...
==============================================
  Menghubungkan ke WiFi: Lab Ternak
..........
  [OK] WiFi terhubung!
  IP Address ESP32  : 192.168.1.50
  API Target        : http://10.188.101.217:8001/api/kirim-data
==============================================

[2345 ms] Mengirim → suhu=37.2°C, kelembaban=62.5%
  Payload   : {"suhu":37.2,"kelembaban":62.5,"timestamp":0}
  HTTP 200   : {"status":"success","message":"Data sensor berhasil disimpan.","suhu_max":37.2,"kelembaban_min":62.5,"data":{"id":1,"temperature":37.2,"humidity":62.5,"created_at":"2026-06-24 14:30:45"}}
  [SYNC] Terkoneksi! Suhu Max Web: 37.2 C

[12345 ms] Mengirim → suhu=36.8°C, kelembaban=61.0%
  Payload   : {"suhu":36.8,"kelembaban":61.0,"timestamp":0}
  HTTP 200   : {"status":"success","message":"Data sensor berhasil disimpan.","suhu_max":37.2,"kelembaban_min":61.0,"data":{"id":2,"temperature":36.8,"humidity":61.0,"created_at":"2026-06-24 14:30:50"}}
  [SYNC] Terkoneksi! Suhu Max Web: 37.2 C
```

---

## 🗄️ Verifikasi Data di Database

### Dengan CLI (MySQL/MariaDB)
```bash
mysql -u root -p nama_database
SELECT * FROM sensor_logs ORDER BY id DESC LIMIT 10;
```

### Dengan phpMyAdmin
1. Buka **phpMyAdmin** (biasanya di `http://localhost/phpmyadmin`)
2. Pilih database Anda
3. Buka tabel `sensor_logs`
4. Anda seharusnya melihat data yang dikirim oleh ESP32

---

## 📊 Monitoring Log File

Semua activity dicatat di `storage/logs/laravel.log`

### Lihat log terbaru (Linux/Mac):
```bash
tail -f storage/logs/laravel.log
```

### Lihat log terbaru (Windows PowerShell):
```powershell
Get-Content storage/logs/laravel.log -Tail 20 -Wait
```

---

## 🐛 Troubleshooting

### Problem: "Connection Refused" di ESP32
**Solusi**:
- Pastikan Laravel berjalan: `php artisan serve --port=8001`
- Periksa IP address laptop (gunakan `ipconfig` di Windows atau `ifconfig` di Linux)
- Update URL di kode ESP32 dengan IP yang benar
- Pastikan WiFi ESP32 dan laptop di network yang sama

### Problem: "X-API-KEY tidak valid"
**Solusi**:
- Copy-paste API Key dengan exact: `815171f9b522f1cd4cd95cb1d4410311`
- Pastikan tidak ada space tambahan di header
- Di Arduino IDE, ubah `const char* apiKey = "815171f9b522f1cd4cd95cb1d4410311";`

### Problem: "Validasi data gagal"
**Solusi**:
- Pastikan JSON format benar: `{"suhu": 36.5, "kelembaban": 60, "timestamp": 0}`
- `suhu` dan `kelembaban` harus number, bukan string
- Range `suhu`: -50 hingga 150
- Range `kelembaban`: 0 hingga 100

### Problem: Database error (HTTP 500)
**Solusi**:
- Jalankan `php artisan migrate` untuk memastikan tabel `sensor_logs` ada
- Check `storage/logs/laravel.log` untuk error detail
- Pastikan SensorLog Model fillable fields sudah benar

### Problem: Response "suhu_max" dan "kelembaban_min" selalu 0
**Solusi**:
- Ini normal jika belum ada data di database
- Setelah request pertama sukses, nilai akan terisi

---

## 📝 Checklist Testing Lengkap

Sebelum deploy ke production, pastikan:

- [ ] cURL test HTTP 200 berhasil
- [ ] cURL test tanpa API Key mengembalikan HTTP 401
- [ ] cURL test dengan API Key salah mengembalikan HTTP 403
- [ ] cURL test dengan data invalid mengembalikan HTTP 422
- [ ] Postman test berhasil mengirim dan menerima response
- [ ] Serial Monitor ESP32 menunjukkan HTTP 200
- [ ] Data muncul di database via phpMyAdmin
- [ ] `suhu_max` dan `kelembaban_min` berisi nilai yang benar
- [ ] Log file di `storage/logs/laravel.log` mencatat activity
- [ ] Multiple request dari ESP32 berhasil tersimpan (test lama-lama)

---

## 🎯 Next Steps

Setelah berhasil synchronisasi:

1. **Implementasi Anomaly Detection**: Gunakan `AnomalyLog` model untuk deteksi anomali berdasarkan data sensor
2. **Hardware Control Endpoint**: Buat endpoint baru untuk mengubah status `fan_status`, `lamp_status`, `humidifier_status`
3. **Dashboard**: Buat dashboard Laravel untuk visualisasi data real-time
4. **Scheduled Tasks**: Implement `php artisan schedule:work` untuk monitoring 24/7
5. **Security Enhancement**: Ganti hardcoded API Key dengan environment variable

---

## 📞 Support

Jika ada error yang tidak terdaftar, check:
1. `storage/logs/laravel.log` - Log lengkap
2. Serial Monitor ESP32 - Response dari server
3. Network connectivity - Ping antar devices


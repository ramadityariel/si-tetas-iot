# 📡 Si-Tetas IoT — Dokumentasi API ESP32 ↔ Laravel

## 🔧 Spesifikasi Teknis

### Endpoint
- **URL**: `http://<IP_LAPTOP>:8001/api/kirim-data`
- **Method**: `POST`
- **Content-Type**: `application/json`
- **Response Type**: `application/json`

---

## 📝 Request dari ESP32

### Headers yang Diperlukan
```
X-API-KEY: 815171f9b522f1cd4cd95cb1d4410311
Content-Type: application/json
```

### Request Body (JSON)
```json
{
  "suhu": 36.5,
  "kelembaban": 60,
  "timestamp": 0
}
```

### Deskripsi Parameter
| Parameter | Tipe | Wajib | Range | Deskripsi |
|-----------|------|-------|-------|-----------|
| `suhu` | Float | ✅ | -50 sampai 150 | Suhu dalam °C |
| `kelembaban` | Float | ✅ | 0 sampai 100 | Kelembaban relatif (%) |
| `timestamp` | Integer | ❌ | - | Unix timestamp (opsional) |

---

## ✅ Response Sukses (HTTP 200)

Ketika data berhasil diterima dan disimpan:

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

### Penjelasan Response
- **`status`**: Status permintaan (`success` atau `error`)
- **`message`**: Pesan deskriptif
- **`suhu_max`**: Nilai suhu tertinggi yang pernah tercatat di database
- **`kelembaban_min`**: Nilai kelembaban terendah yang pernah tercatat di database
- **`data.id`**: ID record yang baru disimpan
- **`data.temperature`**: Suhu yang disimpan (dari parameter `suhu`)
- **`data.humidity`**: Kelembaban yang disimpan (dari parameter `kelembaban`)
- **`data.created_at`**: Waktu record dibuat di server

---

## ❌ Error Response

### 1. X-API-KEY Tidak Ada (HTTP 401)
```json
{
  "status": "error",
  "message": "X-API-KEY header tidak ditemukan.",
  "suhu_max": 0,
  "kelembaban_min": 0
}
```

### 2. X-API-KEY Tidak Valid (HTTP 403)
```json
{
  "status": "error",
  "message": "X-API-KEY tidak valid.",
  "suhu_max": 0,
  "kelembaban_min": 0
}
```

### 3. Validasi Data Gagal (HTTP 422)
```json
{
  "status": "error",
  "message": "Validasi data gagal.",
  "errors": {
    "suhu": ["The suhu field is required."],
    "kelembaban": ["The kelembaban must be a number."]
  },
  "suhu_max": 0,
  "kelembaban_min": 0
}
```

### 4. Database Error (HTTP 500)
```json
{
  "status": "error",
  "message": "Gagal menyimpan data ke database.",
  "suhu_max": 0,
  "kelembaban_min": 0
}
```

---

## 📦 Database Schema

### Tabel: `sensor_logs`

| Kolom | Tipe | Nullable | Default | Deskripsi |
|-------|------|----------|---------|-----------|
| `id` | INT | ❌ | auto_increment | Primary Key |
| `temperature` | FLOAT/DOUBLE | ❌ | - | Suhu dari ESP32 |
| `humidity` | FLOAT/DOUBLE | ❌ | - | Kelembaban dari ESP32 |
| `fan_status` | TINYINT/INT | ❌ | 0 | Status kipas angin |
| `lamp_status` | TINYINT/INT | ❌ | 0 | Status lampu |
| `humidifier_status` | TINYINT/INT | ❌ | 0 | Status humidifier |
| `created_at` | TIMESTAMP | ❌ | CURRENT_TIMESTAMP | Waktu record dibuat |
| `updated_at` | TIMESTAMP | ❌ | CURRENT_TIMESTAMP | Waktu record diupdate |

---

## 🧪 Cara Testing dari Arduino IDE (Serial Monitor)

### Contoh Kode Arduino/C++:
```cpp
#include <WiFi.h>
#include <HTTPClient.h>

const char* ssid = "NAMA_WIFI";
const char* password = "PASSWORD_WIFI";
const char* serverUrl = "http://192.168.1.100:8001/api/kirim-data";  // Ganti IP laptop
const char* apiKey = "815171f9b522f1cd4cd95cb1d4410311";

void setup() {
    Serial.begin(115200);
    WiFi.begin(ssid, password);
    
    while (WiFi.status() != WL_CONNECTED) {
        delay(500);
        Serial.print(".");
    }
    Serial.println("\nWiFi connected!");
}

void loop() {
    if (WiFi.status() == WL_CONNECTED) {
        HTTPClient http;
        http.begin(serverUrl);
        
        // Set headers
        http.addHeader("Content-Type", "application/json");
        http.addHeader("X-API-KEY", apiKey);
        
        // Baca suhu & kelembaban dari sensor (contoh nilai hardcoded)
        float suhu = 36.5;
        float kelembaban = 60.0;
        
        // Buat JSON payload
        String jsonPayload = "{\"suhu\":" + String(suhu) + ",\"kelembaban\":" + String(kelembaban) + ",\"timestamp\":0}";
        
        Serial.print("Sending: ");
        Serial.println(jsonPayload);
        
        // Kirim POST request
        int httpResponseCode = http.POST(jsonPayload);
        
        if (httpResponseCode > 0) {
            String response = http.getString();
            Serial.print("Response: ");
            Serial.println(response);
        } else {
            Serial.print("Error sending POST: ");
            Serial.println(httpResponseCode);
        }
        
        http.end();
    }
    
    delay(5000);  // Kirim data setiap 5 detik
}
```

---

## 🔒 Keamanan

1. **API Key Validation**: Setiap request harus menyertakan header `X-API-KEY` yang valid
2. **Data Validation**: Semua input divalidasi sebelum disimpan
3. **CORS**: API berjalan di mode stateless (tidak menggunakan session)
4. **Logging**: Semua request (sukses & gagal) dicatat di `storage/logs/laravel.log`

---

## 📊 Monitoring & Logging

Semua request dicatat di file log:
- **Lokasi**: `storage/logs/laravel.log`
- **Info yang dicatat**:
  - IP address ESP32
  - Timestamp request
  - Data sensor (suhu & kelembaban)
  - Error (jika ada)

### Contoh Log Sukses:
```
[2026-06-24 14:30:45] local.INFO: [ESP32] Data sensor berhasil disimpan {"id":42,"temperature":36.5,"humidity":60,"ip":"192.168.1.50","timestamp":"2026-06-24 14:30:45"}
```

---

## ⚡ Troubleshooting

| Masalah | Solusi |
|--------|--------|
| Connection Refused | Pastikan Laravel berjalan di port 8001: `php artisan serve --port=8001` |
| 401 Unauthorized | Periksa header `X-API-KEY` dalam request |
| 403 Forbidden | Periksa nilai API Key, harus exact: `815171f9b522f1cd4cd95cb1d4410311` |
| 422 Validation Error | Pastikan format JSON benar, `suhu` & `kelembaban` adalah number |
| 500 Server Error | Lihat log di `storage/logs/laravel.log` untuk detail error |

---

## 📝 Catatan Penting

- **API Key**: Jangan share public! Gunakan environment variable untuk production.
- **Validasi Range**: Suhu harus antara -50 sampai 150°C, kelembaban 0-100%
- **Timestamp**: Parameter `timestamp` bersifat opsional dan tidak digunakan untuk penyimpanan
- **Status Fields**: `fan_status`, `lamp_status`, `humidifier_status` default ke 0, bisa diupdate nantinya melalui endpoint terpisah
- **Response Feedback**: `suhu_max` dan `kelembaban_min` diambil dari seluruh data di database, tidak hanya request terakhir

---

## 📞 Referensi Kode

- **Controller**: `app/Http/Controllers/SensorDataController.php`
- **Model**: `app/Models/SensorLog.php`
- **Route**: `routes/api.php`

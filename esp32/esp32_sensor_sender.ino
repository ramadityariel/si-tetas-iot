/**
 * ============================================================
 * Si-Tetas IoT — ESP32 Data Sender (Disesuaikan)
 * ============================================================
 * Mengirim data sensor suhu & kelembaban ke Laravel API Lokal.
 * ============================================================
 */

#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>

// ── Konfigurasi WiFi ──────────────────────────────────────────────────────────
const char* WIFI_SSID     = "Lab Ternak";   
const char* WIFI_PASSWORD = "LabTNK24";  

// ── Konfigurasi API Laravel & Security ────────────────────────────────────────
// Mengarah ke IP Lokal Laravel Anda (Port 8000)
const String BASE_URL     = "http://10.188.101.217:8001/"; 
const char* apiKey        = "815171f9b522f1cd4cd95cb1d4410311"; // API Key disamakan

// ── Interval pengiriman data (10 detik) ──────────────────────────────────────
const unsigned long INTERVAL_MS = 10000;

// ── Variabel waktu & Threshold Dinamis ────────────────────────────────────────
unsigned long lastSendTime = 0;
float thresholdSuhuMax = 37.5; // Menyimpan data sinkronisasi dari web jika ada

// ─────────────────────────────────────────────────────────────────────────────
void setup() {
    Serial.begin(115200);
    delay(500);
    Serial.println();
    Serial.println("==============================================");
    Serial.println("  Si-Tetas ESP32 (Laravel Lokal) — Memulai...");
    Serial.println("==============================================");

    // Koneksi ke WiFi
    Serial.printf("  Menghubungkan ke WiFi: %s\n", WIFI_SSID);
    WiFi.begin(WIFI_SSID, WIFI_PASSWORD);

    int retries = 0;
    while (WiFi.status() != WL_CONNECTED) {
        delay(500);
        Serial.print(".");
        retries++;
        if (retries > 30) {
            Serial.println("\n  [ERROR] Gagal terhubung ke WiFi. Restart...");
            ESP.restart();
        }
    }

    Serial.println("\n  [OK] WiFi terhubung!");
    Serial.print("  IP Address ESP32  : ");
    Serial.println(WiFi.localIP());
    Serial.print("  API Target        : ");
    Serial.println(BASE_URL + "api/kirim-data");
    Serial.println("==============================================\n");
}

// ─────────────────────────────────────────────────────────────────────────────
void loop() {
    unsigned long now = millis();

    // Kirim data setiap INTERVAL_MS milidetik
    if (now - lastSendTime >= INTERVAL_MS) {
        lastSendTime = now;

        // ── Simulasi data sensor ─────────────────────────────────────────────
        float suhu       = 36.0 + random(0, 25) / 10.0;  // Rentang: 36.0 – 38.4°C
        float kelembaban = 55.0 + random(0, 15);          // Rentang: 55.0 – 70.0%

        Serial.printf("[%lu ms] Mengirim → suhu=%.1f°C, kelembaban=%.1f%%\n",
                      now, suhu, kelembaban);

        kirimDataKeServer(suhu, kelembaban);
    }
}

// ─────────────────────────────────────────────────────────────────────────────
/**
 * Kirim data sensor ke endpoint Laravel via HTTP POST (JSON).
 */
void kirimDataKeServer(float suhu, float kelembaban) {
    // Pastikan WiFi masih terhubung
    if (WiFi.status() != WL_CONNECTED) {
        Serial.println("  [WARN] WiFi terputus! Mencoba reconnect...");
        WiFi.reconnect();
        delay(3000);
        return;
    }

    WiFiClient client; // Menggunakan WiFiClient biasa untuk protokol http://
    HTTPClient http;
    
    // Gabungkan BASE_URL dengan endpoint API Laravel
    String endpoint = BASE_URL + "api/kirim-data";
    http.begin(client, endpoint);
    
    // Tambahkan header pengenal identitas dan tipe data
    http.addHeader("Content-Type", "application/json");
    http.addHeader("Accept", "application/json");
    http.addHeader("X-API-KEY", apiKey); // Menyertakan API Key Anda

    // ── Buat payload JSON (Mendukung ArduinoJson v6/v7) ───────────────────────
    JsonDocument docOut;
    docOut["suhu"]       = suhu;
    docOut["kelembaban"] = kelembaban;
    docOut["timestamp"]  = 0; // Set 0 karena tidak pakai NTP di kode ringkas ini

    String requestBody;
    serializeJson(docOut, requestBody);

    Serial.printf("  Payload   : %s\n", requestBody.c_str());

    // ── Kirim HTTP POST ───────────────────────────────────────────────────────
    int httpCode = http.POST(requestBody);

    if (httpCode == HTTP_CODE_OK || httpCode == 201) {
        String responseBody = http.getString();
        Serial.printf("  HTTP %d   : %s\n", httpCode, responseBody.c_str());
        
        // ── Proses Feedback dari Web (Jika ada data balik dari Laravel) ───────
        JsonDocument docIn;
        DeserializationError error = deserializeJson(docIn, responseBody);
        if (!error) {
            if (docIn.containsKey("suhu_max")) {
                thresholdSuhuMax = docIn["suhu_max"];
                Serial.printf("  [SYNC] Terkoneksi! Suhu Max Web: %.1f C\n\n", thresholdSuhuMax);
            } else {
                Serial.println("  [SYNC] Data berhasil tersimpan di web.\n");
            }
        }
    } else {
        if (httpCode > 0) {
            String responseBody = http.getString();
            Serial.printf("  [ERROR] HTTP %d : %s\n\n", httpCode, responseBody.c_str());
        } else {
            Serial.printf("  [ERROR] HTTP gagal: %s\n\n", http.errorToString(httpCode).c_str());
        }
    }

    http.end();
}
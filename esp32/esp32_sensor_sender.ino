/**
 * ============================================================
 *  Si-Tetas IoT — ESP32 Data Sender
 * ============================================================
 *  Mengirim data sensor suhu & kelembaban ke Laravel API.
 *  Library yang diperlukan (install via Arduino Library Manager):
 *    - ArduinoJson  (versi 7.x atau 6.x)
 *
 *  Library bawaan ESP32 (sudah tersedia):
 *    - WiFi.h
 *    - HTTPClient.h
 * ============================================================
 */

#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>

// ── Konfigurasi WiFi ──────────────────────────────────────────────────────────
const char* WIFI_SSID     = "NAMA_WIFI_ANDA";   // ← Ganti dengan SSID WiFi Anda
const char* WIFI_PASSWORD = "KATA_SANDI_WIFI";  // ← Ganti dengan password WiFi Anda

// ── Konfigurasi API Laravel ───────────────────────────────────────────────────
// Ganti IP di bawah dengan IP komputer Anda (cek via 'ipconfig' di CMD)
// Pastikan ESP32 dan laptop berada di jaringan WiFi yang SAMA.
// Format: http://<IP_LAPTOP>:8000/api/kirim-data
const char* API_URL = "http://10.188.101.217:8000/api/kirim-data";

// ── Interval pengiriman data (10 detik) ──────────────────────────────────────
const unsigned long INTERVAL_MS = 10000;

// ── Variabel waktu ────────────────────────────────────────────────────────────
unsigned long lastSendTime = 0;

// ─────────────────────────────────────────────────────────────────────────────
void setup() {
    Serial.begin(115200);
    delay(500);
    Serial.println();
    Serial.println("==============================================");
    Serial.println("  Si-Tetas ESP32 — Memulai...");
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
    Serial.println(API_URL);
    Serial.println("==============================================\n");
}

// ─────────────────────────────────────────────────────────────────────────────
void loop() {
    unsigned long now = millis();

    // Kirim data setiap INTERVAL_MS milidetik
    if (now - lastSendTime >= INTERVAL_MS) {
        lastSendTime = now;

        // ── Simulasi data sensor (ganti dengan pembacaan DHT22 jika ada) ──────
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
 *
 * @param suhu       Nilai suhu dalam °C
 * @param kelembaban Nilai kelembaban dalam %
 */
void kirimDataKeServer(float suhu, float kelembaban) {
    // Pastikan WiFi masih terhubung
    if (WiFi.status() != WL_CONNECTED) {
        Serial.println("  [WARN] WiFi terputus! Mencoba reconnect...");
        WiFi.reconnect();
        delay(3000);
        return;
    }

    HTTPClient http;
    http.begin(API_URL);
    http.addHeader("Content-Type", "application/json");
    http.addHeader("Accept", "application/json");

    // ── Buat payload JSON ─────────────────────────────────────────────────────
    // ArduinoJson v7: JsonDocument
    // ArduinoJson v6: StaticJsonDocument<128> doc;
    JsonDocument doc;
    doc["suhu"]       = suhu;
    doc["kelembaban"] = kelembaban;

    String payload;
    serializeJson(doc, payload);

    Serial.printf("  Payload  : %s\n", payload.c_str());

    // ── Kirim HTTP POST ───────────────────────────────────────────────────────
    int httpCode = http.POST(payload);

    if (httpCode > 0) {
        String responseBody = http.getString();
        Serial.printf("  HTTP %d   : %s\n\n", httpCode, responseBody.c_str());
    } else {
        Serial.printf("  [ERROR] HTTP gagal: %s\n\n", http.errorToString(httpCode).c_str());
    }

    http.end();
}

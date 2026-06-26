# 📋 DOKUMEN REQUIREMENT: Pembaruan Sistem Threshold Dinamis & Integrasi API ESP32

**Tujuan Update:** Menjadikan database Laravel sebagai *Single Source of Truth* (pusat data tunggal) untuk batas suhu dan kelembaban mesin tetas. ESP32 tidak lagi bergantung mutlak pada *hardcode*, melainkan akan mengambil data dari server. Namun, untuk menjaga validitas model Machine Learning, input dari pengguna di Web Admin akan diberikan batasan (limitasi) yang ketat.

---

## 1. Perubahan Database (Migration)

Buat tabel baru untuk menyimpan riwayat pengaturan threshold.

### Nama Tabel: `threshold_settings`

### Kolom yang dibutuhkan:
- `id` (Primary Key)
- `suhu_bawah` (Float, Default: 37.0)
- `suhu_atas` (Float, Default: 38.0)
- `humid_bawah` (Float, Default: 55.0)
- `humid_atas` (Float, Default: 60.0) → *Catatan: Default wajib 60, bukan 65!*
- `updated_by` (String/Enum: 'web', 'keypad') → *Untuk tracking siapa yang mengubah.*
- `created_at`, `updated_at` (Timestamps)

---

## 2. Pembuatan API Endpoints (Untuk ESP32)

ESP32 butuh dua jalur komunikasi API yang diamankan menggunakan Header `X-API-KEY`.

### A. API GET - Ambil Threshold Terbaru
ESP32 akan melakukan *polling* ke endpoint ini setiap 30 detik.

**Endpoint:** `GET /api/get-threshold`

**Header:** 
```
X-API-KEY: [api_key_project]
```

**Response (JSON):** Ambil 1 baris data paling terbaru (*latest*) dari tabel `threshold_settings`.

```json
{
  "suhu_bawah": 37.0,
  "suhu_atas": 38.0,
  "humid_bawah": 55.0,
  "humid_atas": 60.0
}
```

### B. API POST - Update Threshold dari Hardware
Jika operator mengubah suhu fisik lewat Keypad ESP32, ESP32 akan mengirim data ke endpoint ini.

**Endpoint:** `POST /api/update-threshold`

**Header:** 
```
X-API-KEY: [api_key_project]
Content-Type: application/json
```

**Request Body (JSON):**
```json
{
  "suhu_bawah": 37.2,
  "suhu_atas": 38.2,
  "humid_bawah": 55.0,
  "humid_atas": 60.0
}
```

**Action:** Insert data baru ke tabel `threshold_settings` dengan keterangan `updated_by` = 'keypad'.

---

## 3. Pembaruan Halaman Web Admin (UI/UX & Validasi)

Di halaman dashboard admin, sediakan form untuk mengubah nilai batas suhu dan kelembaban, **TETAPI berikan validasi wajib (*strict rules*) di backend/controller:**

### Aturan Validasi Form:

| Field | Validasi | Keterangan |
|-------|----------|-----------|
| `suhu_bawah` | Wajib diisi, Angka, Min: 37.0, Max: 38.0 | Harus < `suhu_atas` |
| `suhu_atas` | Wajib diisi, Angka, Min: 37.5, Max: 38.5 | - |
| `humid_bawah` | Wajib diisi, Angka, Min: 55.0 | - |
| `humid_atas` | Wajib diisi, Angka, Max: 60.0 | - |

### Alasan (*Penting untuk Skripsi):
Pembatasan/kuncian nilai ini wajib ada agar data operasional tidak melenceng jauh dari dataset yang digunakan saat *training* model Machine Learning (RF & IF). Jika dibebaskan, prediksi klasifikasi telur/kondisi mesin akan menjadi *error/invalid*.

---

## 4. Penyesuaian Logika "Status Kondisi" di Dashboard

Tampilan label status (Baik / Perhatian / Critical) di halaman *monitoring* web tidak boleh lagi menggunakan angka fix/mati (seperti *hardcode* 36-39°C). Status harus dihitung relatif terhadap data `threshold_settings` terbaru yang sedang aktif.

### Logika Perhitungan Baru:

1. **Ambil data `$threshold` terbaru dari database.**

2. **Status BAIK:** 
   - Suhu berada di dalam rentang `$threshold->suhu_bawah` hingga `$threshold->suhu_atas` **DAN**
   - Kelembaban berada di dalam rentang `$threshold->humid_bawah` hingga `$threshold->humid_atas`

3. **Status CRITICAL:**
   - Suhu kurang dari `($threshold->suhu_bawah - 1.0)` **ATAU**
   - Suhu lebih dari `($threshold->suhu_atas + 1.0)` **ATAU**
   - Kelembaban menyimpang ±5% dari batasnya

4. **Status PERHATIAN:**
   - Jika kondisi berada di antara Baik dan Critical (misalnya suhu hanya meleset 0.5 derajat dari batas)

---

## Status Implementasi:

- ✅ Database table `threshold_settings` (sudah ada)
- ❌ API GET `/api/get-threshold` (untuk implementasi)
- ❌ API POST `/api/update-threshold` (untuk implementasi)
- ❌ Form Admin untuk manage threshold (untuk implementasi)
- ❌ Validasi ketat backend (untuk implementasi)
- ❌ Update logika status dinamis (untuk implementasi)

---

**Dokumen ini disusun pada:** 2026-06-26
**Versi:** 1.0
**Status:** Siap untuk implementasi

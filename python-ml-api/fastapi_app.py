from __future__ import annotations

# =============================================================================
# Si-Tetas AI Backend  —  FastAPI + Uvicorn (port 8000)
#
# Endpoint yang tersedia:
#   GET  /health                 → Cek status semua model & kamera
#   GET  /video_feed             → MJPEG stream kamera (untuk <img> tag HTML)
#   GET  /camera_status          → Cek apakah kamera tersedia
#   POST /predict/single         → Prediksi 1 butir telur (CNN MobileNetV2)
#   POST /predict/tray           → Prediksi rak telur / 42 butir (CNN)
#   POST /predict/anomaly        → Deteksi anomali sensor IoT (Isolation Forest)
# =============================================================================

import base64
import io
import logging
import threading
import time
from pathlib import Path
from typing import Any, Generator

import cv2
import joblib
import numpy as np
from fastapi import FastAPI, File, HTTPException, UploadFile
from fastapi.middleware.cors import CORSMiddleware
from fastapi.responses import StreamingResponse
from PIL import Image, ImageDraw
from pydantic import BaseModel, Field
from tensorflow.keras.applications.mobilenet_v2 import preprocess_input
from tensorflow.keras.models import load_model

# ---------------------------------------------------------------------------
# Logging
# ---------------------------------------------------------------------------
logging.basicConfig(
    level=logging.INFO,
    format="%(asctime)s [%(levelname)s] %(name)s: %(message)s",
)
logger = logging.getLogger("si-tetas-api")

# ---------------------------------------------------------------------------
# Path & Konstanta
# ---------------------------------------------------------------------------
APP_DIR           = Path(__file__).resolve().parent
MODEL_CNN_PATH    = APP_DIR / "models" / "mobilenet_best.h5"
MODEL_IF_PATH     = APP_DIR / "isolation_forest_model.joblib"
MODEL_RF_STATUS_PATH = APP_DIR / "sensor_rf_model.joblib"

CLASS_NAMES           = ["fertil_hidup", "fertil_mati", "infertil"]
CONFIDENCE_THRESHOLD  = 0.70

# Indeks kamera yang dicoba secara berurutan (0 = kamera utama, 1 = sekunder)
CAMERA_INDICES = [0, 1, 2]

# ---------------------------------------------------------------------------
# FastAPI App & CORS
# ---------------------------------------------------------------------------
app = FastAPI(
    title="Si-Tetas AI Inference API",
    version="2.0.0",
    description="Backend AI untuk candling telur (CNN) dan deteksi anomali IoT (Isolation Forest).",
)

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=False,
    allow_methods=["*"],
    allow_headers=["*"],
)

# ---------------------------------------------------------------------------
# Load Model CNN (MobileNetV2)
# ---------------------------------------------------------------------------
# Load Model CNN (MobileNetV2) dengan custom objects fallback untuk Keras 3
# ---------------------------------------------------------------------------
from tensorflow.keras.layers import DepthwiseConv2D

class PatchedDepthwiseConv2D(DepthwiseConv2D):
    def __init__(self, *args, **kwargs):
        # Hapus argumen 'groups' karena Keras 3 tidak menerimanya pada layer DepthwiseConv2D
        kwargs.pop('groups', None)
        super().__init__(*args, **kwargs)

cnn_model = None
if MODEL_CNN_PATH.exists():
    try:
        logger.info(f"Memuat model CNN dari: {MODEL_CNN_PATH}")
        cnn_model = load_model(
            str(MODEL_CNN_PATH),
            custom_objects={"DepthwiseConv2D": PatchedDepthwiseConv2D},
            compile=False
        )
        logger.info("✅ Model CNN berhasil dimuat.")
    except Exception as e:
        logger.error(f"❌ Gagal memuat model CNN: {e}")
else:
    logger.warning(f"⚠️  File model CNN tidak ditemukan: {MODEL_CNN_PATH}")

# ---------------------------------------------------------------------------
# Load Model Isolation Forest
# ---------------------------------------------------------------------------
isolation_forest_model = None
if MODEL_IF_PATH.exists():
    try:
        logger.info(f"Memuat model Isolation Forest dari: {MODEL_IF_PATH}")
        isolation_forest_model = joblib.load(str(MODEL_IF_PATH))
        logger.info("✅ Model Isolation Forest berhasil dimuat.")
    except Exception as e:
        logger.error(f"❌ Gagal memuat model Isolation Forest: {e}")
else:
    logger.warning(f"⚠️  File model Isolation Forest tidak ditemukan: {MODEL_IF_PATH}")

# ---------------------------------------------------------------------------
# Load Model Random Forest Status
# ---------------------------------------------------------------------------
sensor_rf_model = None
if MODEL_RF_STATUS_PATH.exists():
    try:
        logger.info(f"Memuat model Random Forest dari: {MODEL_RF_STATUS_PATH}")
        sensor_rf_model = joblib.load(str(MODEL_RF_STATUS_PATH))
        logger.info("✅ Model Random Forest berhasil dimuat.")
    except Exception as e:
        logger.error(f"❌ Gagal memuat model Random Forest: {e}")
else:
    logger.warning(f"⚠️  File model Random Forest tidak ditemukan: {MODEL_RF_STATUS_PATH}")

# ---------------------------------------------------------------------------
# Camera Manager  —  thread-safe singleton
# ---------------------------------------------------------------------------
class CameraManager:
    """
    Mengelola akses ke webcam secara thread-safe.
    Mencoba beberapa indeks kamera sampai ada yang berhasil dibuka.
    Kamera hanya dibuka sekali dan di-reuse oleh semua request stream.
    """

    def __init__(self) -> None:
        self._cap: cv2.VideoCapture | None = None
        self._lock = threading.Lock()
        self._camera_index: int | None = None
        self._initialized = False

    def _try_open(self) -> bool:
        """Coba buka kamera dari daftar indeks. Return True jika berhasil."""
        for idx in CAMERA_INDICES:
            try:
                logger.info(f"Mencoba membuka kamera dengan indeks {idx}...")
                cap = cv2.VideoCapture(idx, cv2.CAP_DSHOW)  # CAP_DSHOW lebih stabil di Windows
                if not cap.isOpened():
                    cap = cv2.VideoCapture(idx)  # Fallback ke default
                
                if cap.isOpened():
                    ret, frame = cap.read()
                    if ret and frame is not None:
                        self._cap = cap
                        self._camera_index = idx
                        # Atur resolusi agar stream lebih ringan
                        self._cap.set(cv2.CAP_PROP_FRAME_WIDTH, 640)
                        self._cap.set(cv2.CAP_PROP_FRAME_HEIGHT, 480)
                        logger.info(f"✅ Kamera indeks {idx} berhasil dibuka.")
                        return True
                    else:
                        cap.release()
                        logger.warning(f"Kamera indeks {idx} terbuka tapi gagal membaca frame.")
                else:
                    logger.warning(f"Kamera indeks {idx} tidak dapat dibuka.")
            except Exception as e:
                logger.error(f"Error saat mencoba membuka kamera indeks {idx}: {e}")
        return False

    def initialize(self) -> bool:
        """Inisialisasi kamera. Aman dipanggil berulang kali."""
        with self._lock:
            if self._initialized and self._cap and self._cap.isOpened():
                return True
            logger.info("Menginisialisasi kamera...")
            success = self._try_open()
            self._initialized = success
            if not success:
                logger.error(
                    "❌ Tidak ada kamera yang berhasil dibuka. "
                    "Pastikan kamera tidak dipakai aplikasi lain (Zoom, OBS, dll.) "
                    "dan driver kamera sudah terinstal dengan benar."
                )
            return success

    def read_frame(self) -> tuple[bool, np.ndarray | None]:
        """Baca satu frame dari kamera."""
        with self._lock:
            if self._cap is None or not self._cap.isOpened():
                # Coba re-inisialisasi jika koneksi terputus
                if not self._try_open():
                    return False, None
            ret, frame = self._cap.read()
            if not ret:
                logger.warning("Gagal membaca frame, mencoba re-open kamera...")
                self._cap.release()
                self._cap = None
                if self._try_open():
                    ret, frame = self._cap.read()
                    return ret, frame
                return False, None
            return ret, frame

    @property
    def is_available(self) -> bool:
        if self._cap is None:
            return False
        return self._cap.isOpened()

    @property
    def camera_index(self) -> int | None:
        return self._camera_index

    def release(self) -> None:
        with self._lock:
            if self._cap:
                self._cap.release()
                self._cap = None
            self._initialized = False
            logger.info("Kamera dilepaskan.")


# Singleton instance
camera_manager = CameraManager()


# ---------------------------------------------------------------------------
# Helper: Encode frame ke JPEG bytes
# ---------------------------------------------------------------------------
def encode_frame_as_jpeg(frame: np.ndarray, quality: int = 80) -> bytes | None:
    """Encode frame OpenCV (BGR numpy array) ke bytes JPEG."""
    encode_params = [int(cv2.IMWRITE_JPEG_QUALITY), quality]
    success, buffer = cv2.imencode(".jpg", frame, encode_params)
    if not success:
        return None
    return buffer.tobytes()


# ---------------------------------------------------------------------------
# Helper: Generator MJPEG Stream
# ---------------------------------------------------------------------------
def mjpeg_stream_generator() -> Generator[bytes, None, None]:
    """
    Generator yang menghasilkan frame MJPEG secara terus-menerus.
    Format MJPEG: multipart/x-mixed-replace, kompatibel dengan tag <img> HTML.
    """
    error_frame_sent = False

    while True:
        ret, frame = camera_manager.read_frame()

        if not ret or frame is None:
            if not error_frame_sent:
                # Kirim frame error placeholder agar <img> tidak kosong
                error_img = np.zeros((480, 640, 3), dtype=np.uint8)
                cv2.putText(
                    error_img,
                    "KAMERA TIDAK TERSEDIA",
                    (80, 220),
                    cv2.FONT_HERSHEY_SIMPLEX,
                    1.0,
                    (0, 80, 200),
                    2,
                    cv2.LINE_AA,
                )
                cv2.putText(
                    error_img,
                    "Periksa koneksi kamera Anda",
                    (100, 270),
                    cv2.FONT_HERSHEY_SIMPLEX,
                    0.7,
                    (150, 150, 150),
                    1,
                    cv2.LINE_AA,
                )
                jpeg_bytes = encode_frame_as_jpeg(error_img)
                if jpeg_bytes:
                    yield (
                        b"--frame\r\n"
                        b"Content-Type: image/jpeg\r\n\r\n" + jpeg_bytes + b"\r\n"
                    )
                error_frame_sent = True
            time.sleep(1.0)  # Tunggu sebelum retry
            continue

        error_frame_sent = False
        jpeg_bytes = encode_frame_as_jpeg(frame)
        if jpeg_bytes is None:
            continue

        # Format MJPEG multipart boundary
        yield (
            b"--frame\r\n"
            b"Content-Type: image/jpeg\r\n\r\n" + jpeg_bytes + b"\r\n"
        )

        # ~30 FPS cap — cukup untuk live preview
        time.sleep(1 / 30)


# ---------------------------------------------------------------------------
# Helper: CNN Image Processing
# ---------------------------------------------------------------------------
def image_to_base64(image: Image.Image) -> str:
    buffer = io.BytesIO()
    image.save(buffer, format="PNG")
    return base64.b64encode(buffer.getvalue()).decode("utf-8")


def prepare_single_image(image: Image.Image) -> tuple[Image.Image, np.ndarray]:
    rgb_image = image.convert("RGB")
    resized   = rgb_image.resize((224, 224))
    array     = np.array(resized).astype(np.float32)
    array     = preprocess_input(array)
    batch     = np.expand_dims(array, axis=0)
    return rgb_image, batch


def predict_single_image(image: Image.Image) -> dict[str, Any]:
    if cnn_model is None:
        raise HTTPException(status_code=503, detail="Model CNN belum dimuat atau tidak ditemukan.")

    rgb_image, batch  = prepare_single_image(image)
    prediction        = cnn_model.predict(batch, verbose=0)[0]
    class_idx         = int(np.argmax(prediction))
    predicted_class   = CLASS_NAMES[class_idx]
    confidence        = float(prediction[class_idx])

    annotated = rgb_image.copy()
    draw      = ImageDraw.Draw(annotated)
    draw.rectangle([0, 0, annotated.size[0], 42], fill=(0, 0, 0))
    draw.text(
        (10, 10),
        f"Prediksi: {predicted_class} ({confidence * 100:.2f}%)",
        fill=(255, 255, 255),
    )

    return {
        "predicted_class": predicted_class,
        "confidence": confidence,
        "scores": {name: float(score) for name, score in zip(CLASS_NAMES, prediction)},
        "annotated_image_base64": image_to_base64(annotated),
    }


def predict_tray_image(image: Image.Image) -> dict[str, Any]:
    if cnn_model is None:
        raise HTTPException(status_code=503, detail="Model CNN belum dimuat atau tidak ditemukan.")

    original  = image.convert("RGB")
    annotated = original.copy()
    draw      = ImageDraw.Draw(annotated)

    width, height = original.size
    row_specs     = [6, 7, 6, 7, 6, 7]
    total_rows    = len(row_specs)
    cell_h        = height // total_rows
    base_cell_w   = width // 7

    results: list[dict[str, Any]] = []

    for row_idx, cols in enumerate(row_specs):
        use_offset  = row_idx % 2 == 1
        x_offset    = base_cell_w // 2 if use_offset else 0
        row_cell_w  = width // cols if not use_offset else base_cell_w
        y1 = row_idx * cell_h
        y2 = height if row_idx == total_rows - 1 else min((row_idx + 1) * cell_h, height)

        for col in range(cols):
            x1 = col * row_cell_w + x_offset
            x2 = min(x1 + row_cell_w, width)

            if x1 >= width or y1 >= height or x2 <= x1 or y2 <= y1:
                continue

            crop       = original.crop((x1, y1, x2, y2)).resize((224, 224))
            arr        = np.array(crop).astype(np.float32)
            arr        = preprocess_input(arr)
            batch      = np.expand_dims(arr, axis=0)
            prediction = cnn_model.predict(batch, verbose=0)[0]
            class_idx  = int(np.argmax(prediction))
            pred_class = CLASS_NAMES[class_idx]
            confidence = float(prediction[class_idx])

            display_class = pred_class if confidence >= CONFIDENCE_THRESHOLD else "uncertain"

            results.append({
                "position":   f"R{row_idx + 1}C{col + 1}",
                "class":      display_class,
                "raw_class":  pred_class,
                "confidence": confidence,
            })

            color_map = {
                "fertil_hidup": (78, 205, 196),
                "fertil_mati":  (255, 217, 61),
                "infertil":     (255, 107, 107),
                "uncertain":    (180, 180, 180),
            }
            color = color_map.get(display_class, (180, 180, 180))
            draw.rectangle([x1, y1, x2, y2], outline=color, width=3)
            draw.rectangle([x1, y1, min(x1 + 100, x2), min(y1 + 35, y2)], fill=(0, 0, 0))
            draw.text((x1 + 4, y1 + 2), f"{row_idx + 1}-{col + 1}\n{confidence:.2f}", fill=(255, 255, 255))

    summary = {
        cls: sum(1 for item in results if item["class"] == cls)
        for cls in ["fertil_hidup", "fertil_mati", "infertil", "uncertain"]
    }

    return {
        "summary":              summary,
        "total_detected":       len(results),
        "results":              results,
        "annotated_image_base64": image_to_base64(annotated),
    }


# ---------------------------------------------------------------------------
# Pydantic Schema — Isolation Forest Input
# ---------------------------------------------------------------------------
class SensorReading(BaseModel):
    """
    Data sensor dari perangkat IoT incubator telur.
    Semua field bersifat opsional agar fleksibel terhadap sensor yang tidak aktif.
    """
    temperature: float | None = Field(None, description="Suhu dalam derajat Celsius", example=37.5)
    humidity:    float | None = Field(None, description="Kelembapan dalam persen (%RH)", example=60.0)
    fan_on:      int | None   = Field(None, description="Status kipas: 1=nyala, 0=mati", example=1)
    heater_on:   int | None   = Field(None, description="Status pemanas: 1=nyala, 0=mati", example=1)
    turner_on:   int | None   = Field(None, description="Status egg-turner: 1=nyala, 0=mati", example=0)


# ---------------------------------------------------------------------------
# Pydantic Schema — Random Forest Status Input
# ---------------------------------------------------------------------------
class StatusPredictionInput(BaseModel):
    temperature: float = Field(..., description="Suhu dalam derajat Celsius", example=36.6)
    humidity:    float = Field(..., description="Kelembapan dalam persen (%RH)", example=61.0)

class BatchStatusPredictionInput(BaseModel):
    data: list[StatusPredictionInput]


# ---------------------------------------------------------------------------
# ========================  ROUTES  =========================================
# ---------------------------------------------------------------------------

@app.get("/health", tags=["System"])
def health_check() -> dict:
    """Cek status semua komponen: model CNN, Isolation Forest, dan kamera."""
    return {
        "status":             "ok",
        "cnn_model":          "loaded" if cnn_model is not None else "not_loaded",
        "cnn_model_path":     str(MODEL_CNN_PATH),
        "isolation_forest":   "loaded" if isolation_forest_model is not None else "not_loaded",
        "if_model_path":      str(MODEL_IF_PATH),
        "camera_available":   camera_manager.is_available,
        "camera_index":       camera_manager.camera_index,
        "classes":            CLASS_NAMES,
    }


@app.get("/camera_status", tags=["Camera"])
def camera_status() -> dict:
    """
    Cek apakah kamera tersedia dan dapat dibuka.
    Endpoint ini juga memicu inisialisasi kamera jika belum dilakukan.
    """
    is_ready = camera_manager.initialize()
    return {
        "available": is_ready,
        "camera_index": camera_manager.camera_index,
        "message": (
            "Kamera siap digunakan."
            if is_ready
            else (
                "Kamera tidak tersedia. "
                "Pastikan kamera tidak sedang dipakai aplikasi lain "
                "(Zoom, Teams, OBS, dll.) dan coba lagi."
            )
        ),
    }


@app.get(
    "/video_feed",
    tags=["Camera"],
    summary="Stream video kamera real-time (MJPEG)",
    description=(
        "Endpoint MJPEG stream. Gunakan di HTML dengan tag:\n"
        "`<img src='http://127.0.0.1:8000/video_feed' />`\n\n"
        "Stream akan otomatis memulai kamera. "
        "Jika kamera tidak tersedia, akan menampilkan frame error informatif."
    ),
)
def video_feed() -> StreamingResponse:
    """
    Streaming video MJPEG dari webcam menggunakan OpenCV.
    Kompatibel langsung dengan tag <img> di HTML tanpa JavaScript tambahan.
    """
    # Coba inisialisasi kamera saat pertama kali diakses
    camera_manager.initialize()

    return StreamingResponse(
        mjpeg_stream_generator(),
        media_type="multipart/x-mixed-replace; boundary=frame",
        headers={
            # Pastikan browser tidak meng-cache stream
            "Cache-Control": "no-cache, no-store, must-revalidate",
            "Pragma":        "no-cache",
            "Expires":       "0",
        },
    )


@app.post("/predict/single", tags=["CNN Prediction"])
async def predict_single(file: UploadFile = File(...)) -> dict:
    """Prediksi satu butir telur dari gambar yang diupload."""
    if not file.content_type or not file.content_type.startswith("image/"):
        raise HTTPException(status_code=400, detail="File harus berupa gambar (image/*).")

    content = await file.read()
    try:
        image = Image.open(io.BytesIO(content))
    except Exception as exc:
        raise HTTPException(status_code=400, detail=f"Gambar tidak valid: {exc}") from exc

    return predict_single_image(image)


@app.post("/predict/tray", tags=["CNN Prediction"])
async def predict_tray(file: UploadFile = File(...)) -> dict:
    """Prediksi seluruh rak telur (42 butir) dari gambar yang diupload."""
    if not file.content_type or not file.content_type.startswith("image/"):
        raise HTTPException(status_code=400, detail="File harus berupa gambar (image/*).")

    content = await file.read()
    try:
        image = Image.open(io.BytesIO(content))
    except Exception as exc:
        raise HTTPException(status_code=400, detail=f"Gambar tidak valid: {exc}") from exc

    return predict_tray_image(image)


@app.post("/predict/anomaly", tags=["Isolation Forest"])
def predict_anomaly(sensor: SensorReading) -> dict:
    """
    Deteksi anomali dari data sensor IoT incubator menggunakan Isolation Forest.

    - **-1** → Anomali terdeteksi (kondisi tidak normal)
    -  **1** → Normal

    Fitur yang digunakan (sesuai urutan saat training):
    `[temperature, humidity, fan_on, heater_on, turner_on]`
    """
    if isolation_forest_model is None:
        raise HTTPException(
            status_code=503,
            detail="Model Isolation Forest belum dimuat. Periksa file 'isolation_forest_model.joblib'.",
        )

    # Bangun feature vector — gunakan 0 sebagai default untuk field yang kosong
    features = np.array([[
        sensor.temperature if sensor.temperature is not None else 0.0,
        sensor.humidity    if sensor.humidity    is not None else 0.0,
        sensor.fan_on      if sensor.fan_on      is not None else 0,
        sensor.heater_on   if sensor.heater_on   is not None else 0,
        sensor.turner_on   if sensor.turner_on   is not None else 0,
    ]], dtype=np.float64)

    try:
        prediction  = isolation_forest_model.predict(features)
        score       = isolation_forest_model.decision_function(features)

        raw_result  = int(prediction[0])
        is_anomaly  = raw_result == -1
        anomaly_score = float(score[0])

        return {
            "is_anomaly":     is_anomaly,
            "raw_prediction": raw_result,      # -1 = anomali, 1 = normal
            "anomaly_score":  anomaly_score,   # makin negatif = makin anomali
            "label":          "ANOMALI" if is_anomaly else "NORMAL",
            "message": (
                "⚠️ Kondisi sensor tidak normal! Periksa incubator segera."
                if is_anomaly
                else "✅ Semua sensor dalam kondisi normal."
            ),
            "input_received": {
                "temperature": sensor.temperature,
                "humidity":    sensor.humidity,
                "fan_on":      sensor.fan_on,
                "heater_on":   sensor.heater_on,
                "turner_on":   sensor.turner_on,
            },
        }

    except Exception as exc:
        logger.error(f"Error saat prediksi anomali: {exc}")
        raise HTTPException(
            status_code=500,
            detail=f"Terjadi kesalahan saat prediksi anomali: {exc}",
        ) from exc


@app.post("/predict/status", tags=["Random Forest Status"])
def predict_status(sensor: StatusPredictionInput) -> dict:
    """
    Prediksi status inkubator (Optimal, Warning, Critical) dari suhu dan kelembapan
    menggunakan Random Forest Classifier.
    """
    if sensor_rf_model is None:
        raise HTTPException(
            status_code=503,
            detail="Model Random Forest Status belum dimuat. Jalankan script training terlebih dahulu.",
        )

    features = np.array([[sensor.temperature, sensor.humidity]], dtype=np.float64)
    try:
        prediction = sensor_rf_model.predict(features)
        status_label = str(prediction[0])
        return {
            "temperature": sensor.temperature,
            "humidity": sensor.humidity,
            "status": status_label,
        }
    except Exception as exc:
        logger.error(f"Error saat prediksi status: {exc}")
        raise HTTPException(
            status_code=500,
            detail=f"Terjadi kesalahan saat prediksi status: {exc}",
        ) from exc


@app.post("/predict/status/batch", tags=["Random Forest Status"])
def predict_status_batch(payload: BatchStatusPredictionInput) -> list[dict]:
    """
    Prediksi batch status inkubator dari daftar pembacaan sensor.
    Sangat cocok digunakan oleh Laravel untuk memperbarui log tabel secara cepat.
    """
    if sensor_rf_model is None:
        raise HTTPException(
            status_code=503,
            detail="Model Random Forest Status belum dimuat. Jalankan script training terlebih dahulu.",
        )

    if not payload.data:
        return []

    features = np.array([[item.temperature, item.humidity] for item in payload.data], dtype=np.float64)
    try:
        predictions = sensor_rf_model.predict(features)
        results = []
        for idx, item in enumerate(payload.data):
            results.append({
                "temperature": item.temperature,
                "humidity": item.humidity,
                "status": str(predictions[idx]),
            })
        return results
    except Exception as exc:
        logger.error(f"Error saat prediksi status batch: {exc}")
        raise HTTPException(
            status_code=500,
            detail=f"Terjadi kesalahan saat prediksi status batch: {exc}",
        ) from exc


# ---------------------------------------------------------------------------
# Startup / Shutdown Events
# ---------------------------------------------------------------------------
@app.on_event("startup")
async def on_startup() -> None:
    """Inisialisasi kamera saat server pertama kali start."""
    logger.info("Server Si-Tetas API starting up...")
    logger.info(f"CNN Model   : {'✅ Loaded' if cnn_model else '❌ Not loaded'}")
    logger.info(f"Isol. Forest: {'✅ Loaded' if isolation_forest_model else '❌ Not loaded'}")
    logger.info(f"Rand. Forest: {'✅ Loaded' if sensor_rf_model else '❌ Not loaded'}")
    # Inisialisasi kamera di background (non-blocking)
    init_thread = threading.Thread(target=camera_manager.initialize, daemon=True)
    init_thread.start()


@app.on_event("shutdown")
async def on_shutdown() -> None:
    """Lepaskan resource kamera saat server shutdown."""
    logger.info("Server shutting down — melepaskan kamera...")
    camera_manager.release()
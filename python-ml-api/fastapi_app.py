from __future__ import annotations

import base64
import io
from pathlib import Path
from typing import Any

import numpy as np
from fastapi import FastAPI, File, HTTPException, UploadFile
from fastapi.middleware.cors import CORSMiddleware
from PIL import Image, ImageDraw
from tensorflow.keras.applications.mobilenet_v2 import preprocess_input
from tensorflow.keras.models import load_model


APP_DIR = Path(__file__).resolve().parent
MODEL_PATH = APP_DIR / "models" / "mobilenet_best.h5"
CLASS_NAMES = ["fertil_hidup", "fertil_mati", "infertil"]
CONFIDENCE_THRESHOLD = 0.70


app = FastAPI(title="Egg Candling Inference API", version="1.0.0")
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)


if not MODEL_PATH.exists():
    raise FileNotFoundError(f"Model tidak ditemukan: {MODEL_PATH}")

model = load_model(MODEL_PATH, compile=False)


def image_to_base64(image: Image.Image) -> str:
    buffer = io.BytesIO()
    image.save(buffer, format="PNG")
    return base64.b64encode(buffer.getvalue()).decode("utf-8")


def prepare_single_image(image: Image.Image) -> tuple[Image.Image, np.ndarray]:
    rgb_image = image.convert("RGB")
    resized = rgb_image.resize((224, 224))
    array = np.array(resized).astype(np.float32)
    array = preprocess_input(array)
    batch = np.expand_dims(array, axis=0)
    return rgb_image, batch


def predict_single_image(image: Image.Image) -> dict[str, Any]:
    rgb_image, batch = prepare_single_image(image)
    prediction = model.predict(batch, verbose=0)[0]
    class_idx = int(np.argmax(prediction))
    predicted_class = CLASS_NAMES[class_idx]
    confidence = float(prediction[class_idx])

    annotated = rgb_image.copy()
    draw = ImageDraw.Draw(annotated)
    draw.rectangle([0, 0, annotated.size[0], 42], fill=(0, 0, 0))
    draw.text((10, 10), f"Prediksi: {predicted_class} ({confidence * 100:.2f}%)", fill=(255, 255, 255))

    return {
        "predicted_class": predicted_class,
        "confidence": confidence,
        "scores": {name: float(score) for name, score in zip(CLASS_NAMES, prediction)},
        "annotated_image_base64": image_to_base64(annotated),
    }


def predict_tray_image(image: Image.Image) -> dict[str, Any]:
    original = image.convert("RGB")
    annotated = original.copy()
    draw = ImageDraw.Draw(annotated)

    width, height = original.size
    row_specs = [6, 7, 6, 7, 6, 7]
    total_rows = len(row_specs)
    cell_h = height // total_rows
    base_cell_w = width // 7

    results = []

    for row_idx, cols in enumerate(row_specs):
        use_offset = row_idx % 2 == 1
        x_offset = base_cell_w // 2 if use_offset else 0
        row_cell_w = width // cols if not use_offset else base_cell_w
        y1 = row_idx * cell_h
        y2 = height if row_idx == total_rows - 1 else min((row_idx + 1) * cell_h, height)

        for col in range(cols):
            x1 = col * row_cell_w + x_offset
            x2 = min(x1 + row_cell_w, width)

            if x1 >= width or y1 >= height or x2 <= x1 or y2 <= y1:
                continue

            crop = original.crop((x1, y1, x2, y2)).resize((224, 224))
            arr = np.array(crop).astype(np.float32)
            arr = preprocess_input(arr)
            batch = np.expand_dims(arr, axis=0)
            prediction = model.predict(batch, verbose=0)[0]
            class_idx = int(np.argmax(prediction))
            predicted_class = CLASS_NAMES[class_idx]
            confidence = float(prediction[class_idx])
            display_class = predicted_class if confidence >= CONFIDENCE_THRESHOLD else "uncertain"

            results.append(
                {
                    "position": f"R{row_idx + 1}C{col + 1}",
                    "class": display_class,
                    "raw_class": predicted_class,
                    "confidence": confidence,
                }
            )

            color_map = {
                "fertil_hidup": (78, 205, 196),
                "fertil_mati": (255, 217, 61),
                "infertil": (255, 107, 107),
                "uncertain": (180, 180, 180),
            }
            color = color_map[display_class]
            draw.rectangle([x1, y1, x2, y2], outline=color, width=3)
            draw.rectangle([x1, y1, min(x1 + 120, x2), min(y1 + 44, y2)], fill=(0, 0, 0))
            draw.text((x1 + 4, y1 + 4), f"{row_idx + 1}-{col + 1}\n{display_class}\n{confidence:.2f}", fill=(255, 255, 255))

    summary = {
        cls: sum(1 for item in results if item["class"] == cls)
        for cls in ["fertil_hidup", "fertil_mati", "infertil", "uncertain"]
    }

    return {
        "summary": summary,
        "total_detected": len(results),
        "results": results,
        "annotated_image_base64": image_to_base64(annotated),
    }


@app.get("/health")
def health_check():
    return {
        "status": "ok",
        "model_path": str(MODEL_PATH),
        "classes": CLASS_NAMES,
    }


@app.post("/predict/single")
async def predict_single(file: UploadFile = File(...)):
    if not file.content_type or not file.content_type.startswith("image/"):
        raise HTTPException(status_code=400, detail="File harus berupa gambar.")

    content = await file.read()
    try:
        image = Image.open(io.BytesIO(content))
    except Exception as exc:
        raise HTTPException(status_code=400, detail=f"Gambar tidak valid: {exc}") from exc

    return predict_single_image(image)


@app.post("/predict/tray")
async def predict_tray(file: UploadFile = File(...)):
    if not file.content_type or not file.content_type.startswith("image/"):
        raise HTTPException(status_code=400, detail="File harus berupa gambar.")

    content = await file.read()
    try:
        image = Image.open(io.BytesIO(content))
    except Exception as exc:
        raise HTTPException(status_code=400, detail=f"Gambar tidak valid: {exc}") from exc

    return predict_tray_image(image)

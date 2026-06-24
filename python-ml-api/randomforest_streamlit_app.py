from __future__ import annotations

from pathlib import Path

import joblib
import numpy as np
import pandas as pd
import streamlit as st


APP_DIR = Path(__file__).resolve().parent
MODEL_PATH = APP_DIR / "random_forest_model.joblib"
SCALER_PATH = APP_DIR / "scaler.joblib"
DEFAULT_DATA_PATH = APP_DIR / "datasensor_inkubator.csv"

FEATURE_COLS = [
    "temperature_diff",
    "humidity_diff",
    "temperature_rolling_mean_5",
    "humidity_rolling_mean_5",
    "temperature_rolling_std_5",
    "humidity_rolling_std_5",
    "temperature_change_rate",
    "humidity_change_rate",
    "temperature_rolling_mean_10",
    "humidity_rolling_std_10",
]

COLUMN_MAPPING = {
    "Timestamp": "timestamp",
    "Suhu (°C)": "temperature",
    "Kelembapan (%)": "humidity",
}

REQUIRED_COLUMNS = ["timestamp", "temperature", "humidity"]


st.set_page_config(
    page_title="Random Forest Inkubator Demo",
    page_icon="🌡️",
    layout="wide",
)


@st.cache_resource
def load_artifacts():
    if not MODEL_PATH.exists():
        raise FileNotFoundError(f"Model tidak ditemukan: {MODEL_PATH}")
    if not SCALER_PATH.exists():
        raise FileNotFoundError(f"Scaler tidak ditemukan: {SCALER_PATH}")

    model = joblib.load(MODEL_PATH)
    scaler = joblib.load(SCALER_PATH)
    return model, scaler


def normalize_sensor_frame(dataframe: pd.DataFrame) -> pd.DataFrame:
    frame = dataframe.copy()
    frame.columns = frame.columns.str.strip()

    rename_map = {source: target for source, target in COLUMN_MAPPING.items() if source in frame.columns}
    if rename_map:
        frame = frame.rename(columns=rename_map)

    missing = [column for column in REQUIRED_COLUMNS if column not in frame.columns]
    if missing:
        raise ValueError(
            "Kolom wajib tidak lengkap. Dibutuhkan: timestamp/temperature/humidity atau "
            f"Timestamp/Suhu (°C)/Kelembapan (%). Missing: {missing}"
        )

    frame["timestamp"] = pd.to_datetime(frame["timestamp"], dayfirst=True, errors="coerce")
    frame["temperature"] = pd.to_numeric(frame["temperature"], errors="coerce")
    frame["humidity"] = pd.to_numeric(frame["humidity"], errors="coerce")

    frame = frame.dropna(subset=REQUIRED_COLUMNS)
    frame = frame.sort_values("timestamp").reset_index(drop=True)
    return frame


def add_time_features(dataframe: pd.DataFrame) -> pd.DataFrame:
    frame = dataframe.copy()

    frame["temperature_diff"] = frame["temperature"].diff(1)
    frame["humidity_diff"] = frame["humidity"].diff(1)

    frame["temperature_rolling_mean_5"] = frame["temperature"].shift(1).rolling(window=5).mean()
    frame["humidity_rolling_mean_5"] = frame["humidity"].shift(1).rolling(window=5).mean()

    frame["temperature_rolling_std_5"] = frame["temperature"].shift(1).rolling(window=5).std()
    frame["humidity_rolling_std_5"] = frame["humidity"].shift(1).rolling(window=5).std()

    prev_temp = frame["temperature"].shift(1)
    prev_hum = frame["humidity"].shift(1)

    frame["temperature_change_rate"] = frame["temperature_diff"] / prev_temp
    frame["humidity_change_rate"] = frame["humidity_diff"] / prev_hum

    frame["temperature_rolling_mean_10"] = frame["temperature"].shift(1).rolling(window=10).mean()
    frame["humidity_rolling_std_10"] = frame["humidity"].shift(1).rolling(window=10).std()

    frame.replace([np.inf, -np.inf], np.nan, inplace=True)
    frame = frame.dropna().reset_index(drop=True)
    return frame


def predict_frame(dataframe: pd.DataFrame, model, scaler):
    cleaned = normalize_sensor_frame(dataframe)
    engineered = add_time_features(cleaned)

    if engineered.empty:
        raise ValueError(
            "Data tidak cukup untuk feature engineering. Dibutuhkan minimal 11 baris sensor berurutan."
        )

    missing_features = [column for column in FEATURE_COLS if column not in engineered.columns]
    if missing_features:
        raise ValueError(f"Feature belum lengkap: {missing_features}")

    x_values = engineered[FEATURE_COLS]
    x_scaled = scaler.transform(x_values)
    predictions = model.predict(x_scaled)

    result = engineered.copy()
    result["predicted_label"] = predictions

    if hasattr(model, "predict_proba"):
        probabilities = model.predict_proba(x_scaled)
        classes = list(model.classes_)
        for index, class_name in enumerate(classes):
            result[f"prob_{class_name}"] = probabilities[:, index]
        result["confidence"] = probabilities.max(axis=1)

    if "label" in result.columns:
        result["is_correct"] = result["label"].astype(str) == result["predicted_label"].astype(str)

    return cleaned, engineered, result


st.title("Random Forest Inkubator Demo")
st.caption("Local web demo untuk model random forest yang dilatih dari notebook randomforest.ipynb")

with st.sidebar:
    st.header("Model Files")
    st.write(f"Model: {MODEL_PATH.name}")
    st.write(f"Scaler: {SCALER_PATH.name}")
    st.write("Input: CSV sensor dengan kolom timestamp, temperature, humidity")
    st.write("Atau kolom asli: Timestamp, Suhu (°C), Kelembapan (%)")

try:
    model, scaler = load_artifacts()
    st.success("Model dan scaler berhasil dimuat.")
except Exception as exc:
    st.error(str(exc))
    st.stop()

left_col, right_col = st.columns([1, 1])
with left_col:
    use_sample = st.checkbox("Gunakan file sample bawaan", value=True)
with right_col:
    uploaded_file = st.file_uploader("Upload CSV sensor", type=["csv"])

if uploaded_file is not None:
    input_frame = pd.read_csv(uploaded_file)
elif use_sample and DEFAULT_DATA_PATH.exists():
    input_frame = pd.read_csv(DEFAULT_DATA_PATH)
else:
    input_frame = None

if input_frame is None:
    st.info("Upload file CSV atau aktifkan sample bawaan untuk mulai prediksi.")
    st.stop()

st.subheader("Preview Data")
st.dataframe(input_frame.head(20), use_container_width=True)

try:
    cleaned_frame, engineered_frame, prediction_frame = predict_frame(input_frame, model, scaler)
except Exception as exc:
    st.error(str(exc))
    st.stop()

st.subheader("Hasil Prediksi")
metric_col1, metric_col2, metric_col3 = st.columns(3)
metric_col1.metric("Rows input", len(cleaned_frame))
metric_col2.metric("Rows prediksi", len(prediction_frame))
metric_col3.metric("Class unik", prediction_frame["predicted_label"].nunique())

summary = prediction_frame["predicted_label"].value_counts().rename_axis("label").reset_index(name="count")
summary["percent"] = (summary["count"] / summary["count"].sum() * 100).round(2)

st.dataframe(summary, use_container_width=True)
st.bar_chart(summary.set_index("label")["count"])

if "label" in prediction_frame.columns:
    accuracy_value = float((prediction_frame["label"].astype(str) == prediction_frame["predicted_label"].astype(str)).mean())
    st.metric("Accuracy terhadap label input", f"{accuracy_value * 100:.2f}%")

st.subheader("Prediksi Lengkap")
show_columns = [
    "timestamp",
    "temperature",
    "humidity",
    "predicted_label",
]
for column_name in ["confidence", "label", "is_correct"]:
    if column_name in prediction_frame.columns:
        show_columns.append(column_name)

for class_name in getattr(model, "classes_", []):
    probability_column = f"prob_{class_name}"
    if probability_column in prediction_frame.columns:
        show_columns.append(probability_column)

st.dataframe(prediction_frame[show_columns], use_container_width=True)

csv_output = prediction_frame.to_csv(index=False).encode("utf-8")
st.download_button(
    label="Download hasil prediksi CSV",
    data=csv_output,
    file_name="random_forest_predictions.csv",
    mime="text/csv",
)

with st.expander("Lihat data engineered"):
    st.dataframe(engineered_frame.head(20), use_container_width=True)

st.info(
    "Catatan: feature engineering memakai rolling window, jadi beberapa baris awal akan terbuang. "
    "Minimal input yang masuk akal adalah 11 baris sensor berurutan."
)

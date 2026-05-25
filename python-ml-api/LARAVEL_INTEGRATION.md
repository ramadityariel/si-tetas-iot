# Laravel Integration Guide

This project now uses a Python FastAPI service for inference with `models/mobilenet_best.h5`.

## 1. Run the Python API

Install dependencies:

```bash
pip install -r requirements_api.txt
```

Start the API:

```bash
uvicorn fastapi_app:app --reload --host 127.0.0.1 --port 8000
```

Health check:

```bash
GET http://127.0.0.1:8000/health
```

## 2. API Endpoints

- `POST /predict/single` for 1 egg image
- `POST /predict/tray` for tray image

Both endpoints accept multipart form upload with the field name `file`.

## 3. Laravel Example Controller

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EggPredictController extends Controller
{
    public function single(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10240',
        ]);

        $response = Http::attach(
            'file',
            file_get_contents($request->file('image')->getRealPath()),
            $request->file('image')->getClientOriginalName()
        )->post('http://127.0.0.1:8000/predict/single');

        return response()->json($response->json(), $response->status());
    }

    public function tray(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:20480',
        ]);

        $response = Http::attach(
            'file',
            file_get_contents($request->file('image')->getRealPath()),
            $request->file('image')->getClientOriginalName()
        )->post('http://127.0.0.1:8000/predict/tray');

        return response()->json($response->json(), $response->status());
    }
}
```

## 4. Laravel Route Example

```php
use App\Http\Controllers\EggPredictController;

Route::post('/predict/single', [EggPredictController::class, 'single']);
Route::post('/predict/tray', [EggPredictController::class, 'tray']);
```

## 5. Notes

- Keep `fastapi_app.py` and the `models/` folder in the same project root.
- The Python API returns JSON, including an annotated image in Base64.
- If Laravel runs on another machine, replace `127.0.0.1` with the machine IP where FastAPI runs.

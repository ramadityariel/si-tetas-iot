<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\CandlingSession;
use App\Models\CandlingResult;

class CandlingApiController extends Controller
{
    public function uploadCandling(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'tray_id' => 'required|in:Atas,Tengah,Bawah',
        ]);

        $path = $request->file('image')->store('candling', 'public');

        $session = CandlingSession::create([
            'tray_id' => $request->tray_id,
            'status' => 'Pending'
        ]);

        $results = [];
        for ($i = 1; $i <= 36; $i++) {
            $results[] = [
                'session_id' => $session->id,
                'egg_position' => $i,
                'status_deteksi_ai' => null,
                'confidence_score' => null,
                'image_path' => $path,
                'is_manual_override' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        CandlingResult::insert($results);

        return response()->json([
            'status' => 'success',
            'message' => 'Upload berhasil dan sesi dibuat.',
            'session_id' => $session->id,
            'image_path' => $path
        ]);
    }

    public function getSessionResult($session_id)
    {
        $results = CandlingResult::where('session_id', $session_id)
            ->orderBy('egg_position')
            ->get();
            
        if ($results->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Session not found'], 404);
        }

        $formatted = [];
        foreach ($results as $r) {
            $formatted["egg_{$r->egg_position}"] = [
                'id' => $r->id,
                'status' => $r->is_manual_override ? $r->status_manual : $r->status_deteksi_ai,
                'confidence' => $r->confidence_score,
                'is_override' => $r->is_manual_override,
            ];
        }

        return response()->json([
            'status' => 'success',
            'session_id' => $session_id,
            'data' => $formatted
        ]);
    }

    public function updateEggStatus(Request $request)
    {
        $request->validate([
            'result_id' => 'required|exists:candling_results,id',
            'new_status' => 'required|in:Fertil Hidup,Fertil Mati,Infertil',
        ]);

        $result = CandlingResult::find($request->result_id);
        $result->is_manual_override = true;
        $result->status_manual = $request->new_status;
        $result->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Status override berhasil disimpan.'
        ]);
    }
}

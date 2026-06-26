<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Exception;

class ThresholdSettingController extends Controller
{
    private $database;

    public function __construct()
    {
        $firebaseCredentials = base_path(env('FIREBASE_CREDENTIALS', 'storage/app/firebase-credentials.json'));
        $firebaseDbUrl = env('FIREBASE_DATABASE_URL', 'https://si-tetas-default-rtdb.firebaseio.com/');

        if (file_exists($firebaseCredentials)) {
            $factory = (new Factory)
                ->withServiceAccount($firebaseCredentials)
                ->withDatabaseUri($firebaseDbUrl);

            $this->database = $factory->createDatabase();
        }
    }

    /**
     * TAMPILAN HALAMAN FORM THRESHOLD
     */
    public function index()
    {
        // Nilai default (cadangan) jika Firebase masih kosong, disesuaikan dengan struktur baru kamu
        $thresholds = [
            'suhu_bawah'  => 37.0,
            'suhu_atas'   => 38.0,
            'humid_bawah' => 55.0,
            'humid_atas'  => 60.0
        ];

        try {
            if ($this->database) {
                // Sesuai request: mengambil data dari path bertingkat 'settings/threshold'
                $firebaseData = $this->database->getReference('settings/threshold')->getValue();
                
                if (!empty($firebaseData)) {
                    $thresholds = array_merge($thresholds, $firebaseData);
                }
            }
        } catch (Exception $e) {
            logger()->error('Gagal ambil data threshold dari Firebase: ' . $e->getMessage());
        }

        return view('threshold_settings', compact('thresholds'));
    }

    /**
     * PROSES TOMBOL "SIMPAN PENGATURAN"
     */
    public function store(Request $request)
    {
        // Validasi input form (menyesuaikan nama key baru)
        $request->validate([
            'suhu_bawah'  => 'required|numeric',
            'suhu_atas'   => 'required|numeric',
            'humid_bawah' => 'required|numeric',
            'humid_atas'  => 'required|numeric',
        ]);

        try {
            if (!$this->database) {
                return redirect()->back()->with('error', 'Koneksi ke Firebase gagal!');
            }

            // Susun data sesuai struktur JSON yang kamu inginkan
            $dataToSave = [
                'suhu_bawah'  => (float)$request->suhu_bawah,
                'suhu_atas'   => (float)$request->suhu_atas,
                'humid_bawah' => (float)$request->humid_bawah,
                'humid_atas'  => (float)$request->humid_atas,
            ];

            // Masuk langsung ke path 'settings/threshold' agar menghasilkan struktur JSON bertingkat
            $this->database->getReference('settings/threshold')->set($dataToSave);

            return redirect()->back()->with('success', 'Pengaturan berhasil disimpan ke Firebase dengan struktur baru!');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }
}
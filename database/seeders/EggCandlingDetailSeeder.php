<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CandlingHistory;
use App\Models\EggCandlingDetail;

class EggCandlingDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil riwayat candling yang belum memiliki data detail 88 telur
        $histories = CandlingHistory::doesntHave('eggCandlingDetails')->get();

        foreach ($histories as $history) {
            $details = [];
            
            for ($i = 1; $i <= 88; $i++) {
                $statusRand = rand(1, 100);
                
                // 5% kemungkinan kosong
                if ($statusRand <= 5) {
                    $status = 'kosong';
                    $score = null;
                } else {
                    $status = rand(0, 1) === 1 ? 'fertil' : 'infertil';
                    // Akurasi di atas 90%
                    $score = rand(9000, 9999) / 100; // 90.00 hingga 99.99
                }

                $details[] = [
                    'candling_id' => $history->id,
                    'egg_id' => str_pad($i, 2, '0', STR_PAD_LEFT),
                    'prediction_result' => $status,
                    'confidence_score' => $score,
                    'notes' => $status == 'kosong' ? 'Tidak ada objek terdeteksi' : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            // Menggunakan insert bulk agar query lebih cepat
            EggCandlingDetail::insert($details);
        }
    }
}

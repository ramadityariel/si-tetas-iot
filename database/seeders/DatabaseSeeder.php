<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::create([
            'name' => 'TNK SV IPB',
            'email' => 'admin@sitetas.id',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'role' => 'super_admin',
        ]);

        $articles = [
            [
                'title' => 'Pengenalan Inkubator Pintar',
                'subtitle' => 'Mengenal lebih dekat teknologi inkubator penetas telur.',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in dui mauris. Vivamus hendrerit arcu sed erat molestie vehicula.',
                'category' => 'TEKNOLOGI',
            ],
            [
                'title' => 'Cara Menjaga Suhu',
                'subtitle' => 'Tips dan trik menjaga suhu agar penetasan berhasil optimal.',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'category' => 'PANDUAN',
            ],
            [
                'title' => 'Pentingnya Kelembapan',
                'subtitle' => 'Mengapa kelembapan sangat berpengaruh dalam proses penetasan.',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi.',
                'category' => 'EDUKASI',
            ],
        ];

        foreach ($articles as $article) {
            \App\Models\Article::create([
                'title' => $article['title'],
                'subtitle' => $article['subtitle'],
                'slug' => \Illuminate\Support\Str::slug($article['title']),
                'content' => $article['content'],
                'category' => $article['category'],
                'thumbnail' => 'dummy-thumbnail.jpg',
                'author_id' => $user->id,
            ]);
        }

        // Generate 20 dummy SensorLog
        $now = \Carbon\Carbon::now();
        for ($i = 0; $i < 20; $i++) {
            \App\Models\SensorLog::create([
                'temperature' => rand(365, 385) / 10,
                'humidity' => rand(55, 65),
                'fan_status' => (bool) rand(0, 1),
                'created_at' => $now->copy()->subMinutes(20 - $i),
                'updated_at' => $now->copy()->subMinutes(20 - $i),
            ]);
        }

        // Generate Dummy HatchPrediction
        \App\Models\HatchPrediction::create([
            'batch_id' => 'B-' . date('Ymd') . '-01',
            'estimated_date' => \Carbon\Carbon::now()->addDays(3)->format('Y-m-d'),
            'confidence_score' => 92,
            'status' => 'Mulai Pipping (Retakan)',
        ]);

        \App\Models\HatchPrediction::create([
            'batch_id' => 'B-' . date('Ymd') . '-01',
            'estimated_date' => \Carbon\Carbon::now()->addDays(6)->format('Y-m-d'),
            'confidence_score' => 85,
            'status' => 'Puncak Penetasan',
        ]);

        \App\Models\HatchPrediction::create([
            'batch_id' => 'B-' . date('Ymd') . '-01',
            'estimated_date' => \Carbon\Carbon::now()->addDays(7)->format('Y-m-d'),
            'confidence_score' => 78,
            'status' => 'Finalisasi & Pembersihan',
        ]);
    }
}

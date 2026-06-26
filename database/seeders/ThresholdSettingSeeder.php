<?php

namespace Database\Seeders;

use App\Models\ThresholdSetting;
use Illuminate\Database\Seeder;

class ThresholdSettingSeeder extends Seeder
{
    public function run(): void
    {
        ThresholdSetting::updateOrCreate(
            ['name' => 'Default'],
            [
                'temp_good_min' => 37.0,
                'temp_good_max' => 38.0,
                'humidity_good_min' => 55.0,
                'humidity_good_max' => 60.0,
                'temp_warning_min' => 36.0,
                'temp_warning_max' => 39.0,
                'humidity_warning_min' => 50.0,
                'humidity_warning_max' => 65.0,
                'is_active' => true,
            ]
        );
    }
}

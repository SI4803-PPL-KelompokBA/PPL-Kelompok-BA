<?php

namespace Database\Seeders;

use App\Models\Land;
use Illuminate\Database\Seeder;

class LandSeeder extends Seeder
{
    public function run(): void
    {
        Land::updateOrCreate(['nama_lahan' => 'Kebun Utara'], [
            'lokasi' => 'Sumedang, Jawa Barat', 'luas_area' => 25.5,
            'target_pengurangan_emisi' => 10, 'status_operasional' => 'aktif',
        ]);
        Land::updateOrCreate(['nama_lahan' => 'Kebun Selatan'], [
            'lokasi' => 'Garut, Jawa Barat', 'luas_area' => 40,
            'target_pengurangan_emisi' => 15, 'status_operasional' => 'aktif',
        ]);
    }
}
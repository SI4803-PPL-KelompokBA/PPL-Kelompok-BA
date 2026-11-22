<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FuelFactorSeeder extends Seeder
{
    /**
     * Faktor emisi BBM, kategori 'BBM', scope 1, satuan kg CO2/liter (hanya CO2).
     *
     * SUMBER (default IPCC):
     * - IPCC 2006 Guidelines Vol. 2 Bab 1 Tabel 1.2 (nilai kalor bersih / NCV):
     *     motor gasoline = 44,3 TJ/Gg ; gas/diesel oil = 43,0 TJ/Gg
     * - IPCC 2006 Guidelines Vol. 2 Bab 1 Tabel 1.4 (faktor CO2 default):
     *     motor gasoline = 69.300 kg/TJ ; gas/diesel oil = 74.100 kg/TJ
     * - Massa jenis: ASUMSI 0,74 kg/L (bensin) dan 0,84 kg/L (solar).
     *   BUKAN dari IPCC. Ganti dengan data resmi Pertamina/ESDM.
     *
     * [WAJIB VERIFIKASI] Cocokkan angka dengan dokumen asli sebelum merge,
     * dan putuskan apakah tim memakai faktor nasional (KLHK/ESDM) sebagai pengganti.
     */
    public function run(): void
    {
        // kg CO2/liter = (kg CO2/TJ x TJ/Gg) / 1.000.000 (-> kg/kg) x massa jenis (kg/L)
        $hitung = fn (float $efTj, float $ncv, float $massaJenis) =>
            round(($efTj * $ncv / 1_000_000) * $massaJenis, 4);

        $factors = [
            ['nama_faktor' => 'Bensin (Pertalite/Pertamax)', 'nilai_emisi' => $hitung(69300, 44.3, 0.74)], // IPCC 2006 T1.2 & T1.4
            ['nama_faktor' => 'Solar (Diesel)',              'nilai_emisi' => $hitung(74100, 43.0, 0.84)], // IPCC 2006 T1.2 & T1.4
        ];

        foreach ($factors as $f) {
            $key = ['kategori' => 'BBM', 'nama_faktor' => $f['nama_faktor']];

            $values = [
                'scope'       => 1,
                'nilai_emisi' => $f['nilai_emisi'],
                'satuan'      => 'kg CO2/liter',
                'updated_at'  => now(),
            ];

            if (! DB::table('emission_factors')->where($key)->exists()) {
                $values['created_at'] = now();
            }

            DB::table('emission_factors')->updateOrInsert($key, $values);
        }
    }
}
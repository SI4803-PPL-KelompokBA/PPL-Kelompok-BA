<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FertilizerFactorSeeder extends Seeder
{
    /**
     * Faktor emisi pupuk (kg CO2e per kg pupuk), scope 1.
     *
     * Urea = 1,57 mengikuti kriteria selesai PBI-01 (100 kg -> 157,00 kg CO2e).
     *
     * SUMBER: [WAJIB DIISI SEBELUM MERGE] contoh kandidat: IPCC 2006 Guidelines
     * Vol. 4 Bab 11 (emisi aplikasi urea & N2O), GHG Protocol, atau publikasi
     * resmi KLHK/Kementan. Tulis dokumen, tahun, dan nomor tabel per baris.
     * Nilai selain Urea di bawah BELUM TERVERIFIKASI.
     */
    public function run(): void
    {
        $factors = [
            ['nama_faktor' => 'Urea',               'nilai_emisi' => 1.57], // sumber: ...
            ['nama_faktor' => 'ZA (Amonium Sulfat)', 'nilai_emisi' => 0.70], // sumber: ... (belum terverifikasi)
            ['nama_faktor' => 'SP-36',               'nilai_emisi' => 0.50], // sumber: ... (belum terverifikasi)
            ['nama_faktor' => 'KCl',                 'nilai_emisi' => 0.55], // sumber: ... (belum terverifikasi)
            ['nama_faktor' => 'Pupuk Organik',       'nilai_emisi' => 0.10], // sumber: ... (belum terverifikasi)
        ];

        foreach ($factors as $f) {  
            DB::table('emission_factors')->updateOrInsert(
                ['kategori' => 'Pupuk', 'nama_faktor' => $f['nama_faktor']],
                [
                    'scope'       => 1,
                    'nilai_emisi' => $f['nilai_emisi'],
                    'satuan'      => 'kg CO2e/kg',
                    'updated_at'  => now(),
                    'created_at'  => now(),
                ]
            );
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\EmissionFactor;
use Illuminate\Database\Seeder;

abstract class FactorSeeder extends Seeder
{
    /** [kategori, nama_faktor, scope, nilai_emisi, satuan] */
    protected array $rows = [];

    public function run(): void
    {
        foreach ($this->rows as [$kategori, $nama, $scope, $nilai, $satuan]) {
            EmissionFactor::updateOrCreate(
                ['kategori' => $kategori, 'nama_faktor' => $nama],
                ['scope' => $scope, 'nilai_emisi' => $nilai, 'satuan' => $satuan]
            );
        }
    }
}
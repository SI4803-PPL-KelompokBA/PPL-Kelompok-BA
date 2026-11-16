<?php
namespace Database\Seeders;

class NaturalSinkFactorSeeder extends FactorSeeder
{
    protected array $rows = [
        ['Penyerapan Karbon', 'Agroforestri (pohon)', null, -22,   'kg CO2e/pohon'],
        ['Penyerapan Karbon', 'Biochar',              null, -2500, 'kg CO2e/ton'],
    ];
}
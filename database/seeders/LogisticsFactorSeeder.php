<?php
namespace Database\Seeders;

class LogisticsFactorSeeder extends FactorSeeder
{
    protected array $rows = [
        ['Logistik Ekspor', 'Kapal kontainer', 3, 0.016, 'kg CO2e/ton-km'],
        ['Logistik Ekspor', 'Pesawat kargo',   3, 0.60,  'kg CO2e/ton-km'],
    ];
}

<?php
namespace Database\Seeders;

class FuelFactorSeeder extends FactorSeeder
{
    protected array $rows = [
        ['BBM', 'Solar',  1, 2.68, 'kg CO2e/liter'],
        ['BBM', 'Bensin', 1, 2.31, 'kg CO2e/liter'],
    ];
}
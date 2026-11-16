<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            LandSeeder::class,
            FertilizerFactorSeeder::class,
            FuelFactorSeeder::class,
            ElectricityFactorSeeder::class,
            TransportFactorSeeder::class,
            LogisticsFactorSeeder::class,
            NaturalSinkFactorSeeder::class,
        ]);
    }
}
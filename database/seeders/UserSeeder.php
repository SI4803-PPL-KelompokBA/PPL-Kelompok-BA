<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['Data Inputter', 'inputter@cbam.test', 'data_inputter'],
            ['ESG Officer',   'esg@cbam.test',      'esg_officer'],
            ['Auditor',       'auditor@cbam.test',  'auditor'],
        ];

        foreach ($users as [$name, $email, $role]) {
            User::updateOrCreate(['email' => $email], [
                'name' => $name,
                'password' => 'password',   // di-hash otomatis oleh cast
                'role' => $role,
                'email_verified_at' => now(),
            ]);
        }
    }
}
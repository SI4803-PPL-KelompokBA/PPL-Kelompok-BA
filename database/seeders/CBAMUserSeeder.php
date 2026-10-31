<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class CBAMUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Data Inputter',
                'email' => 'inputter@cbam.com',
                'password' => 'Cbam@12345',
                'role' => 'inputter',
            ],
            [
                'name' => 'ESG Officer',
                'email' => 'esg@cbam.com',
                'password' => 'Cbam@12345',
                'role' => 'esg_officer',
            ],
            [
                'name' => 'Auditor',
                'email' => 'auditor@cbam.com',
                'password' => 'Cbam@12345',
                'role' => 'auditor',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}

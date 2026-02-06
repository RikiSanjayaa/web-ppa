<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultPassword = (string) env('DEFAULT_USER_PASSWORD', 'password');

        $users = [
            [
                'name' => (string) env('ADMIN_NAME', 'Super Admin'),
                'email' => (string) env('ADMIN_EMAIL', 'admin@ppa.local'),
                'is_admin' => true,
                'password' => (string) env('ADMIN_PASSWORD', $defaultPassword),
            ],
            [
                'name' => 'Operator Aduan',
                'email' => 'operator@ppa.local',
                'is_admin' => true,
                'password' => $defaultPassword,
            ],
            [
                'name' => 'Editor Informasi',
                'email' => 'editor@ppa.local',
                'is_admin' => true,
                'password' => $defaultPassword,
            ],
            [
                'name' => 'Petugas Layanan',
                'email' => 'petugas@ppa.local',
                'is_admin' => false,
                'password' => $defaultPassword,
            ],
            [
                'name' => 'Analis Data',
                'email' => 'analis@ppa.local',
                'is_admin' => false,
                'password' => $defaultPassword,
            ],
        ];

        foreach ($users as $user) {
            User::query()->updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make($user['password']),
                    'is_admin' => $user['is_admin'],
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}

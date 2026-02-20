<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Buat role jika belum ada
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        // Assign role ke semua user yang sudah ada berdasarkan flag is_admin
        User::where('is_admin', true)->each(function (User $user) use ($superAdmin) {
            // User yang sudah ada jadikan super_admin
            if (! $user->hasAnyRole(['super_admin', 'admin'])) {
                $user->assignRole($superAdmin);
            }
        });
    }
}

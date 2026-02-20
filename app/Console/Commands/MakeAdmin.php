<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class MakeAdmin extends Command
{
    protected $signature = 'make:admin
                            {--name= : Nama admin}
                            {--email= : Email admin}
                            {--password= : Password (default: random)}
                            {--role=super_admin : Role (super_admin atau admin)}';

    protected $description = 'Buat akun admin baru dengan role tertentu';

    public function handle(): int
    {
        $name     = $this->option('name') ?? $this->ask('Nama admin');
        $email    = $this->option('email') ?? $this->ask('Email admin');
        $password = $this->option('password') ?? $this->secret('Password (kosongkan untuk generate otomatis)');
        $role     = $this->option('role');

        if (! in_array($role, ['super_admin', 'admin'])) {
            $this->error('Role harus "super_admin" atau "admin".');
            return self::FAILURE;
        }

        if (User::where('email', $email)->exists()) {
            $this->error("Email {$email} sudah terdaftar!");
            return self::FAILURE;
        }

        if (empty($password)) {
            $password = \Illuminate\Support\Str::password(12);
            $this->info("Password dibuat otomatis: {$password}");
        }

        // Pastikan role ada
        Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);

        $user = User::create([
            'name'     => $name,
            'email'    => $email,
            'password' => bcrypt($password),
            'is_admin' => true,
        ]);

        $user->assignRole($role);

        $this->newLine();
        $this->table(
            ['Field', 'Value'],
            [
                ['Nama',  $name],
                ['Email', $email],
                ['Role',  $role],
            ]
        );

        $this->info('✅ Akun admin berhasil dibuat!');

        return self::SUCCESS;
    }
}

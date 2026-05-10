<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ─── SUPER ADMIN ──────────────────────────────────────────────────────
        User::create([
            'name'     => 'Super Admin',
            'email'    => 'admin@qles.com',
            'password' => Hash::make('admin123'),
            'role'     => 'super_admin',
        ]);

        // ─── TEACHER DEMO ─────────────────────────────────────────────────────
        User::create([
            'name'     => 'Pak Budi',
            'email'    => 'teacher@qles.com',
            'password' => Hash::make('teacher123'),
            'role'     => 'teacher',
        ]);

        // ─── STUDENT DEMO ─────────────────────────────────────────────────────
        User::create([
            'name'     => 'Siswa Demo',
            'email'    => 'student@qles.com',
            'password' => Hash::make('student123'),
            'role'     => 'student',
        ]);

        $this->command->info('✅ Seeder berhasil! Akun yang dibuat:');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Super Admin', 'admin@qles.com', 'admin123'],
                ['Teacher', 'teacher@qles.com', 'teacher123'],
                ['Student', 'student@qles.com', 'student123'],
            ]
        );
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Dosen: Johannes
        User::updateOrCreate(
            ['email' => 'johannes@del.ac.id'],
            [
                'name' => 'Johannes',
                'password' => Hash::make('password123'),
                'role' => 'dosen'
            ]
        );

        // Akun Asesor
        User::updateOrCreate(
            ['email' => 'asesor@del.ac.id'],
            [
                'name' => 'Dr. Asesor Kinerja',
                'password' => Hash::make('password123'),
                'role' => 'asesor'
            ]
        );

        // Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@del.ac.id'],
            [
                'name' => 'Administrator TSI',
                'password' => Hash::make('password123'),
                'role' => 'admin'
            ]
        );
    }
}
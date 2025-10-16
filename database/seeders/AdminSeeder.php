<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admin')->insert([
            'nama_admin' => 'Administrator',
            'email' => 'admin@example.com',
            'password_admin' => Hash::make('password123'), // pastikan ini sama dengan kolom yg kamu pakai
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

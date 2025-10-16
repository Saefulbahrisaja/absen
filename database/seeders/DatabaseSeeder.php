<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // <-- tambahkan ini
use App\Models\User;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $users = [
        //     ['name' => 'Dicki Prayudi', 'email' => 'gets.prayudi@gmail.com'],
        //     ['name' => 'Achmad Andika Gunawan', 'email' => 'achmadandika.gun@gmail.com'],
        //     ['name' => 'Adesty Pratiwi', 'email' => 'adestypratiwi@gmail.com'],
        //     ['name' => 'Syarip Suryana', 'email' => 'syareefsuryanando09@gmail.com'],
        //     ['name' => 'Muhammad Dikri Abdilla', 'email' => 'dikriabd04@gmail.com'],
        //     ['name' => 'Mila Hertiana', 'email' => 'milahertiana06@gmail.com'],
        //     ['name' => 'Annisa Tsakila Talita Rahma', 'email' => 'atsakila@gmail.com'],
        //     ['name' => 'Mutia Zulfana', 'email' => 'mutiazulfana98@gmail.com'],
        //     ['name' => 'Putri Lestari', 'email' => 'putrilestarii3107@gmail.com'],
        //     ['name' => 'Andress Juniawan', 'email' => 'andressjuniawan@gmail.com'],
        //     ['name' => 'Ilham Akbar', 'email' => 'akbarilham2404@gmail.com'],
        // ];

        // foreach ($users as $user) {
        //     User::create([
        //         'name' => $user['name'],
        //         'email' => $user['email'],
        //         'password' => Hash::make('password123'), // default password
        //     ]);
        // }

        $this->call([
            AdminSeeder::class,
        ]);
    }
}

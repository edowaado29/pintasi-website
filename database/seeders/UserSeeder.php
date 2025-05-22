<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
        'nama' => 'Bidan Boy',
        'email' => 'bidan@gmail.com',
        'password' => bcrypt('asdasdasd'),
        'role' => 'bidan',
        'no_hp' => '08123456789',
        'alamat' => 'Jl. Jalan No.1',
        'foto' => null,
    ]);
    }
}

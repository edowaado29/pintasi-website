<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ibu;
use App\Models\Bayi;
use App\Models\User;
use App\Models\Resep;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeder User
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'nama' => "User $i",
                'email' => "user$i@email.com",
                'password' => Hash::make('password'),
                'role' => 'kader',
                'no_hp' => '0812345678' . $i,
                'alamat' => "Alamat User $i",
                'foto' => null,
            ]);
        }

        // Seeder Ibu
        for ($i = 1; $i <= 10; $i++) {
            Ibu::create([
                'email' => "ibu$i@email.com",
                'password' => Hash::make('password'),
                'nik' => '32760' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'nama_ibu' => "Ibu $i",
                'tempat_lahir' => "Kota $i",
                'tanggal_lahir' => now()->subYears(25 + $i)->format('Y-m-d'),
                'alamat' => "Alamat Ibu $i",
                'telepon' => '0812345678' . $i,
                'foto' => null,
            ]);
        }

        // Seeder Bayi
        for ($i = 1; $i <= 10; $i++) {
            Bayi::create([
                'id_ibu' => $i,
                'no_kk' => '1234567890' . $i,
                'nik_bayi' => '32760' . str_pad($i, 6, '1', STR_PAD_LEFT),
                'nama_bayi' => "Bayi $i",
                'tanggal_lahir' => now()->subMonths($i * 2)->format('Y-m-d'),
                'jenis_kelamin' => $i % 2 == 0 ? 'Laki-laki' : 'Perempuan',
                'nama_ayah' => "Ayah $i",
                'nama_ibu' => "Ibu $i",
                'foto_bayi' => null,
            ]);
        }

        // Seeder Resep
        for ($i = 1; $i <= 10; $i++) {
            Resep::create([
                'nama_resep' => "Resep $i",
                'langkah' => "Langkah membuat resep $i.",
                'jumlah_porsi' => rand(1, 5),
                'min_usia' => rand(6, 12),
                'max_usia' => rand(13, 24),
                'total_kalori' => rand(100, 300),
                'total_protein' => rand(5, 20),
                'total_lemak' => rand(2, 10),
                'total_karbohidrat' => rand(10, 50),
                'total_serat' => rand(1, 5),
                'gambar_resep' => null,
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            ['name' => 'Aurellia', 'role' => 1],
            ['name' => 'Putri', 'role' => 2],
            ['name' => 'Budi', 'role' => 3],
            ['name' => 'Arsyaningrum', 'role' => 4],
            ['name' => 'Farisah', 'role' => 5],
            ['name' => 'Ghassani', 'role' => 1],
            ['name' => 'Nabila', 'role' => 2],
            ['name' => 'Najma', 'role' => 3],
            ['name' => 'Manika', 'role' => 4],
        ];

        foreach ($users as $index => $data) {
            User::create([
                'name' => $data['name'],
                'email' => strtolower($data['name']) . '@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => $data['role'],
                'region' => 'BTM',
                'perusahaan' => $data['role'] == 5 ? 'Telkomsel' : 'PGNCOM',
                'bagian' => $data['role'] == 5 ? null : (rand(0, 1) ? 'Pengawas' : null),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // HAPUS DATA (AMAN DI POSTGRES)
        User::query()->delete();

        $users = [
            [
                'uuid' => '6a6c6fbb-7ec9-4ef6-81fa-345000505f74',
                'nama' => 'Super Administrator',
                'email' => 'superadmin@rentalcamera.com',
                'role' => 'superadmin',
            ],
            [
                'uuid' => 'bdc00770-d957-4d6a-bcd5-72b143d1ed3f',
                'nama' => 'Administrator',
                'email' => 'admin@rentalcamera.com',
                'role' => 'admin',
            ],
            [
                'uuid' => '5f495f63-da6a-483f-a418-28e476a669a6',
                'nama' => 'Customer Premium',
                'email' => 'customer@example.com',
                'role' => 'customer',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'uuid' => $user['uuid'],
                    'nama' => $user['nama'],
                    'email_verified_at' => now(),
                    'password' => Hash::make('password123'),
                    'telepon' => '08' . rand(1111111111, 9999999999),
                    'alamat' => 'Jl. Contoh No. ' . rand(1, 50),
                    'kota' => 'Jakarta',
                    'provinsi' => 'DKI Jakarta',
                    'kode_pos' => '12345',
                    'tanggal_lahir' => now()->subYears(rand(20, 40)),
                    'jenis_kelamin' => rand(0, 1) ? 'L' : 'P',
                    'role' => $user['role'],
                    'status' => 'active',
                    'poin_reward' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Customer random
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'uuid' => Str::uuid(),
                'nama' => 'Customer ' . $i,
                'email' => "customer{$i}@example.com",
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'telepon' => '08' . rand(1111111111, 9999999999),
                'alamat' => 'Jl. Customer No. ' . $i,
                'kota' => 'Jakarta',
                'provinsi' => 'DKI Jakarta',
                'kode_pos' => '1234' . $i,
                'tanggal_lahir' => now()->subYears(rand(18, 45)),
                'jenis_kelamin' => rand(0, 1) ? 'L' : 'P',
                'role' => 'customer',
                'status' => 'active',
                'poin_reward' => rand(0, 200),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ Users seeded successfully: ' . User::count());
    }
}

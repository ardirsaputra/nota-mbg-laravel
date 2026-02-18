<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin seed (idempotent)
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'admin',
                'password' => 'admin123123',
                'role' => 'admin',
            ]
        );
        // Seed toko data
        $this->call(TokoSeeder::class);
        // Seed default settings
        $this->call(SettingSeeder::class);
    }
}

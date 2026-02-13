<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Toko;

class TokoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $toko_data = [
            // [
            //     'nama_toko' => 'SPPG PUJO ASRI',
            //     'alamat' => 'PUJO ASRI, TRIMURJO',
            // ],
        ];

        foreach ($toko_data as $toko) {
            Toko::create($toko);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaultSettings = [
            ['key' => 'website_name', 'value' => 'CV Mia Jaya Abadi', 'type' => 'text'],
            ['key' => 'company_name', 'value' => 'CV Mia Jaya Abadi', 'type' => 'text'],
            ['key' => 'hero_title', 'value' => 'Solusi Terpercaya untuk Kebutuhan Anda', 'type' => 'text'],
            ['key' => 'hero_description', 'value' => 'Kami menyediakan produk berkualitas dengan harga kompetitif dan pelayanan terbaik untuk memenuhi kebutuhan bisnis Anda.', 'type' => 'textarea'],
            ['key' => 'about_title', 'value' => 'Tentang Kami', 'type' => 'text'],
            ['key' => 'about_description', 'value' => 'CV Mia Jaya Abadi adalah perusahaan yang bergerak di bidang distribusi barang dengan komitmen memberikan pelayanan terbaik kepada pelanggan. Dengan pengalaman bertahun-tahun, kami terus berinovasi untuk memberikan solusi terbaik.', 'type' => 'textarea'],
            ['key' => 'phone_1', 'value' => '0812-3456-7890', 'type' => 'text'],
            ['key' => 'phone_2', 'value' => '0813-9876-5432', 'type' => 'text'],
            ['key' => 'address', 'value' => 'Jl. Contoh No. 123, Jakarta Pusat, DKI Jakarta', 'type' => 'text'],
            ['key' => 'address_2', 'value' => '', 'type' => 'text'],
            [
                'key' => 'operating_hours',
                'value' => json_encode([
                    'Senin' => '08:00 - 17:00',
                    'Selasa' => '08:00 - 17:00',
                    'Rabu' => '08:00 - 17:00',
                    'Kamis' => '08:00 - 17:00',
                    'Jumat' => '08:00 - 17:00',
                    'Sabtu' => '08:00 - 14:00',
                    'Minggu' => 'Tutup',
                ]),
                'type' => 'json'
            ],
            [
                'key' => 'features',
                'value' => json_encode([
                    ['icon' => 'fa-shield-alt', 'title' => 'Kualitas Terjamin', 'description' => 'Produk berkualitas tinggi yang telah tersertifikasi'],
                    ['icon' => 'fa-shipping-fast', 'title' => 'Pengiriman Cepat', 'description' => 'Pengiriman dalam 24 jam ke seluruh Indonesia'],
                    ['icon' => 'fa-tags', 'title' => 'Harga Kompetitif', 'description' => 'Harga terbaik di kelasnya dengan kualitas terjamin'],
                    ['icon' => 'fa-headset', 'title' => 'Layanan 24/7', 'description' => 'Tim customer service siap melayani kapan saja'],
                ]),
                'type' => 'json'
            ],
            [
                'key' => 'services',
                'value' => json_encode([
                    ['title' => 'Distribusi Barang', 'description' => 'Kami menyediakan layanan distribusi barang ke seluruh Indonesia dengan sistem yang terpercaya dan tepat waktu.', 'image' => ''],
                    ['title' => 'Konsultasi Bisnis', 'description' => 'Tim kami siap membantu mengembangkan bisnis Anda dengan solusi yang tepat sasaran dan efektif.', 'image' => ''],
                    ['title' => 'Layanan Custom', 'description' => 'Solusi khusus disesuaikan dengan kebutuhan bisnis Anda untuk hasil yang maksimal.', 'image' => ''],
                ]),
                'type' => 'json'
            ],
        ];

        foreach ($defaultSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type']
                ]
            );
        }

        $this->command->info('Default settings have been seeded!');
    }
}

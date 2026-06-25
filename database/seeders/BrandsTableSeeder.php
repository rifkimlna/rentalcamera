<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandsTableSeeder extends Seeder
{
    public function run(): void
    {
        // HAPUS DATA (AMAN DI POSTGRES)
        Brand::query()->delete();

        $brands = [
            [
                'nama_brand' => 'Canon',
                'deskripsi' => 'Produsen kamera dan peralatan fotografi terkemuka dari Jepang',
            ],
            [
                'nama_brand' => 'Sony',
                'deskripsi' => 'Perusahaan elektronik multinasional dengan lini kamera mirrorless',
            ],
            [
                'nama_brand' => 'Nikon',
                'deskripsi' => 'Produsen kamera dan lensa optik profesional',
            ],
            [
                'nama_brand' => 'Fujifilm',
                'deskripsi' => 'Kamera dengan teknologi warna yang unik',
            ],
            [
                'nama_brand' => 'Panasonic',
                'deskripsi' => 'Kamera dan peralatan videografi profesional',
            ],
            [
                'nama_brand' => 'DJI',
                'deskripsi' => 'Produsen drone terkemuka di dunia',
            ],
            [
                'nama_brand' => 'Godox',
                'deskripsi' => 'Peralatan lighting dan flash untuk fotografi',
            ],
            [
                'nama_brand' => 'Rode',
                'deskripsi' => 'Microphone dan peralatan audio profesional',
            ],
            [
                'nama_brand' => 'Manfrotto',
                'deskripsi' => 'Tripod dan peralatan pendukung kamera',
            ],
        ];

        foreach ($brands as $brand) {
            Brand::updateOrCreate(
                ['slug' => Str::slug($brand['nama_brand'])],
                [
                    'nama_brand' => $brand['nama_brand'],
                    'slug' => Str::slug($brand['nama_brand']),
                    'deskripsi' => $brand['deskripsi'],
                    'logo' => null,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('✅ Brands seeded successfully: ' . Brand::count());
    }
}

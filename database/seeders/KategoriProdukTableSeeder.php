<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriProduk;

class KategoriProdukTableSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Seeding kategori produk...');

        // Hapus data lama (AMAN untuk PostgreSQL)
        KategoriProduk::query()->delete();

        $kategori = [
            [
                'id' => 1,
                'nama_kategori' => 'Kamera DSLR',
                'slug' => 'kamera-dslr',
                'deskripsi' => 'Kamera digital single-lens reflex untuk fotografi profesional',
                'icon' => null,
                'urutan' => 1,
                'status' => 'active',
            ],
            [
                'id' => 2,
                'nama_kategori' => 'Kamera Mirrorless',
                'slug' => 'kamera-mirrorless',
                'deskripsi' => 'Kamera ringan dengan kualitas tinggi tanpa cermin',
                'icon' => null,
                'urutan' => 2,
                'status' => 'active',
            ],
            [
                'id' => 3,
                'nama_kategori' => 'Lensa Kamera',
                'slug' => 'lensa-kamera',
                'deskripsi' => 'Berbagai jenis lensa untuk kebutuhan fotografi berbeda',
                'icon' => null,
                'urutan' => 3,
                'status' => 'active',
            ],
            [
                'id' => 4,
                'nama_kategori' => 'Lighting Equipment',
                'slug' => 'lighting-equipment',
                'deskripsi' => 'Peralatan pencahayaan untuk studio dan outdoor',
                'icon' => null,
                'urutan' => 4,
                'status' => 'active',
            ],
            [
                'id' => 5,
                'nama_kategori' => 'Audio Equipment',
                'slug' => 'audio-equipment',
                'deskripsi' => 'Microphone dan peralatan audio untuk videografi',
                'icon' => null,
                'urutan' => 5,
                'status' => 'active',
            ],
            [
                'id' => 6,
                'nama_kategori' => 'Tripod & Stabilizer',
                'slug' => 'tripod-stabilizer',
                'deskripsi' => 'Alat penstabil kamera untuk hasil yang smooth',
                'icon' => null,
                'urutan' => 6,
                'status' => 'active',
            ],
            [
                'id' => 7,
                'nama_kategori' => 'Drone',
                'slug' => 'drone',
                'deskripsi' => 'Drone untuk aerial photography dan videography',
                'icon' => null,
                'urutan' => 7,
                'status' => 'active',
            ],
            [
                'id' => 8,
                'nama_kategori' => 'Aksesoris',
                'slug' => 'aksesoris',
                'deskripsi' => 'Aksesoris pendukung fotografi dan videografi',
                'icon' => null,
                'urutan' => 8,
                'status' => 'active',
            ],
        ];

        foreach ($kategori as $kat) {
            KategoriProduk::updateOrCreate(
                ['slug' => $kat['slug']],
                $kat
            );
        }

        $this->command->info('✅ Kategori produk berhasil diseed: ' . count($kategori));
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;
use App\Models\Brand;
use App\Models\KategoriProduk;

class ProdukTableSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI DEPENDENCY (WAJIB ADA)
        |--------------------------------------------------------------------------
        */

        $kategori = KategoriProduk::whereIn('slug', [
            'kamera-dslr',
            'kamera-mirrorless',
            'lensa-kamera',
            'lighting-equipment',
            'tripod-stabilizer',
            'audio-equipment',
            'drone',
        ])->get()->keyBy('slug');

        $brand = Brand::whereIn('slug', [
            'canon',
            'sony',
            'nikon',
            'fujifilm',
            'dji',
            'godox',
            'rode',
            'manfrotto',
        ])->get()->keyBy('slug');

        if ($kategori->count() < 7 || $brand->count() < 8) {
            $this->command->error('❌ Seeder Produk gagal: kategori atau brand belum lengkap.');
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | DATA PRODUK
        |--------------------------------------------------------------------------
        */

        $produkData = [
            [
                'kode_produk' => 'CAM001',
                'nama_produk' => 'Canon EOS 5D Mark IV',
                'slug' => 'canon-eos-5d-mark-iv',
                'kategori_id' => $kategori['kamera-dslr']->id,
                'brand_id' => $brand['canon']->id,
                'deskripsi_singkat' => 'Kamera DSLR full-frame profesional dengan 30.4MP',
                'deskripsi_lengkap' => 'Canon EOS 5D Mark IV adalah kamera DSLR full-frame profesional dengan kemampuan foto dan video 4K.',
                'spesifikasi' => [
                    'sensor' => 'Full-frame CMOS 30.4MP',
                    'processor' => 'DIGIC 6+',
                    'iso' => '100-32000',
                ],
                'fitur' => 'Dual Pixel CMOS AF, Weather Sealing',
                'harga_per_hari' => 250000,
                'harga_per_minggu' => 1500000,
                'harga_per_bulan' => 5000000,
                'stok_total' => 5,
                'stok_tersedia' => 5,
                'minimum_sewa' => 1,
                'maximum_sewa' => 14,
                'gambar_utama' => 'products/canon-5d-mark-iv.jpg',
                'status' => 'available',
                'is_featured' => true,
                'is_recommended' => true,
                'berat' => 890,
                'dimensi' => '15.1 x 11.6 x 7.6 cm',
                'kondisi' => 'bekas_excellent',
                'tahun_pembuatan' => 2016,
            ],

            [
                'kode_produk' => 'CAM003',
                'nama_produk' => 'Sony A7 III',
                'slug' => 'sony-a7-iii',
                'kategori_id' => $kategori['kamera-mirrorless']->id,
                'brand_id' => $brand['sony']->id,
                'deskripsi_singkat' => 'Kamera mirrorless full-frame all-rounder',
                'deskripsi_lengkap' => 'Sony A7 III adalah kamera mirrorless serba bisa untuk foto dan video.',
                'spesifikasi' => [
                    'sensor' => 'Full-frame 24.2MP',
                    'af' => '693 phase-detection',
                ],
                'fitur' => 'Eye AF, 5-axis Stabilization',
                'harga_per_hari' => 220000,
                'harga_per_minggu' => 1320000,
                'harga_per_bulan' => 4400000,
                'stok_total' => 6,
                'stok_tersedia' => 6,
                'minimum_sewa' => 1,
                'maximum_sewa' => 14,
                'gambar_utama' => 'products/sony-a7-iii.jpg',
                'status' => 'available',
                'is_featured' => true,
                'is_recommended' => false,
                'berat' => 650,
                'dimensi' => '12.7 x 9.6 x 7.4 cm',
                'kondisi' => 'bekas_excellent',
                'tahun_pembuatan' => 2018,
            ],

            [
                'kode_produk' => 'DRN001',
                'nama_produk' => 'DJI Mavic Air 2',
                'slug' => 'dji-mavic-air-2',
                'kategori_id' => $kategori['drone']->id,
                'brand_id' => $brand['dji']->id,
                'deskripsi_singkat' => 'Drone compact kamera 48MP',
                'deskripsi_lengkap' => 'DJI Mavic Air 2 dengan kamera 48MP dan flight time 34 menit.',
                'spesifikasi' => [
                    'kamera' => '48MP',
                    'video' => '4K 60fps',
                    'flight_time' => '34 menit',
                ],
                'fitur' => 'OcuSync 2.0, 4K Video',
                'harga_per_hari' => 300000,
                'harga_per_minggu' => 1800000,
                'harga_per_bulan' => 6000000,
                'stok_total' => 3,
                'stok_tersedia' => 3,
                'minimum_sewa' => 1,
                'maximum_sewa' => 7,
                'gambar_utama' => 'products/dji-mavic-air-2.jpg',
                'status' => 'available',
                'is_featured' => true,
                'is_recommended' => true,
                'berat' => 570,
                'dimensi' => '18.3 x 9.7 x 8.4 cm',
                'kondisi' => 'baru',
                'tahun_pembuatan' => 2020,
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | INSERT / UPDATE
        |--------------------------------------------------------------------------
        */

        foreach ($produkData as $produk) {
            Produk::updateOrCreate(
                ['kode_produk' => $produk['kode_produk']],
                $produk
            );
        }

        $this->command->info('✅ Produk seeded: ' . count($produkData));
    }
}

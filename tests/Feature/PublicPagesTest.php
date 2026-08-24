<?php

namespace Tests\Feature;

use App\Models\Produk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_utama_tampil(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Stekpro', false);
    }

    public function test_katalog_publik_menampilkan_produk_dan_meta_seo(): void
    {
        Produk::create([
            'kode_produk' => 'TEST-PUB-01',
            'nama_produk' => 'Kamera Publik Uji',
            'slug' => 'kamera-publik-uji',
            'harga_per_hari' => 150000,
            'stok_total' => 2,
            'stok_tersedia' => 2,
            'status' => 'available',
        ]);

        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertSee('Kamera Publik Uji');
        // Meta SEO dari Fase 4
        $response->assertSee('name="description"', false);
        $response->assertSee('property="og:title"', false);
    }

    public function test_detail_produk_publik_tampil_dengan_cta_guest(): void
    {
        Produk::create([
            'kode_produk' => 'TEST-PUB-02',
            'nama_produk' => 'Lensa Publik Uji',
            'slug' => 'lensa-publik-uji',
            'deskripsi_singkat' => 'Lensa uji untuk halaman publik.',
            'harga_per_hari' => 75000,
            'stok_total' => 1,
            'stok_tersedia' => 1,
            'status' => 'available',
        ]);

        $response = $this->get('/products/lensa-publik-uji');

        $response->assertStatus(200);
        $response->assertSee('Lensa Publik Uji');
        $response->assertSee('Login untuk Menyewa');
        $response->assertSee('Daftar Gratis');
    }

    public function test_halaman_tidak_ditemukan_menampilkan_404_kustom(): void
    {
        $response = $this->get('/halaman-tidak-ada-sekali');

        $response->assertStatus(404);
    }
}

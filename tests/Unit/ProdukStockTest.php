<?php

namespace Tests\Unit;

use App\Models\Produk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProdukStockTest extends TestCase
{
    use RefreshDatabase;

    private Produk $produk;

    protected function setUp(): void
    {
        parent::setUp();

        $this->produk = Produk::create([
            'kode_produk' => 'TEST-STK-01',
            'nama_produk' => 'Kamera Uji Stok',
            'harga_per_hari' => 100000,
            'stok_total' => 5,
            'stok_tersedia' => 5,
            'stok_dipinjam' => 0,
            'stok_rusak' => 0,
            'status' => 'available',
        ]);
    }

    public function test_rent_mengurangi_stok_tersedia_dan_menambah_dipinjam(): void
    {
        $this->assertTrue($this->produk->updateStock('rent', 3));

        $this->assertEquals(2, $this->produk->fresh()->stok_tersedia);
        $this->assertEquals(3, $this->produk->fresh()->stok_dipinjam);
    }

    public function test_rent_melebihi_stok_gagal_tanpa_mengubah_data(): void
    {
        $this->assertTrue($this->produk->updateStock('rent', 3));
        $this->assertFalse($this->produk->updateStock('rent', 5));

        $this->assertEquals(2, $this->produk->fresh()->stok_tersedia);
        $this->assertEquals(3, $this->produk->fresh()->stok_dipinjam);
    }

    public function test_return_mengembalikan_stok_dengan_benar(): void
    {
        $this->produk->updateStock('rent', 3);
        $this->produk->updateStock('return', 3);

        $this->assertEquals(5, $this->produk->fresh()->stok_tersedia);
        $this->assertEquals(0, $this->produk->fresh()->stok_dipinjam);
    }

    public function test_return_berlebih_tidak_menggelembungkan_stok(): void
    {
        $this->produk->updateStock('rent', 1);
        // Return 99 padahal hanya 1 dipinjam
        $this->produk->updateStock('return', 99);

        $p = $this->produk->fresh();
        $this->assertEquals(0, $p->stok_dipinjam);
        $this->assertEquals(5, $p->stok_tersedia);
    }

    public function test_damage_dan_repair_terclamp_dengan_benar(): void
    {
        $this->produk->updateStock('damage', 2);
        $this->assertEquals(2, $this->produk->fresh()->stok_rusak);
        $this->assertEquals(3, $this->produk->fresh()->stok_tersedia);

        // Repair 99 padahal hanya 2 rusak
        $this->produk->updateStock('repair', 99);
        $p = $this->produk->fresh();
        $this->assertEquals(0, $p->stok_rusak);
        $this->assertEquals(5, $p->stok_tersedia);
    }

    public function test_konservasi_total_stok_setelah_operasi_campuran(): void
    {
        $this->produk->updateStock('rent', 2);
        $this->produk->updateStock('damage', 1);
        $this->produk->updateStock('return', 1);

        $p = $this->produk->fresh();
        $total = $p->stok_tersedia + $p->stok_dipinjam + $p->stok_rusak;
        $this->assertEquals(5, $total);
    }
}

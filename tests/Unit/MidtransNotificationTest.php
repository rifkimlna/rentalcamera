<?php

namespace Tests\Unit;

use App\Models\Produk;
use App\Models\Transaksis;
use App\Models\DetailTransaksis;
use App\Services\MidtransService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class MidtransNotificationTest extends TestCase
{
    use RefreshDatabase;

    private MidtransService $service;

    private Transaksis $transaksi;

    private Produk $produk;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(MidtransService::class);

        $this->produk = Produk::create([
            'kode_produk' => 'TEST-MDT-01',
            'nama_produk' => 'Kamera Uji Midtrans',
            'harga_per_hari' => 100000,
            'stok_total' => 3,
            'stok_tersedia' => 3,
            'stok_dipinjam' => 0,
            'stok_rusak' => 0,
            'status' => 'available',
        ]);

        $this->transaksi = Transaksis::create([
            'nama_customer' => 'Tester',
            'telepon_customer' => '081200000000',
            'email_customer' => 'tester@example.com',
            'grand_total' => 100000,
            'lama_sewa' => 1,
            'midtrans_order_id' => 'TRX-TEST-001-1234567890',
            'status_pembayaran' => 'pending',
            'status_transaksi' => 'menunggu_pembayaran',
        ]);
    }

    /**
     * Simulasikan checkout: kurangi stok dan buat baris detail transaksi.
     */
    private function simulateCheckout(int $jumlah): void
    {
        $this->produk->updateStock('rent', $jumlah);

        DetailTransaksis::create([
            'transaksi_id' => $this->transaksi->id,
            'produk_id' => $this->produk->id,
            'kode_produk' => $this->produk->kode_produk,
            'nama_produk' => $this->produk->nama_produk,
            'harga_per_hari' => 100000,
            'jumlah' => $jumlah,
            'lama_sewa' => 1,
            'subtotal' => 100000 * $jumlah,
        ]);
    }

    private function notif(array $overrides = []): Request
    {
        $payload = array_merge([
            'order_id' => $this->transaksi->midtrans_order_id,
            'transaction_id' => 'mid-x1',
            'transaction_status' => 'settlement',
            'payment_type' => 'gopay',
            'gross_amount' => '100000.00',
            'fraud_status' => null,
            'status_code' => '200',
            'status_message' => 'Success',
            'signature_key' => 'ignored-at-service-level',
            'currency' => 'IDR',
        ], $overrides);

        return Request::create('/callback', 'POST', $payload);
    }

    public function test_gross_amount_berbeda_dari_database_ditolak(): void
    {
        $processed = $this->service->handleNotification(
            $this->notif(['gross_amount' => '50000.00', 'transaction_status' => 'settlement'])
        );

        $this->assertFalse($processed);
        $this->assertEquals('pending', $this->transaksi->fresh()->status_pembayaran);
        $this->assertEquals('menunggu_pembayaran', $this->transaksi->fresh()->status_transaksi);
    }

    public function test_settlement_dengan_nomor_cocok_menandai_lunas(): void
    {
        $processed = $this->service->handleNotification($this->notif());

        $this->assertTrue($processed);
        $this->assertEquals('settlement', $this->transaksi->fresh()->status_pembayaran);
        $this->assertEquals('dikonfirmasi', $this->transaksi->fresh()->status_transaksi);
        $this->assertNotNull($this->transaksi->fresh()->paid_at);
    }

    public function test_pending_telat_tidak_menurunkan_transaksi_lunas(): void
    {
        $this->service->handleNotification($this->notif());
        $this->service->handleNotification($this->notif(['transaction_status' => 'pending']));

        $this->assertEquals('settlement', $this->transaksi->fresh()->status_pembayaran);
        $this->assertEquals('dikonfirmasi', $this->transaksi->fresh()->status_transaksi);
    }

    public function test_expire_setelah_settlement_tidak_membatalkan_order(): void
    {
        // Stok sudah berkurang saat checkout (simulasikan)
        $this->simulateCheckout(2);

        $this->service->handleNotification($this->notif());
        $this->service->handleNotification($this->notif(['transaction_status' => 'expire']));

        $t = $this->transaksi->fresh();
        $this->assertEquals('settlement', $t->status_pembayaran);
        $this->assertNotEquals('dibatalkan', $t->status_transaksi);
        // Stok tidak dikembalikan untuk order yang lunas
        $this->assertEquals(1, Produk::first()->stok_tersedia);
    }

    public function test_settlement_telat_tidak_menghidupkan_order_dibatalkan(): void
    {
        // Customer membatalkan sebelum settlement telat masuk
        $this->transaksi->update([
            'status_pembayaran' => 'cancel',
            'status_transaksi' => 'dibatalkan',
            'cancelled_at' => now(),
        ]);

        $this->service->handleNotification($this->notif());

        $t = $this->transaksi->fresh();
        $this->assertEquals('cancel', $t->status_pembayaran);
        $this->assertEquals('dibatalkan', $t->status_transaksi);
    }

    public function test_expire_pada_transaksi_pending_membatalkan_dan_mengembalikan_stok(): void
    {
        $this->simulateCheckout(2);

        $this->service->handleNotification($this->notif(['transaction_status' => 'expire']));

        $t = $this->transaksi->fresh();
        $this->assertEquals('expire', $t->status_pembayaran);
        $this->assertEquals('dibatalkan', $t->status_transaksi);
        // Stok kembali penuh karena order batal
        $this->assertEquals(3, Produk::first()->stok_tersedia);
        $this->assertEquals(0, Produk::first()->stok_dipinjam);
    }
}

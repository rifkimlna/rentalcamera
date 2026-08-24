<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;
use Carbon\Carbon;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $vouchers = [
            [
                'kode_voucher' => 'NEWUSER10',
                'nama_voucher' => 'Diskon Pengguna Baru 10%',
                'type' => 'percentage',
                'value' => 10,
                'min_purchase' => 100000,
                'max_discount' => 50000,
                'kuota' => 100,
                'kuota_terpakai' => 0,
                'start_date' => Carbon::now()->subDays(1),
                'end_date' => Carbon::now()->addDays(30),
                'is_active' => true,
                'user_id' => null,
                'kategori_id' => null,
                'produk_id' => null,
            ],
            [
                'kode_voucher' => 'HEMAT50',
                'nama_voucher' => 'Potongan Rp50.000',
                'type' => 'fixed',
                'value' => 50000,
                'min_purchase' => 300000,
                'max_discount' => null,
                'kuota' => 50,
                'kuota_terpakai' => 5,
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->addDays(25),
                'is_active' => true,
                'user_id' => null,
                'kategori_id' => null,
                'produk_id' => null,
            ],
            [
                'kode_voucher' => 'KAMERALENS',
                'nama_voucher' => 'Spesial Kamera & Lensa',
                'type' => 'percentage',
                'value' => 15,
                'min_purchase' => 200000,
                'max_discount' => 100000,
                'kuota' => 30,
                'kuota_terpakai' => 12,
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->addDays(20),
                'is_active' => true,
                'user_id' => null,
                'kategori_id' => 1,
                'produk_id' => null,
            ],
            [
                'kode_voucher' => 'WEEKEND20',
                'nama_voucher' => 'Promo Akhir Pekan 20%',
                'type' => 'percentage',
                'value' => 20,
                'min_purchase' => 50000,
                'max_discount' => 75000,
                'kuota' => 200,
                'kuota_terpakai' => 45,
                'start_date' => Carbon::now()->addDays(1),
                'end_date' => Carbon::now()->addDays(7),
                'is_active' => true,
                'user_id' => null,
                'kategori_id' => null,
                'produk_id' => null,
            ],
            [
                'kode_voucher' => 'VIP100',
                'nama_voucher' => 'Voucher VIP Spesial',
                'type' => 'fixed',
                'value' => 100000,
                'min_purchase' => 500000,
                'max_discount' => null,
                'kuota' => 10,
                'kuota_terpakai' => 0,
                'start_date' => Carbon::now()->subDays(1),
                'end_date' => Carbon::now()->addDays(14),
                'is_active' => false,
                'user_id' => null,
                'kategori_id' => null,
                'produk_id' => null,
            ],
        ];

        foreach ($vouchers as $voucher) {
            Voucher::create($voucher);
        }

        $this->command->info('VoucherSeeder: ' . count($vouchers) . ' vouchers created.');
    }
}

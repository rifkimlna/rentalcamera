<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        echo "Starting database seeding...\n";

        try {
            // Cara resmi Laravel (AMAN untuk MySQL & PostgreSQL)
            Schema::disableForeignKeyConstraints();

            $this->call([
                UsersTableSeeder::class,
                BrandsTableSeeder::class,
                KategoriProdukTableSeeder::class,
                PaymentMethodsTableSeeder::class,
                SettingsTableSeeder::class,
                ProdukTableSeeder::class,
                StudioSeeder::class,
                LayananSeeder::class,
                VoucherSeeder::class,
            ]);

            echo "\n✅ All seeders completed successfully!\n";

        } catch (\Exception $e) {
            echo "\n❌ Seeding failed: " . $e->getMessage() . "\n";
            throw $e;
        } finally {
            // Wajib aktifkan kembali
            Schema::enableForeignKeyConstraints();
        }
    }
}

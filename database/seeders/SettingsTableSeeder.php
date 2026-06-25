<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'app_name',
                'value' => 'Sewa Kamera Pro',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Nama aplikasi',
            ],
            [
                'key' => 'app_description',
                'value' => 'Penyewaan kamera dan perlengkapan fotografi profesional',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Deskripsi aplikasi',
            ],
            [
                'key' => 'app_logo',
                'value' => 'logo.png',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Logo aplikasi',
            ],
            [
                'key' => 'app_favicon',
                'value' => 'favicon.ico',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Favicon aplikasi',
            ],
            [
                'key' => 'timezone',
                'value' => 'Asia/Jakarta',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Zona waktu aplikasi',
            ],
            [
                'key' => 'currency',
                'value' => 'IDR',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Mata uang',
            ],
            [
                'key' => 'currency_symbol',
                'value' => 'Rp',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Simbol mata uang',
            ],
            [
                'key' => 'date_format',
                'value' => 'd F Y',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Format tanggal',
            ],
            [
                'key' => 'time_format',
                'value' => 'H:i',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Format waktu',
            ],
            [
                'key' => 'items_per_page',
                'value' => '20',
                'type' => 'number',
                'group' => 'general',
                'description' => 'Jumlah item per halaman',
            ],

            // Company Information
            [
                'key' => 'company_name',
                'value' => 'Sewa Kamera Pro Indonesia',
                'type' => 'text',
                'group' => 'company',
                'description' => 'Nama perusahaan',
            ],
            [
                'key' => 'company_address',
                'value' => 'Jl. Contoh No. 123, Jakarta Pusat, DKI Jakarta',
                'type' => 'text',
                'group' => 'company',
                'description' => 'Alamat perusahaan',
            ],
            [
                'key' => 'company_phone',
                'value' => '+62 812 3456 7890',
                'type' => 'text',
                'group' => 'company',
                'description' => 'Telepon perusahaan',
            ],
            [
                'key' => 'company_email',
                'value' => 'info@sewakamerapro.com',
                'type' => 'text',
                'group' => 'company',
                'description' => 'Email perusahaan',
            ],
            [
                'key' => 'company_website',
                'value' => 'https://sewakamerapro.com',
                'type' => 'text',
                'group' => 'company',
                'description' => 'Website perusahaan',
            ],
            [
                'key' => 'company_working_hours',
                'value' => 'Senin - Minggu, 08:00 - 22:00 WIB',
                'type' => 'text',
                'group' => 'company',
                'description' => 'Jam kerja',
            ],

            // Rental Settings
            [
                'key' => 'min_rental_days',
                'value' => '1',
                'type' => 'number',
                'group' => 'rental',
                'description' => 'Minimal hari sewa',
            ],
            [
                'key' => 'max_rental_days',
                'value' => '30',
                'type' => 'number',
                'group' => 'rental',
                'description' => 'Maksimal hari sewa',
            ],
            [
                'key' => 'default_insurance_fee',
                'value' => '1',
                'type' => 'number',
                'group' => 'rental',
                'description' => 'Biaya asuransi default (%)',
            ],
            [
                'key' => 'late_return_penalty',
                'value' => '50',
                'type' => 'number',
                'group' => 'rental',
                'description' => 'Denda keterlambatan (%) per hari',
            ],
            [
                'key' => 'early_return_refund',
                'value' => '80',
                'type' => 'number',
                'group' => 'rental',
                'description' => 'Refund pengembalian awal (%)',
            ],
            [
                'key' => 'deposit_percentage',
                'value' => '20',
                'type' => 'number',
                'group' => 'rental',
                'description' => 'Persentase deposit dari total sewa',
            ],
            [
                'key' => 'deposit_refund_days',
                'value' => '3',
                'type' => 'number',
                'group' => 'rental',
                'description' => 'Hari pengembalian deposit setelah selesai sewa',
            ],

            // Shipping Settings
            [
                'key' => 'shipping_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'shipping',
                'description' => 'Aktifkan pengiriman',
            ],
            [
                'key' => 'shipping_fee',
                'value' => '20000',
                'type' => 'number',
                'group' => 'shipping',
                'description' => 'Biaya pengiriman default',
            ],
            [
                'key' => 'free_shipping_min_amount',
                'value' => '500000',
                'type' => 'number',
                'group' => 'shipping',
                'description' => 'Minimum belanja untuk gratis ongkir',
            ],
            [
                'key' => 'pickup_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'shipping',
                'description' => 'Aktifkan ambil di tempat',
            ],
            [
                'key' => 'same_day_delivery_cutoff',
                'value' => '14:00',
                'type' => 'text',
                'group' => 'shipping',
                'description' => 'Batas waktu untuk same-day delivery',
            ],
            [
                'key' => 'delivery_range_km',
                'value' => '50',
                'type' => 'number',
                'group' => 'shipping',
                'description' => 'Jangkauan pengiriman (km)',
            ],

            // Payment Settings
            [
                'key' => 'payment_expiry_hours',
                'value' => '24',
                'type' => 'number',
                'group' => 'payment',
                'description' => 'Batas waktu pembayaran (jam)',
            ],
            [
                'key' => 'auto_cancel_unpaid_hours',
                'value' => '24',
                'type' => 'number',
                'group' => 'payment',
                'description' => 'Otomatis batalkan pesanan belum bayar (jam)',
            ],
            [
                'key' => 'min_deposit_topup',
                'value' => '50000',
                'type' => 'number',
                'group' => 'payment',
                'description' => 'Minimum topup deposit',
            ],
            [
                'key' => 'max_deposit_topup',
                'value' => '10000000',
                'type' => 'number',
                'group' => 'payment',
                'description' => 'Maksimum topup deposit',
            ],
            [
                'key' => 'midtrans_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'payment',
                'description' => 'Aktifkan Midtrans',
            ],
            [
                'key' => 'midtrans_sandbox',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'payment',
                'description' => 'Gunakan Midtrans Sandbox',
            ],

            // Email Settings
            [
                'key' => 'mail_from_name',
                'value' => 'Sewa Kamera Pro',
                'type' => 'text',
                'group' => 'email',
                'description' => 'Nama pengirim email',
            ],
            [
                'key' => 'mail_from_address',
                'value' => 'noreply@sewakamerapro.com',
                'type' => 'text',
                'group' => 'email',
                'description' => 'Email pengirim',
            ],
            [
                'key' => 'mail_reply_to',
                'value' => 'support@sewakamerapro.com',
                'type' => 'text',
                'group' => 'email',
                'description' => 'Email untuk balasan',
            ],
            [
                'key' => 'send_order_confirmation',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'email',
                'description' => 'Kirim konfirmasi pemesanan',
            ],
            [
                'key' => 'send_payment_confirmation',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'email',
                'description' => 'Kirim konfirmasi pembayaran',
            ],
            [
                'key' => 'send_shipping_notification',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'email',
                'description' => 'Kirim notifikasi pengiriman',
            ],

            // Notification Settings
            [
                'key' => 'notify_new_order',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notification',
                'description' => 'Notifikasi pesanan baru',
            ],
            [
                'key' => 'notify_payment_received',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notification',
                'description' => 'Notifikasi pembayaran diterima',
            ],
            [
                'key' => 'notify_low_stock',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notification',
                'description' => 'Notifikasi stok rendah',
            ],
            [
                'key' => 'low_stock_threshold',
                'value' => '3',
                'type' => 'number',
                'group' => 'notification',
                'description' => 'Batas stok rendah',
            ],
            [
                'key' => 'sms_notifications',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'notification',
                'description' => 'Aktifkan notifikasi SMS',
            ],
            [
                'key' => 'whatsapp_notifications',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notification',
                'description' => 'Aktifkan notifikasi WhatsApp',
            ],

            // SEO Settings
            [
                'key' => 'meta_title',
                'value' => 'Sewa Kamera Pro - Penyewaan Kamera Profesional',
                'type' => 'text',
                'group' => 'seo',
                'description' => 'Meta title',
            ],
            [
                'key' => 'meta_description',
                'value' => 'Sewa kamera dan perlengkapan fotografi profesional dengan harga terjangkau. Tersedia berbagai jenis kamera, lensa, dan lighting.',
                'type' => 'text',
                'group' => 'seo',
                'description' => 'Meta description',
            ],
            [
                'key' => 'meta_keywords',
                'value' => 'sewa kamera, rental kamera, fotografi, videografi, perlengkapan kamera, sewa lensa',
                'type' => 'text',
                'group' => 'seo',
                'description' => 'Meta keywords',
            ],
            [
                'key' => 'og_image',
                'value' => 'og-image.jpg',
                'type' => 'text',
                'group' => 'seo',
                'description' => 'Open Graph image',
            ],
            [
                'key' => 'google_analytics_id',
                'value' => '',
                'type' => 'text',
                'group' => 'seo',
                'description' => 'Google Analytics ID',
            ],

            // Social Media
            [
                'key' => 'facebook_url',
                'value' => 'https://facebook.com/sewakamerapro',
                'type' => 'text',
                'group' => 'social',
                'description' => 'Facebook URL',
            ],
            [
                'key' => 'instagram_url',
                'value' => 'https://instagram.com/sewakamerapro',
                'type' => 'text',
                'group' => 'social',
                'description' => 'Instagram URL',
            ],
            [
                'key' => 'twitter_url',
                'value' => 'https://twitter.com/sewakamerapro',
                'type' => 'text',
                'group' => 'social',
                'description' => 'Twitter URL',
            ],
            [
                'key' => 'youtube_url',
                'value' => 'https://youtube.com/sewakamerapro',
                'type' => 'text',
                'group' => 'social',
                'description' => 'YouTube URL',
            ],
            [
                'key' => 'tiktok_url',
                'value' => 'https://tiktok.com/@sewakamerapro',
                'type' => 'text',
                'group' => 'social',
                'description' => 'TikTok URL',
            ],

            // Maintenance Mode
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'system',
                'description' => 'Mode maintenance',
            ],
            [
                'key' => 'maintenance_message',
                'value' => 'Sistem sedang dalam pemeliharaan. Silakan coba lagi nanti.',
                'type' => 'text',
                'group' => 'system',
                'description' => 'Pesan maintenance',
            ],
            [
                'key' => 'allow_admin_access',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'system',
                'description' => 'Izinkan akses admin saat maintenance',
            ],
        ];

        foreach ($settings as $setting) {
            // Gunakan updateOrCreate untuk menghindari duplicate entry
            Setting::updateOrCreate(
                ['key' => $setting['key']], // Cari berdasarkan key
                $setting // Data yang akan diupdate/dibuat
            );
        }

        $this->command->info('Settings seeded successfully. Total: ' . count($settings));
    }
}
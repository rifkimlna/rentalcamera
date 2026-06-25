<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Studio;
use App\Models\PaketStudio;
use App\Models\StudioBooking;
use App\Models\User;
use Illuminate\Support\Str;

class StudioSeeder extends Seeder
{
    public function run(): void
    {
        // Studio 1: Podcast Studio
        $podcast = Studio::create([
            'nama_studio' => 'Podcast Studio Pro',
            'slug' => 'podcast-studio-pro',
            'deskripsi' => 'Studio podcast profesional dengan akustik premium, cocok untuk rekaman podcast, voice over, dan konten audio. Dilengkapi peredam suara dan monitor berkualitas tinggi.',
            'fasilitas' => 'AC, Sound Proofing, Monitor Speaker, Mixer, Headphone, Meja Bundar, Kursi Ergonomis, Wi-Fi',
            'harga_per_jam' => 75000,
            'gambar_utama' => null,
            'status' => 'active',
        ]);

        // Studio 2: Photo Studio
        $photo = Studio::create([
            'nama_studio' => 'Photo Studio Master',
            'slug' => 'photo-studio-master',
            'deskripsi' => 'Studio foto profesional dengan pencahayaan lengkap dan berbagai backdrop. Cocok untuk foto produk, portrait, fashion, dan creative photography.',
            'fasilitas' => 'AC, Lighting Kit, Backdrop (Putih/Hitam/Hijau), Softbox, Reflector, Tripod, Wi-Fi, Rias',
            'harga_per_jam' => 100000,
            'gambar_utama' => null,
            'status' => 'active',
        ]);

        // Studio 3: Content Creator Room
        $content = Studio::create([
            'nama_studio' => 'Content Creator Room',
            'slug' => 'content-creator-room',
            'deskripsi' => 'Ruang serbaguna untuk content creator. Dilengkapi peralatan streaming, green screen, dan tata cahaya yang dapat disesuaikan untuk berbagai kebutuhan konten.',
            'fasilitas' => 'AC, Green Screen, Ring Light, Tripod, Monitor 4K, Wi-Fi, Meja Adjustable, Kursi Gaming',
            'harga_per_jam' => 60000,
            'gambar_utama' => null,
            'status' => 'active',
        ]);

        // Studio 4: Mini TV Studio
        $tv = Studio::create([
            'nama_studio' => 'Mini TV Studio',
            'slug' => 'mini-tv-studio',
            'deskripsi' => 'Studio televisi mini dengan multi-camera setup, cocok untuk wawancara, talkshow, dan siaran langsung. Dilengkapi dengan teleprompter dan mixing console.',
            'fasilitas' => 'AC, 3x Kamera, Teleprompter, Switcher, Monitor, Lighting Grid, Sound System, Wi-Fi, Green Room',
            'harga_per_jam' => 150000,
            'gambar_utama' => null,
            'status' => 'active',
        ]);

        // =====================
        // PAKET STUDIO
        // =====================

        // Paket untuk Podcast Studio
        PaketStudio::create([
            'studio_id' => $podcast->id,
            'nama_paket' => 'Podcast Basic',
            'slug' => 'podcast-basic',
            'deskripsi' => 'Paket dasar untuk rekaman podcast dengan 2 mic dan monitoring.',
            'harga' => 150000,
            'durasi_jam' => 2,
            'include_alat' => '2x Mic Condenser, 2x Headphone, Audio Interface, Mixer 4ch',
            'status' => 'active',
        ]);

        PaketStudio::create([
            'studio_id' => $podcast->id,
            'nama_paket' => 'Podcast Premium',
            'slug' => 'podcast-premium',
            'deskripsi' => 'Paket lengkap untuk produksi podcast dengan 4 mic, kamera, dan editor.',
            'harga' => 350000,
            'durasi_jam' => 4,
            'include_alat' => '4x Mic Condenser, 4x Headphone, Audio Interface, Mixer 8ch, Kamera Sony A6400, Lighting Kit',
            'status' => 'active',
        ]);

        PaketStudio::create([
            'studio_id' => $podcast->id,
            'nama_paket' => 'Podcast Full Day',
            'slug' => 'podcast-full-day',
            'deskripsi' => 'Sewa studio sehari penuh untuk produksi konten audio skala besar.',
            'harga' => 500000,
            'durasi_jam' => 8,
            'include_alat' => '6x Mic Condenser, 6x Headphone, Audio Interface, Mixer 12ch, 2x Kamera Sony A6400, Lighting Kit, Monitoring',
            'status' => 'active',
        ]);

        // Paket untuk Photo Studio
        PaketStudio::create([
            'studio_id' => $photo->id,
            'nama_paket' => 'Photo Basic',
            'slug' => 'photo-basic',
            'deskripsi' => 'Paket dasar untuk foto produk dan portrait dengan 1 backdrop.',
            'harga' => 180000,
            'durasi_jam' => 2,
            'include_alat' => 'Kamera Canon EOS R10, 2x Softbox, Backdrop, Reflector, Tethering Cable',
            'status' => 'active',
        ]);

        PaketStudio::create([
            'studio_id' => $photo->id,
            'nama_paket' => 'Photo Professional',
            'slug' => 'photo-professional',
            'deskripsi' => 'Paket profesional untuk fashion dan creative photography dengan 3 backdrop.',
            'harga' => 400000,
            'durasi_jam' => 4,
            'include_alat' => 'Kamera Sony A7IV, 3x Profoto Light, 3x Backdrop, Softbox, Beauty Dish, Reflector, C-Stand',
            'status' => 'active',
        ]);

        // Paket untuk Content Creator Room
        PaketStudio::create([
            'studio_id' => $content->id,
            'nama_paket' => 'Streaming Basic',
            'slug' => 'streaming-basic',
            'deskripsi' => 'Paket untuk live streaming dengan green screen dan ring light.',
            'harga' => 100000,
            'durasi_jam' => 2,
            'include_alat' => 'Ring Light, Green Screen, Webcam Logitech, Wireless Mic, Monitor 24inch',
            'status' => 'active',
        ]);

        PaketStudio::create([
            'studio_id' => $content->id,
            'nama_paket' => 'Content Creator Bundle',
            'slug' => 'content-creator-bundle',
            'deskripsi' => 'Paket lengkap untuk produksi konten YouTube/TikTok.',
            'harga' => 250000,
            'durasi_jam' => 4,
            'include_alat' => 'Kamera Sony ZV-E10, 2x Lighting Kit, Green Screen, Wireless Mic, Tripod, Monitor 4K',
            'status' => 'active',
        ]);

        // Paket untuk Mini TV Studio
        PaketStudio::create([
            'studio_id' => $tv->id,
            'nama_paket' => 'Talkshow Basic',
            'slug' => 'talkshow-basic',
            'deskripsi' => 'Paket untuk wawancara dan talkshow dengan 2 kamera.',
            'harga' => 400000,
            'durasi_jam' => 2,
            'include_alat' => '2x Kamera Sony FX3, Teleprompter, 4x Wireless Mic, Switcher, Monitor 32inch',
            'status' => 'active',
        ]);

        PaketStudio::create([
            'studio_id' => $tv->id,
            'nama_paket' => 'Talkshow Premium',
            'slug' => 'talkshow-premium',
            'deskripsi' => 'Paket lengkap talkshow dengan 3 kamera, green room, dan crew support.',
            'harga' => 800000,
            'durasi_jam' => 4,
            'include_alat' => '3x Kamera Sony FX6, Teleprompter, 6x Wireless Mic, Switcher Pro, Lighting Grid, 2x Monitor, Green Room',
            'status' => 'active',
        ]);

        // =====================
        // SAMPLE BOOKINGS
        // =====================
        $customer = User::where('role', 'customer')->first();

        if ($customer) {
            StudioBooking::create([
                'user_id' => $customer->id,
                'studio_id' => $podcast->id,
                'paket_studio_id' => null,
                'tipe_booking' => 'studio',
                'tanggal_booking' => now()->addDays(2)->format('Y-m-d'),
                'jam_mulai' => '09:00',
                'jam_selesai' => '11:00',
                'durasi_jam' => 2,
                'total_harga' => $podcast->harga_per_jam * 2,
                'catatan' => 'Untuk rekaman podcast episode mingguan.',
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);

            StudioBooking::create([
                'user_id' => $customer->id,
                'studio_id' => $photo->id,
                'paket_studio_id' => PaketStudio::where('slug', 'photo-basic')->value('id'),
                'tipe_booking' => 'paket',
                'tanggal_booking' => now()->addDays(5)->format('Y-m-d'),
                'jam_mulai' => '13:00',
                'jam_selesai' => '15:00',
                'durasi_jam' => 2,
                'total_harga' => 180000,
                'catatan' => 'Foto produk untuk katalog e-commerce.',
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            StudioBooking::create([
                'user_id' => $customer->id,
                'studio_id' => $content->id,
                'paket_studio_id' => null,
                'tipe_booking' => 'studio',
                'tanggal_booking' => now()->subDays(1)->format('Y-m-d'),
                'jam_mulai' => '10:00',
                'jam_selesai' => '14:00',
                'durasi_jam' => 4,
                'total_harga' => $content->harga_per_jam * 4,
                'catatan' => null,
                'status' => 'completed',
                'payment_status' => 'paid',
                'paid_at' => now()->subDays(1),
            ]);
        }

        $this->command->info('Studio, paket, dan sample booking berhasil dibuat!');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Layanan;
use App\Models\PaketLayanan;
use App\Models\LayananBooking;
use App\Models\User;
use Illuminate\Support\Str;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        $prewedding = Layanan::create([
            'nama_layanan' => 'Prewedding',
            'slug' => 'prewedding',
            'deskripsi' => 'Abadikan momen spesial sebelum hari pernikahan dengan sesi prewedding profesional. Kami menyediakan fotografer dan videografer berpengalaman dengan berbagai konsep menarik.',
            'kategori' => 'Prewedding',
            'harga_mulai' => 1500000,
            'ikon' => '💑',
            'gambar_utama' => null,
            'status' => 'active',
        ]);

        $wedding = Layanan::create([
            'nama_layanan' => 'Wedding',
            'slug' => 'wedding',
            'deskripsi' => 'Dokumentasi pernikahan lengkap dari persiapan hingga resepsi. Tim profesional kami akan mengabadikan setiap momen berharga di hari bahagia Anda.',
            'kategori' => 'Wedding',
            'harga_mulai' => 3500000,
            'ikon' => '💒',
            'gambar_utama' => null,
            'status' => 'active',
        ]);

        $liveStreaming = Layanan::create([
            'nama_layanan' => 'Live Streaming',
            'slug' => 'live-streaming',
            'deskripsi' => 'Layanan siaran langsung untuk berbagai acara seperti seminar, konser, pernikahan, dan event corporate. Didukung peralatan broadcasting profesional.',
            'kategori' => 'Live Streaming',
            'harga_mulai' => 2000000,
            'ikon' => '📡',
            'gambar_utama' => null,
            'status' => 'active',
        ]);

        $videografi = Layanan::create([
            'nama_layanan' => 'Videografi',
            'slug' => 'videografi',
            'deskripsi' => 'Jasa pembuatan video profesional untuk berbagai kebutuhan, mulai dari company profile, video promosi, dokumentasi event, hingga konten media sosial.',
            'kategori' => 'Videografi',
            'harga_mulai' => 1000000,
            'ikon' => '🎥',
            'gambar_utama' => null,
            'status' => 'active',
        ]);

        $dokumentasi = Layanan::create([
            'nama_layanan' => 'Dokumentasi Event',
            'slug' => 'dokumentasi-event',
            'deskripsi' => 'Dokumentasi foto dan video untuk berbagai event seperti ulang tahun, gathering, wisuda, seminar, dan acara corporate lainnya.',
            'kategori' => 'Dokumentasi Event',
            'harga_mulai' => 800000,
            'ikon' => '📸',
            'gambar_utama' => null,
            'status' => 'active',
        ]);

        $fotobooth = Layanan::create([
            'nama_layanan' => 'Fotobooth',
            'slug' => 'fotobooth',
            'deskripsi' => 'Layanan fotobooth dengan berbagai properti lucu dan backdrop menarik. Cocok untuk memeriahkan acara Anda. Hasil foto bisa langsung dicetak.',
            'kategori' => 'Fotobooth',
            'harga_mulai' => 500000,
            'ikon' => '📷',
            'gambar_utama' => null,
            'status' => 'active',
        ]);

        $drone = Layanan::create([
            'nama_layanan' => 'Drone',
            'slug' => 'drone',
            'deskripsi' => 'Jasa pengambilan gambar dan video dari udara menggunakan drone berkualitas tinggi. Cocok untuk dokumentasi properti, event outdoor, dan cinematic shoot.',
            'kategori' => 'Drone',
            'harga_mulai' => 1200000,
            'ikon' => '🛸',
            'gambar_utama' => null,
            'status' => 'active',
        ]);

        // Paket untuk Prewedding
        PaketLayanan::create([
            'layanan_id' => $prewedding->id,
            'nama_paket' => 'Prewedding Basic',
            'slug' => 'prewedding-basic',
            'deskripsi' => 'Sesi prewedding 1 lokasi dengan 1 konsep.',
            'harga' => 1500000,
            'durasi_jam' => 3,
            'include' => '1 Lokasi, 1 Konsep, 20 Foto Editan, 1 Album Cetak, 1 Videografer',
            'status' => 'active',
        ]);

        PaketLayanan::create([
            'layanan_id' => $prewedding->id,
            'nama_paket' => 'Prewedding Premium',
            'slug' => 'prewedding-premium',
            'deskripsi' => 'Sesi prewedding 2 lokasi dengan 2 konsep dan videografi.',
            'harga' => 3500000,
            'durasi_jam' => 6,
            'include' => '2 Lokasi, 2 Konsep, 40 Foto Editan, 1 Album Premium, Video Cinematic 3 Menit, 2 Fotografer, 1 Videografer',
            'status' => 'active',
        ]);

        PaketLayanan::create([
            'layanan_id' => $prewedding->id,
            'nama_paket' => 'Prewedding Exclusive',
            'slug' => 'prewedding-exclusive',
            'deskripsi' => 'Sesi prewedding full day dengan 3 lokasi dan tim lengkap.',
            'harga' => 6500000,
            'durasi_jam' => 10,
            'include' => '3 Lokasi, 3 Konsep, 60 Foto Editan, 2 Album Premium, Video Cinematic 5 Menit, Drone, 2 Fotografer, 2 Videografer, MUA, Sewa Busana',
            'status' => 'active',
        ]);

        // Paket untuk Wedding
        PaketLayanan::create([
            'layanan_id' => $wedding->id,
            'nama_paket' => 'Wedding Basic',
            'slug' => 'wedding-basic',
            'deskripsi' => 'Dokumentasi wedding 1 kamera, coverage 6 jam.',
            'harga' => 3500000,
            'durasi_jam' => 6,
            'include' => '1 Fotografer, 1 Videografer, 100 Foto Editan, Video Highlight 3 Menit, Album Cetak',
            'status' => 'active',
        ]);

        PaketLayanan::create([
            'layanan_id' => $wedding->id,
            'nama_paket' => 'Wedding Premium',
            'slug' => 'wedding-premium',
            'deskripsi' => 'Dokumentasi wedding 2 kamera, coverage 10 jam.',
            'harga' => 6500000,
            'durasi_jam' => 10,
            'include' => '2 Fotografer, 2 Videografer, 200 Foto Editan, Video Highlight 5 Menit, Video Full Dokumentasi, 2 Album Premium, Cetak Foto',
            'status' => 'active',
        ]);

        PaketLayanan::create([
            'layanan_id' => $wedding->id,
            'nama_paket' => 'Wedding Exclusive',
            'slug' => 'wedding-exclusive',
            'deskripsi' => 'Dokumentasi wedding lengkap dengan drone, 3 kamera, dan tim besar.',
            'harga' => 12000000,
            'durasi_jam' => 12,
            'include' => '3 Fotografer, 3 Videografer, 400+ Foto Editan, Video Cinematic 7 Menit, Video Full Dokumentasi, Drone, 3 Album Premium, Cetak Foto, Fotobooth, Album Orang Tua',
            'status' => 'active',
        ]);

        // Paket untuk Live Streaming
        PaketLayanan::create([
            'layanan_id' => $liveStreaming->id,
            'nama_paket' => 'Streaming Basic',
            'slug' => 'streaming-basic',
            'deskripsi' => 'Live streaming 1 kamera untuk acara sederhana.',
            'harga' => 2000000,
            'durasi_jam' => 3,
            'include' => '1 Kamera, 1 Operator, Platform YouTube/Zoom, Audio Mixing Dasar',
            'status' => 'active',
        ]);

        PaketLayanan::create([
            'layanan_id' => $liveStreaming->id,
            'nama_paket' => 'Streaming Professional',
            'slug' => 'streaming-professional',
            'deskripsi' => 'Live streaming multi-camera dengan production quality tinggi.',
            'harga' => 5000000,
            'durasi_jam' => 6,
            'include' => '3 Kamera, 2 Operator, Switcher, Audio Professional, Grafis & Overlay, Rekaman Hard Drive, Platform Multi-Streaming',
            'status' => 'active',
        ]);

        // Paket untuk Videografi
        PaketLayanan::create([
            'layanan_id' => $videografi->id,
            'nama_paket' => 'Video Basic',
            'slug' => 'video-basic',
            'deskripsi' => 'Pembuatan video singkat untuk media sosial atau profil.',
            'harga' => 1000000,
            'durasi_jam' => 2,
            'include' => '1 Videografer, 1 Kamera, Editing Sederhana, Durasi 1-2 Menit, Musik Background',
            'status' => 'active',
        ]);

        PaketLayanan::create([
            'layanan_id' => $videografi->id,
            'nama_paket' => 'Video Professional',
            'slug' => 'video-professional',
            'deskripsi' => 'Pembuatan video profesional dengan konsep dan editing lengkap.',
            'harga' => 3500000,
            'durasi_jam' => 6,
            'include' => '1 Videografer, 1 Asisten, 2 Kamera, Drone, Editing Profesional, Color Grading, Durasi 3-5 Menit, Revisi 2x',
            'status' => 'active',
        ]);

        // Paket untuk Dokumentasi Event
        PaketLayanan::create([
            'layanan_id' => $dokumentasi->id,
            'nama_paket' => 'Dokumentasi Basic',
            'slug' => 'dokumentasi-basic',
            'deskripsi' => 'Dokumentasi foto event sederhana, coverage 3 jam.',
            'harga' => 800000,
            'durasi_jam' => 3,
            'include' => '1 Fotografer, 50 Foto Editan, Gallery Online',
            'status' => 'active',
        ]);

        PaketLayanan::create([
            'layanan_id' => $dokumentasi->id,
            'nama_paket' => 'Dokumentasi Lengkap',
            'slug' => 'dokumentasi-lengkap',
            'deskripsi' => 'Dokumentasi foto dan video event lengkap.',
            'harga' => 2500000,
            'durasi_jam' => 6,
            'include' => '1 Fotografer, 1 Videografer, 100 Foto Editan, Video Highlight 3 Menit, Gallery Online, Cetak Foto',
            'status' => 'active',
        ]);

        // Paket untuk Fotobooth
        PaketLayanan::create([
            'layanan_id' => $fotobooth->id,
            'nama_paket' => 'Fotobooth Basic',
            'slug' => 'fotobooth-basic',
            'deskripsi' => 'Fotobooth sederhana dengan 1 backdrop dan properti dasar.',
            'harga' => 500000,
            'durasi_jam' => 3,
            'include' => '1 Backdrop, Kamera DSLR, Cetak 2R Unlimited, Properti Dasar, 1 Operator',
            'status' => 'active',
        ]);

        PaketLayanan::create([
            'layanan_id' => $fotobooth->id,
            'nama_paket' => 'Fotobooth Premium',
            'slug' => 'fotobooth-premium',
            'deskripsi' => 'Fotobooth lengkap dengan 2 backdrop,道具, dan frame custom.',
            'harga' => 1200000,
            'durasi_jam' => 5,
            'include' => '2 Backdrop, Kamera Mirrorless, Cetak 2R & 4R Unlimited, Properti Lengkap, Frame Custom, Buku Tamu, 2 Operator, Album Kenangan',
            'status' => 'active',
        ]);

        // Paket untuk Drone
        PaketLayanan::create([
            'layanan_id' => $drone->id,
            'nama_paket' => 'Drone Basic',
            'slug' => 'drone-basic',
            'deskripsi' => 'Pengambilan gambar udara durasi pendek untuk dokumentasi.',
            'harga' => 1200000,
            'durasi_jam' => 2,
            'include' => '1 Pilot Drone, Drone DJI, 20 Foto Aerial, Video 4K 10 Menit, Editing Dasar',
            'status' => 'active',
        ]);

        PaketLayanan::create([
            'layanan_id' => $drone->id,
            'nama_paket' => 'Drone Professional',
            'slug' => 'drone-professional',
            'deskripsi' => 'Pengambilan gambar udara lengkap dengan editing profesional.',
            'harga' => 3000000,
            'durasi_jam' => 4,
            'include' => '1 Pilot Drone, 1 Asisten, Drone DJI Pro, 50+ Foto Aerial, Video 4K Cinematic 15 Menit, Editing Profesional, Color Grading',
            'status' => 'active',
        ]);

        // Sample Bookings
        $customer = User::where('role', 'customer')->first();

        if ($customer) {
            LayananBooking::create([
                'user_id' => $customer->id,
                'layanan_id' => $prewedding->id,
                'paket_layanan_id' => PaketLayanan::where('slug', 'prewedding-premium')->value('id'),
                'tipe_booking' => 'paket',
                'tanggal_booking' => now()->addDays(7)->format('Y-m-d'),
                'jam_mulai' => '08:00',
                'jam_selesai' => '14:00',
                'durasi_jam' => 6,
                'total_harga' => 3500000,
                'admin_fee' => 0,
                'grand_total' => 3500000,
                'catatan' => 'Lokasi di Taman Kota dan Gedung Serbaguna.',
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);

            LayananBooking::create([
                'user_id' => $customer->id,
                'layanan_id' => $liveStreaming->id,
                'paket_layanan_id' => PaketLayanan::where('slug', 'streaming-professional')->value('id'),
                'tipe_booking' => 'paket',
                'tanggal_booking' => now()->addDays(14)->format('Y-m-d'),
                'jam_mulai' => '09:00',
                'jam_selesai' => '15:00',
                'durasi_jam' => 6,
                'total_harga' => 5000000,
                'admin_fee' => 0,
                'grand_total' => 5000000,
                'catatan' => null,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            LayananBooking::create([
                'user_id' => $customer->id,
                'layanan_id' => $fotobooth->id,
                'paket_layanan_id' => PaketLayanan::where('slug', 'fotobooth-premium')->value('id'),
                'tipe_booking' => 'paket',
                'tanggal_booking' => now()->subDays(2)->format('Y-m-d'),
                'jam_mulai' => '10:00',
                'jam_selesai' => '15:00',
                'durasi_jam' => 5,
                'total_harga' => 1200000,
                'admin_fee' => 0,
                'grand_total' => 1200000,
                'catatan' => 'Acara ulang tahun anak.',
                'status' => 'completed',
                'payment_status' => 'paid',
                'paid_at' => now()->subDays(2),
            ]);
        }

        $this->command->info('Layanan, paket, dan sample booking berhasil dibuat!');
    }
}

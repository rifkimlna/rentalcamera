@extends('layouts.app')

@section('title', 'Stekpro — Sewa Equipment, Studio & Layanan Kreatif Profesional')
@section('meta-description', 'Sewa kamera, lensa, lighting, studio foto, dan jasa videografer profesional. Gear terawat, harga transparan, booking cepat.')

@section('content')
@php
    // Semua katalog equipment terpusat di halaman customer (tamu boleh lihat-lihat, sewa wajib login)
    $eqIndex = route('customer.products.index');
    $stIndex = route('customer.studio.index');
    $lyIndex = route('customer.layanan.index');

    $statProducts = $stats['products'] ?? 500;
    $statCustomers = $stats['customers'] ?? 2000;
    $statTransactions = $stats['transactions'] ?? 5000;

    // Fallback dummy bila database masih kosong (agar tampilan tetap hidup)
    $dummyEquipment = [
        ['nama' => 'Sony A7 III + 28-70mm', 'kat' => 'Kamera', 'harga' => 250000, 'satuan' => 'hari', 'rating' => 4.9, 'ulasan' => 132, 'stok' => true],
        ['nama' => 'Canon EOS R6 Mark II', 'kat' => 'Kamera', 'harga' => 350000, 'satuan' => 'hari', 'rating' => 4.9, 'ulasan' => 98, 'stok' => true],
        ['nama' => 'Sony FE 50mm f/1.4 GM', 'kat' => 'Lensa', 'harga' => 150000, 'satuan' => 'hari', 'rating' => 4.8, 'ulasan' => 76, 'stok' => true],
        ['nama' => 'Godox SL150 II + Softbox', 'kat' => 'Lighting', 'harga' => 120000, 'satuan' => 'hari', 'rating' => 4.8, 'ulasan' => 64, 'stok' => true],
        ['nama' => 'DJI RS 3 Pro Stabilizer', 'kat' => 'Stabilizer', 'harga' => 200000, 'satuan' => 'hari', 'rating' => 4.9, 'ulasan' => 88, 'stok' => true],
        ['nama' => 'Rode Wireless GO II', 'kat' => 'Audio', 'harga' => 80000, 'satuan' => 'hari', 'rating' => 4.7, 'ulasan' => 112, 'stok' => true],
    ];
    $dummyStudio = [
        ['nama' => 'Studio A — Cyclorama Putih', 'fasilitas' => ['Cyclorama 6x7m', 'AC + Ruang Ganti', 'Lighting Kit'], 'harga' => 150000, 'satuan' => 'jam', 'rating' => 4.9, 'ulasan' => 210],
        ['nama' => 'Studio B — Podcast & Live', 'fasilitas' => ['4 Mic + Mixer', 'Backdrop Akustik', 'Kamera 3 Angle'], 'harga' => 175000, 'satuan' => 'jam', 'rating' => 4.8, 'ulasan' => 96],
        ['nama' => 'Studio C — Foto Produk', 'fasilitas' => ['Meja Still Life', 'Godox 3 Titik', 'Properti Lengkap'], 'harga' => 100000, 'satuan' => 'jam', 'rating' => 4.9, 'ulasan' => 143],
        ['nama' => 'Outdoor — Rooftop Golden Hour', 'fasilitas' => ['Area 120m²', 'Sunset View', 'Tenda + Kursi'], 'harga' => 125000, 'satuan' => 'jam', 'rating' => 4.7, 'ulasan' => 58],
    ];
    $dummyLayanan = [
        ['nama' => 'Videografer Event', 'kat' => 'Operator', 'harga' => 750000, 'satuan' => 'acara', 'rating' => 4.9, 'ulasan' => 87],
        ['nama' => 'Foto Wedding + Album', 'kat' => 'Fotografer', 'harga' => 2500000, 'satuan' => 'paket', 'rating' => 5.0, 'ulasan' => 64],
        ['nama' => 'Crew Studio (2 Orang)', 'kat' => 'Crew', 'harga' => 400000, 'satuan' => 'shift', 'rating' => 4.8, 'ulasan' => 52],
        ['nama' => 'Editing Cinematic', 'kat' => 'Post-Pro', 'harga' => 500000, 'satuan' => 'project', 'rating' => 4.9, 'ulasan' => 73],
    ];
    $dummyTesti = [
        ['nama' => 'Budi Santoso', 'peran' => 'Fotografer Wedding', 'teks' => 'Kamera selalu dalam kondisi prima, baterai full, memori bersih. Booking jam 9 pagi, jam 11 sudah bisa shooting. Langganan 2 tahun!', 'rating' => 5],
        ['nama' => 'Sari Dewi', 'peran' => 'Content Creator', 'teks' => 'Sewa lighting + studio podcast untuk 20 episode. Tim-nya helpful banget, ruangan bersih dan akustiknya enak. Harga transparan, no hidden fee.', 'rating' => 5],
        ['nama' => 'Rizky Pratama', 'peran' => 'Videografer Freelance', 'teks' => 'DJI RS 3 Pro + A7S III ready semua. Pernah unit bermasalah langsung diganti unit cadangan tanpa drama. Recommended!', 'rating' => 5],
        ['nama' => 'Nadia Putri', 'peran' => 'Owner Brand Skincare', 'teks' => 'Pakai layanan foto produk + studio C. Hasilnya clean banget, cocok untuk katalog marketplace. Revisi juga cepat.', 'rating' => 5],
        ['nama' => 'Andi Wijaya', 'peran' => 'Event Organizer', 'teks' => 'Sewa 3 kamera + operator untuk seminar 500 peserta. Koordinasi via WA responsif, crew datang 1 jam lebih awal. Profesional!', 'rating' => 5],
        ['nama' => 'Mega Lestari', 'peran' => 'Mahasiswa Film', 'teks' => 'Harga ramah kantong buat tugas akhir. Dapat briefing cara pakai alat juga. Sangat membantu pemula seperti aku.', 'rating' => 4],
    ];
    $faqs = $faqs ?? [
        ['question' => 'Berapa lama proses penyewaan?', 'answer' => 'Proses penyewaan dapat diselesaikan dalam 1–2 jam setelah pembayaran berhasil. Pengiriman dilakukan sesuai jadwal yang Anda pilih.'],
        ['question' => 'Bagaimana cara pengembalian alat?', 'answer' => 'Kembalikan langsung ke store kami atau gunakan layanan penjemputan. Pastikan unit lengkap dan dalam kondisi baik.'],
        ['question' => 'Boleh memperpanjang masa sewa?', 'answer' => 'Bisa. Hubungi CS via WhatsApp sebelum masa sewa berakhir, perpanjangan dikenakan tarif normal per hari/jam.'],
        ['question' => 'Bagaimana jika alat rusak atau hilang?', 'answer' => 'Biaya perbaikan/penggantian mengikuti tingkat kerusakan sesuai SOP. Kami sarankan mengambil opsi perlindungan sewa saat checkout.'],
    ];
@endphp

<div x-data="{
    activeTab: 'equipment',
    scrollTesti(dir) {
        const el = document.getElementById('testiTrack');
        if (el) el.scrollBy({ left: dir * Math.min(el.clientWidth * 0.85, 380), behavior: 'smooth' });
    }
}">

{{-- ============ 1. HERO : minimalis hitam-putih ala animated-hero ============ --}}
<section class="relative overflow-hidden bg-white" x-data="{ titleNumber: 0, titles: ['profesional', 'terawat', 'cepat', 'transparan', 'terpercaya'] }" x-init="setInterval(() => { titleNumber = (titleNumber + 1) % titles.length }, 2000)">
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12 sm:pt-24 lg:pt-32 lg:pb-16">
        <div class="flex gap-8 items-center justify-center flex-col text-center">
            <div>
                <a href="#katalog" class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border border-neutral-200 text-xs font-medium text-neutral-600 hover:border-black hover:text-black transition-all">
                    500+ unit gear & 3 studio siap dibooking
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12"/></svg>
                </a>
            </div>
            <div class="flex gap-4 flex-col items-center">
                <h1 class="text-5xl md:text-7xl max-w-2xl tracking-tighter font-normal text-black">
                    Sewa Equipment
                    <span class="relative flex w-full justify-center overflow-hidden text-center md:pb-4 md:pt-1 min-h-[1.2em]">
                        <template x-for="(title, index) in titles" :key="index">
                            <span x-show="titleNumber === index"
                                  x-transition:enter="transition ease-out duration-300"
                                  x-transition:enter-start="opacity-0 translate-y-full"
                                  x-transition:enter-end="opacity-100 translate-y-0"
                                  class="absolute font-semibold text-black whitespace-nowrap"
                                  x-text="title"></span>
                        </template>
                        <span class="invisible font-semibold whitespace-nowrap" x-text="titles[0]">&nbsp;</span>
                    </span>
                </h1>

                <p class="text-lg md:text-xl leading-relaxed tracking-tight text-neutral-500 max-w-2xl text-center">
                    Kamera, lensa, lighting, studio foto, sampai videografer semua terawat, siap pakai, dan bisa dibooking dalam hitungan menit.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ $eqIndex }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-full bg-black hover:bg-neutral-800 text-white text-sm font-medium transition-all">
                    Booking Sekarang
                </a>
                <a href="https://wa.me/6281234567890?text=Halo%20Stekpro,%20saya%20mau%20konsultasi"
                   target="_blank" rel="noopener"
                   class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-full bg-white border border-neutral-300 text-black text-sm font-medium hover:border-black hover:bg-neutral-50 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    Hubungi Kami
                </a>
            </div>

            {{-- Stats menyatu dengan hero — tanpa garis/border --}}
            <div class="mt-14 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div><p class="text-2xl sm:text-3xl font-semibold tracking-tight text-black">{{ number_format($statProducts) }}+</p><p class="text-[13px] text-neutral-500 mt-1">Unit Equipment</p></div>
                <div><p class="text-2xl sm:text-3xl font-semibold tracking-tight text-black">{{ number_format($statCustomers) }}+</p><p class="text-[13px] text-neutral-500 mt-1">Kreator Percaya Kami</p></div>
                <div><p class="text-2xl sm:text-3xl font-semibold tracking-tight text-black">{{ number_format($statTransactions) }}+</p><p class="text-[13px] text-neutral-500 mt-1">Booking Selesai</p></div>
                <div><p class="text-2xl sm:text-3xl font-semibold tracking-tight text-black">98%</p><p class="text-[13px] text-neutral-500 mt-1">Tingkat Kepuasan</p></div>
            </div>
    </div>
</section>

{{-- ============ 2. KATALOG UTAMA : interactive tabs ============ --}}
<section id="katalog" class="scroll-mt-24 bg-[#FAFAFA] py-14 sm:py-20">
    <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-6">
        <div class="text-center max-w-2xl mx-auto mb-8">
            <span class="badge-brand mb-3">Katalog Utama</span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-slate-900 mb-3">Satu platform untuk semua kebutuhan produksi</h2>
            <p class="text-[15px] text-slate-500">Pilih tab di bawah untuk menjelajahi equipment, studio, dan layanan kreatif. Semua harga transparan per jam / hari.</p>
        </div>

        {{-- Tab bar : glass pill --}}
        <div class="flex justify-center mb-8">
            <div class="inline-flex p-1.5 rounded-full glass-card shadow-sm gap-1 max-w-full overflow-x-auto no-scrollbar" role="tablist">
                <button @click="activeTab = 'equipment'" :class="activeTab === 'equipment' ? 'bg-[#111111] text-white shadow-md' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100'" class="tab-pill inline-flex items-center gap-2 px-5 sm:px-7 py-2.5 rounded-full text-sm font-bold whitespace-nowrap" role="tab">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Equipment
                </button>
                <button @click="activeTab = 'studio'" :class="activeTab === 'studio' ? 'bg-[#111111] text-white shadow-md' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100'" class="tab-pill inline-flex items-center gap-2 px-5 sm:px-7 py-2.5 rounded-full text-sm font-bold whitespace-nowrap" role="tab">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5h1v5m-1 0h2"/></svg>
                    Studio
                </button>
                <button @click="activeTab = 'layanan'" :class="activeTab === 'layanan' ? 'bg-[#111111] text-white shadow-md' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100'" class="tab-pill inline-flex items-center gap-2 px-5 sm:px-7 py-2.5 rounded-full text-sm font-bold whitespace-nowrap" role="tab">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Layanan
                </button>
            </div>
        </div>

        {{-- PANEL : EQUIPMENT --}}
        <div x-show="activeTab === 'equipment'" x-transition.opacity.duration.250ms class="tab-panel-enter">
            <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2 sm:gap-3 lg:gap-5">
                @forelse(($featuredProducts ?? collect())->take(8) as $p)
                    <article class="catalog-card group min-w-0">
                        <div class="aspect-[4/3] overflow-hidden bg-neutral-100">
                            @if(!empty($p->gambar_utama))
                                <img src="{{ asset('storage/' . $p->gambar_utama) }}" alt="{{ $p->nama_produk }}" class="w-full h-full object-cover block" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-neutral-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-2 sm:p-3">
                            <p class="text-[11px] font-medium uppercase tracking-wider text-neutral-400 mb-1">{{ $p->kategori->nama_kategori ?? 'Equipment' }}</p>
                            <h3 class="font-semibold text-black text-[13px] sm:text-[15px] leading-snug line-clamp-1">{{ $p->nama_produk }}</h3>
                            <p class="text-[11px] sm:text-xs text-neutral-400 mt-1">{{ number_format((float)($p->rating ?? 0), 1) }} · {{ $p->jumlah_ulasan ?? 0 }} ulasan · Stok {{ $p->stok_tersedia ?? '-' }}</p>
                            <div class="flex flex-col gap-1.5 sm:flex-row sm:items-center sm:justify-between mt-2 sm:mt-3 pt-2 sm:pt-3 border-t border-neutral-100">
                                <p class="text-sm sm:text-[15px] font-semibold text-black">Rp {{ number_format($p->harga_per_hari, 0, ',', '.') }}<span class="text-xs font-normal text-neutral-400">/hari</span></p>
                                @php $pUrl = !empty($p->slug) ? route('customer.products.show', $p->slug) : $eqIndex; @endphp
                                <a href="{{ $pUrl }}" class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 rounded-full bg-black hover:bg-neutral-800 text-white text-xs sm:text-[13px] font-medium transition-colors w-full sm:w-auto justify-center shrink-0">Sewa</a>
                            </div>
                        </div>
                    </article>
                @empty
                    @foreach($dummyEquipment as $d)
                        <article class="catalog-card group min-w-0">
                            <div class="aspect-[4/3] overflow-hidden bg-neutral-100 flex items-center justify-center">
                                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-neutral-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div class="p-2 sm:p-3">
                                <p class="text-[11px] font-medium uppercase tracking-wider text-neutral-400 mb-1">{{ $d['kat'] }}</p>
                                <h3 class="font-semibold text-black text-[13px] sm:text-[15px] leading-snug line-clamp-1">{{ $d['nama'] }}</h3>
                                <p class="text-[11px] sm:text-xs text-neutral-400 mt-1">{{ $d['rating'] }} · {{ $d['ulasan'] }} ulasan · Tersedia</p>
                                <div class="flex flex-col gap-1.5 sm:flex-row sm:items-center sm:justify-between mt-2 sm:mt-3 pt-2 sm:pt-3 border-t border-neutral-100">
                                    <p class="text-sm sm:text-[15px] font-semibold text-black">Rp {{ number_format($d['harga'], 0, ',', '.') }}<span class="text-xs font-normal text-neutral-400">/{{ $d['satuan'] }}</span></p>
                                    <a href="{{ $eqIndex }}" class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 rounded-full bg-black hover:bg-neutral-800 text-white text-xs sm:text-[13px] font-medium transition-colors w-full sm:w-auto justify-center shrink-0">Sewa</a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                @endforelse
            </div>
            <div class="text-center mt-8">
                <a href="{{ $eqIndex }}" class="inline-flex items-center gap-2 px-7 py-3 rounded-full bg-white border border-slate-200 text-sm font-bold text-slate-700 hover:border-[#111111]/50 hover:text-[#111111] hover:shadow-lg hover:-translate-y-px transition-all">Lihat Semua Equipment
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12"/></svg>
                </a>
            </div>
        </div>

        {{-- PANEL : STUDIO --}}
        <div x-show="activeTab === 'studio'" x-cloak x-transition.opacity.duration.250ms class="tab-panel-enter">
            <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2 sm:gap-3 lg:gap-5">
                @forelse(($studios ?? collect().take(0)) as $s)
                    <article class="catalog-card group min-w-0">
                        <div class="aspect-[4/3] overflow-hidden bg-neutral-100">
                            @if(!empty($s->gambar_utama))
                                <img src="{{ asset('storage/' . $s->gambar_utama) }}" alt="{{ $s->nama_studio }}" class="w-full h-full object-cover block" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-neutral-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5h1v5m-1 0h2"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-2 sm:p-3">
                            <p class="text-[11px] font-medium uppercase tracking-wider text-neutral-400 mb-1">Studio</p>
                            <h3 class="font-semibold text-black text-[13px] sm:text-[15px] leading-snug line-clamp-1">{{ $s->nama_studio }}</h3>
                            @php $fasList = array_slice(array_filter((array)($s->fasilitas ?? []), 'is_string'), 0, 2); @endphp
                            <p class="text-[11px] sm:text-xs text-neutral-400 mt-1 line-clamp-1">{{ number_format((float)($s->rating ?? 0), 1) }} · {{ $s->jumlah_ulasan ?? 0 }} ulasan{{ count($fasList) ? ' · ' . implode(' · ', array_map(fn($f) => Str::limit($f, 20), $fasList)) : '' }}</p>
                            <div class="flex flex-col gap-1.5 sm:flex-row sm:items-center sm:justify-between mt-2 sm:mt-3 pt-2 sm:pt-3 border-t border-neutral-100">
                                <p class="text-sm sm:text-[15px] font-semibold text-black">Rp {{ number_format($s->harga_per_jam, 0, ',', '.') }}<span class="text-xs font-normal text-neutral-400">/jam</span></p>
                                {{-- Tamu boleh lihat detail studio; booking dikunci wajib login di halaman detail --}}
                                @php $sUrl = !empty($s->slug) ? route('customer.studio.show', $s->slug) : $stIndex; @endphp
                                <a href="{{ $sUrl }}" class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 rounded-full bg-black hover:bg-neutral-800 text-white text-xs sm:text-[13px] font-medium transition-colors w-full sm:w-auto justify-center shrink-0">Sewa</a>
                            </div>
                        </div>
                    </article>
                @empty
                    @foreach($dummyStudio as $d)
                        <article class="catalog-card group min-w-0">
                            <div class="aspect-[4/3] overflow-hidden bg-neutral-100 flex items-center justify-center">
                                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-neutral-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5h1v5m-1 0h2"/></svg>
                            </div>
                            <div class="p-2 sm:p-3">
                                <p class="text-[11px] font-medium uppercase tracking-wider text-neutral-400 mb-1">Studio</p>
                                <h3 class="font-semibold text-black text-[13px] sm:text-[15px] leading-snug line-clamp-1">{{ $d['nama'] }}</h3>
                                <p class="text-[11px] sm:text-xs text-neutral-400 mt-1 line-clamp-1">{{ $d['rating'] }} · {{ $d['ulasan'] }} ulasan · {{ implode(' · ', array_slice($d['fasilitas'], 0, 2)) }}</p>
                                <div class="flex flex-col gap-1.5 sm:flex-row sm:items-center sm:justify-between mt-2 sm:mt-3 pt-2 sm:pt-3 border-t border-neutral-100">
                                    <p class="text-sm sm:text-[15px] font-semibold text-black">Rp {{ number_format($d['harga'], 0, ',', '.') }}<span class="text-xs font-normal text-neutral-400">/{{ $d['satuan'] }}</span></p>
                                    <a href="{{ $stIndex }}" class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 rounded-full bg-black hover:bg-neutral-800 text-white text-xs sm:text-[13px] font-medium transition-colors w-full sm:w-auto justify-center shrink-0">Sewa</a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                @endforelse
            </div>
            <div class="text-center mt-8">
                <a href="{{ $stIndex }}" class="inline-flex items-center gap-2 px-7 py-3 rounded-full bg-white border border-slate-200 text-sm font-bold text-slate-700 hover:border-[#111111]/50 hover:text-[#111111] hover:shadow-lg hover:-translate-y-px transition-all">Lihat Semua Studio
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12"/></svg>
                </a>
            </div>
        </div>

        {{-- PANEL : LAYANAN --}}
        <div x-show="activeTab === 'layanan'" x-cloak x-transition.opacity.duration.250ms class="tab-panel-enter">
            <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2 sm:gap-3 lg:gap-5">
                @forelse(($layanans ?? collect()->take(0)) as $l)
                    <article class="catalog-card group flex flex-col overflow-hidden min-w-0">
                        @if(!empty($l->gambar_utama))
                            <div class="aspect-[4/3] overflow-hidden bg-neutral-100">
                                <img src="{{ asset('storage/' . $l->gambar_utama) }}" alt="{{ $l->nama_layanan }}" class="w-full h-full object-cover block" loading="lazy">
                            </div>
                        @endif
                        <div class="p-2 sm:p-3 flex flex-col flex-1">
                        <p class="text-[11px] font-medium uppercase tracking-wider text-neutral-400 mb-1">{{ $l->kategori ?? 'Layanan' }}</p>
                        <h3 class="font-semibold text-black text-[13px] sm:text-[15px] leading-snug line-clamp-1">{{ $l->nama_layanan }}</h3>
                        <p class="text-[11px] sm:text-xs text-neutral-400 mt-1">{{ number_format((float)($l->rating ?? 0), 1) }} · {{ $l->jumlah_ulasan ?? 0 }} ulasan</p>
                        <p class="hidden sm:block text-[13px] text-neutral-500 line-clamp-2 mt-2">{{ Str::limit($l->deskripsi ?? 'Tim profesional siap membantu produksi Anda dari awal sampai akhir.', 80) }}</p>
                        <div class="mt-auto flex items-center justify-between gap-2 pt-3 mt-3 border-t border-neutral-100">
                            <p class="text-sm sm:text-[15px] font-semibold text-black">Rp {{ number_format($l->harga_mulai, 0, ',', '.') }}</p>
                            {{-- Tamu boleh lihat detail layanan; booking dikunci wajib login di halaman detail --}}
                            @php $lUrl = !empty($l->slug) ? route('customer.layanan.show', $l->slug) : $lyIndex; @endphp
                            <a href="{{ $lUrl }}" class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 rounded-full bg-black hover:bg-neutral-800 text-white text-xs sm:text-[13px] font-medium transition-colors w-full sm:w-auto justify-center shrink-0">Sewa</a>
                        </div>
                        </div>
                    </article>
                @empty
                    @foreach($dummyLayanan as $d)
                        <article class="catalog-card group p-2 sm:p-3 flex flex-col min-w-0">
                            <p class="text-[11px] font-medium uppercase tracking-wider text-neutral-400 mb-1">{{ $d['kat'] }}</p>
                            <h3 class="font-semibold text-black text-[13px] sm:text-[15px] leading-snug line-clamp-1">{{ $d['nama'] }}</h3>
                            <p class="text-[11px] sm:text-xs text-neutral-400 mt-1">{{ $d['rating'] }} · {{ $d['ulasan'] }} ulasan</p>
                            <p class="text-[13px] text-neutral-500 mt-2">Tim profesional + peralatan standar broadcast.</p>
                            <div class="mt-auto flex items-center justify-between gap-2 pt-3 mt-3 border-t border-neutral-100">
                                <p class="text-sm sm:text-[15px] font-semibold text-black">Rp {{ number_format($d['harga'], 0, ',', '.') }}<span class="text-xs font-normal text-neutral-400">/{{ $d['satuan'] }}</span></p>
                                <a href="{{ $lyIndex }}" class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 rounded-full bg-black hover:bg-neutral-800 text-white text-xs sm:text-[13px] font-medium transition-colors w-full sm:w-auto justify-center shrink-0">Sewa</a>
                            </div>
                        </article>
                    @endforeach
                @endforelse
            </div>
            <div class="text-center mt-8">
                <a href="{{ $lyIndex }}" class="inline-flex items-center gap-2 px-7 py-3 rounded-full bg-white border border-slate-200 text-sm font-bold text-slate-700 hover:border-[#111111]/50 hover:text-[#111111] hover:shadow-lg hover:-translate-y-px transition-all">Lihat Semua Layanan
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ============ 4. CARA SEWA ============ --}}
<section id="cara-sewa" class="scroll-mt-24 bg-[#FAFAFA] py-16 sm:py-24">
    <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-6">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="badge-brand mb-3">Cara Sewa</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-slate-900 mb-3">Booking semudah pesan ojek online</h2>
            <p class="text-[15px] text-slate-500">Empat langkah simpel dari pilih unit sampai siap produksi.</p>
        </div>
        {{-- Ular minimalis: angka polos besar + judul, jejak dashed melayang di celah --}}
        @php
            $steps = [
                ['n' => '01', 't' => 'Pilih Unit / Layanan'],
                ['n' => '02', 't' => 'Atur Jadwal & Checkout'],
                ['n' => '03', 't' => 'Verifikasi Kilat'],
                ['n' => '04', 't' => 'Ambil / Tiba di Lokasi'],
            ];
        @endphp

        {{-- DESKTOP: baris zigzag, jejak S melayang antar langkah --}}
        <div class="hidden lg:flex items-start justify-center">
            @foreach($steps as $i => $s)
                <div class="reveal {{ $i > 0 ? 'reveal-delay-' . $i : '' }} {{ $i % 2 === 1 ? 'mt-44' : '' }} shrink-0 w-44 text-center">
                    <div class="text-6xl font-extrabold tracking-tight text-slate-900 leading-none">{{ $s['n'] }}</div>
                    <p class="mt-3 text-lg font-bold text-slate-900 leading-snug">{{ $s['t'] }}</p>
                </div>
                @if($i < 3)
                    @if($i % 2 === 0)
                        <svg class="w-20 h-36 shrink-0 mt-[72px]" viewBox="0 0 80 160" fill="none" aria-hidden="true">
                            <path d="M10 8 C 10 70 70 80 70 152" stroke="#111111" stroke-opacity="0.22" stroke-width="2" stroke-dasharray="7 6" stroke-linecap="round"/>
                        </svg>
                    @else
                        <svg class="w-20 h-36 shrink-0 mt-[72px]" viewBox="0 0 80 160" fill="none" aria-hidden="true">
                            <path d="M10 152 C 10 90 70 80 70 8" stroke="#111111" stroke-opacity="0.22" stroke-width="2" stroke-dasharray="7 6" stroke-linecap="round"/>
                        </svg>
                    @endif
                @endif
            @endforeach
        </div>

        {{-- MOBILE: tumpukan tengah, jejak vertikal putus di tiap langkah --}}
        <div class="lg:hidden flex flex-col items-center">
            @foreach($steps as $i => $s)
                @if($i > 0)
                    <div class="h-10 border-l-2 border-dashed border-neutral-300" aria-hidden="true"></div>
                @endif
                <div class="reveal {{ $i > 0 ? 'reveal-delay-' . $i : '' }} text-center">
                    <div class="text-6xl font-extrabold tracking-tight text-slate-900 leading-none">{{ $s['n'] }}</div>
                    <p class="mt-2 text-base font-bold text-slate-900 leading-snug">{{ $s['t'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ 5. TESTIMONI ============ --}}
<section class="bg-white py-16 sm:py-24 overflow-hidden">
    <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-6">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
            <div class="max-w-xl">
                <span class="badge-brand mb-3">Testimoni</span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-slate-900 mb-2">Dipercaya 2.000+ kreator</h2>
                <p class="text-[15px] text-slate-500">Geser untuk membaca ulasan dan melihat hasil produksi di studio kami.</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <button @click="scrollTesti(-1)" aria-label="Sebelumnya" class="w-10 h-10 rounded-full border border-slate-200 text-slate-500 flex items-center justify-center hover:border-[#111111] hover:text-[#111111] active:scale-95 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button @click="scrollTesti(1)" aria-label="Berikutnya" class="w-10 h-10 rounded-full border border-slate-200 text-slate-500 flex items-center justify-center hover:border-[#111111] hover:text-[#111111] active:scale-95 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>

        {{-- Carousel testimoni : quote editorial + progress --}}
        <div id="testiTrack" class="flex gap-10 sm:gap-14 overflow-x-auto no-scrollbar snap-x snap-mandatory pb-2 -mx-4 px-4 sm:mx-0 sm:px-0 [mask-image:linear-gradient(to_right,transparent,black_3%,black_97%,transparent)]">
            @php $listTesti = (isset($testimonials) && $testimonials->isNotEmpty()) ? $testimonials : collect($dummyTesti); @endphp
            @foreach($listTesti as $t)
                @php
                    $nama = $t['nama'] ?? ($t->user->nama ?? 'Pelanggan Stekpro');
                    $peran = $t['peran'] ?? 'Penyewa Terverifikasi';
                    $teks = $t['teks'] ?? ($t->komentar ?? '');
                    $rate = (int)($t['rating'] ?? ($t->rating ?? 5));
                @endphp
                <figure class="snap-start shrink-0 w-[85%] sm:w-[calc(50%-28px)] lg:w-[calc(33.333%-37px)] relative pt-10">
                    <span class="absolute top-0 left-0 font-[Georgia,serif] text-[76px] leading-[0.8] text-slate-900/[0.07] select-none" aria-hidden="true">&ldquo;</span>
                    <div class="flex text-black gap-1 mb-4">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-3.5 h-3.5 {{ $i <= $rate ? '' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M10 1.5l2.6 5.3 5.9.9-4.2 4.1 1 5.8L10 14.9l-5.3 2.7 1-5.8L1.5 7.7l5.9-.9L10 1.5z"/></svg>
                        @endfor
                    </div>
                    <blockquote class="text-base sm:text-lg text-slate-800 leading-relaxed mb-6">“{{ Str::limit($teks, 180) }}”</blockquote>
                    <figcaption class="flex items-center gap-2.5">
                        <span class="w-6 h-[2px] bg-[#111111] shrink-0"></span>
                        <span>
                            <span class="block text-sm font-bold text-slate-900">{{ $nama }}</span>
                            <span class="block text-xs text-slate-400 mt-0.5">{{ $peran }}</span>
                        </span>
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ 6. FAQ ============ --}}
<section class="bg-white py-14 sm:py-20">
    <div class="max-w-3xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-8">
            <span class="badge-brand mb-3">FAQ</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 mb-2">Masih ragu? Ini yang sering ditanyakan</h2>
        </div>
        <div class="space-y-3" x-data="{ openFaq: 0 }">
            @foreach($faqs as $i => $f)
                <div class="bg-white rounded-2xl border transition-all duration-200 overflow-hidden" :class="openFaq === {{ $i }} ? 'border-[#111111]/40 shadow-[0_12px_30px_-12px_rgba(0,0,0,0.3)]' : 'border-slate-200/80 hover:border-slate-300'">
                    <button @click="openFaq = openFaq === {{ $i }} ? -1 : {{ $i }}" class="w-full flex items-center justify-between gap-4 px-5 sm:px-6 py-4 text-left">
                        <span class="text-sm sm:text-[15px] font-bold text-slate-800">{{ $f['question'] }}</span>
                        <span class="w-8 h-8 shrink-0 rounded-full flex items-center justify-center transition-all" :class="openFaq === {{ $i }} ? 'bg-[#111111] text-white rotate-45' : 'bg-slate-100 text-slate-500'">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        </span>
                    </button>
                    <div x-show="openFaq === {{ $i }}" x-collapse>
                        <p class="px-5 sm:px-6 pb-5 text-sm text-slate-500 leading-relaxed">{{ $f['answer'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <p class="text-center text-sm text-slate-500 mt-6">Butuh jawaban cepat?
            <a href="https://wa.me/6281234567890" target="_blank" rel="noopener" class="font-bold text-[#111111] hover:underline">Chat WhatsApp CS ?</a>
        </p>
    </div>
</section>

</div>
@endsection

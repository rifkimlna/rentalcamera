@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')
<section class="bg-base-100">
    <div class="max-w-6xl mx-auto px-4 py-16">
        <p class="text-xs text-base-content/40 uppercase tracking-wider mb-2">Tentang Kami</p>
        <h1 class="text-3xl font-light tracking-tight mb-6">Sewa Kamera Pro</h1>
        <div class="max-w-3xl">
            <p class="text-base text-base-content/60 leading-relaxed mb-4">
                Sewa Kamera Pro adalah platform penyewaan perlengkapan fotografi dan videografi 
                yang berkomitmen memberikan layanan terbaik bagi para kreator, fotografer profesional, 
                dan hobiis di seluruh Indonesia.
            </p>
            <p class="text-base text-base-content/60 leading-relaxed mb-4">
                Berdiri sejak 2020, kami telah melayani ribuan pelanggan dengan berbagai kebutuhan — 
                dari dokumentasi pernikahan, produksi film, hingga konten kreatif. Setiap peralatan 
                menjalani perawatan rutin dan pengecekan kualitas sebelum sampai ke tangan Anda.
            </p>
            <p class="text-base text-base-content/60 leading-relaxed">
                Kami percaya bahwa akses terhadap peralatan berkualitas tidak harus mahal. 
                Dengan sistem sewa yang fleksibel dan transparan, siapa pun bisa berkarya 
                tanpa batasan.
            </p>
        </div>
    </div>
</section>

<section class="bg-base-200 border-t border-base-300">
    <div class="max-w-6xl mx-auto px-4 py-16">
        <p class="text-xs text-base-content/40 uppercase tracking-wider mb-2">Keunggulan</p>
        <h2 class="text-2xl font-light mb-8">Mengapa Memilih Kami</h2>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-base-100 border border-base-300 rounded-box p-6">
                <p class="font-medium text-sm mb-2">Peralatan Terawat</p>
                <p class="text-sm text-base-content/60 leading-relaxed">Setiap unit dicek dan dirawat rutin. Kebersihan dan performa adalah prioritas utama kami.</p>
            </div>
            <div class="bg-base-100 border border-base-300 rounded-box p-6">
                <p class="font-medium text-sm mb-2">Harga Transparan</p>
                <p class="text-sm text-base-content/60 leading-relaxed">Tidak ada biaya tersembunyi. Harga yang tercantum adalah harga yang Anda bayar.</p>
            </div>
            <div class="bg-base-100 border border-base-300 rounded-box p-6">
                <p class="font-medium text-sm mb-2">Pengiriman Cepat</p>
                <p class="text-sm text-base-content/60 leading-relaxed">Same-day delivery untuk area Jabodetabek. Gratis ongkir untuk sewa minimal Rp500.000.</p>
            </div>
            <div class="bg-base-100 border border-base-300 rounded-box p-6">
                <p class="font-medium text-sm mb-2">Support 24/7</p>
                <p class="text-sm text-base-content/60 leading-relaxed">Tim customer service siap membantu kapan pun melalui chat, telepon, atau email.</p>
            </div>
            <div class="bg-base-100 border border-base-300 rounded-box p-6">
                <p class="font-medium text-sm mb-2">Asuransi Sewa</p>
                <p class="text-sm text-base-content/60 leading-relaxed">Tenang selama menyewa dengan opsi asuransi yang melindungi peralatan Anda.</p>
            </div>
            <div class="bg-base-100 border border-base-300 rounded-box p-6">
                <p class="font-medium text-sm mb-2">Fleksibel</p>
                <p class="text-sm text-base-content/60 leading-relaxed">Sewa harian, mingguan, atau bulanan. Perpanjangan mudah tanpa ribet.</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-base-100 border-t border-base-300">
    <div class="max-w-6xl mx-auto px-4 py-16">
        <p class="text-xs text-base-content/40 uppercase tracking-wider mb-2">Tim</p>
        <h2 class="text-2xl font-light mb-8">Tim Kami</h2>
        <div class="grid md:grid-cols-4 gap-6">
            @php
                $team = [
                    ['name' => 'Ahmad Rizki', 'role' => 'Founder & CEO'],
                    ['name' => 'Dinda Pratama', 'role' => 'Operations Manager'],
                    ['name' => 'Fajar Nugroho', 'role' => 'Technical Lead'],
                    ['name' => 'Sari Indah', 'role' => 'Customer Service'],
                ];
            @endphp
            @foreach($team as $member)
            <div class="text-center">
                <div class="w-20 h-20 rounded-full bg-base-200 mx-auto mb-3 flex items-center justify-center">
                    <span class="text-2xl font-light text-base-content/40">{{ substr($member['name'], 0, 1) }}</span>
                </div>
                <p class="font-medium text-sm">{{ $member['name'] }}</p>
                <p class="text-xs text-base-content/50">{{ $member['role'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-base-200 border-t border-base-300">
    <div class="max-w-6xl mx-auto px-4 py-16 text-center">
        <h2 class="text-2xl font-light mb-3">Siap menyewa?</h2>
        <p class="text-sm text-base-content/60 mb-6">Daftar sekarang dan mulai karya terbaik Anda.</p>
        @guest
        <a href="{{ route('register') }}" class="btn btn-neutral">Daftar Sekarang</a>
        @else
        <a href="{{ route('customer.products.index') }}" class="btn btn-neutral">Sewa Sekarang</a>
        @endguest
    </div>
</section>
@endsection

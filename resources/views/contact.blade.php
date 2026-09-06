@extends('layouts.app')

@section('title', 'Kontak')

@section('content')
<x-flash-messages />
<section class="section-dim">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-12 py-24 lg:py-32">
        <p class="text-sm font-medium text-[#86868b] mb-4 tracking-wide">Kontak</p>
        <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-[#1d1d1f] mb-12">Hubungi Kami</h1>

        <div class="grid md:grid-cols-2 gap-12 lg:gap-16">
            {{-- Info --}}
            <div class="space-y-8">
                <div>
                    <h3 class="text-xs font-semibold text-[#86868b] uppercase tracking-wider mb-3">Alamat</h3>
                    <p class="text-base text-[#6e6e73] leading-relaxed">Jl. Fotografi No. 123, Kel. Cikole<br>Kec. Cisaat, Sukabumi<br>10230, Indonesia</p>
                </div>
                <div>
                    <h3 class="text-xs font-semibold text-[#86868b] uppercase tracking-wider mb-3">Telepon</h3>
                    <a href="tel:+6281234567890" class="text-base text-[#1d1d1f] font-medium hover:text-[#0071e3] transition-colors">+62 812 3456 7890</a>
                </div>
                <div>
                    <h3 class="text-xs font-semibold text-[#86868b] uppercase tracking-wider mb-3">Email</h3>
                    <a href="mailto:info@sewakamerapro.com" class="text-base text-[#1d1d1f] font-medium hover:text-[#0071e3] transition-colors">info@sewakamerapro.com</a>
                </div>
                <div>
                    <h3 class="text-xs font-semibold text-[#86868b] uppercase tracking-wider mb-3">Jam Operasional</h3>
                    <div class="space-y-1.5 text-sm text-[#6e6e73]">
                        <div class="flex justify-between gap-8"><span>Senin - Jumat</span><span class="font-medium text-[#1d1d1f]">08:00 - 20:00</span></div>
                        <div class="flex justify-between gap-8"><span>Sabtu</span><span class="font-medium text-[#1d1d1f]">09:00 - 18:00</span></div>
                        <div class="flex justify-between gap-8"><span>Minggu</span><span class="font-medium text-[#1d1d1f]">10:00 - 16:00</span></div>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <div class="card-apple-static p-6 lg:p-8">
                <h3 class="text-lg font-semibold text-[#1d1d1f] mb-6">Kirim Pesan</h3>
                <form method="POST" action="{{ route('contact') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-xs font-medium text-[#86868b] mb-1.5">Nama</label>
                        <input type="text" id="name" name="name" class="input-apple" required placeholder="Nama lengkap">
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-xs font-medium text-[#86868b] mb-1.5">Email</label>
                        <input type="email" id="email" name="email" class="input-apple" required placeholder="nama@example.com">
                    </div>
                    <div class="mb-6">
                        <label for="message" class="block text-xs font-medium text-[#86868b] mb-1.5">Pesan</label>
                        <textarea id="message" name="message" class="input-apple resize-none" rows="4" required placeholder="Tulis pesan Anda..."></textarea>
                    </div>
                    <button type="submit" class="btn-dark-apple w-full !py-3">Kirim Pesan</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

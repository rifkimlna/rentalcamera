@extends('layouts.app')

@section('title', 'Kontak')

@section('content')
<section class="bg-base-100">
    <div class="max-w-6xl mx-auto px-4 py-16">
        <p class="text-xs text-base-content/40 uppercase tracking-wider mb-2">Kontak</p>
        <h1 class="text-3xl font-light tracking-tight mb-10">Hubungi Kami</h1>

        <div class="grid md:grid-cols-2 gap-10">
            <div>
                <div class="space-y-6">
                    <div>
                        <p class="font-medium text-sm mb-1">Alamat</p>
                        <p class="text-sm text-base-content/60">Jl. Fotografi No. 123, Kel. Menteng<br>Kec. Tanah Abang, Jakarta Pusat<br>10230, Indonesia</p>
                    </div>
                    <div>
                        <p class="font-medium text-sm mb-1">Telepon</p>
                        <p class="text-sm text-base-content/60">+62 812 3456 7890</p>
                    </div>
                    <div>
                        <p class="font-medium text-sm mb-1">Email</p>
                        <p class="text-sm text-base-content/60">info@sewakamerapro.com</p>
                    </div>
                    <div>
                        <p class="font-medium text-sm mb-1">Jam Operasional</p>
                        <table class="text-sm text-base-content/60">
                            <tr><td class="pr-6 py-0.5">Senin - Jumat</td><td>08:00 - 20:00</td></tr>
                            <tr><td class="pr-6 py-0.5">Sabtu</td><td>09:00 - 18:00</td></tr>
                            <tr><td class="pr-6 py-0.5">Minggu</td><td>10:00 - 16:00</td></tr>
                        </table>
                    </div>
                </div>
            </div>

            <div>
                <form method="POST" action="{{ route('contact') }}" class="bg-base-100 border border-base-300 rounded-box p-6">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium mb-1">Nama</label>
                        <input type="text" id="name" name="name" class="input input-bordered w-full" required placeholder="Nama lengkap">
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" id="email" name="email" class="input input-bordered w-full" required placeholder="nama@example.com">
                    </div>
                    <div class="mb-4">
                        <label for="message" class="block text-sm font-medium mb-1">Pesan</label>
                        <textarea id="message" name="message" class="textarea textarea-bordered w-full" rows="4" required placeholder="Tulis pesan Anda..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-neutral">Kirim Pesan</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

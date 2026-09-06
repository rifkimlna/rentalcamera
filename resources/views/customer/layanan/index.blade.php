@extends('layouts.customer')

@section('title', 'Layanan - Stekpro Multimedia & Broadcast')

@section('content')
<div>
    <div class="flex items-center justify-between mb-4 sm:mb-6 gap-2">
        <div class="min-w-0">
            <h1 class="text-lg sm:text-2xl font-bold text-[#1d1d1f] tracking-tight">Layanan</h1>
            <p class="text-xs sm:text-sm text-[#6e6e73] mt-0.5 hidden sm:block">Jasa fotografi dan videografi profesional untuk berbagai kebutuhan Anda</p>
        </div>
    </div>

    <!-- Filter -->
    <div class="card-apple-static mb-4 sm:mb-6 p-3 sm:p-4 lg:p-5">
        <form method="GET" action="{{ route('customer.layanan.index') }}">
            <!-- Desktop: grid -->
            <div class="hidden lg:grid lg:grid-cols-3 gap-4">
                <div class="col-span-2">
                    <label class="block text-xs font-medium text-[#86868b] mb-1.5">Cari Layanan</label>
                    <input type="text" name="search" class="input-apple" placeholder="Cari layanan..." value="{{ request('search') }}">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="btn-dark-apple !px-4 !py-2.5 flex-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/></svg>
                        Cari
                    </button>
                    <a href="{{ route('customer.layanan.index') }}" class="btn-outline-apple !px-3 !py-2.5" title="Reset">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </a>
                </div>
            </div>

            <!-- Mobile: compact -->
            <div class="lg:hidden flex items-center gap-2">
                <div class="relative flex-1">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#86868b]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/></svg>
                    </span>
                    <input type="text" name="search" class="input-apple !pl-9" placeholder="Cari layanan..." value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn-dark-apple !px-5 !py-3">Cari</button>
                <a href="{{ route('customer.layanan.index') }}" class="btn-outline-apple !px-3 !py-3" title="Reset">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </a>
            </div>
        </form>
    </div>

    <!-- Layanan grid -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4 lg:gap-5">
    @forelse($layanans as $layanan)
        <div class="card-apple-static rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <!-- Image -->
            <a href="{{ route('customer.layanan.show', $layanan->slug) }}" class="group relative overflow-hidden bg-[#f5f5f7] aspect-square block">
                @if($layanan->gambar_utama)
                    <img src="{{ asset('storage/' . $layanan->gambar_utama) }}"
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $layanan->nama_layanan }}">
                @else
                    <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-slate-200 via-slate-100 to-[#f5f5f7]">
                        <svg class="h-12 sm:h-16 w-12 sm:w-16 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-transparent"></div>

                <div class="absolute bottom-0 left-0 right-0 p-2.5 sm:p-3 lg:p-4 text-white">
                    <p class="text-xs sm:text-sm lg:text-base font-semibold truncate">{{ $layanan->nama_layanan }}</p>
                    <div class="flex items-center gap-0.5 sm:gap-1 mt-0.5 sm:mt-1">
                        <div class="flex items-center gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="h-2.5 w-2.5 sm:h-3 sm:w-3 {{ $i <= round($layanan->rating) ? 'text-[#ff9500]' : 'text-white/30' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <span class="text-[10px] sm:text-xs text-white/80">({{ $layanan->rating }})</span>
                    </div>
                </div>
            </a>

            <!-- Body -->
            <div class="p-2.5 sm:p-3 lg:p-4 bg-white">
                <div class="flex items-center justify-between gap-1.5 mb-1.5 sm:mb-2">
                    <div>
                        <span class="text-xs sm:text-sm lg:text-base font-bold text-[#1d1d1f]">{{ $layanan->harga_mulai_formatted }}</span>
                        <span class="text-[10px] sm:text-xs text-[#6e6e73]">/mulai</span>
                        @if($layanan->paket_active_count > 0)
                            <span class="text-[10px] sm:text-xs text-[#86868b] block mt-0.5">{{ $layanan->paket_active_count }} paket</span>
                        @endif
                    </div>
                </div>

                @if($layanan->kategori)
                    <div class="flex flex-wrap gap-1 mb-2 sm:mb-3">
                        <span class="badge-apple !text-[9px] sm:!text-[10px] !px-1.5 sm:!px-2 !py-0">{{ $layanan->kategori }}</span>
                    </div>
                @endif

                <div class="flex gap-1.5 sm:gap-2">
                    <a href="{{ route('customer.layanan.show', $layanan->slug) }}" class="btn-outline-apple flex-1 !text-[11px] sm:!text-xs lg:!text-sm !py-1.5 sm:!py-2">Detail</a>
                    {{-- Tamu diarahkan ke detail dulu (sama seperti Equipment); user login langsung buka modal booking --}}
                    @auth
                        <button type="button" class="btn-dark-apple flex-1 !text-[11px] sm:!text-xs lg:!text-sm !py-1.5 sm:!py-2" onclick="openLayananModal({{ $layanan->id }})">Sewa</button>
                    @else
                        <a href="{{ route('customer.layanan.show', $layanan->slug) }}" class="btn-dark-apple flex-1 !text-[11px] sm:!text-xs lg:!text-sm !py-1.5 sm:!py-2 text-center">Sewa</a>
                    @endauth
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full card-apple-static p-12 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[#d1d1d6] mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/></svg>
            <p class="text-[#6e6e73] mb-3">Layanan tidak ditemukan</p>
            <a href="{{ route('customer.layanan.index') }}" class="btn-outline-apple !text-sm">Reset Pencarian</a>
        </div>
    @endforelse
    </div>

    @if($layanans->hasPages())
        <div class="flex justify-center mt-4">
            {{ $layanans->links() }}
        </div>
    @endif
</div>

<!-- Modal Sewa Layanan -->
<dialog id="layananModal" class="modal">
    <div class="modal-box rounded-2xl max-w-lg rounded-2xl">
        <form method="dialog">
            <button class="hover:bg-[#f5f5f7] rounded-full p-2 transition-all absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-1" id="modalLayananName">Sewa Layanan</h3>
        <p class="text-sm text-[#6e6e73] mb-4" id="modalLayananPrice">Pilih paket atau sewa per jam</p>

        <form id="layananBookingForm">
            @csrf
            <input type="hidden" name="layanan_id" id="modal_layanan_id">
            <input type="hidden" name="tipe_booking" id="modal_tipe_booking" value="layanan">
            <input type="hidden" name="paket_layanan_id" id="modal_paket_layanan_id" value="">

            <!-- Paket List -->
            <div id="modalPaketList" class="space-y-2 mb-4"></div>

            <!-- Booking Date & Time -->
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="py-1"><span class="block text-xs font-medium text-[#6e6e73]">Tanggal</span></label>
                    <input type="date" name="tanggal_booking" class="input-apple w-full " required min="{{ date('Y-m-d') }}">
                </div>
                <div>
                    <label class="py-1"><span class="block text-xs font-medium text-[#6e6e73]">Jam Mulai</span></label>
                    <input type="time" name="jam_mulai" class="input-apple w-full " required>
                </div>
            </div>

            <button type="submit" class="btn-dark-apple w-full btn-sm" id="modalBookingBtn">
                Lanjutkan ke Checkout
            </button>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

@push('scripts')
<script>
    const layanansData = {!! json_encode($layanansJson) !!};
    const LAYANAN_IS_GUEST = {{ auth()->check() ? 'false' : 'true' }};
    const LAYANAN_LOGIN_URL = '{{ route("login") }}';

    function openLayananModal(layananId) {
        if (LAYANAN_IS_GUEST) {
            alert('Silakan login terlebih dahulu untuk menyewa.');
            window.location.href = LAYANAN_LOGIN_URL;
            return;
        }
        const layanan = layanansData[layananId];
        if (!layanan) return;

        document.getElementById('modalLayananName').textContent = layanan.nama_layanan;
        document.getElementById('modalLayananPrice').textContent = 'Mulai dari ' + layanan.harga_mulai_formatted + '/jam';
        document.getElementById('modal_layanan_id').value = layanan.id;

        const paketList = document.getElementById('modalPaketList');
        paketList.innerHTML = '';

        // Custom jam option
        const customDiv = document.createElement('div');
        customDiv.className = 'flex items-center gap-3 p-3 rounded-lg border border-[#e5e5e7] cursor-pointer hover:bg-[#f5f5f7] has-checked:border-[#1d1d1f] has-checked:bg-[#1d1d1f]/5';
        customDiv.innerHTML = `
            <input type="radio" name="tipe_booking_radio" value="layanan" class="radio radio-sm" checked>
            <div class="flex-1">
                <strong class="text-sm">Sewa per Jam</strong>
                <p class="text-xs text-[#6e6e73]">Fleksibel, bayar per jam</p>
            </div>
            <div class="text-right">
                <strong class="text-sm">${layanan.harga_mulai_formatted}</strong>
                <p class="text-xs text-[#6e6e73]">/ jam</p>
            </div>
        `;
        paketList.appendChild(customDiv);

        // Duration input (for custom hours)
        const durasiDiv = document.createElement('div');
        durasiDiv.id = 'durasiManual';
        durasiDiv.className = 'mt-2';
        durasiDiv.innerHTML = `
            <label class="py-1"><span class="block text-xs font-medium text-[#6e6e73]">Durasi (jam)</span></label>
            <input type="number" name="durasi_jam" class="input-apple w-full " value="1" min="1" max="8">
        `;
        paketList.appendChild(durasiDiv);

        // Paket options
        layanan.paketActive.forEach(function(paket) {
            const div = document.createElement('div');
            div.className = 'flex items-center gap-3 p-3 rounded-lg border border-[#e5e5e7] cursor-pointer hover:bg-[#f5f5f7] has-checked:border-[#1d1d1f] has-checked:bg-[#1d1d1f]/5';
            div.innerHTML = `
                <input type="radio" name="tipe_booking_radio" value="paket" data-paket-id="${paket.id}" data-durasi="${paket.durasi_jam}" class="radio radio-sm">
                <div class="flex-1 min-w-0">
                    <strong class="text-sm">${paket.nama_paket}</strong>
                    <p class="text-xs text-[#6e6e73] truncate">${paket.deskripsi || ''}</p>
                    <span class="!text-[10px] !px-2 !py-0.5 badge-apple mt-1">${paket.durasi_jam} jam</span>
                    ${paket.include ? `<span class="!text-[10px] !px-2 !py-0.5 badge-outline mt-1">+ Include</span>` : ''}
                </div>
                <div class="text-right shrink-0">
                    <strong class="text-sm">${paket.harga_formatted}</strong>
                </div>
            `;
            div.querySelector('input').addEventListener('change', function() {
                togglePaketInput();
            });
            paketList.appendChild(div);
        });

        document.getElementById('layananModal').showModal();
    }

    function togglePaketInput() {
        const selected = document.querySelector('input[name="tipe_booking_radio"]:checked');
        const durasiManual = document.getElementById('durasiManual');
        if (selected && selected.value === 'paket') {
            durasiManual.classList.add('hidden');
            document.getElementById('modal_tipe_booking').value = 'paket';
            document.getElementById('modal_paket_layanan_id').value = selected.dataset.paketId || '';
        } else {
            durasiManual.classList.remove('hidden');
            document.getElementById('modal_tipe_booking').value = 'layanan';
            document.getElementById('modal_paket_layanan_id').value = '';
        }
    }

    document.addEventListener('change', function(e) {
        if (e.target && e.target.name === 'tipe_booking_radio') {
            togglePaketInput();
        }
    });

    // AJAX booking submission -> store in session, go to checkout
    document.getElementById('layananBookingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        if (LAYANAN_IS_GUEST) { window.location.href = LAYANAN_LOGIN_URL; return; }
        const btn = document.getElementById('modalBookingBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="gooey-loader" style="--gooey-dot:7px;margin-right:8px"><i></i><i></i><i></i></span>Memproses...';

        const formData = new FormData(this);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("customer.layanan.direct-booking") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(res => {
            if (res.status === 401) { window.location.href = LAYANAN_LOGIN_URL; return null; }
            return res.json();
        })
        .then(data => {
            if (!data) return;
            if (data.success) {
                document.getElementById('layananModal').close();
                window.location.href = '{{ route("customer.checkout.index") }}';
            } else {
                document.getElementById('layananModal').close();
                Swal.fire({
                    icon: 'error',
                    title: 'Layanan Tidak Tersedia',
                    text: data.message || 'Terjadi kesalahan. Silakan coba jadwal lain.',
                    confirmButtonColor: '#1d1d1f',
                    confirmButtonText: 'OK',
                });
                btn.disabled = false;
                btn.textContent = 'Lanjutkan ke Checkout';
            }
        })
        .catch(() => {
            document.getElementById('layananModal').close();
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan',
                text: 'Terjadi kesalahan. Silakan coba lagi.',
                confirmButtonColor: '#1d1d1f',
                confirmButtonText: 'OK',
            });
            btn.disabled = false;
            btn.textContent = 'Lanjutkan ke Checkout';
        });
    });
</script>
@endpush
@endsection


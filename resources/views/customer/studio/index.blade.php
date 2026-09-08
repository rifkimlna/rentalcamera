@extends('layouts.customer')

@section('title', 'Studio - Stekpro Multimedia & Broadcast')

@section('content')
<div>
    <div class="flex items-center justify-between mb-4 sm:mb-6 gap-2">
        <div class="min-w-0">
            <h1 class="text-lg sm:text-2xl font-bold text-[#1d1d1f] tracking-tight">Sewa Studio</h1>
            <p class="text-xs sm:text-sm text-[#6e6e73] mt-0.5 hidden sm:block">Temukan studio terbaik untuk kebutuhan foto, video, dan konten Anda</p>
        </div>
    </div>

    <!-- Search minimalis -->
    <form method="GET" action="{{ route('customer.studio.index') }}" class="mb-4 sm:mb-6">
        <div class="flex items-center gap-2">
            <div class="relative flex-1 min-w-0">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#86868b]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/></svg>
                </span>
                <input type="text" name="search" class="input-apple !rounded-full !pl-11" placeholder="Cari studio..." value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn-dark-apple shrink-0 !px-5 sm:!px-7">Cari</button>
            <a href="{{ route('customer.studio.index') }}" class="btn-outline-apple shrink-0 !p-3" title="Reset">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </a>
        </div>
    </form>

    <!-- Studio grid -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4 lg:gap-5">
    @forelse($studios as $studio)
        <div class="card-apple overflow-hidden">
            <a href="{{ route('customer.studio.show', $studio->slug) }}" class="group relative overflow-hidden bg-[#f5f5f7] aspect-square block">
                @if($studio->gambar_utama)
                    <img src="{{ asset('storage/' . $studio->gambar_utama) }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $studio->nama_studio }}">
                @else
                    <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-[#f0f0f2] to-[#e5e5e7]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 sm:h-16 w-12 sm:w-16 text-[#d1d1d6]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

                <div class="absolute bottom-0 left-0 right-0 p-2.5 sm:p-3 lg:p-4 text-white">
                    <p class="text-xs sm:text-sm lg:text-base font-semibold truncate">{{ $studio->nama_studio }}</p>
                    <div class="flex items-center gap-0.5 sm:gap-1 mt-0.5 sm:mt-1">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="h-2.5 w-2.5 sm:h-3 sm:w-3 {{ $i <= round($studio->rating) ? 'text-[#ff9500]' : 'text-white/30' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                        <span class="text-[10px] sm:text-xs text-white/70 ml-0.5 sm:ml-1">({{ $studio->rating }})</span>
                    </div>
                </div>
            </a>

            <div class="p-2.5 sm:p-3 lg:p-4 bg-white">
                <div class="flex items-center justify-between gap-1.5 mb-1.5 sm:mb-2">
                    <div class="min-w-0">
                        <div class="flex items-baseline gap-1">
                            <span class="text-xs sm:text-sm lg:text-base font-bold text-[#1d1d1f]">{{ $studio->harga_per_jam_formatted }}</span>
                            <span class="text-[10px] sm:text-xs text-[#86868b]">/jam</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-1 mb-2 sm:mb-3 flex-wrap">
                    @if($studio->paket_active_count > 0)
                        <span class="badge-apple !text-[9px] sm:!text-[10px] !px-1.5 sm:!px-2 !py-0">{{ $studio->paket_active_count }} paket</span>
                    @endif
                    @if($studio->fasilitas)
                        @php $fasilitasList = is_array($studio->fasilitas) ? $studio->fasilitas : array_map('trim', explode(',', $studio->fasilitas)); @endphp
                        @foreach(array_slice($fasilitasList, 0, 2) as $f)
                            <span class="badge-apple !text-[9px] sm:!text-[10px] !px-1.5 sm:!px-2 !py-0">{{ $f }}</span>
                        @endforeach
                    @endif
                </div>

                <div class="flex gap-1.5 sm:gap-2">
                    <a href="{{ route('customer.studio.show', $studio->slug) }}" class="flex-1 text-center text-[11px] sm:text-xs lg:text-sm font-medium py-1.5 sm:py-2 rounded-lg lg:rounded-xl border border-[#e5e5e7] text-[#1d1d1f] hover:bg-[#f5f5f7] transition-colors">Detail</a>
                    <button type="button" onclick="openStudioModal({{ $studio->id }})" class="flex-1 text-center text-[11px] sm:text-xs lg:text-sm font-medium py-1.5 sm:py-2 rounded-lg lg:rounded-xl bg-[#1d1d1f] text-white hover:bg-[#333] active:scale-[0.98] transition-all">Sewa</button>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full card-apple-static p-12 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[#d1d1d6] mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/></svg>
            <p class="text-[#6e6e73] mb-3">Studio tidak ditemukan</p>
            <a href="{{ route('customer.studio.index') }}" class="btn-outline-apple !px-5 !py-2 !text-sm">Reset Pencarian</a>
        </div>
    @endforelse
    </div>

    @if($studios->hasPages())
        <div class="flex justify-center mt-4">
            {{ $studios->links() }}
        </div>
    @endif
</div>

<!-- Modal Sewa Studio — Kotak Kecil Laptop -->
<dialog id="studioModal" class="p-0 bg-transparent backdrop:bg-black/30 backdrop:backdrop-blur-[2px] open:animate-[fadeIn_0.2s_ease] max-w-none w-full h-full max-h-none items-center justify-center p-3">
    <div class="bg-white rounded-xl shadow-[0_8px_24px_rgba(0,0,0,0.12)] w-full mx-auto my-auto overflow-hidden max-h-[75vh] overflow-y-auto" style="max-width:280px; width: calc(100vw - 32px);">
        <div class="sticky top-0 bg-white px-4 lg:px-3 pt-4 lg:pt-3 pb-3 lg:pb-2.5 border-b border-[#f0f0f2]">
            <h3 class="text-[13px] lg:text-[12px] font-semibold text-[#1d1d1f] tracking-tight leading-none" id="modalStudioName">Sewa Studio</h3>
            <p class="text-[11px] lg:text-[10px] text-[#86868b] leading-none mt-1" id="modalStudioPrice">Pilih paket atau sewa per jam</p>
        </div>

        <form id="studioBookingForm" class="px-4 lg:px-3 pb-4 lg:pb-3 pt-3 lg:pt-2.5">
            @csrf
            <input type="hidden" name="studio_id" id="modal_studio_id">
            <input type="hidden" name="tipe_booking" id="modal_tipe_booking" value="studio">
            <input type="hidden" name="paket_studio_id" id="modal_paket_studio_id" value="">

            <div id="modalPaketList" class="space-y-2 mb-4"></div>

            <div class="grid grid-cols-2 gap-2 mb-3">
                <div>
                    <label class="block text-[10px] font-medium text-[#86868b] tracking-wide uppercase mb-1">Tanggal</label>
                    <input type="date" name="tanggal_booking" class="input-apple !py-1.5 !text-[12px] !rounded-lg w-full" required min="{{ date('Y-m-d') }}">
                </div>
                <div>
                    <label class="block text-[10px] font-medium text-[#86868b] tracking-wide uppercase mb-1">Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="input-apple !py-1.5 !text-[12px] !rounded-lg w-full" required>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="button" class="flex-1 py-2 rounded-full border border-[#e5e5e7] text-[12px] font-medium text-[#6e6e73] hover:bg-[#f5f5f7] hover:text-[#1d1d1f] active:scale-[0.98] transition" onclick="studioModal.close()">Batal</button>
                <button type="submit" class="flex-1 py-2 rounded-full bg-[#1d1d1f] text-white text-[12px] font-semibold hover:bg-black active:scale-[0.98] transition flex items-center justify-center" id="modalBookingBtn">Lanjutkan</button>
            </div>
            <p class="text-[11px] text-[#86868b] text-center mt-3">Slot akan dicek otomatis sebelum checkout</p>
        </form>
    </div>
</dialog>
<style>@keyframes fadeIn{from{opacity:0;transform:scale(0.98)}to{opacity:1;transform:scale(1)}} dialog::backdrop{background:rgba(0,0,0,0.3);backdrop-filter:blur(2px)} dialog[open]{display:flex}</style>

@push('scripts')
<script>
    const studiosData = {!! json_encode($studiosJson) !!};
    const STUDIO_IS_GUEST = {{ auth()->check() ? 'false' : 'true' }};
    const STUDIO_LOGIN_URL = '{{ route("login") }}';

    // backdrop click close + minimal card helper
    document.getElementById('studioModal')?.addEventListener('click', function(e){ if(e.target===this) this.close(); });
    function updateRadioCards() {
        document.querySelectorAll('#modalPaketList > div').forEach(function(card){
            const radio = card.querySelector('input[type="radio"]');
            if(!radio) return;
            if(radio.checked){ card.classList.add('border-[#1d1d1f]','bg-[#f5f5f7]'); card.classList.remove('border-[#f0f0f2]','border-[#e5e5e7]'); }
            else { card.classList.remove('border-[#1d1d1f]','bg-[#f5f5f7]'); card.classList.add('border-[#f0f0f2]'); }
        });
    }
    function openStudioModal(studioId) {
        if (STUDIO_IS_GUEST) {
            alert('Silakan login terlebih dahulu untuk menyewa.');
            window.location.href = STUDIO_LOGIN_URL;
            return;
        }
        const studio = studiosData[studioId];
        if (!studio) return;

        document.getElementById('modalStudioName').textContent = studio.nama_studio;
        document.getElementById('modalStudioPrice').textContent = 'Mulai dari ' + studio.harga_per_jam_formatted + '/jam';
        document.getElementById('modal_studio_id').value = studio.id;

        const paketList = document.getElementById('modalPaketList');
        paketList.innerHTML = '';

        // Custom jam option — minimal card compact
        const customDiv = document.createElement('div');
        customDiv.className = 'flex items-center gap-2.5 p-2.5 rounded-xl border cursor-pointer hover:bg-[#f5f5f7] transition-all';
        customDiv.innerHTML = `
            <input type="radio" name="tipe_booking_radio" value="studio" class="radio radio-sm shrink-0" checked>
            <div class="flex-1 min-w-0">
                <strong class="text-[13px] text-[#1d1d1f]">Sewa per Jam</strong>
                <p class="text-[11px] text-[#86868b]">Fleksibel, bayar per jam</p>
            </div>
            <div class="text-right shrink-0">
                <strong class="text-[13px] text-[#1d1d1f]">${studio.harga_per_jam_formatted}</strong>
                <p class="text-[10px] text-[#86868b]">/ jam</p>
            </div>
        `;
        customDiv.addEventListener('click', function(){ this.querySelector('input').checked=true; togglePaketInput(); updateRadioCards(); });
        paketList.appendChild(customDiv);

        // Duration input
        const durasiDiv = document.createElement('div');
        durasiDiv.id = 'durasiManual';
        durasiDiv.className = 'mt-2 mb-1';
        durasiDiv.innerHTML = `
            <label class="block text-[11px] font-medium text-[#86868b] tracking-wide uppercase mb-1.5">Durasi (jam)</label>
            <div class="flex items-center rounded-xl bg-[#f5f5f7] p-0.5">
                <button type="button" class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center" onclick="let e=this.nextElementSibling; if(parseInt(e.value)>1){e.value=parseInt(e.value)-1;}">−</button>
                <input type="number" name="durasi_jam" class="flex-1 bg-transparent text-center text-sm font-semibold outline-none py-1.5" value="1" min="1" max="8">
                <button type="button" class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center" onclick="let e=this.previousElementSibling; e.value=parseInt(e.value)+1;">+</button>
            </div>
        `;
        paketList.appendChild(durasiDiv);

        // Paket options — minimal compact
        studio.paketActive.forEach(function(paket) {
            const div = document.createElement('div');
            div.className = 'flex items-center gap-2.5 p-2.5 rounded-xl border cursor-pointer hover:bg-[#f5f5f7] transition-all';
            div.innerHTML = `
                <input type="radio" name="tipe_booking_radio" value="paket" data-paket-id="${paket.id}" data-durasi="${paket.durasi_jam}" class="radio radio-sm shrink-0">
                <div class="flex-1 min-w-0">
                    <strong class="text-[13px] text-[#1d1d1f]">${paket.nama_paket}</strong>
                    <p class="text-[11px] text-[#86868b] truncate">${paket.deskripsi || ''}</p>
                    <div class="flex gap-1 mt-1">
                        <span class="inline-flex text-[10px] px-2 py-0.5 rounded-full bg-[#f5f5f7] text-[#6e6e73] border border-transparent">${paket.durasi_jam} jam</span>
                        ${paket.include_alat ? `<span class="inline-flex text-[10px] px-2 py-0.5 rounded-full bg-white border border-[#e5e5e7] text-[#6e6e73]">+ Alat</span>` : ''}
                    </div>
                </div>
                <div class="text-right shrink-0">
                    <strong class="text-[13px] text-[#1d1d1f]">${paket.harga_formatted}</strong>
                </div>
            `;
            div.addEventListener('click', function(){ this.querySelector('input').checked=true; togglePaketInput(); updateRadioCards(); });
            div.querySelector('input').addEventListener('change', function() { togglePaketInput(); updateRadioCards(); });
            paketList.appendChild(div);
        });

        togglePaketInput();
        updateRadioCards();
        document.getElementById('studioModal').showModal();
    }

    function togglePaketInput() {
        const selected = document.querySelector('input[name="tipe_booking_radio"]:checked');
        const durasiManual = document.getElementById('durasiManual');
        if (selected && selected.value === 'paket') {
            durasiManual.classList.add('hidden');
            document.getElementById('modal_tipe_booking').value = 'paket';
            document.getElementById('modal_paket_studio_id').value = selected.dataset.paketId || '';
        } else {
            durasiManual.classList.remove('hidden');
            document.getElementById('modal_tipe_booking').value = 'studio';
            document.getElementById('modal_paket_studio_id').value = '';
        }
    }

    document.addEventListener('change', function(e) {
        if (e.target && e.target.name === 'tipe_booking_radio') {
            togglePaketInput();
            updateRadioCards();
        }
    });

    // AJAX booking submission -> store in session, go to checkout
    document.getElementById('studioBookingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        if (STUDIO_IS_GUEST) { window.location.href = STUDIO_LOGIN_URL; return; }
        const btn = document.getElementById('modalBookingBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="gooey-loader" style="--gooey-dot:7px;margin-right:8px"><i></i><i></i><i></i></span>Memproses...';

        const formData = new FormData(this);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("customer.studio.direct-booking") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(res => {
            if (res.status === 401) { window.location.href = STUDIO_LOGIN_URL; return null; }
            return res.json();
        })
        .then(data => {
            if (!data) return;
            if (data.success) {
                document.getElementById('studioModal').close();
                window.location.href = '{{ route("customer.checkout.index") }}';
            } else {
                document.getElementById('studioModal').close();
                Swal.fire({
                    icon: 'error',
                    title: 'Studio Tidak Tersedia',
                    text: data.message || 'Terjadi kesalahan. Silakan coba jadwal lain.',
                    confirmButtonColor: '#1d1d1f',
                    confirmButtonText: 'OK',
                });
                btn.disabled = false;
                btn.textContent = 'Lanjutkan';
            }
        })
        .catch(() => {
            document.getElementById('studioModal').close();
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan',
                text: 'Terjadi kesalahan. Silakan coba lagi.',
                confirmButtonColor: '#1d1d1f',
                confirmButtonText: 'OK',
            });
            btn.disabled = false;
            btn.textContent = 'Lanjutkan';
        });
    });
</script>
@endpush
@endsection


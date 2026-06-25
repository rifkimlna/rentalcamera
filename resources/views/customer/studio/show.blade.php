@extends('layouts.customer')

@section('title', $studio->nama_studio)
@section('page-title', $studio->nama_studio)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="card bg-base-100 border border-base-300">
            <div class="card-body p-4">
                @if($studio->gambar_utama)
                    <img src="{{ asset('storage/' . $studio->gambar_utama) }}" class="w-full h-64 object-cover rounded mb-4" alt="{{ $studio->nama_studio }}">
                @endif
                <h3 class="text-base font-medium">{{ $studio->nama_studio }}</h3>
                <p class="text-sm text-base-content/60 mt-1">{{ $studio->deskripsi ?? 'Tidak ada deskripsi.' }}</p>

                @if($studio->fasilitas)
                    <h4 class="text-sm font-medium mt-4 mb-2">Fasilitas</h4>
                    @php $fasilitasList = is_array($studio->fasilitas) ? $studio->fasilitas : array_map('trim', explode(',', $studio->fasilitas)); @endphp
                    <div class="flex flex-wrap gap-1">
                        @foreach($fasilitasList as $f)
                            <span class="badge badge-outline">{{ $f }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        @if($studio->paketActive->count() > 0)
        <div class="card bg-base-100 border border-base-300 mt-4">
            <div class="card-body p-4">
                <h4 class="text-sm font-medium mb-3">Paket Studio</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($studio->paketActive as $paket)
                    <div class="border border-base-300 rounded p-3">
                        <div class="flex items-start gap-2">
                            <input type="radio" name="tipe_booking" value="paket" data-paket-id="{{ $paket->id }}"
                                   data-harga="{{ $paket->harga }}" data-durasi="{{ $paket->durasi_jam }}"
                                   class="radio radio-sm radio-primary mt-1 booking-type-paket"
                                   onchange="selectPaket({{ $paket->id }}, {{ $paket->harga }}, {{ $paket->durasi_jam }})">
                            <div class="flex-1">
                                <h5 class="text-sm font-medium">{{ $paket->nama_paket }}</h5>
                                <p class="text-xs text-base-content/60">{{ $paket->deskripsi }}</p>
                                @if($paket->include_alat)
                                    @php $alatList = array_map('trim', explode(',', $paket->include_alat)); @endphp
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @foreach($alatList as $alat)
                                            <span class="badge badge-sm badge-ghost">{{ $alat }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="text-sm font-bold">{{ $paket->harga_formatted }}</span>
                                    <span class="text-xs text-base-content/60">{{ $paket->durasi_jam }} jam</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="lg:col-span-1">
        <div class="card bg-base-100 border border-base-300 sticky top-20">
            <div class="card-body p-4">
                <h4 class="text-sm font-medium mb-3">Booking Studio</h4>

                <form method="POST" action="{{ route('customer.studio.booking') }}" id="bookingForm">
                    @csrf
                    <input type="hidden" name="studio_id" value="{{ $studio->id }}">
                    <input type="hidden" name="tipe_booking" id="tipe_booking" value="studio">
                    <input type="hidden" name="paket_studio_id" id="paket_studio_id" value="">
                    <input type="hidden" name="durasi_jam" id="durasi_jam" value="1">

                    <div class="mb-3">
                        <label class="label"><span class="label-text">Pilih Studio Saja</span></label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="tipe_radio" value="studio" class="radio radio-sm radio-primary" checked
                                   onchange="selectStudio()">
                            <span class="text-sm">{{ $studio->harga_per_jam_formatted }} / jam</span>
                        </label>
                    </div>

                    <div id="durasi_input" class="mb-3">
                        <label class="label"><span class="label-text">Durasi (jam)</span></label>
                        <input type="number" name="durasi_manual" class="input input-bordered w-full text-sm" value="1" min="1"
                               onchange="updateHarga()">
                    </div>

                    <div class="mb-3">
                        <label class="label"><span class="label-text">Tanggal Booking</span></label>
                        <input type="date" name="tanggal_booking" class="input input-bordered w-full text-sm" required
                               min="{{ date('Y-m-d') }}">
                        @error('tanggal_booking') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="label"><span class="label-text">Jam Mulai</span></label>
                        <input type="time" name="jam_mulai" class="input input-bordered w-full text-sm" required>
                        @error('jam_mulai') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="label"><span class="label-text">Metode Pembayaran</span></label>
                        <div class="space-y-2">
                            @foreach($paymentMethods as $method)
                            <label class="flex items-start gap-3 cursor-pointer p-2 rounded-lg border border-base-300 has-checked:border-primary">
                                <input type="radio" name="payment_method_id" value="{{ $method->id }}"
                                       class="radio radio-sm radio-primary mt-0.5 payment-method"
                                       data-fee-percentage="{{ $method->fee_percentage }}"
                                       data-fee-flat="{{ $method->fee_flat }}"
                                       {{ $loop->first ? 'checked' : '' }}
                                       onchange="updateHarga()">
                                <div>
                                    <span class="text-sm font-medium">{{ $method->name }}</span>
                                    @if($method->fee_percentage > 0 || $method->fee_flat > 0)
                                    <span class="text-xs text-base-content/60 block">
                                        Biaya admin:
                                        @if($method->fee_percentage > 0){{ $method->fee_percentage }}%@endif
                                        @if($method->fee_flat > 0)+ Rp {{ number_format($method->fee_flat, 0, ',', '.') }}@endif
                                    </span>
                                    @endif
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="label"><span class="label-text">Catatan</span></label>
                        <textarea name="catatan" class="textarea textarea-bordered w-full text-sm" rows="2" placeholder="Catatan tambahan..."></textarea>
                    </div>

                    <div class="border-t border-base-300 pt-3 mb-3 space-y-1">
                        <div class="flex justify-between text-sm">
                            <span>Biaya Studio:</span>
                            <span id="total_harga_display">{{ $studio->harga_per_jam_formatted }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span>Biaya Admin:</span>
                            <span id="admin_fee_display">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-sm font-bold border-t border-base-200 pt-1">
                            <span>Total:</span>
                            <span id="grand_total_display">{{ $studio->harga_per_jam_formatted }}</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm w-full">Booking Sekarang</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const hargaPerJam = {{ $studio->harga_per_jam }};

    function selectPaket(paketId, harga, durasi) {
        document.getElementById('tipe_booking').value = 'paket';
        document.getElementById('paket_studio_id').value = paketId;
        document.getElementById('durasi_jam').value = durasi;
        document.getElementById('durasi_input').classList.add('hidden');
        // Uncheck studio radio
        document.querySelector('input[name="tipe_radio"][value="studio"]').checked = false;
        updateHarga();
    }

    function selectStudio() {
        document.getElementById('tipe_booking').value = 'studio';
        document.getElementById('paket_studio_id').value = '';
        document.getElementById('durasi_input').classList.remove('hidden');
        // Uncheck all paket radios
        document.querySelectorAll('.booking-type-paket').forEach(el => el.checked = false);
        updateHarga();
    }

    function getBaseHarga() {
        const tipe = document.getElementById('tipe_booking').value;
        if (tipe === 'paket') {
            const selected = document.querySelector('.booking-type-paket:checked');
            return selected ? parseInt(selected.dataset.harga) : 0;
        }
        const durasi = parseInt(document.getElementById('durasi_jam').value) || 1;
        return hargaPerJam * durasi;
    }

    function getSelectedPaymentMethod() {
        return document.querySelector('.payment-method:checked');
    }

    function calculateAdminFee(amount) {
        const method = getSelectedPaymentMethod();
        if (!method) return 0;
        const percentage = parseFloat(method.dataset.feePercentage) || 0;
        const flat = parseFloat(method.dataset.feeFlat) || 0;
        return (amount * percentage / 100) + flat;
    }

    function updateHarga() {
        const base = getBaseHarga();
        const adminFee = calculateAdminFee(base);
        const grandTotal = base + adminFee;

        document.getElementById('durasi_jam').value = document.getElementById('tipe_booking').value === 'paket'
            ? (document.querySelector('.booking-type-paket:checked')?.dataset.durasi || 1)
            : (parseInt(document.querySelector('input[name="durasi_manual"]')?.value) || 1);

        document.getElementById('total_harga_display').textContent = 'Rp ' + base.toLocaleString('id-ID');
        document.getElementById('admin_fee_display').textContent = 'Rp ' + adminFee.toLocaleString('id-ID');
        document.getElementById('grand_total_display').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
    }
</script>
@endpush
@endsection

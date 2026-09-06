@extends('layouts.admin')

@section('title', 'Brands')
@section('page-title', 'Brands')

@section('content')
<x-flash-messages />
<div class="bg-white rounded-2xl border border-[#f0f0f2]">
    <div class="p-5">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-base font-semibold">Manajemen Brand</h2>
            <button class="btn-dark-apple !text-sm !px-3 !py-1.5" onclick="document.getElementById('addBrandModal').showModal()">Tambah Brand</button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th>Nama Brand</th>
                        <th>Deskripsi</th>
                        <th>Jumlah Produk</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($brands as $brand)
                    <tr>
                        <td class="font-medium">{{ $brand->nama_brand }}</td>
                        <td class="text-xs text-[#6e6e73]">{{ Str::limit($brand->deskripsi, 60) }}</td>
                        <td>{{ $brand->products_count ?? $brand->products()->count() }}</td>
                        <td>
                            <span class="badge {{ $brand->status === 'active' ? 'badge-success' : 'badge-apple' }} text-xs">
                                {{ $brand->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <button class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors text-[#ff9500]"
                                        onclick="editBrand('{{ $brand->id }}', '{{ addslashes($brand->nama_brand) }}', '{{ addslashes($brand->deskripsi ?? '') }}', '{{ $brand->status }}')">Edit</button>
                                <form method="POST" action="{{ route('admin.brands.destroy', $brand->id) }}" onsubmit="return confirm('Hapus brand ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-[#6e6e73] hover:bg-[#f5f5f7] rounded-xl !text-[10px] !px-2 !py-0.5 transition-colors text-[#d70015]">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-[#6e6e73] py-4">Belum ada brand</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<dialog id="addBrandModal" class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="fixed inset-0 z-50 flex items-center justify-center-box">
        <h3 class="font-semibold text-lg mb-4">Tambah Brand</h3>
        <form method="POST" action="{{ route('admin.brands.store') }}">
            @csrf
            <div class=" mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Nama Brand</span></label>
                <input type="text" name="nama_brand" class="input-apple" required>
            </div>
            <div class=" mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Deskripsi</span></label>
                <textarea name="deskripsi" class="input-apple resize-none" rows="3"></textarea>
            </div>
            <div class=" mb-4">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Status</span></label>
                <select name="status" class="select-apple">
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                </select>
            </div>
            <div class="fixed inset-0 z-50 flex items-center justify-center-action">
                <button type="button" class="btn-dark-apple" onclick="document.getElementById('addBrandModal').close()">Batal</button>
                <button type="submit" class="btn-dark-apple">Simpan</button>
            </div>
        </form>
    </div>
</dialog>

<dialog id="editBrandModal" class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="fixed inset-0 z-50 flex items-center justify-center-box">
        <h3 class="font-semibold text-lg mb-4">Edit Brand</h3>
        <form method="POST" id="editBrandForm">
            @csrf @method('PUT')
            <div class=" mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Nama Brand</span></label>
                <input type="text" name="nama_brand" id="editBrandName" class="input-apple" required>
            </div>
            <div class=" mb-3">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Deskripsi</span></label>
                <textarea name="deskripsi" id="editBrandDesc" class="input-apple resize-none" rows="3"></textarea>
            </div>
            <div class=" mb-4">
                <label class="block text-xs font-medium text-[#86868b] mb-1.5"><span class="">Status</span></label>
                <select name="status" id="editBrandStatus" class="select-apple">
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                </select>
            </div>
            <div class="fixed inset-0 z-50 flex items-center justify-center-action">
                <button type="button" class="btn-dark-apple" onclick="document.getElementById('editBrandModal').close()">Batal</button>
                <button type="submit" class="btn-dark-apple">Simpan</button>
            </div>
        </form>
    </div>
</dialog>

@push('scripts')
<script>
function editBrand(id, name, desc, status) {
    document.getElementById('editBrandForm').action = "{{ url('admin/brands') }}/" + id;
    document.getElementById('editBrandName').value = name;
    document.getElementById('editBrandDesc').value = desc;
    document.getElementById('editBrandStatus').value = status;
    document.getElementById('editBrandModal').showModal();
}
</script>
@endpush
@endsection

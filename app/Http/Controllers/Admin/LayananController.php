<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\PaketLayanan;
use App\Models\LayananBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LayananController extends Controller
{
    public function index(Request $request)
    {
        $query = Layanan::withCount('pakets');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_layanan', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $layanans = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.layanan.index', compact('layanans'));
    }

    public function create()
    {
        return view('admin.layanan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_layanan' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'kategori' => 'nullable|string|max:50',
            'harga_mulai' => 'required|numeric|min:0',
            'ikon' => 'nullable|string|max:100',
            'gambar_utama' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['slug'] = Str::slug($validated['nama_layanan']) . '-' . Str::random(4);

        if ($request->hasFile('gambar_utama')) {
            $filename = 'layanan-' . time() . '.' . $request->file('gambar_utama')->getClientOriginalExtension();
            $validated['gambar_utama'] = $request->file('gambar_utama')->storeAs('layanan', $filename, 'public');
        }

        Layanan::create($validated);

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $layanan = Layanan::with('paketActive')->withCount('bookings')->findOrFail($id);
        return view('admin.layanan.show', compact('layanan'));
    }

    public function edit($id)
    {
        $layanan = Layanan::findOrFail($id);
        return view('admin.layanan.edit', compact('layanan'));
    }

    public function update(Request $request, $id)
    {
        $layanan = Layanan::findOrFail($id);

        $validated = $request->validate([
            'nama_layanan' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'kategori' => 'nullable|string|max:50',
            'harga_mulai' => 'required|numeric|min:0',
            'ikon' => 'nullable|string|max:100',
            'gambar_utama' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('gambar_utama')) {
            if ($layanan->gambar_utama && Storage::disk('public')->exists($layanan->gambar_utama)) {
                Storage::disk('public')->delete($layanan->gambar_utama);
            }
            $filename = 'layanan-' . time() . '.' . $request->file('gambar_utama')->getClientOriginalExtension();
            $validated['gambar_utama'] = $request->file('gambar_utama')->storeAs('layanan', $filename, 'public');
        }

        $layanan->update($validated);

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $layanan = Layanan::withCount('bookings')->findOrFail($id);

        if ($layanan->bookings_count > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus layanan yang memiliki riwayat booking.');
        }

        if ($layanan->gambar_utama && Storage::disk('public')->exists($layanan->gambar_utama)) {
            Storage::disk('public')->delete($layanan->gambar_utama);
        }

        $layanan->delete();

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Layanan berhasil dihapus.');
    }

    // Paket CRUD
    public function paketIndex($layananId)
    {
        $layanan = Layanan::findOrFail($layananId);
        $pakets = $layanan->pakets()->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.layanan.paket_index', compact('layanan', 'pakets'));
    }

    public function paketCreate($layananId)
    {
        $layanan = Layanan::findOrFail($layananId);
        return view('admin.layanan.paket_create', compact('layanan'));
    }

    public function paketStore(Request $request, $layananId)
    {
        $layanan = Layanan::findOrFail($layananId);

        $validated = $request->validate([
            'nama_paket' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'durasi_jam' => 'required|integer|min:1',
            'include' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['layanan_id'] = $layanan->id;
        $validated['slug'] = Str::slug($validated['nama_paket']) . '-' . Str::random(4);

        if ($request->hasFile('gambar')) {
            $filename = 'paket-layanan-' . time() . '.' . $request->file('gambar')->getClientOriginalExtension();
            $validated['gambar'] = $request->file('gambar')->storeAs('layanan/paket', $filename, 'public');
        }

        PaketLayanan::create($validated);

        return redirect()->route('admin.layanan.paket.index', $layanan->id)
            ->with('success', 'Paket layanan berhasil ditambahkan.');
    }

    public function paketEdit($layananId, $paketId)
    {
        $layanan = Layanan::findOrFail($layananId);
        $paket = PaketLayanan::where('layanan_id', $layananId)->findOrFail($paketId);
        return view('admin.layanan.paket_edit', compact('layanan', 'paket'));
    }

    public function paketUpdate(Request $request, $layananId, $paketId)
    {
        $layanan = Layanan::findOrFail($layananId);
        $paket = PaketLayanan::where('layanan_id', $layananId)->findOrFail($paketId);

        $validated = $request->validate([
            'nama_paket' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'durasi_jam' => 'required|integer|min:1',
            'include' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('gambar')) {
            if ($paket->gambar && Storage::disk('public')->exists($paket->gambar)) {
                Storage::disk('public')->delete($paket->gambar);
            }
            $filename = 'paket-layanan-' . time() . '.' . $request->file('gambar')->getClientOriginalExtension();
            $validated['gambar'] = $request->file('gambar')->storeAs('layanan/paket', $filename, 'public');
        }

        $paket->update($validated);

        return redirect()->route('admin.layanan.paket.index', $layanan->id)
            ->with('success', 'Paket layanan berhasil diperbarui.');
    }

    public function paketDestroy($layananId, $paketId)
    {
        $paket = PaketLayanan::where('layanan_id', $layananId)->findOrFail($paketId);

        if ($paket->gambar && Storage::disk('public')->exists($paket->gambar)) {
            Storage::disk('public')->delete($paket->gambar);
        }

        $paket->delete();

        return redirect()->route('admin.layanan.paket.index', $layananId)
            ->with('success', 'Paket layanan berhasil dihapus.');
    }

    // Bookings Management
    public function bookings(Request $request)
    {
        $query = LayananBooking::with(['user', 'layanan', 'paketLayanan', 'paymentMethod']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($qq) => $qq->where('nama', 'like', "%{$search}%"))
                  ->orWhereHas('layanan', fn($qq) => $qq->where('nama_layanan', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('layanan_id')) {
            $query->where('layanan_id', $request->layanan_id);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);
        $layanans = Layanan::active()->orderBy('nama_layanan')->get(['id', 'nama_layanan']);

        return view('admin.layanan.bookings', compact('bookings', 'layanans'));
    }

    public function bookingUpdateStatus(Request $request, $id)
    {
        $booking = LayananBooking::findOrFail($id);

        $validated = $request->validate([
            'status' => 'nullable|in:pending,confirmed,completed,cancelled',
            'payment_status' => 'nullable|in:pending,paid,failed,expired,refunded',
        ]);

        $data = [];
        if (isset($validated['status']) && $validated['status'] !== $booking->status) {
            $data['status'] = $validated['status'];
        }
        if (isset($validated['payment_status']) && $validated['payment_status'] !== $booking->payment_status) {
            $data['payment_status'] = $validated['payment_status'];
            if ($validated['payment_status'] === 'paid' && !$booking->paid_at) {
                $data['paid_at'] = now();
            }
        }

        if (empty($data)) {
            return redirect()->back()->with('info', 'Tidak ada perubahan status.');
        }

        $booking->update($data);

        return redirect()->back()
            ->with('success', 'Status booking berhasil diperbarui.');
    }

    public function bookingPrint($id)
    {
        $booking = LayananBooking::with(['user', 'layanan', 'paketLayanan', 'paymentMethod'])
            ->findOrFail($id);
        return view('admin.layanan.print', compact('booking'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Studio;
use App\Models\PaketStudio;
use App\Models\StudioBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudioController extends Controller
{
    public function index(Request $request)
    {
        $query = Studio::withCount('pakets');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_studio', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $studios = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.studio.index', compact('studios'));
    }

    public function create()
    {
        return view('admin.studio.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_studio' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'fasilitas' => 'nullable|string',
            'harga_per_jam' => 'required|numeric|min:0',
            'gambar_utama' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['slug'] = Str::slug($validated['nama_studio']) . '-' . Str::random(4);

        if ($request->hasFile('gambar_utama')) {
            $filename = 'studio-' . time() . '.' . $request->file('gambar_utama')->getClientOriginalExtension();
            $validated['gambar_utama'] = $request->file('gambar_utama')->storeAs('studio', $filename, 'public');
        }

        Studio::create($validated);

        return redirect()->route('admin.studio.index')
            ->with('success', 'Studio berhasil ditambahkan.');
    }

    public function show($id)
    {
        $studio = Studio::with('paketActive')->withCount('bookings')->findOrFail($id);
        return view('admin.studio.show', compact('studio'));
    }

    public function edit($id)
    {
        $studio = Studio::findOrFail($id);
        return view('admin.studio.edit', compact('studio'));
    }

    public function update(Request $request, $id)
    {
        $studio = Studio::findOrFail($id);

        $validated = $request->validate([
            'nama_studio' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'fasilitas' => 'nullable|string',
            'harga_per_jam' => 'required|numeric|min:0',
            'gambar_utama' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('gambar_utama')) {
            if ($studio->gambar_utama && Storage::disk('public')->exists($studio->gambar_utama)) {
                Storage::disk('public')->delete($studio->gambar_utama);
            }
            $filename = 'studio-' . time() . '.' . $request->file('gambar_utama')->getClientOriginalExtension();
            $validated['gambar_utama'] = $request->file('gambar_utama')->storeAs('studio', $filename, 'public');
        }

        $studio->update($validated);

        return redirect()->route('admin.studio.index')
            ->with('success', 'Studio berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $studio = Studio::withCount('bookings')->findOrFail($id);

        if ($studio->bookings_count > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus studio yang memiliki riwayat booking.');
        }

        if ($studio->gambar_utama && Storage::disk('public')->exists($studio->gambar_utama)) {
            Storage::disk('public')->delete($studio->gambar_utama);
        }

        $studio->delete();

        return redirect()->route('admin.studio.index')
            ->with('success', 'Studio berhasil dihapus.');
    }

    // Paket CRUD
    public function paketIndex($studioId)
    {
        $studio = Studio::findOrFail($studioId);
        $pakets = $studio->pakets()->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.studio.paket_index', compact('studio', 'pakets'));
    }

    public function paketCreate($studioId)
    {
        $studio = Studio::findOrFail($studioId);
        return view('admin.studio.paket_create', compact('studio'));
    }

    public function paketStore(Request $request, $studioId)
    {
        $studio = Studio::findOrFail($studioId);

        $validated = $request->validate([
            'nama_paket' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'durasi_jam' => 'required|integer|min:1',
            'include_alat' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['studio_id'] = $studio->id;
        $validated['slug'] = Str::slug($validated['nama_paket']) . '-' . Str::random(4);

        if ($request->hasFile('gambar')) {
            $filename = 'paket-' . time() . '.' . $request->file('gambar')->getClientOriginalExtension();
            $validated['gambar'] = $request->file('gambar')->storeAs('studio/paket', $filename, 'public');
        }

        PaketStudio::create($validated);

        return redirect()->route('admin.studio.paket.index', $studio->id)
            ->with('success', 'Paket studio berhasil ditambahkan.');
    }

    public function paketEdit($studioId, $paketId)
    {
        $studio = Studio::findOrFail($studioId);
        $paket = PaketStudio::where('studio_id', $studioId)->findOrFail($paketId);
        return view('admin.studio.paket_edit', compact('studio', 'paket'));
    }

    public function paketUpdate(Request $request, $studioId, $paketId)
    {
        $studio = Studio::findOrFail($studioId);
        $paket = PaketStudio::where('studio_id', $studioId)->findOrFail($paketId);

        $validated = $request->validate([
            'nama_paket' => 'required|string|max:200',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'durasi_jam' => 'required|integer|min:1',
            'include_alat' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('gambar')) {
            if ($paket->gambar && Storage::disk('public')->exists($paket->gambar)) {
                Storage::disk('public')->delete($paket->gambar);
            }
            $filename = 'paket-' . time() . '.' . $request->file('gambar')->getClientOriginalExtension();
            $validated['gambar'] = $request->file('gambar')->storeAs('studio/paket', $filename, 'public');
        }

        $paket->update($validated);

        return redirect()->route('admin.studio.paket.index', $studio->id)
            ->with('success', 'Paket studio berhasil diperbarui.');
    }

    public function paketDestroy($studioId, $paketId)
    {
        $paket = PaketStudio::where('studio_id', $studioId)->findOrFail($paketId);

        if ($paket->gambar && Storage::disk('public')->exists($paket->gambar)) {
            Storage::disk('public')->delete($paket->gambar);
        }

        $paket->delete();

        return redirect()->route('admin.studio.paket.index', $studioId)
            ->with('success', 'Paket studio berhasil dihapus.');
    }

    public function bookings(Request $request)
    {
        $query = StudioBooking::with(['user', 'studio', 'paketStudio', 'paymentMethod']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($qq) => $qq->where('nama', 'like', "%{$search}%"))
                  ->orWhereHas('studio', fn($qq) => $qq->where('nama_studio', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('studio_id')) {
            $query->where('studio_id', $request->studio_id);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);
        $studios = Studio::active()->orderBy('nama_studio')->get(['id', 'nama_studio']);

        return view('admin.studio.bookings', compact('bookings', 'studios'));
    }

    public function bookingUpdateStatus(Request $request, $id)
    {
        $booking = StudioBooking::findOrFail($id);

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
        $booking = StudioBooking::with(['user', 'studio', 'paketStudio', 'paymentMethod'])
            ->findOrFail($id);
        return view('admin.studio.print', compact('booking'));
    }
}

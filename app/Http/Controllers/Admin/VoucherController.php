<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Models\User;
use App\Models\KategoriProduk;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $query = Voucher::with(['user', 'kategori', 'produk']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_voucher', 'like', "%{$search}%")
                  ->orWhere('nama_voucher', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status_filter')) {
            if ($request->status_filter === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status_filter === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status_filter === 'expired') {
                $query->where('end_date', '<', now());
            } elseif ($request->status_filter === 'upcoming') {
                $query->where('start_date', '>', now());
            }
        }

        $vouchers = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        $users = User::where('role', 'customer')->orderBy('nama')->get();
        $categories = KategoriProduk::where('status', 'active')->orderBy('nama_kategori')->get();
        $products = Produk::where('status', 'available')->orderBy('nama_produk')->get();
        return view('admin.vouchers.create', compact('users', 'categories', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_voucher' => 'nullable|string|max:50|unique:vouchers,kode_voucher',
            'nama_voucher' => 'required|string|max:100',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'kuota' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
            'user_id' => 'nullable|exists:users,id',
            'kategori_id' => 'nullable|exists:kategori_produk,id',
            'produk_id' => 'nullable|exists:produk,id',
        ]);

        $data = $request->all();
        if (empty($data['kode_voucher'])) {
            $data['kode_voucher'] = strtoupper(Str::random(8));
        }
        $data['is_active'] = $request->boolean('is_active');

        Voucher::create($data);

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher berhasil dibuat.');
    }

    public function show(Voucher $voucher)
    {
        $voucher->load(['user', 'kategori', 'produk', 'usages.user']);
        return view('admin.vouchers.show', compact('voucher'));
    }

    public function edit(Voucher $voucher)
    {
        $users = User::where('role', 'customer')->orderBy('nama')->get();
        $categories = KategoriProduk::where('status', 'active')->orderBy('nama_kategori')->get();
        $products = Produk::where('status', 'available')->orderBy('nama_produk')->get();
        return view('admin.vouchers.edit', compact('voucher', 'users', 'categories', 'products'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'kode_voucher' => 'required|string|max:50|unique:vouchers,kode_voucher,' . $voucher->id,
            'nama_voucher' => 'required|string|max:100',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'kuota' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
            'user_id' => 'nullable|exists:users,id',
            'kategori_id' => 'nullable|exists:kategori_produk,id',
            'produk_id' => 'nullable|exists:produk,id',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->boolean('is_active');

        $voucher->update($data);

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher berhasil diperbarui.');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher berhasil dihapus.');
    }

    public function toggleActive(Voucher $voucher)
    {
        $voucher->is_active = !$voucher->is_active;
        $voucher->save();

        return response()->json([
            'success' => true,
            'is_active' => $voucher->is_active,
        ]);
    }
}

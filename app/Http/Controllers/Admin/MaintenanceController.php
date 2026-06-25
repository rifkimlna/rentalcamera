<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use App\Models\Produk;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Maintenance::with('produk');
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan produk
        if ($request->filled('produk_id')) {
            $query->where('produk_id', $request->produk_id);
        }
        
        // Filter berdasarkan jenis maintenance
        if ($request->filled('jenis_maintenance')) {
            $query->where('jenis_maintenance', $request->jenis_maintenance);
        }
        
        // Filter berdasarkan tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_mulai', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_mulai', '<=', $request->end_date);
        }
        
        $maintenances = $query->orderBy('created_at', 'desc')->paginate(20);
        $products = Produk::where('status', 'available')->orderBy('nama_produk')->get();
        
        $statuses = [
            'pending' => 'Pending',
            'in_progress' => 'Dalam Proses',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];
        
        $jenisMaintenance = [
            'routine' => 'Rutin',
            'repair' => 'Perbaikan',
            'cleaning' => 'Pembersihan',
            'calibration' => 'Kalibrasi',
        ];
        
        return view('admin.maintenance.index', compact('maintenances', 'products', 'statuses', 'jenisMaintenance'));
    }

    public function create()
    {
        $products = Produk::where('status', 'available')->orderBy('nama_produk')->get();
        return view('admin.maintenance.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'serial_number' => 'nullable|string|max:100',
            'jenis_maintenance' => 'required|in:routine,repair,cleaning,calibration',
            'deskripsi' => 'required|string',
            'biaya' => 'nullable|numeric|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'teknisi' => 'nullable|string|max:100',
            'catatan' => 'nullable|string',
        ]);
        
        // Create maintenance record
        $maintenance = Maintenance::create($request->all());
        
        // Update product status if maintenance is active
        if (in_array($maintenance->status, ['pending', 'in_progress'])) {
            $product = Produk::find($maintenance->produk_id);
            if ($product) {
                $product->update(['status' => 'maintenance']);
            }
        }
        
        return redirect()->route('admin.maintenance.index')
            ->with('success', 'Jadwal maintenance berhasil ditambahkan.');
    }

    public function show($id)
    {
        $maintenance = Maintenance::with('produk')->findOrFail($id);
        return view('admin.maintenance.show', compact('maintenance'));
    }

    public function edit($id)
    {
        $maintenance = Maintenance::findOrFail($id);
        $products = Produk::where('status', 'available')
            ->orWhere('id', $maintenance->produk_id)
            ->orderBy('nama_produk')
            ->get();
            
        return view('admin.maintenance.edit', compact('maintenance', 'products'));
    }

    public function update(Request $request, $id)
    {
        $maintenance = Maintenance::findOrFail($id);
        $oldStatus = $maintenance->status;
        
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'serial_number' => 'nullable|string|max:100',
            'jenis_maintenance' => 'required|in:routine,repair,cleaning,calibration',
            'deskripsi' => 'required|string',
            'biaya' => 'nullable|numeric|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'teknisi' => 'nullable|string|max:100',
            'catatan' => 'nullable|string',
        ]);
        
        $maintenance->update($request->all());
        
        // Update product status based on maintenance status
        $product = Produk::find($maintenance->produk_id);
        if ($product) {
            if ($maintenance->status === 'completed') {
                $product->update(['status' => 'available']);
            } elseif (in_array($maintenance->status, ['pending', 'in_progress'])) {
                $product->update(['status' => 'maintenance']);
            } elseif ($maintenance->status === 'cancelled' && $oldStatus !== 'completed') {
                $product->update(['status' => 'available']);
            }
        }
        
        return redirect()->route('admin.maintenance.index')
            ->with('success', 'Maintenance berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $maintenance = Maintenance::findOrFail($id);
        
        // Update product status back to available if maintenance was active
        if (in_array($maintenance->status, ['pending', 'in_progress'])) {
            $product = Produk::find($maintenance->produk_id);
            if ($product) {
                $product->update(['status' => 'available']);
            }
        }
        
        $maintenance->delete();
        
        return redirect()->route('admin.maintenance.index')
            ->with('success', 'Maintenance berhasil dihapus.');
    }

    public function updateStatus(Request $request, $id)
    {
        $maintenance = Maintenance::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'catatan' => 'nullable|string',
        ]);
        
        $oldStatus = $maintenance->status;
        $maintenance->update([
            'status' => $request->status,
            'catatan' => $request->catatan,
            'tanggal_selesai' => $request->status === 'completed' ? now() : $maintenance->tanggal_selesai,
        ]);
        
        // Update product status
        $product = Produk::find($maintenance->produk_id);
        if ($product) {
            if ($request->status === 'completed') {
                $product->update(['status' => 'available']);
            } elseif (in_array($request->status, ['pending', 'in_progress'])) {
                $product->update(['status' => 'maintenance']);
            } elseif ($request->status === 'cancelled' && $oldStatus !== 'completed') {
                $product->update(['status' => 'available']);
            }
        }
        
        return redirect()->back()
            ->with('success', 'Status maintenance berhasil diperbarui.');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.portfolios.index', compact('portfolios'));
    }

    public function create()
    {
        return view('admin.portfolios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|in:foto,video',
            'url' => 'nullable|string|max:500',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $request->sort_order ?? 0;

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('portfolios', 'public');
        }

        if ($request->filled('url')) {
            $validated['embed_url'] = Portfolio::generateEmbedUrl($request->url);
            $validated['platform'] = Portfolio::detectPlatform($request->url);

            $detectedTipe = Portfolio::detectTipeFromUrl($request->url);
            if ($detectedTipe) {
                $validated['tipe'] = $detectedTipe;
            }
        }

        Portfolio::create($validated);

        return redirect()->route('admin.portfolios.index')
            ->with('success', 'Portfolio berhasil ditambahkan.');
    }

    public function edit(Portfolio $portfolio)
    {
        return view('admin.portfolios.edit', compact('portfolio'));
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|in:foto,video',
            'url' => 'nullable|string|max:500',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $request->sort_order ?? 0;

        if ($request->hasFile('gambar')) {
            if ($portfolio->gambar) {
                Storage::disk('public')->delete($portfolio->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('portfolios', 'public');
        }

        if ($request->filled('url')) {
            $validated['embed_url'] = Portfolio::generateEmbedUrl($request->url);
            $validated['platform'] = Portfolio::detectPlatform($request->url);

            $detectedTipe = Portfolio::detectTipeFromUrl($request->url);
            if ($detectedTipe) {
                $validated['tipe'] = $detectedTipe;
            }
        } else {
            $validated['embed_url'] = null;
            $validated['platform'] = null;
        }

        $portfolio->update($validated);

        return redirect()->route('admin.portfolios.index')
            ->with('success', 'Portfolio berhasil diperbarui.');
    }

    public function destroy(Portfolio $portfolio)
    {
        if ($portfolio->gambar) {
            Storage::disk('public')->delete($portfolio->gambar);
        }
        $portfolio->delete();

        return redirect()->route('admin.portfolios.index')
            ->with('success', 'Portfolio berhasil dihapus.');
    }

    public function toggleActive(Portfolio $portfolio)
    {
        $portfolio->update(['is_active' => !$portfolio->is_active]);
        return redirect()->route('admin.portfolios.index')
            ->with('success', 'Status portfolio berhasil diubah.');
    }
}

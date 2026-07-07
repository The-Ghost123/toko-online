<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('nama_kategori')->get();
        $query = Product::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_produk', 'like', "%{$keyword}%")
                    ->orWhere('deskripsi', 'like', "%{$keyword}%");
            });
        }

        $products = $query->latest()->paginate(10)->withQueryString();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'        => 'required|exists:categories,id',
            'nama_produk'        => 'required|string|max:150',
            'deskripsi'          => 'nullable|string',
            'harga'              => 'required|integer|min:0',
            'foto_produk'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'ketersediaan_stok'  => 'required|in:tersedia,habis',
            'nomor_whatsapp'     => 'required|string|max:20',
        ]);

        // Handle upload
        if ($request->hasFile('foto_produk')) {
            $validated['foto_produk'] = $request->file('foto_produk')
                                                 ->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id'        => 'required|exists:categories,id',
            'nama_produk'        => 'required|string|max:150',
            'deskripsi'          => 'nullable|string',
            'harga'              => 'required|integer|min:0',
            'foto_produk'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'ketersediaan_stok'  => 'required|in:tersedia,habis',
            'nomor_whatsapp'     => 'required|string|max:20',
        ]);

        // Ganti foto jika ada upload baru
        if ($request->hasFile('foto_produk')) {
            // Hapus foto lama
            if ($product->foto_produk) {
                Storage::disk('public')->delete($product->foto_produk);
            }
            $validated['foto_produk'] = $request->file('foto_produk')
                                                 ->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil diperbarui!');
    }

    public function dashboard()
    {
        return view('admin.dashboard', [
            'totalProducts'  => Product::count(),
            'totalCategories'=> Category::count(),
            'tersedia'       => Product::where('ketersediaan_stok', 'tersedia')->count(),
            'habis'          => Product::where('ketersediaan_stok', 'habis')->count(),
            'latestProducts' => Product::with('category')->latest()->take(5)->get(),
        ]);
    }

    public function destroy(Product $product)
    {
        if ($product->foto_produk) {
            Storage::disk('public')->delete($product->foto_produk);
        }
        $product->delete();

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil dihapus!');
    }
}

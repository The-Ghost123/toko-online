<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\WebsiteActivity;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('products')->get();

        $query = Product::with('category')
                        ->where('ketersediaan_stok', 'tersedia');

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->kategori);
            });
        }

        // Pencarian nama produk
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_produk', 'like', "%{$keyword}%")
                  ->orWhere('deskripsi', 'like', "%{$keyword}%");
            });
        }

        WebsiteActivity::create([
            'type' => 'page_view',
            'page' => 'home',
            'user_id' => auth()->id(),
        ]);

        $productCount = $query->count();
        $products = $query->latest()->take(6)->get();

        return view('public.home', compact('products', 'categories', 'productCount'));
    }

    public function products(Request $request)
    {
        $categories = Category::withCount('products')->get();

        $query = Product::with('category')
                        ->where('ketersediaan_stok', 'tersedia');

        if ($request->filled('kategori')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->kategori);
            });
        }

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_produk', 'like', "%{$keyword}%")
                  ->orWhere('deskripsi', 'like', "%{$keyword}%");
            });
        }

        WebsiteActivity::create([
            'type' => 'page_view',
            'page' => 'product_listing',
            'user_id' => auth()->id(),
        ]);

        $products = $query->latest()->paginate(12)->withQueryString();

        return view('public.products', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        WebsiteActivity::create([
            'product_id' => $product->id,
            'type' => 'page_view',
            'page' => 'product_detail',
            'user_id' => auth()->id(),
        ]);

        $product->load(['reviews' => function ($query) {
            $query->latest();
        }, 'reviews.user']);

        return view('public.product-detail', compact('product'));
    }

    public function redirectToWhatsapp(Product $product)
    {
        WebsiteActivity::create([
            'product_id' => $product->id,
            'type' => 'whatsapp_click',
            'page' => 'product_detail',
            'user_id' => auth()->id(),
        ]);

        $text = urlencode('Halo, saya tertarik dengan produk ' . $product->nama_produk . '. Apakah masih tersedia?');

        return redirect()->away("https://wa.me/{$product->nomor_whatsapp}?text={$text}");
    }

    public function about()
    {
        $page = Page::firstOrCreate([
            'slug' => 'about',
        ], [
            'title' => 'FashionStore: Toko Fashion Online Profesional',
            'content' => '<p class="text-muted">FashionStore adalah toko online yang dibuat untuk mempromosikan koleksi fashion terbaru. Website ini dirancang untuk memudahkan pengunjung melihat katalog produk, mencari berdasarkan kategori, dan melakukan pemesanan via WhatsApp.</p><p class="text-muted">Kami menampilkan koleksi baju pria, baju wanita, sepatu, dan aksesori dengan tampilan responsif. Semua produk memiliki informasi detail, harga, dan status ketersediaan stok.</p><p class="text-muted">Website ini dibuat sebagai bagian dari Tugas Akhir menggunakan Laravel dan Bootstrap, dengan fokus pada presentasi produk yang rapi dan profesional.</p>',
            'image' => 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=1200&q=80',
        ]);

        return view('public.about', compact('page'));
    }
}
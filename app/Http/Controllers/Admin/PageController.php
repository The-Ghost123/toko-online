<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function edit()
    {
        $page = Page::firstOrCreate([
            'slug' => 'about',
        ], [
            'title' => 'FashionStore: Toko Fashion Online Profesional',
            'content' => '<p class="text-muted">FashionStore adalah toko online yang dibuat untuk mempromosikan koleksi fashion terbaru. Website ini dirancang untuk memudahkan pengunjung melihat katalog produk, mencari berdasarkan kategori, dan melakukan pemesanan via WhatsApp.</p><p class="text-muted">Kami menampilkan koleksi baju pria, baju wanita, sepatu, dan aksesori dengan tampilan responsif. Semua produk memiliki informasi detail, harga, dan status ketersediaan stok.</p><p class="text-muted">Website ini dibuat sebagai bagian dari Tugas Akhir menggunakan Laravel dan Bootstrap, dengan fokus pada presentasi produk yang rapi dan profesional.</p>',
            'image' => 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=1200&q=80',
        ]);

        return view('admin.about.edit', compact('page'));
    }

    public function update(Request $request)
    {
        $page = Page::where('slug', 'about')->firstOrFail();

        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only('title', 'content');

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada dan bukan URL eksternal
            if ($page->image && !str_starts_with($page->image, 'http')) {
                Storage::disk('public')->delete($page->image);
            }
            // Upload gambar baru
            $data['image'] = $request->file('image')->store('pages', 'public');
        }

        $page->update($data);

        return redirect()->route('admin.pages.about.edit')
                         ->with('success', 'Konten Tentang Kami berhasil diperbarui!');
    }
}

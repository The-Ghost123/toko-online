@extends('layouts.app')
@section('title', $page->title ?? 'Tentang Kami')
@section('content')

<div class="row align-items-center gy-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm overflow-hidden">
            <img src="{{ $page->image ? (str_starts_with($page->image, 'http') ? $page->image : asset('storage/' . $page->image)) : 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=1200&q=80' }}"
                 class="w-100" alt="{{ $page->title ?? 'Tentang Kami' }}"
                 style="height:420px; object-fit:cover;">
        </div>
    </div>
    <div class="col-lg-6">
        <span class="hero-tag">Tentang Kami</span>
        <h2>{{ $page->title ?? 'FashionStore: Toko Fashion Online Profesional' }}</h2>
        {!! $page->content ?? '<p class="text-muted">FashionStore adalah toko online yang dibuat untuk mempromosikan koleksi fashion terbaru. Website ini dirancang untuk memudahkan pengunjung melihat katalog produk, mencari berdasarkan kategori, dan melakukan pemesanan via WhatsApp.</p><p class="text-muted">Kami menampilkan koleksi baju pria, baju wanita, sepatu, dan aksesori dengan tampilan responsif. Semua produk memiliki informasi detail, harga, dan status ketersediaan stok.</p><p class="text-muted">Website ini dibuat sebagai bagian dari Tugas Akhir menggunakan Laravel dan Bootstrap, dengan fokus pada presentasi produk yang rapi dan profesional.</p>' !!}
        <div class="mt-4">
            <a href="{{ route('products') }}" class="btn btn-dark rounded-pill px-4 py-2">Lihat Produk</a>
            <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-outline-dark rounded-pill px-4 py-2 ms-2">Hubungi WhatsApp</a>
        </div>
    </div>
</div>

@endsection
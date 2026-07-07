@extends('layouts.app')
@section('title', 'Katalog Fashion Terkini')
@section('show-categories')
@endsection

@section('content')

<div class="catalog-hero mb-4">
    <div class="hero-text">
        <span class="hero-tag">FashionStore</span>
        <h2>Temukan Koleksi Fashion Terbaik</h2>
        <p>Jelajahi katalog kami dengan baju pria, baju wanita, sepatu, dan aksesori pilihan yang siap menunjang gaya harian Anda.</p>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('products') }}" class="btn-hero-primary">Lihat Semua Produk</a>
            <a href="{{ route('about') }}" class="btn btn-outline-dark rounded-pill px-4 py-2">Tentang Kami</a>
        </div>
    </div>
    <div class="hero-features">
        <div class="feat-item">
            <i class="bi bi-truck"></i>
            <span>Pengiriman Cepat</span>
        </div>
        <div class="feat-item">
            <i class="bi bi-shield-check"></i>
            <span>Produk Original</span>
        </div>
        <div class="feat-item">
            <i class="bi bi-whatsapp"></i>
            <span>Order via WA</span>
        </div>
    </div>
</div>

<div class="mb-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h3 class="mb-2">Kategori Unggulan</h3>
            <p class="text-muted mb-0">Cepat pilih kategori yang ingin Anda lihat untuk menemukan produk terbaik.</p>
        </div>
        <a href="{{ route('products') }}" class="btn btn-outline-dark rounded-pill">See More</a>
    </div>
    <div class="row row-cols-1 row-cols-md-3 g-3 mt-3">
        @forelse($categories->take(3) as $cat)
            <div class="col">
                <a href="{{ route('products', ['kategori' => $cat->slug]) }}" class="text-decoration-none text-dark">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden">
                        <img src="{{ $cat->foto_url }}" class="card-img-top" alt="{{ $cat->nama_kategori }}" style="height:220px; object-fit:cover;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $cat->nama_kategori }}</h5>
                            <p class="card-text text-muted">{{ $cat->deskripsi ?? 'Jelajahi produk terbaru pada kategori ini.' }}</p>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col">
                <div class="card h-100 border-0 shadow-sm overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1521334884684-d80222895322?auto=format&fit=crop&w=900&q=80" class="card-img-top" alt="Baju Pria" style="height:220px; object-fit:cover;">
                    <div class="card-body">
                        <h5 class="card-title">Baju Pria</h5>
                        <p class="card-text text-muted">Pilihan kemeja, kaos, dan outerwear pria.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 border-0 shadow-sm overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=80" class="card-img-top" alt="Baju Wanita" style="height:220px; object-fit:cover;">
                    <div class="card-body">
                        <h5 class="card-title">Baju Wanita</h5>
                        <p class="card-text text-muted">Model modern dan nyaman untuk tampilan feminin.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 border-0 shadow-sm overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1519741491071-20dc8d8f88b3?auto=format&fit=crop&w=900&q=80" class="card-img-top" alt="Sepatu" style="height:220px; object-fit:cover;">
                    <div class="card-body">
                        <h5 class="card-title">Sepatu</h5>
                        <p class="card-text text-muted">Sepatu kasual dan formal untuk berbagai kesempatan.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>

<div class="row align-items-center bg-white border rounded-4 shadow-sm p-4 mb-5">
    <div class="col-lg-7">
        <h3>Tentang FashionStore</h3>
        <p class="text-muted">FashionStore hadir untuk memudahkan pemasaran produk fashion secara profesional. Kami menampilkan katalog, filter kategori, dan tombol pemesanan via WhatsApp untuk memudahkan pengunjung.</p>
    </div>
    <div class="col-lg-5 text-lg-end">
        <a href="{{ route('about') }}" class="btn btn-outline-dark rounded-pill px-4">Selengkapnya</a>
    </div>
</div>

<div class="mb-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h3 class="mb-2">Produk Terpopuler</h3>
            <p class="text-muted mb-0">Menampilkan {{ $products->count() }} produk dari total {{ $productCount }} stok tersedia.</p>
        </div>
        <a href="{{ route('products') }}" class="btn btn-outline-dark rounded-pill">See More</a>
    </div>
</div>

@if($products->isEmpty())
    <div class="empty-state">
        <i class="bi bi-bag-x"></i>
        <p>Tidak ada produk tersedia.</p>
    </div>
@else
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
        @each('public.partials.product-card', $products, 'product')
    </div>
@endif

@endsection
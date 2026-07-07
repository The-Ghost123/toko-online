@extends('layouts.app')
@section('title', 'Wishlist Saya')
@section('content')

<div class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="mb-1">Wishlist Saya</h1>
                <p class="text-muted">Produk yang sudah Anda simpan untuk dibeli nanti.</p>
            </div>
        </div>

        @if($wishlists->isEmpty())
            <div class="alert alert-info rounded-4">
                Anda belum menambahkan produk apa pun ke wishlist. <a href="{{ route('products') }}">Jelajahi produk sekarang</a>.
            </div>
        @else
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                @foreach($wishlists as $wishlist)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm">
                            <img src="{{ $wishlist->product->foto_url }}" class="card-img-top" alt="{{ $wishlist->product->nama_produk }}" style="height: 220px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $wishlist->product->nama_produk }}</h5>
                                <p class="text-muted mb-2">{{ $wishlist->product->category->nama_kategori }}</p>
                                <p class="fw-bold mb-3">Rp {{ number_format($wishlist->product->harga, 0, ',', '.') }}</p>
                                <div class="mt-auto d-flex gap-2">
                                    <a href="{{ route('products.show', $wishlist->product) }}" class="btn btn-outline-dark btn-sm flex-grow-1">Detail</a>
                                    <form action="{{ route('wishlist.toggle', $wishlist->product) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $wishlists->links() }}
            </div>
        @endif
    </div>
</div>

@endsection

@extends('layouts.admin')
@section('title', 'Wishlist Produk')
@section('page-title', 'Wishlist untuk Produk')

@section('content')
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
        <div>
            <h6 class="fw-semibold mb-0">Wishlist untuk {{ $product->nama_produk }}</h6>
            <small class="text-muted">Jumlah: {{ $wishlistUsers->count() }} pengguna</small>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        @if($wishlistUsers->isEmpty())
            <div class="alert alert-info">Belum ada pengguna yang menyimpan produk ini ke wishlist.</div>
        @else
            <div class="list-group">
                @foreach($wishlistUsers as $entry)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">{{ $entry->user->name }}</div>
                            <small class="text-muted">{{ $entry->user->email }}</small>
                        </div>
                        <span class="text-muted">{{ $entry->created_at->diffForHumans() }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

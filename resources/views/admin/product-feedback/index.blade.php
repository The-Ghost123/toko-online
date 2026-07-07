@extends('layouts.admin')
@section('title', 'Ulasan Produk')
@section('page-title', 'Ulasan Produk')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card rounded-4 shadow-sm p-4 h-100">
            <div class="text-muted small">Produk dengan Ulasan</div>
            <div class="fw-bold display-6">{{ $summary['products_with_reviews'] }}</div>
            <div class="text-muted">Produk yang memiliki review pelanggan</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card rounded-4 shadow-sm p-4 h-100">
            <div class="text-muted small">Total Komentar</div>
            <div class="fw-bold display-6">{{ $summary['total_comments'] }}</div>
            <div class="text-muted">Total review pelanggan</div>
        </div>
    </div>
</div>

@if($products->isEmpty())
    <div class="card rounded-4 shadow-sm p-5 text-center text-muted">
        <div class="mb-3"><i class="bi bi-emoji-smile fs-1"></i></div>
        <h5>Tidak ada produk yang saat ini memiliki komentar.</h5>
        <p class="mb-0">Ulasan produk akan muncul di halaman ini ketika pelanggan memberikan rating atau komentar.</p>
    </div>
@else
    <div class="row g-4">
        @foreach($products as $product)
            <div class="col-12">
                <div class="card rounded-4 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 align-items-start">
                            <div>
                                <h5 class="fw-bold mb-1">{{ $product->nama_produk }}</h5>
                                <div class="text-muted small mb-2">Kategori: {{ $product->category->nama_kategori ?? 'Tidak tersedia' }}</div>
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-success">{{ $product->reviews_count }} review</span>
                                    <span class="badge bg-secondary">Stok: {{ ucfirst($product->ketersediaan_stok) }}</span>
                                </div>
                            </div>
                            <div class="text-end">
                                @if(request()->routeIs('admin.*'))
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-primary btn-sm rounded-pill">Kelola Produk</a>
                                @elseif(request()->routeIs('owner.*'))
                                    <a href="{{ route('owner.dashboard') }}" class="btn btn-outline-primary btn-sm rounded-pill">Dashboard Owner</a>
                                @else
                                    <a href="{{ route('products.show', $product) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill">Lihat Produk</a>
                                @endif
                            </div>
                        </div>

                        <div class="row g-4 mt-4">
                            <div class="col-lg-4">
                                <div class="rounded-4 overflow-hidden border border-gray-200">
                                    <img src="{{ $product->foto_url }}" alt="{{ $product->nama_produk }}" class="w-100 object-cover" style="min-height: 220px;">
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="rounded-4 border border-gray-200 p-4 bg-light">
                                    <h6 class="mb-3">Komentar Terbaru</h6>
                                    @if($product->reviews->isEmpty())
                                        <p class="text-muted mb-0">Belum ada komentar untuk produk ini.</p>
                                    @else
                                        <div class="list-group list-group-flush">
                                            @foreach($product->reviews->take(5) as $review)
                                                <div class="list-group-item px-0 py-3 border-0">
                                                    <div class="d-flex align-items-center justify-content-between gap-3">
                                                        <div>
                                                            <p class="mb-1 fw-semibold">{{ $review->user->name ?? 'Pengguna' }}</p>
                                                            <p class="small text-muted mb-0">{{ $review->created_at->format('d M Y H:i') }}</p>
                                                        </div>
                                                        <div class="text-warning small">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                    @if($review->comment)
                                                        @php
                                                            $comment = trim($review->comment);
                                                            $preview = strlen($comment) > 220 ? substr($comment, 0, 220) . '...' : $comment;
                                                            $isLong = strlen($comment) > 220;
                                                        @endphp
                                                        <p class="mt-2 mb-1 review-comment-preview" id="review-comment-preview-{{ $review->id }}">{{ $preview }}</p>
                                                        @if($isLong)
                                                            <p class="mt-2 mb-1 review-comment-full d-none" id="review-comment-full-{{ $review->id }}">{{ $comment }}</p>
                                                            <button type="button" class="btn btn-sm btn-link p-0" onclick="toggleReviewComment({{ $review->id }})" id="review-comment-toggle-{{ $review->id }}">Lihat selengkapnya</button>
                                                        @endif
                                                    @endif
                                                    @if($review->photo)
                                                        <div class="overflow-hidden rounded-3 border border-gray-200 mt-2" style="max-width: 240px;">
                                                            <img src="{{ $review->photo_url }}" alt="Foto review {{ $product->nama_produk }}" class="w-100 object-cover" style="height: 160px;">
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                        @if($product->reviews->count() > 5)
                                            <p class="small text-muted mt-3 mb-0">Menampilkan 5 komentar terbaru dari total {{ $product->reviews->count() }} komentar.</p>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $products->links("pagination::bootstrap-5") }}</div>
@endif

<script>
    function toggleReviewComment(reviewId) {
        const preview = document.getElementById(`review-comment-preview-${reviewId}`);
        const full = document.getElementById(`review-comment-full-${reviewId}`);
        const button = document.getElementById(`review-comment-toggle-${reviewId}`);

        if (!preview || !full || !button) return;

        if (full.classList.contains('d-none')) {
            full.classList.remove('d-none');
            preview.classList.add('d-none');
            button.textContent = 'Sembunyikan';
        } else {
            full.classList.add('d-none');
            preview.classList.remove('d-none');
            button.textContent = 'Lihat selengkapnya';
        }
    }
</script>
@endsection


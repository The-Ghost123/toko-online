@extends('layouts.app')
@section('title', 'Daftar Produk')
@section('show-categories')
@endsection

@section('content')

<div class="mb-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h2 class="mb-1">Daftar Produk</h2>
            @if(request('kategori'))
                <p class="text-muted mb-0">Menampilkan produk kategori <strong>{{ request('kategori') }}</strong>.</p>
            @elseif(request('search'))
                <p class="text-muted mb-0">Hasil pencarian untuk "<strong>{{ request('search') }}</strong>".</p>
            @else
                <p class="text-muted mb-0">Semua produk tersedia ditampilkan di sini.</p>
            @endif
        </div>
        <a href="{{ route('products') }}" class="btn btn-dark rounded-pill">Reset Filter</a>
    </div>
</div>

<div class="row align-items-end gy-3 mb-4">
    <div class="col-lg-4">
        <form action="{{ route('products') }}" method="GET">
            <label for="kategori" class="form-label text-muted small">Filter Kategori</label>
            <select id="kategori" name="kategori" class="form-select" onchange="this.form.submit()">
                <option value="" {{ !request('kategori') ? 'selected' : '' }}>Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}" {{ request('kategori') == $cat->slug ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                @endforeach
            </select>
        </form>
    </div>
    <div class="col-lg-8">
        <form action="{{ route('products') }}" method="GET" class="d-flex gap-2">
            @if(request('kategori'))
                <input type="hidden" name="kategori" value="{{ request('kategori') }}">
            @endif
            <input type="search" name="search" class="form-control" placeholder="Cari produk..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-dark rounded-pill px-4">Cari</button>
        </form>
    </div>
</div>

@if(request('search'))
<div class="d-flex align-items-center justify-content-between mb-4">
    <p class="text-muted mb-0" style="font-size:.88rem;">
        Hasil ditemukan: <strong>{{ $products->total() }}</strong>
    </p>
    <a href="{{ route('products') }}" style="font-size:.82rem; color:var(--muted); text-decoration:none;">
        <i class="bi bi-x-circle me-1"></i>Hapus Pencarian
    </a>
</div>
@endif

@if($products->isEmpty())
    <div class="empty-state">
        <i class="bi bi-bag-x"></i>
        <p>Belum ada produk yang cocok dengan filter saat ini.</p>
    </div>
@else
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
        @each('public.partials.product-card', $products, 'product')
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $products->links() }}
    </div>
@endif

@endsection

{{-- Lightbox modal for product images --}}
<div class="modal fade" id="productLightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 text-center">
                <button type="button" class="btn btn-light rounded-circle position-absolute top-0 end-0 m-3 shadow-sm" data-bs-dismiss="modal" aria-label="Tutup" style="width:40px;height:40px;display:grid;place-items:center;">
                    <i class="bi bi-x-lg"></i>
                </button>
                <img src="" alt="" class="lightbox-img img-fluid rounded" style="max-height: 80vh; object-fit: contain; display: inline-block;">
                <div class="small text-muted mt-2">Klik di luar gambar atau tekan <kbd>Esc</kbd> untuk menutup.</div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('productLightboxModal');
    if (!modalEl) return;
    const modalImg = modalEl.querySelector('.lightbox-img');
    const bsModal = new bootstrap.Modal(modalEl);

    document.body.addEventListener('click', function(e) {
        const trigger = e.target.closest('.product-image-trigger');
        if (!trigger) return;
        e.preventDefault();
        const src = trigger.dataset.image;
        const name = trigger.dataset.name || '';
        modalImg.src = src;
        modalImg.alt = name;
        bsModal.show();
    });

    // keyboard accessibility: open with Enter/Space when focused
    document.body.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            const active = document.activeElement;
            if (active && active.classList && active.classList.contains('product-image-trigger')) {
                e.preventDefault();
                active.click();
            }
        }
    });

    modalEl.addEventListener('hidden.bs.modal', function() {
        modalImg.src = '';
    });
});
</script>
@endpush
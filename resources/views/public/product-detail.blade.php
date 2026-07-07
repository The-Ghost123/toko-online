@extends('layouts.app')
@section('title', $product->nama_produk)
@section('content')

<div class="container py-5">
    <div class="row g-4 align-items-start">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="ratio ratio-4x3">
                    <div class="product-image-trigger h-100 w-100 d-block" role="button" tabindex="0" data-image="{{ $product->foto_url }}" data-name="{{ $product->nama_produk }}">
                        <img src="{{ $product->foto_url }}" alt="{{ $product->nama_produk }}" class="object-fit-cover rounded-4 w-100 h-100" loading="lazy" style="object-fit: cover; cursor: pointer;">
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex flex-wrap gap-2">
                <a href="{{ route('products') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Produk
                </a>
                <a href="{{ route('products.whatsapp', $product) }}" target="_blank" class="btn btn-success rounded-pill px-4 py-2">
                    <i class="bi bi-whatsapp me-2"></i> Order via WhatsApp
                </a>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="bg-white rounded-4 p-4 shadow-sm border border-gray-200">
                <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary mb-3">Detail Produk</span>
                <h1 class="display-6 fw-bold mb-3">{{ $product->nama_produk }}</h1>

                <div class="mb-4 d-flex flex-wrap gap-3 align-items-center">
                    <span class="badge rounded-pill bg-secondary text-white py-2 px-3">{{ $product->category->nama_kategori }}</span>
                    <span class="badge rounded-pill {{ $product->ketersediaan_stok === 'tersedia' ? 'bg-success' : 'bg-danger' }} text-white py-2 px-3">{{ ucfirst($product->ketersediaan_stok) }}</span>
                </div>

                <div class="d-flex align-items-center gap-4 mb-4 flex-wrap">
                    <div>
                        <p class="text-muted mb-1">Harga</p>
                        <p class="h2 fw-bold mb-0">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-4 bg-light p-3 d-flex align-items-center gap-3">
                        <div class="text-warning small">
                            @php $rating = $product->average_rating ? round($product->average_rating) : 0; @endphp
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi {{ $i <= $rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                        </div>
                        <div>
                            <div class="small text-dark fw-semibold">{{ $product->review_count > 0 ? number_format($product->average_rating, 1) : '-' }}</div>
                            <div class="small text-muted">{{ $product->review_count }} ulasan</div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h5 class="fw-semibold mb-2">Deskripsi Produk</h5>
                    <p class="text-muted mb-0">{{ $product->deskripsi ?? 'Belum ada deskripsi untuk produk ini.' }}</p>
                </div>

                <div class="rounded-4 border border-gray-200 p-4 mb-4 bg-light">
                    <div class="mb-3">
                        <p class="small text-muted mb-1">Jumlah</p>
                        <div class="input-group" style="max-width: 210px;">
                            <button class="btn btn-outline-secondary" type="button" onclick="decreaseQuantity()">−</button>
                            <input type="number" id="quantity" class="form-control text-center" value="1" min="1">
                            <button class="btn btn-outline-secondary" type="button" onclick="increaseQuantity()">+</button>
                        </div>
                    </div>

                    @if ($product->ketersediaan_stok === 'tersedia')
                        <div class="d-grid gap-2">
                            <button type="button" onclick="addProductToCart({{ $product->id }})" class="btn btn-dark rounded-pill px-4 py-2 w-100">
                                <i class="bi bi-cart-plus me-2"></i> Tambah ke Keranjang
                            </button>
                            <button type="button" onclick="buyNow({{ $product->id }})" class="btn btn-outline-dark rounded-pill px-4 py-2 w-100">
                                <i class="bi bi-bag-check me-2"></i> Beli Sekarang
                            </button>
                        </div>
                    @else
                        <button type="button" class="btn btn-secondary rounded-pill px-4 py-2 w-100" disabled>Produk Habis</button>
                    @endif
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <div class="rounded-4 bg-white border border-gray-200 p-3">
                            <span class="small text-muted">Kategori</span>
                            <div class="fw-semibold">{{ $product->category->nama_kategori }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="rounded-4 bg-white border border-gray-200 p-3">
                            <span class="small text-muted">Stok</span>
                            <div class="fw-semibold">{{ ucfirst($product->ketersediaan_stok) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-5">
        <div class="col-lg-8">
            <div class="bg-white rounded-4 p-4 shadow-sm border border-gray-200">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start gap-3 mb-4">
                    <div>
                        <h3 class="h5 mb-1">Ulasan Pembeli</h3>
                        <p class="small text-muted mb-0">Lihat pengalaman pembeli sebelumnya.</p>
                    </div>
                    @if($product->review_count > 0)
                        <div class="rounded-4 bg-light px-3 py-2 text-center">
                            <div class="text-warning mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= $rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor
                            </div>
                            <div class="small text-dark fw-semibold">{{ number_format($product->average_rating, 1) }}</div>
                            <div class="small text-muted">{{ $product->review_count }} ulasan</div>
                        </div>
                    @endif
                </div>

                @if($product->review_count > 0)
                    <div class="d-flex flex-column gap-4">
                        @foreach($product->reviews as $review)
                            <div class="rounded-4 border border-gray-200 p-4">
                                <div class="d-flex flex-column flex-sm-row justify-content-between gap-3 align-items-start">
                                    <div>
                                        <p class="fw-semibold mb-1">{{ $review->user->name ?? 'Pengguna' }}</p>
                                        <p class="small text-muted mb-0">{{ $review->created_at->format('d M Y') }}</p>
                                    </div>
                                    <div class="text-warning small">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor
                                    </div>
                                </div>

                                @if($review->comment)
                                    <p class="mt-3 text-body">{{ $review->comment }}</p>
                                @endif

                                @if($review->photo)
                                    <div class="mt-3 overflow-hidden rounded-4 border border-gray-200">
                                        <img src="{{ $review->photo_url }}" alt="Foto ulasan {{ $product->nama_produk }}" class="w-100 object-cover" style="max-height: 360px;">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-4 border border-dashed border-gray-300 p-4 text-center text-muted">
                        Belum ada ulasan untuk produk ini. Jadilah yang pertama memberi rating dan komentar!
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            <div class="bg-white rounded-4 p-4 shadow-sm border border-gray-200">
                <h4 class="h6 text-uppercase text-muted mb-3">Informasi Produk</h4>
                <dl class="row mb-0">
                    <dt class="col-6 text-muted">Kategori</dt>
                    <dd class="col-6 text-end">{{ $product->category->nama_kategori }}</dd>

                    <dt class="col-6 text-muted">Stok</dt>
                    <dd class="col-6 text-end">{{ ucfirst($product->ketersediaan_stok) }}</dd>

                    <dt class="col-6 text-muted">Harga</dt>
                    <dd class="col-6 text-end">Rp {{ number_format($product->harga, 0, ',', '.') }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<script>
    function increaseQuantity() {
        const input = document.getElementById('quantity');
        input.value = parseInt(input.value) + 1;
    }

    function decreaseQuantity() {
        const input = document.getElementById('quantity');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }

    async function addProductToCart(productId) {
        const quantity = parseInt(document.getElementById('quantity').value);
        try {
            await cart.addToCart(productId, quantity);
        } catch (error) {
            console.error('Error adding to cart:', error);
        }
    }

    async function buyNow(productId) {
        const quantity = parseInt(document.getElementById('quantity').value);

        try {
            await cart.addToCart(productId, quantity);
            const cartData = await cart.getCart();
            const cartItem = cartData.items.find(item => item.product_id === productId);

            if (!cartItem) {
                throw new Error('Produk tidak ditemukan di keranjang setelah ditambahkan. Silakan coba lagi.');
            }

            const params = new URLSearchParams();
            params.append('items[]', cartItem.id);
            window.location.href = `/checkout?${params.toString()}`;
        } catch (error) {
            console.error('Error processing buy now:', error);
        }
    }
</script>

@endsection

{{-- Lightbox modal for product detail image --}}
<div class="modal fade" id="productLightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 text-center">
                <button type="button" class="btn btn-light rounded-circle position-absolute top-0 end-0 m-3 shadow-sm" data-bs-dismiss="modal" aria-label="Tutup" style="width:40px;height:40px;display:grid;place-items:center;">
                    <i class="bi bi-x-lg"></i>
                </button>
                <img src="" alt="" class="lightbox-img img-fluid rounded" style="max-height: 90vh; object-fit: contain; display: inline-block;">
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

<div class="col">
    <div class="card card-product h-100">
        {{-- Image Container with Overlay --}}
        <div class="position-relative" style="height: 220px; overflow: hidden; background: #f0f0f0;">
              <div class="product-image-trigger d-block h-100 w-100" role="button" tabindex="0"
                  data-image="{{ $product->foto_url }}" data-name="{{ $product->nama_produk }}"
                  style="text-decoration: none;">
                 <img src="{{ $product->foto_url }}"
                     alt="{{ $product->nama_produk }}"
                     class="w-100 h-100"
                     style="object-fit: cover; transition: transform .25s ease; cursor: pointer;">
              </div>

            {{-- Stock Badge --}}
            <div class="position-absolute top-0 start-0 p-2" style="z-index: 10;">
                <span class="badge badge-stok {{ $product->ketersediaan_stok === 'tersedia' ? 'bg-success' : 'bg-danger' }}">
                    <i class="bi {{ $product->ketersediaan_stok === 'tersedia' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill' }}"></i>
                    {{ $product->ketersediaan_stok === 'tersedia' ? 'Tersedia' : 'Habis' }}
                </span>
            </div>
        </div>

        {{-- Card Content --}}
        <div class="card-body d-flex flex-column flex-grow-1">
            {{-- Category --}}
            <small class="text-muted mb-2 d-inline-block" style="width: fit-content;">
                <i class="bi bi-tag"></i> {{ $product->category->nama_kategori }}
            </small>

            {{-- Product Name --}}
            <h6 class="card-title fw-semibold mb-2 flex-grow-1" style="line-height: 1.4; -webkit-line-clamp: 2; display: -webkit-box; -webkit-box-orient: vertical; overflow: hidden;">
                {{ $product->nama_produk }}
            </h6>

            {{-- Description --}}
            @if($product->deskripsi)
                <small class="text-muted mb-3" style="line-height: 1.3; -webkit-line-clamp: 2; display: -webkit-box; -webkit-box-orient: vertical; overflow: hidden;">
                    {{ $product->deskripsi }}
                </small>
            @endif

            {{-- Price --}}
            <div class="mb-3">
                <p class="fw-bold text-dark mb-0" style="font-size: 1.3rem;">
                    Rp {{ number_format($product->harga, 0, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Card Footer with CTA --}}
        <div class="card-footer bg-transparent border-0 pb-3 px-3 pt-0">
            <div class="d-flex gap-2">
                <a href="{{ route('products.show', $product) }}" class="btn btn-outline-dark btn-sm flex-grow-1 rounded-pill">Detail</a>
                @php
                    $pesanWa = urlencode("Halo, saya tertarik dengan produk *{$product->nama_produk}* seharga Rp " . number_format($product->harga, 0, ',', '.') . ". Apakah masih tersedia?");
                    $waUrl   = "https://wa.me/{$product->nomor_whatsapp}?text={$pesanWa}";
                @endphp
                <a href="{{ $waUrl }}" target="_blank" class="btn btn-wa btn-sm flex-grow-1 rounded-pill d-flex align-items-center justify-content-center" style="gap: 8px;">
                    <i class="bi bi-whatsapp"></i>
                    <span>Chat</span>
                </a>
            </div>
        </div>
    </div>
</div>

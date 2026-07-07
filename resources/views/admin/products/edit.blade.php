@extends('layouts.admin')
@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')

@section('content')
<div class="card border-0 shadow-sm rounded-3" style="max-width: 680px;">
    <div class="card-body p-4">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-medium">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" name="nama_produk"
                           class="form-control @error('nama_produk') is-invalid @enderror"
                           value="{{ old('nama_produk', $product->nama_produk) }}">
                    @error('nama_produk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-medium">Kategori <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-medium">Harga (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="harga"
                           class="form-control"
                           value="{{ old('harga', $product->harga) }}" min="0">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-medium">Ketersediaan Stok</label>
                    <select name="ketersediaan_stok" class="form-select">
                        <option value="tersedia" {{ old('ketersediaan_stok', $product->ketersediaan_stok) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="habis" {{ old('ketersediaan_stok', $product->ketersediaan_stok) == 'habis' ? 'selected' : '' }}>Habis</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-medium">Nomor WhatsApp</label>
                    <div class="input-group">
                        <span class="input-group-text">+</span>
                        <input type="text" name="nomor_whatsapp"
                               class="form-control"
                               value="{{ old('nomor_whatsapp', $product->nomor_whatsapp) }}">
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label fw-medium">Deskripsi</label>
                    <textarea name="deskripsi" rows="3"
                              class="form-control">{{ old('deskripsi', $product->deskripsi) }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label fw-medium">Ganti Foto Produk</label>
                    {{-- Foto saat ini --}}
                    @if($product->foto_produk)
                        <div class="mb-2">
                            <img src="{{ $product->foto_url }}" alt="Foto saat ini"
                                 style="width:100px;height:100px;object-fit:cover;border-radius:8px;border:1px solid #dee2e6;">
                            <small class="d-block text-muted mt-1">Foto saat ini</small>
                        </div>
                    @endif
                    <input type="file" name="foto_produk"
                           class="form-control @error('foto_produk') is-invalid @enderror"
                           accept="image/*" onchange="previewFoto(this)">
                    <small class="text-muted">Kosongkan jika tidak ingin mengganti foto.</small>
                    @error('foto_produk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="mt-2">
                        <img id="preview" src="" alt="Preview"
                             style="display:none;width:100px;height:100px;object-fit:cover;border-radius:8px;">
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-dark">
                    <i class="bi bi-check-lg me-1"></i> Update Produk
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewFoto(input) {
    const preview = document.getElementById('preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
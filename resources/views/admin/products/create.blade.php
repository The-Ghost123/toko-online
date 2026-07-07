@extends('layouts.admin')
@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk')

@section('content')
<div class="card border-0 shadow-sm rounded-3" style="max-width: 680px;">
    <div class="card-body p-4">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-medium">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" name="nama_produk"
                           class="form-control @error('nama_produk') is-invalid @enderror"
                           value="{{ old('nama_produk') }}" placeholder="contoh: Dress Batik Modern">
                    @error('nama_produk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-medium">Kategori <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-medium">Harga (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="harga"
                           class="form-control @error('harga') is-invalid @enderror"
                           value="{{ old('harga') }}" placeholder="150000" min="0">
                    @error('harga')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-medium">Ketersediaan Stok <span class="text-danger">*</span></label>
                    <select name="ketersediaan_stok" class="form-select @error('ketersediaan_stok') is-invalid @enderror">
                        <option value="tersedia" {{ old('ketersediaan_stok') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="habis" {{ old('ketersediaan_stok') == 'habis' ? 'selected' : '' }}>Habis</option>
                    </select>
                    @error('ketersediaan_stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-medium">Nomor WhatsApp <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">+</span>
                        <input type="text" name="nomor_whatsapp"
                               class="form-control @error('nomor_whatsapp') is-invalid @enderror"
                               value="{{ old('nomor_whatsapp') }}" placeholder="6281234567890">
                    </div>
                    <small class="text-muted">Format: 628xxx (tanpa +)</small>
                    @error('nomor_whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-medium">Deskripsi</label>
                    <textarea name="deskripsi" rows="3"
                              class="form-control">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label fw-medium">Foto Produk</label>
                    <input type="file" name="foto_produk" id="foto_produk"
                           class="form-control @error('foto_produk') is-invalid @enderror"
                           accept="image/*" onchange="previewFoto(this)">
                    <small class="text-muted">JPG, PNG, WEBP. Maks 2MB.</small>
                    @error('foto_produk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="mt-2">
                        <img id="preview" src="" alt="Preview"
                             style="display:none; width:120px; height:120px; object-fit:cover; border-radius:8px; border:1px solid #dee2e6;">
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-dark">
                    <i class="bi bi-check-lg me-1"></i> Simpan Produk
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
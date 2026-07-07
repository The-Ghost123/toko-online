@extends('layouts.admin')
@section('title', 'Produk')
@section('page-title', 'Manajemen Produk')

@section('content')
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-semibold mb-0">Daftar Produk</h6>
        <a href="{{ route('admin.products.create') }}" class="btn btn-dark btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Produk
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.products.index') }}" method="GET" class="row g-2 align-items-end mb-4">
            <div class="col-md-5">
                <label for="search" class="form-label small text-muted">Cari Produk</label>
                <input
                    type="search"
                    id="search"
                    name="search"
                    class="form-control"
                    placeholder="Cari nama atau deskripsi produk..."
                    value="{{ request('search') }}"
                >
            </div>
            <div class="col-md-4">
                <label for="category_id" class="form-label small text-muted">Filter Kategori</label>
                <select id="category_id" name="category_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ (string) request('category_id') === (string) $category->id ? 'selected' : '' }}>
                            {{ $category->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-dark flex-fill">
                    <i class="bi bi-search me-1"></i> Cari
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                    Reset
                </a>
            </div>
        </form>

        @if(request('search') || request('category_id'))
            <div class="alert alert-light border d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span>
                    Menampilkan <strong>{{ $products->total() }}</strong> produk
                    @if(request('search'))
                        untuk pencarian "<strong>{{ request('search') }}</strong>"
                    @endif
                    @if(request('category_id'))
                        @php $selectedCategory = $categories->firstWhere('id', (int) request('category_id')); @endphp
                        @if($selectedCategory)
                            pada kategori <strong>{{ $selectedCategory->nama_kategori }}</strong>
                        @endif
                    @endif
                </span>
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">Hapus Filter</a>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="50">#</th>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th width="160">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>{{ $products->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $product->foto_url }}"
                                     style="width:45px;height:45px;object-fit:cover;border-radius:8px;">
                                <div>
                                    <div class="fw-medium">{{ $product->nama_produk }}</div>
                                    <small class="text-muted">{{ Str::limit($product->deskripsi, 40) }}</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $product->category->nama_kategori }}</span></td>
                        <td class="fw-medium">Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product) }}"
                               class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus produk ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Belum ada produk. <a href="{{ route('admin.products.create') }}">Tambah sekarang</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $products->links() }}</div>
    </div>
</div>
@endsection

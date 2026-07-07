@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    {{-- Stat: Total Produk --}}
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-primary bg-opacity-10">
                    <i class="bi bi-box-seam fs-4 text-primary"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Produk</div>
                    <div class="fw-bold fs-4">{{ $totalProducts }}</div>
                </div>
            </div>
        </div>
    </div>
    {{-- Stat: Total Kategori --}}
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-warning bg-opacity-10">
                    <i class="bi bi-tag fs-4 text-warning"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Kategori</div>
                    <div class="fw-bold fs-4">{{ $totalCategories }}</div>
                </div>
            </div>
        </div>
    </div>
    {{-- Stat: Stok Tersedia --}}
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-success bg-opacity-10">
                    <i class="bi bi-check-circle fs-4 text-success"></i>
                </div>
                <div>
                    <div class="text-muted small">Stok Tersedia</div>
                    <div class="fw-bold fs-4">{{ $tersedia }}</div>
                </div>
            </div>
        </div>
    </div>
    {{-- Stat: Stok Habis --}}
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-danger bg-opacity-10">
                    <i class="bi bi-x-circle fs-4 text-danger"></i>
                </div>
                <div>
                    <div class="text-muted small">Stok Habis</div>
                    <div class="fw-bold fs-4">{{ $habis }}</div>
                </div>
            </div>
        </div>
    </div>
    {{-- Stat: Active Customers --}}
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-warning bg-opacity-10">
                    <i class="bi bi-people fs-4 text-warning"></i>
                </div>
                <div>
                    <div class="text-muted small">Active Customers</div>
                    <div id="active-customers-count" class="fw-bold fs-4">{{ $activeCustomers }}</div>
                </div>
            </div>
        </div>
    </div>
    {{-- Stat: Pending Verifikasi Pembayaran --}}
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-warning bg-opacity-10">
                    <i class="bi bi-wallet2 fs-4 text-warning"></i>
                </div>
                <div>
                    <div class="text-muted small">Menunggu Verifikasi</div>
                    <div class="fw-bold fs-4">{{ $pendingPayments }}</div>
                </div>
            </div>
        </div>
    </div>
    {{-- Stat: Monitoring Website --}}
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-info bg-opacity-10">
                    <i class="bi bi-bar-chart-line fs-4 text-info"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Kunjungan</div>
                    <div class="fw-bold fs-4">{{ $totalVisits }}</div>
                </div>
            </div>
        </div>
    </div>
    {{-- Stat: WhatsApp Clicks --}}
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-success bg-opacity-10">
                    <i class="bi bi-whatsapp fs-4 text-success"></i>
                </div>
                <div>
                    <div class="text-muted small">Klik WhatsApp</div>
                    <div class="fw-bold fs-4">{{ $totalWhatsappClicks }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Produk Terbaru --}}
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white border-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
        <h6 class="fw-semibold mb-0">Produk Terbaru</h6>
        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-dark">
            Lihat Semua
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestProducts as $product)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $product->foto_url }}"
                                     style="width:40px;height:40px;object-fit:cover;border-radius:8px;">
                                <span class="fw-medium">{{ $product->nama_produk }}</span>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark">{{ $product->category->nama_kategori }}</span></td>
                        <td>Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $product->ketersediaan_stok === 'tersedia' ? 'bg-success' : 'bg-danger' }}">
                                {{ $product->ketersediaan_stok }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-3">Belum ada produk</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
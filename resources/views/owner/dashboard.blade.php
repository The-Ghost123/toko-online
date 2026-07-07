@extends('layouts.admin')
@section('title', 'Owner Dashboard')
@section('page-title', 'Owner Dashboard')

@section('content')
<div class="row g-4 mb-4">
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
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 bg-warning bg-opacity-10">
                    <i class="bi bi-tag fs-4 text-warning"></i>
                </div>
                <div>
                    <div class="text-muted small">Stok Tersedia</div>
                    <div class="fw-bold fs-4">{{ $totalAvailable }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card shadow-sm rounded-3">
            <div class="card-header bg-white border-0">
                <h6 class="fw-semibold mb-0">Info Toko</h6>
            </div>
            <div class="card-body">
                <p class="text-muted">Owner dapat memantau performa website, melihat jumlah klik WhatsApp, dan mengelola informasi toko atau sosial media.</p>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Total Produk
                        <span class="badge bg-dark">{{ $totalProducts }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Stok Tersedia
                        <span class="badge bg-success">{{ $totalAvailable }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Stok Habis
                        <span class="badge bg-danger">{{ $totalOutOfStock }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Klik WhatsApp
                        <span class="badge bg-success">{{ $totalWhatsappClicks }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm rounded-3">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-semibold mb-0">Aksi Cepat</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('owner.orders.index') }}" class="btn btn-outline-dark">Kelola Transaksi</a>
                    <a href="{{ route('owner.customers.index') }}" class="btn btn-outline-dark">Kelola Pembeli</a>
                    <a href="{{ route('owner.social-media.edit') }}" class="btn btn-outline-dark">Kelola Sosial Media</a>
                    <a href="{{ route('owner.dashboard') }}" class="btn btn-outline-primary">Segarkan Dashboard</a>
                    <!-- Tombol baru di aksi cepat -->
                    <!-- Tambahkan tombol lain sesuai kebutuhan -->
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white border-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
        <h6 class="fw-semibold mb-0">Produk dengan Klik WhatsApp Terbanyak</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th>Jumlah Klik WA</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($whatsappProducts as $item)
                    <tr>
                        <td>{{ $item->product?->nama_produk ?? 'Produk tidak tersedia' }}</td>
                        <td>{{ $item->total }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="text-center text-muted py-3">Belum ada klik WhatsApp</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

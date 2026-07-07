@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-4">Analisis Keranjang Ditinggalkan</h2>
            <p class="text-muted">Halaman ini menampilkan keranjang yang berpotensi hilang karena pelanggan belum melakukan checkout. Gunakan data ini untuk melihat peluang follow-up dan optimasi produk.</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total Keranjang Ditinggalkan</h6>
                    <h3 class="mb-0">{{ $totalCount }}</h3>
                    <small class="text-muted">Tidak diperbarui lebih dari {{ $abandonedDays }} hari</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Potensi Pendapatan</h6>
                    <h3 class="mb-0">Rp {{ number_format($totalValue, 0, ',', '.') }}</h3>
                    <small class="text-muted">Dari keranjang yang ditinggalkan</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Keranjang Ditinggalkan -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Detail Keranjang Ditinggalkan</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nama Pelanggan</th>
                        <th>Email</th>
                        <th>Barang</th>
                        <th>Nilai Keranjang</th>
                        <th>Terakhir Diperbarui</th>
                        <th>Hari Lalu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($carts as $cart)
                        <tr>
                            <td>
                                <strong>{{ $cart->user->name }}</strong>
                            </td>
                            <td>{{ $cart->user->email }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $cart->total_items }} produk</span>
                            </td>
                            <td>
                                <strong>Rp {{ number_format($cart->total_price, 0, ',', '.') }}</strong>
                            </td>
                            <td>{{ $cart->updated_at->format('d M Y H:i') }}</td>
                            <td>
                                <span class="text-muted">{{ now()->diffInDays($cart->updated_at) }} hari</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.carts.show', $cart) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> Lihat
                                </a>
                                <form action="{{ route('admin.carts.remind', $cart) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Catat pengingat untuk pelanggan ini?')">
                                        <i class="bi bi-bell"></i> Ingatkan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                Tidak ada keranjang ditinggalkan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-light">
            {{ $carts->links() }}
        </div>
    </div>
</div>

@endsection

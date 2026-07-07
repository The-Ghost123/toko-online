@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-4">Analitik & Wawasan Keranjang</h2>
            <p class="text-muted">Lihat ringkasan perilaku pembeli berdasarkan keranjang yang terisi dan produk yang paling sering dikeranjangkan.</p>
        </div>
    </div>

    <!-- Metrik Utama -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-left-primary">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Keranjang Terisi 30 Hari</h6>
                    <h3 class="mb-0">{{ $cartsLast30Days }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-left-success">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Pesanan Dibuat 30 Hari</h6>
                    <h3 class="mb-0">{{ $completedCarts }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-left-info">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Rata-rata Nilai Keranjang</h6>
                    <h3 class="mb-0">Rp {{ number_format($avgCartValue ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Produk Paling Sering Dikeranjangkan -->
        <div class="col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-1">10 Produk Paling Sering Dikeranjangkan</h5>
                    <p class="text-muted mb-0">Frekuensi menunjukkan berapa kali produk tersebut ditambahkan ke keranjang oleh semua pelanggan.</p>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Frekuensi</th>
                                <th class="text-center">Total Qty</th>
                                <th class="text-end">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($popularCartProducts as $item)
                                <tr>
                                    <td class="fw-semibold">{{ $item->product->nama_produk }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-warning text-dark px-3 py-2">{{ $item->cart_count }}</span>
                                    </td>
                                    <td class="text-center">{{ $item->total_qty }}</td>
                                    <td class="text-end">Rp {{ number_format($item->total_value, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-1">Pelanggan dengan Keranjang Ditinggalkan</h5>
                    <p class="text-muted mb-0">Keranjang dianggap ditinggalkan jika tidak diperbarui lebih dari {{ $abandonedDays }} hari.</p>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Pelanggan</th>
                                <th>Email</th>
                                <th class="text-center">Jumlah Keranjang</th>
                                <th class="text-end">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($frequentAbandoners as $cart)
                                <tr>
                                    <td class="fw-semibold">{{ $cart->user->name }}</td>
                                    <td>{{ $cart->user->email }}</td>
                                    <td class="text-center">{{ $cart->count }}</td>
                                    <td class="text-end">Rp {{ number_format($cart->value, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Tidak ada data keranjang ditinggalkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .border-left-primary {
        border-left: 4px solid #007bff !important;
    }
    .border-left-success {
        border-left: 4px solid #28a745 !important;
    }
    .border-left-info {
        border-left: 4px solid #17a2b8 !important;
    }
    .border-left-warning {
        border-left: 4px solid #ffc107 !important;
    }
</style>
@endsection

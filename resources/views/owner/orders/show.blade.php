@extends('layouts.admin')

@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi Pelanggan')

@section('content')
@php
    $statusClasses = [
        \App\Models\Order::STATUS_PENDING_PAYMENT => 'secondary',
        \App\Models\Order::STATUS_PAYMENT_SUBMITTED => 'warning',
        \App\Models\Order::STATUS_PAYMENT_VERIFIED => 'success',
        \App\Models\Order::STATUS_SHIPPED => 'primary',
        \App\Models\Order::STATUS_COMPLETED => 'success',
        \App\Models\Order::STATUS_REFUNDED => 'danger',
        \App\Models\Order::STATUS_CANCELLED => 'dark',
    ];
@endphp

<div class="row g-4">
    <div class="col-12">
        <div class="card shadow-sm rounded-3">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
                    <div>
                        <h5 class="card-title mb-1">Pesanan #{{ $order->id }}</h5>
                        <p class="text-muted mb-0">Detail transaksi, status, dan item pesanan.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('owner.orders.index') }}" class="btn btn-outline-secondary btn-sm">Kembali ke Transaksi</a>
                        @if(in_array($order->status, [\App\Models\Order::STATUS_COMPLETED, \App\Models\Order::STATUS_REFUNDED, \App\Models\Order::STATUS_CANCELLED], true))
                            <form action="{{ route('owner.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Hapus pesanan ini? Tindakan ini tidak bisa dibatalkan.');" class="m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus Pesanan</button>
                            </form>
                        @endif
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="card border-0 rounded-3 shadow-sm p-4">
                            <h6 class="mb-3">Informasi Pelanggan</h6>
                            <p class="mb-1"><strong>{{ $order->user->name }}</strong></p>
                            <p class="text-muted mb-1">{{ $order->user->email }}</p>
                            <p class="text-muted">Alamat pengiriman:</p>
                            <p>{{ $order->shipping_address ?: 'Tidak tersedia' }}</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card border-0 rounded-3 shadow-sm p-4">
                            <h6 class="mb-3">Rincian Pesanan</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Status</span>
                                <span>
                                    @php $statusClass = $statusClasses[$order->status] ?? 'secondary'; @endphp
                                    @if($order->status === \App\Models\Order::STATUS_COMPLETED)
                                        <span class="badge bg-success">{{ $order->status_label }}</span>
                                    @elseif($order->status === \App\Models\Order::STATUS_REFUNDED)
                                        <span class="badge bg-danger">{{ $order->status_label }}</span>
                                    @else
                                        <span class="badge bg-{{ $statusClass }} {{ $statusClass === 'warning' ? 'text-dark' : '' }}">{{ $order->status_label }}</span>
                                    @endif
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total Item</span>
                                <span>{{ $order->total_items }} item</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total Harga</span>
                                <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Waktu Pemesanan</span>
                                <span>{{ $order->created_at->format('d M Y H:i') }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Metode Pembayaran</span>
                                <span>{{ ucwords(str_replace('-', ' ', $order->payment_method)) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 rounded-3 shadow-sm mt-4 p-4">
                    <h6 class="mb-3">Item Pesanan</h6>
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $item->product_name }}</div>
                                            <div class="text-muted small">{{ $item->product?->category?->nama_kategori ?? '' }}</div>
                                        </td>
                                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

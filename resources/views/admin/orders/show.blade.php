@extends('layouts.admin')

@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan')

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
    $statusClass = $statusClasses[$order->status] ?? 'secondary';
@endphp

<div class="row gy-4">
    <div class="col-12">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pesanan
        </a>

        <div class="card shadow-sm rounded-3 border-0">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                    <div>
                        <h4 class="mb-1">Pesanan #{{ $order->id }}</h4>
                        <p class="mb-1 text-muted">{{ $order->user->name }} — {{ $order->user->email }}</p>
                        @if($order->status === \App\Models\Order::STATUS_COMPLETED)
                            <span class="badge bg-success fs-6">
                                <i class="bi bi-check-circle-fill me-1"></i>{{ $order->status_label }}
                            </span>
                        @else
                            <span class="badge bg-{{ $statusClass }} fs-6">{{ $order->status_label }}</span>
                        @endif
                    </div>
                    <div class="text-md-end">
                        <div class="text-muted small">Dibuat pada</div>
                        <div class="fw-semibold">{{ $order->created_at->format('d M Y H:i') }}</div>
                        <div class="mt-2">
                            <span class="text-muted small">Total Pesanan</span>
                            <div class="fs-4 fw-semibold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                            <div class="text-muted">{{ $order->total_items }} item</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm rounded-3 border-0 mb-4">
            <div class="card-body">
                <h6 class="mb-3">Informasi Pengiriman</h6>
                <p class="mb-2 text-muted small">Alamat</p>
                <p class="mb-3">{{ $order->shipping_address ?: 'Alamat tidak tersedia' }}</p>

                <p class="mb-2 text-muted small">Koordinat</p>
                <p class="mb-3">{{ $order->shipping_latitude ? $order->shipping_latitude . ', ' . $order->shipping_longitude : 'Tidak tersedia' }}</p>

                <p class="mb-2 text-muted small">Metode Pembayaran</p>
                <p>{{ ucwords(str_replace('-', ' ', $order->payment_method)) }}</p>
            </div>
        </div>

        <div class="card shadow-sm rounded-3 border-0">
            <div class="card-body">
                <h6 class="mb-3">Rincian Pengiriman</h6>
                <dl class="row mb-0">
                    <dt class="col-6 text-muted">Nomor Resi</dt>
                    <dd class="col-6">{{ $order->tracking_number ?: '-' }}</dd>

                    <dt class="col-6 text-muted">Bukti Pembayaran</dt>
                    <dd class="col-6">
                        @if($order->proof_photo)
                            <a href="{{ Storage::url($order->proof_photo) }}" target="_blank" class="text-decoration-none">Lihat</a>
                        @else
                            -
                        @endif
                    </dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card shadow-sm rounded-3 border-0">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="mb-1">Item dalam Pesanan</h5>
                        <p class="text-muted mb-0">Jumlah produk: {{ $order->items->count() }}</p>
                    </div>
                </div>

                <div class="row g-3">
                    @foreach($order->items as $item)
                        <div class="col-12">
                            <div class="card border-light rounded-3 shadow-sm">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="flex-shrink-0 rounded-3 overflow-hidden" style="width:72px;height:72px;">
                                            <img src="{{ $item->product_photo ? Storage::url($item->product_photo) : 'https://via.placeholder.com/120x120?text=No+Image' }}" alt="{{ $item->product_name }}" class="img-fluid h-100 w-100 object-fit-cover">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $item->product_name }}</h6>
                                            <p class="mb-1 text-muted">Qty: {{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                            <p class="mb-0 fw-semibold">Subtotal: Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <div class="text-muted">Total Harga Pesanan</div>
                    <div class="fs-5 fw-semibold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

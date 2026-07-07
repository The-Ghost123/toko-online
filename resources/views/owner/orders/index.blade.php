@extends('layouts.admin')

@section('title', 'Transaksi Owner')
@section('page-title', 'Transaksi Pelanggan')

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
                        <h5 class="card-title mb-1">Transaksi Pelanggan</h5>
                        <p class="text-muted mb-0">Monitor semua transaksi dan hapus pesanan yang sudah selesai atau direfund.</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('owner.orders.index') }}" class="btn btn-outline-secondary btn-sm">Semua</a>
                        <a href="{{ route('owner.orders.index', ['status' => 'completed']) }}" class="btn btn-outline-success btn-sm">Selesai</a>
                        <a href="{{ route('owner.orders.index', ['status' => 'refunded']) }}" class="btn btn-outline-danger btn-sm">Refunded</a>
                        <a href="{{ route('owner.orders.index', ['status' => 'cancelled']) }}" class="btn btn-outline-dark btn-sm">Cancelled</a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Pelanggan</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Tanggal</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $order->user->name }}</div>
                                        <div class="text-muted small">{{ $order->user->email }}</div>
                                    </td>
                                    <td>
                                        @php $statusClass = $statusClasses[$order->status] ?? 'secondary'; @endphp
                                        @if($order->status === \App\Models\Order::STATUS_COMPLETED)
                                            <span class="badge bg-success">{{ $order->status_label }}</span>
                                        @elseif($order->status === \App\Models\Order::STATUS_REFUNDED)
                                            <span class="badge bg-danger">{{ $order->status_label }}</span>
                                        @else
                                            <span class="badge bg-{{ $statusClass }} {{ $statusClass === 'warning' ? 'text-dark' : '' }}">{{ $order->status_label }}</span>
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('owner.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                            @if(in_array($order->status, [\App\Models\Order::STATUS_COMPLETED, \App\Models\Order::STATUS_REFUNDED, \App\Models\Order::STATUS_CANCELLED], true))
                                                <form action="{{ route('owner.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Hapus pesanan ini? Tindakan ini tidak bisa dibatalkan.');" class="m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Tidak ada transaksi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

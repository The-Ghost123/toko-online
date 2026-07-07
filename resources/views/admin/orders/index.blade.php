@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')
@section('page-title', 'Manajemen Pesanan')

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
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="card-title mb-1">Daftar Transaksi Pelanggan</h5>
                        <p class="text-muted mb-0">Verifikasi pembayaran dan input nomor resi pengiriman di sini.</p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ route('admin.orders.map') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-geo-alt"></i> Lihat Peta
                        </a>
                    </div>
                </div>

                <div id="orders-map-container" class="mb-4" style="display:block;">
                    <div class="mb-2 d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge rounded-pill" style="background:#6c757d;width:14px;height:14px;display:inline-block"></span>
                            <small class="text-muted">Menunggu Pembayaran</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge rounded-pill" style="background:#ffc107;width:14px;height:14px;display:inline-block"></span>
                            <small class="text-muted">Pembayaran Diajukan</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge rounded-pill" style="background:#198754;width:14px;height:14px;display:inline-block"></span>
                            <small class="text-muted">Pembayaran Diverifikasi</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge rounded-pill" style="background:#0d6efd;width:14px;height:14px;display:inline-block"></span>
                            <small class="text-muted">Dikirim</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge rounded-pill" style="background:#dc3545;width:14px;height:14px;display:inline-block"></span>
                            <small class="text-muted">Direfund</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge rounded-pill" style="background:#212529;width:14px;height:14px;display:inline-block"></span>
                            <small class="text-muted">Dibatalkan</small>
                        </div>
                    </div>
                    <div id="orders-map" style="height: 420px; border-radius: 8px; border:1px solid #e5e7eb;"></div>
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
                                <th>Alamat</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Bukti Transfer</th>
                                <th>Resi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr data-order-id="{{ $order->id }}" class="order-row">
                                    <td>{{ $order->id }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $order->user->name }}</div>
                                        <div class="text-muted">{{ $order->user->email }}</div>
                                    </td>
                                    <td>
                                        <small>{{ Str::limit($order->shipping_address, 80) ?: 'Tidak tersedia' }}</small>
                                    </td>
                                    <td>
                                        <div>Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                                        <small class="text-muted">{{ $order->total_items }} item</small>
                                    </td>
                                    <td>
                                        @php $statusClass = $statusClasses[$order->status] ?? 'secondary'; @endphp
                                        @if($order->status === \App\Models\Order::STATUS_COMPLETED)
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle-fill me-1"></i>{{ $order->status_label }}
                                            </span>
                                        @else
                                            <span class="badge bg-{{ $statusClass }} {{ in_array($statusClass, ['warning'], true) ? 'text-dark' : '' }}">{{ $order->status_label }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->proof_photo)
                                            <a href="{{ Storage::url($order->proof_photo) }}" target="_blank" class="btn btn-sm btn-outline-secondary">Lihat Bukti</a>
                                        @else
                                            <span class="text-muted">Tidak ada</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->tracking_number)
                                            <div class="fw-semibold">{{ $order->tracking_number }}</div>
                                        @else
                                            <span class="text-muted">Belum ada</span>
                                        @endif
                                    </td>
                                    <td class="w-25">
                                            <div class="d-flex flex-column gap-2">
                                            @if($order->status === \App\Models\Order::STATUS_PAYMENT_SUBMITTED)
                                                <form action="{{ route('admin.orders.verify', $order) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">Verifikasi Pembayaran</button>
                                                </form>
                                            @endif

                                            @if(in_array($order->status, [\App\Models\Order::STATUS_PAYMENT_VERIFIED, \App\Models\Order::STATUS_PROCESSING], true))
                                                <form action="{{ route('admin.orders.ship', $order) }}" method="POST" class="d-flex gap-2 flex-column">
                                                    @csrf
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" name="tracking_number" class="form-control form-control-sm" placeholder="Nomor Resi" value="{{ old('tracking_number') }}">
                                                    </div>
                                                    <button type="submit" class="btn btn-sm btn-primary">Simpan Resi</button>
                                                </form>
                                            @endif
                                            <div class="d-flex gap-2">
                                                @php
                                                    $mapBtnColor = $statusClasses[$order->status] ?? 'secondary';
                                                @endphp
                                                <button class="btn btn-sm btn-{{ $mapBtnColor }} map-btn" type="button" data-order-id="{{ $order->id }}">Lihat Peta</button>
                                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="collapse" id="order-items-{{ $order->id }}">
                                    <td colspan="8">
                                        <div class="card card-body bg-light border-0">
                                            <div class="mb-3">
                                                <h6 class="mb-2">Alamat Pengiriman</h6>
                                                <p class="mb-3 text-muted">{{ $order->shipping_address ?: 'Alamat tidak tersedia' }}</p>
                                                <h6 class="mb-2">Item Pesanan</h6>
                                                <div class="row g-3">
                                                    @foreach($order->items as $item)
                                                        <div class="col-md-6 col-lg-4">
                                                            <div class="border rounded-3 p-3 h-100">
                                                                <div class="d-flex align-items-center gap-3 mb-2">
                                                                    <div class="flex-shrink-0 rounded-3 overflow-hidden" style="width:54px;height:54px;">
                                                                        <img src="{{ $item->product_photo ? Storage::url($item->product_photo) : 'https://via.placeholder.com/120x120?text=No+Image' }}" alt="{{ $item->product_name }}" class="img-fluid h-100 w-100 object-fit-cover">
                                                                    </div>
                                                                    <div>
                                                                        <div class="fw-semibold">{{ $item->product_name }}</div>
                                                                        <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                                                    </div>
                                                                </div>
                                                                <div class="text-muted">Rp {{ number_format($item->price, 0, ',', '.') }} x {{ $item->quantity }}</div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <small class="text-secondary">Tanggal pemesanan: {{ $order->created_at->format('d M Y H:i') }}</small>
                                                </div>
                                                <div><small class="text-secondary">Payment method: {{ ucwords(str_replace('-', ' ', $order->payment_method)) }}</small></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Tidak ada transaksi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
                            @php
                                $ordersForMap = $orders->map(function($order) {
                                    if ($order->shipping_latitude && $order->shipping_longitude) {
                                        return [
                                            'id' => $order->id,
                                            'lat' => (float)$order->shipping_latitude,
                                            'lng' => (float)$order->shipping_longitude,
                                            'label' => "#{$order->id} — {$order->user->name}",
                                            'name' => $order->user->name,
                                            'address' => $order->shipping_address,
                                            'total' => 'Rp ' . number_format($order->total_price, 0, ',', '.'),
                                            'status' => $order->status_label,
                                            'status_key' => $order->status,
                                            'items' => $order->items->map(fn($i) => ['name' => $i->product_name, 'qty' => $i->quantity])->values(),
                                        ];
                                    }
                                })->filter()->values();
                            @endphp

                            @push('scripts')
                            @vite('resources/js/admin-orders-map.js')
                            <script>
                                const ordersMapData = @json($ordersForMap);

                                function initOrdersMap() {
                                    if (typeof L === 'undefined') return;
                                    const mapEl = document.getElementById('orders-map');
                                    if (!mapEl) return;

                                    const map = L.map('orders-map').setView([-6.2088, 106.8456], 10);
                                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                        maxZoom: 19,
                                        attribution: '&copy; OpenStreetMap contributors'
                                    }).addTo(map);

                                    const markers = {};
                                    const statusColors = {
                                        'pending_payment': '#6c757d',
                                        'payment_submitted': '#ffc107',
                                        'payment_verified': '#198754',
                                        'shipped': '#0d6efd',
                                        'completed': '#198754',
                                        'refunded': '#dc3545',
                                        'cancelled': '#212529'
                                    };

                                    ordersMapData.forEach(o => {
                                        let marker;
                                        
                                        // Untuk status completed, tampilkan checkmark
                                        if (o.status_key === 'completed') {
                                            const checkmarkIcon = L.divIcon({
                                                html: `<div style="background-color: #198754; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3); font-weight: bold; font-size: 18px;">✓</div>`,
                                                iconSize: [32, 32],
                                                className: 'custom-checkmark-marker'
                                            });
                                            marker = L.marker([o.lat, o.lng], { icon: checkmarkIcon }).addTo(map);
                                        } else {
                                            const color = statusColors[o.status_key] || '#007bff';
                                            marker = L.circleMarker([o.lat, o.lng], {
                                                radius: 8,
                                                color: color,
                                                fillColor: color,
                                                fillOpacity: 0.9,
                                                weight: 2
                                            }).addTo(map);
                                        }

                                        marker.bindTooltip(o.label, {permanent: true, direction: 'top', className: 'order-marker-label'}).openTooltip();

                                        const itemsHtml = (o.items || []).map(i => `<div style="font-size:0.9em;">- ${i.name} x ${i.qty}</div>`).join('');
                                        const popup = `
                                            <div style="max-width:320px">
                                                <strong>Pesanan ${o.id} - ${o.name}</strong><br>
                                                <small>${o.address}</small>
                                                <div style="margin-top:6px"><strong>Items:</strong>${itemsHtml}</div>
                                                <div style="margin-top:6px"><strong>Total:</strong> ${o.total}</div>
                                                <div><strong>Status:</strong> ${o.status}</div>
                                            </div>`;
                                        marker.bindPopup(popup, {
                                            closeButton: true,
                                            autoClose: true,
                                            closeOnClick: true,
                                        });

                                        marker.on('click', function() {
                                            marker.openPopup();
                                            const row = document.querySelector(`[data-order-id="${o.id}"]`);
                                            if (row) {
                                                document.querySelectorAll('.order-row').forEach(r => r.style.backgroundColor = '');
                                                row.style.backgroundColor = '#f0f7ff';
                                            }
                                        });

                                        markers[o.id] = marker;
                                    });

                                    const markerList = Object.values(markers || {});
                                    if (markerList.length) {
                                        const bounds = L.latLngBounds(markerList.map(m => m.getLatLng()));
                                        map.fitBounds(bounds, {padding: [40,40], maxZoom: 13});
                                    }

                                    // expose helper to center map on an order
                                    window.centerOrderOnMap = function(orderId) {
                                        const m = markers[orderId];
                                        if (!m) return;
                                        // Smoothly fly to marker and zoom in for a closer view
                                        try {
                                            map.flyTo(m.getLatLng(), 15, { animate: true, duration: 0.8 });
                                        } catch (e) {
                                            map.setView(m.getLatLng(), 15);
                                        }
                                        m.openPopup();
                                        document.querySelectorAll('.order-row').forEach(r => r.style.backgroundColor = '');
                                        const row = document.querySelector(`[data-order-id="${orderId}"]`);
                                        if (row) {
                                            row.style.backgroundColor = '#f0f7ff';
                                        }
                                    };

                                    // expose map instance so we can close popups from outside
                                    window.ordersMap = map;
                                }

                                document.addEventListener('DOMContentLoaded', function() {
                                    const container = document.getElementById('orders-map-container');
                                    // initialize map immediately
                                    setTimeout(() => { initOrdersMap(); }, 50);

                                    // close any open map popup when clicking outside the map
                                    document.addEventListener('click', function(e) {
                                        const mapEl = document.getElementById('orders-map');
                                        if (!mapEl) return;
                                        if (!mapEl.contains(e.target) && window.ordersMap) {
                                            try { window.ordersMap.closePopup(); } catch (err) { /* ignore */ }
                                        }
                                    });

                                    // attach per-row map buttons
                                    document.querySelectorAll('.map-btn').forEach(btn => {
                                        btn.addEventListener('click', function() {
                                            const id = this.dataset.orderId;
                                            if (!id) return;
                                            if (window.centerOrderOnMap) window.centerOrderOnMap(id);
                                        });
                                    });
                                });
                            </script>
                            <style>
                                .order-marker-label {
                                    background: rgba(255,255,255,0.9);
                                    border-radius: 4px;
                                    padding: 2px 6px;
                                    font-weight: 600;
                                    box-shadow: 0 1px 2px rgba(0,0,0,0.08);
                                    color: #0b5ed7;
                                }
                            </style>
                            @endpush

                            @endsection

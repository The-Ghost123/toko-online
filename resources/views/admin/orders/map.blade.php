@extends('layouts.admin')

@section('title', 'Peta Lokasi Pembeli')
@section('page-title', 'Peta Lokasi Pembeli')

@section('content')
@php
    $statusClasses = [
        \App\Models\Order::STATUS_PENDING_PAYMENT => 'secondary',
        \App\Models\Order::STATUS_PAYMENT_SUBMITTED => 'warning',
        \App\Models\Order::STATUS_PAYMENT_VERIFIED => 'info',
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
                <style>
                    .filter-btn-wrapper .btn-check {
                        display: none;
                    }
                    
                    .filter-btn-wrapper label {
                        padding: 8px 16px !important;
                        border-radius: 20px !important;
                        cursor: pointer;
                        transition: all 0.3s ease;
                        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                        display: flex;
                        align-items: center;
                        gap: 6px;
                        user-select: none;
                        font-size: 0.875rem;
                    }
                    
                    .filter-btn-wrapper label:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
                    }
                    
                    .filter-btn-wrapper label:active {
                        transform: translateY(0);
                    }
                    
                    .filter-btn-wrapper .btn-check:checked + label {
                        transform: scale(1.05);
                        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
                        font-weight: 700;
                    }
                    
                    .filter-status-indicator {
                        width: 10px;
                        height: 10px;
                        border-radius: 50%;
                        display: inline-block;
                        animation: pulse 2s infinite;
                    }
                    
                    @keyframes pulse {
                        0%, 100% { opacity: 1; }
                        50% { opacity: 0.6; }
                    }
                </style>

                <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
                    <div>
                        <h5 class="card-title mb-1">Peta Lokasi Pesanan</h5>
                        <p class="text-muted mb-0">Lihat lokasi pengiriman semua pesanan pada peta. Klik marker untuk detail pesanan.</p>
                    </div>
                    <div class="filter-btn-wrapper" role="group" style="gap: 8px; display: flex; flex-wrap: wrap;">
                        <input type="radio" class="btn-check" name="filterStatus" id="filterAll" value="all" checked>
                        <label style="background-color: #6c757d; color: white; border: none;" for="filterAll">
                            <span class="filter-status-indicator" style="background-color: #ffffff;"></span>Semua Pesanan
                        </label>
                        
                        <input type="radio" class="btn-check" name="filterStatus" id="filterSubmitted" value="{{ \App\Models\Order::STATUS_PAYMENT_SUBMITTED }}">
                        <label style="background-color: #ffc107; color: #333; border: none;" for="filterSubmitted">
                            <i class="bi bi-clock-fill" style="font-size: 0.75rem;"></i>Perlu Verifikasi
                        </label>
                        
                        <input type="radio" class="btn-check" name="filterStatus" id="filterVerified" value="{{ \App\Models\Order::STATUS_PAYMENT_VERIFIED }}">
                        <label style="background-color: #0d6efd; color: white; border: none;" for="filterVerified">
                            <i class="bi bi-check2" style="font-size: 0.75rem;"></i>Siap Kirim
                        </label>
                        
                        <input type="radio" class="btn-check" name="filterStatus" id="filterShipped" value="{{ \App\Models\Order::STATUS_SHIPPED }}">
                        <label style="background-color: #0d6efd; color: white; border: none;" for="filterShipped">
                            <i class="bi bi-truck" style="font-size: 0.75rem;"></i>Dikirim
                        </label>

                        <input type="radio" class="btn-check" name="filterStatus" id="filterCompleted" value="{{ \App\Models\Order::STATUS_COMPLETED }}">
                        <label style="background-color: #198754; color: white; border: none;" for="filterCompleted">
                            <i class="bi bi-check-circle-fill" style="font-size: 0.75rem;"></i>Completed
                        </label>

                        <input type="radio" class="btn-check" name="filterStatus" id="filterRefunded" value="{{ \App\Models\Order::STATUS_REFUNDED }}">
                        <label style="background-color: #dc3545; color: white; border: none;" for="filterRefunded">
                            <i class="bi bi-arrow-counterclockwise" style="font-size: 0.75rem;"></i>Refunded
                        </label>

                        <input type="radio" class="btn-check" name="filterStatus" id="filterCancelled" value="{{ \App\Models\Order::STATUS_CANCELLED }}">
                        <label style="background-color: #212529; color: white; border: none;" for="filterCancelled">
                            <i class="bi bi-x-circle-fill" style="font-size: 0.75rem;"></i>Cancelled
                        </label>
                    </div>
                </div>

                <div id="map" style="height: 600px; border-radius: 12px; margin-bottom: 20px;"></div>

                <div id="map-filter-summary" class="alert alert-light border mb-3" style="display: none;"></div>

                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Pesanan</th>
                                        <th>Pelanggan</th>
                                        <th>Alamat</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="ordersTableBody">
                                    @forelse($orders as $order)
                                        @if($order->shipping_latitude && $order->shipping_longitude)
                                            <tr class="order-row" data-order-id="{{ $order->id }}" data-status="{{ $order->status }}">
                                                <td>
                                                    <strong>#{{ $order->id }}</strong>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold">{{ $order->user->name }}</div>
                                                    <small class="text-muted">{{ $order->user->email }}</small>
                                                </td>
                                                <td>
                                                    <small>{{ Str::limit($order->shipping_address, 50) }}</small><br>
                                                    <small class="text-muted">📍 {{ $order->shipping_latitude }}, {{ $order->shipping_longitude }}</small>
                                                </td>
                                                <td>
                                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                                </td>
                                                <td>
                                                    @php $statusClass = $statusClasses[$order->status] ?? 'secondary'; @endphp
                                                    @if($order->status === \App\Models\Order::STATUS_COMPLETED)
                                                        <span class="badge bg-success">
                                                            <i class="bi bi-check-circle-fill me-1"></i>{{ $order->status_label }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-{{ $statusClass }} {{ in_array($statusClass, ['warning', 'info'], true) ? 'text-dark' : '' }}">{{ $order->status_label }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.orders.index') }}#order-items-{{ $order->id }}" class="btn btn-sm btn-outline-primary" target="_blank">Lihat Detail</a>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">Tidak ada pesanan dengan lokasi yang tersedia.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@vite('resources/js/admin-orders-map.js')
@php
    $ordersData = $orders->map(function($order) {
        if ($order->shipping_latitude && $order->shipping_longitude) {
            return [
                'id' => $order->id,
                'lat' => (float)$order->shipping_latitude,
                'lng' => (float)$order->shipping_longitude,
                'name' => $order->user->name,
                'address' => $order->shipping_address,
                'total' => 'Rp ' . number_format($order->total_price, 0, ',', '.'),
                'status' => $order->status_label,
                'email' => $order->user->email,
                'status_badge' => $order->status,
                'items' => $order->items->map(fn($item) => [
                    'name' => $item->product_name,
                    'qty' => $item->quantity,
                    'price' => 'Rp ' . number_format($item->price, 0, ',', '.'),
                ])->values(),
            ];
        }
    })->filter()->values();
@endphp

<script>
    let map;
    let markers = [];

    const ordersData = @json($ordersData);

    function showMapError(message) {
        const mapContainer = document.getElementById('map');
        if (mapContainer) {
            mapContainer.innerHTML = `<div class="p-6 text-center text-red-700">${message}</div>`;
        }
    }

    function getMarkerColor(status) {
        const colors = {
            'payment_submitted': '#FFC107',
            'payment_verified': '#0D6EFD',
            'shipped': '#0D6EFD',
            'completed': '#198754',
            'refunded': '#DC3545',
            'cancelled': '#212529',
        };
        return colors[status] || '#6C757D';
    }

    function createMarker(order) {
        let marker;
        
        if (order.status_badge === 'completed') {
            // Marker khusus untuk completed dengan ikon checkmark
            const checkmarkIcon = L.divIcon({
                html: `<div style="background-color: #198754; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3); font-weight: bold; font-size: 18px;">✓</div>`,
                iconSize: [32, 32],
                className: 'custom-checkmark-marker'
            });
            marker = L.marker([order.lat, order.lng], { icon: checkmarkIcon }).addTo(map);
        } else {
            // Circle marker untuk status lainnya
            marker = L.circleMarker([order.lat, order.lng], {
                radius: 8,
                color: getMarkerColor(order.status_badge),
                fillColor: getMarkerColor(order.status_badge),
                fillOpacity: 0.9,
                weight: 2
            }).addTo(map);
        }

        const itemsHtml = (order.items || [])
            .map(item => `<li>${item.name} x ${item.qty} <span style="color:#6c757d;">(${item.price})</span></li>`)
            .join('');

        const popupContent = `
            <div style="max-width: 300px;">
                <div style="font-weight: bold; margin-bottom: 8px;">Pesanan #${order.id}</div>
                <div style="margin-bottom: 8px;"><strong>Pemesan:</strong> ${order.name}</div>
                <div style="margin-bottom: 8px;"><strong>Email:</strong> ${order.email}</div>
                <div style="margin-bottom: 8px;"><strong>Alamat:</strong><br>${order.address}</div>
                <div style="margin-bottom: 8px;"><strong>Status:</strong> ${order.status}</div>
                <div style="margin-bottom: 8px;"><strong>Total:</strong> ${order.total}</div>
                <div style="margin-bottom: 8px;">
                    <strong>Barang:</strong>
                    <ul style="margin: 4px 0 0 18px; padding: 0;">${itemsHtml || '<li>Tidak ada item</li>'}</ul>
                </div>
                <div style="margin-top: 12px; padding-top: 8px; border-top: 1px solid #ccc;"><a href="{{ route('admin.orders.index') }}" style="color: #0D6EFD; text-decoration: none;">Lihat di Manajemen Pesanan →</a></div>
            </div>
        `;

        marker.bindPopup(popupContent, {
            closeButton: true,
            autoClose: true,
            closeOnClick: true,
        });
        marker.on('click', function () {
            marker.openPopup();
        });
        marker.orderId = order.id;
        marker.statusBadge = order.status_badge;
        return marker;
    }

    function initMap() {
        const defaultCenter = [-6.2088, 106.8456];
        map = L.map('map').setView(defaultCenter, 10);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener noreferrer">OpenStreetMap</a> contributors'
        }).addTo(map);

        addMarkers();
        setupFilterButtons();

        document.getElementById('map').addEventListener('click', function(event) {
            const link = event.target.closest('.leaflet-control-attribution a');
            if (link) {
                event.preventDefault();
                window.open(link.href, '_blank', 'noopener');
            }
        });
    }

    function addMarkers() {
        markers.forEach(item => item.marker.remove());
        markers = [];

        ordersData.forEach(order => {
            const marker = createMarker(order);
            marker.on('click', function() {
                marker.openPopup();
                document.querySelectorAll('.order-row').forEach(r => r.style.backgroundColor = '');
                const row = document.querySelector(`[data-order-id="${order.id}"]`);
                if (row) {
                    row.style.backgroundColor = '#f0f7ff';
                }
            });
            markers.push({ order, marker });
        });

        fitMapToVisibleMarkers();
    }

    function setupFilterButtons() {
        document.querySelectorAll('input[name="filterStatus"]').forEach(radio => {
            radio.addEventListener('change', filterOrders);
        });
    }

    function filterOrders() {
        const selectedStatus = document.querySelector('input[name="filterStatus"]:checked').value;
        const selectedLabel = document.querySelector('input[name="filterStatus"]:checked + label')?.textContent.trim() || 'Semua Pesanan';

        document.querySelectorAll('.order-row').forEach(row => {
            const rowStatus = row.dataset.status;
            row.style.display = (selectedStatus === 'all' || rowStatus === selectedStatus) ? '' : 'none';
        });

        markers.forEach(item => {
            if (selectedStatus === 'all' || item.order.status_badge === selectedStatus) {
                item.marker.addTo(map);
            } else {
                item.marker.remove();
            }
        });

        fitMapToVisibleMarkers(selectedStatus, selectedLabel);
    }

    function fitMapToVisibleMarkers(selectedStatus = 'all', selectedLabel = 'Semua Pesanan') {
        const visibleMarkers = markers.filter(item => selectedStatus === 'all' || item.order.status_badge === selectedStatus);
        const summary = document.getElementById('map-filter-summary');

        if (summary) {
            summary.style.display = '';
            summary.textContent = `${selectedLabel}: ${visibleMarkers.length} pesanan ditampilkan. Tabel di bawah mengikuti filter yang sama.`;
        }

        if (visibleMarkers.length === 0) {
            map.setView([-2.5489, 118.0149], 5);
            return;
        }

        if (visibleMarkers.length === 1) {
            const single = visibleMarkers[0].marker.getLatLng();
            map.setView(single, 13);
            visibleMarkers[0].marker.openPopup();
            return;
        }

        const bounds = L.latLngBounds(visibleMarkers.map(item => item.marker.getLatLng()));
        map.fitBounds(bounds, {
            padding: [80, 80],
            maxZoom: 13,
        });
    }

    document.addEventListener('DOMContentLoaded', initMap);
</script>
@endsection

@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <a href="{{ route('admin.carts.abandoned') }}" class="btn btn-sm btn-secondary mb-3">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <h2 class="mb-2">Detail Keranjang Ditinggalkan</h2>
            <p class="text-muted">Pelanggan: <strong>{{ $cart->user->name }}</strong></p>
        </div>
    </div>

    <div class="row">
        <!-- Cart Items -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Item Keranjang ({{ $cart->total_items }} item)</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Kuantitas</th>
                                <th>Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart->items as $item)
                                <tr>
                                    <td>
                                        <a href="{{ route('products.show', $item->product) }}" target="_blank">
                                            {{ $item->product->nama_produk }}
                                        </a>
                                    </td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td><strong>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Cart Summary & Actions -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Ringkasan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Item:</span>
                            <strong>{{ $cart->total_items }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Nilai:</span>
                            <strong>Rp {{ number_format($cart->total_price, 0, ',', '.') }}</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span>Hari Sejak Update:</span>
                            <strong>{{ $daysSinceUpdate }} hari</strong>
                        </div>
                    </div>

                    <div class="alert alert-warning" role="alert">
                        <strong>Terakhir Diperbarui:</strong> {{ $cart->updated_at->format('d M Y H:i:s') }}
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informasi Pelanggan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small text-muted">Nama</label>
                        <p>{{ $cart->user->name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted">Email</label>
                        <p>
                            <a href="mailto:{{ $cart->user->email }}">{{ $cart->user->email }}</a>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted">Peran</label>
                        <p>
                            <span class="badge bg-info">{{ ucfirst($cart->user->role) }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Aksi</h5>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-warning btn-sm w-100 mb-2" onclick="sendReminder()">
                        <i class="bi bi-bell"></i> Kirim Email Pengingat
                    </button>
                    <button type="button" class="btn btn-info btn-sm w-100 mb-2" onclick="contactCustomer()">
                        <i class="bi bi-whatsapp"></i> Hubungi via WhatsApp
                    </button>
                    <a href="{{ route('products') }}" target="_blank" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="bi bi-box"></i> Lihat Produk
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function sendReminder() {
        if (confirm('Kirim email pengingat ke pelanggan ini?')) {
            // TODO: Implementasikan endpoint backend untuk mengirim pengingat
            alert('Email pengingat akan dikirim ke: {{ $cart->user->email }}');
        }
    }

    function contactCustomer() {
        window.open('https://wa.me/?text=Hello%20{{ urlencode($cart->user->name) }}', '_blank');
    }
</script>
@endsection

@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col lg:flex-row gap-8">
            <div class="lg:w-2/3 bg-white shadow rounded-2xl p-8">
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
                    <p class="text-gray-600 mt-2">Pilih metode pembayaran dan unggah bukti transfer untuk menyelesaikan pesanan.</p>
                </div>

                @if(session('error'))
                    <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-4 text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-6">
                        <div class="rounded-2xl border border-gray-200 p-6 bg-slate-50">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h2>
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <p class="text-sm text-gray-600">Total item</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ $checkoutTotalItems }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Total harga</p>
                                    <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($checkoutTotalPrice, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-gray-200 p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Metode Pembayaran</h2>
                            <div class="space-y-3">
                                @foreach($paymentMethods as $methodKey => $label)
                                    <label class="flex items-center gap-3 rounded-2xl border border-gray-200 p-4 hover:border-gray-300 transition">
                                        <input type="radio" name="payment_method" value="{{ $methodKey }}" class="payment-method text-blue-600 focus:ring-blue-500" {{ old('payment_method') === $methodKey ? 'checked' : ($loop->first ? 'checked' : '') }}>
                                        <span class="text-gray-900 font-semibold">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('payment_method')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <div class="mt-6 rounded-2xl border border-blue-100 bg-blue-50 p-4">
                                <h3 class="text-base font-semibold text-blue-900 mb-2">Informasi Pembayaran</h3>
                                <p class="text-sm text-gray-700 mb-4">Pilih metode pembayaran untuk melihat nomor rekening, nomor e-wallet, atau QRIS.</p>

                                <div id="payment-info-bank" class="payment-info hidden space-y-3">
                                    <div class="rounded-2xl bg-white p-4 border border-blue-100">
                                        <p class="text-sm text-gray-600">Bank</p>
                                        <p class="font-semibold text-gray-900">{{ $paymentSettings['bank_name'] }}</p>
                                        <p class="text-sm text-gray-700">No. Rekening: <span class="font-medium">{{ $paymentSettings['bank_account'] }}</span></p>
                                        <p class="text-sm text-gray-700">Atas Nama: <span class="font-medium">{{ $paymentSettings['bank_account_name'] }}</span></p>
                                    </div>
                                </div>

                                <div id="payment-info-ewallet" class="payment-info hidden space-y-3">
                                    <div class="rounded-2xl bg-white p-4 border border-blue-100">
                                        <p class="text-sm text-gray-600">E-Wallet</p>
                                        <p class="font-semibold text-gray-900">{{ $paymentSettings['ewallet_provider'] }}</p>
                                        <p class="text-sm text-gray-700">No. E-Wallet: <span class="font-medium">{{ $paymentSettings['ewallet_number'] }}</span></p>
                                    </div>
                                </div>

                                <div id="payment-info-qris" class="payment-info hidden space-y-3">
                                    <div class="rounded-2xl bg-white p-4 border border-blue-100">
                                        <p class="text-sm text-gray-600">QRIS</p>
                                        @if($paymentSettings['qris_image'])
                                            <img src="{{ Storage::url($paymentSettings['qris_image']) }}" alt="QRIS" class="max-w-full rounded-2xl border border-gray-200">
                                            <p class="text-sm text-gray-700 mt-2">Scan QRIS di atas untuk membayar.</p>
                                        @else
                                            <p class="text-sm text-gray-700">QRIS belum disiapkan oleh admin. Silakan pilih metode lain atau hubungi admin.</p>
                                        @endif
                                    </div>
                                </div>

                                <div id="payment-info-empty" class="text-sm text-gray-600">Silakan pilih metode pembayaran di atas untuk melihat instruksi.</div>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-gray-200 p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Alamat Pengiriman</h2>
                            <p class="text-sm text-gray-600 mb-4">Tentukan alamat pengiriman Anda. Klik pada peta untuk memilih lokasi.</p>
                            
                            <!-- Peta Google Maps -->
                            <div id="map" class="w-full h-80 rounded-2xl border border-gray-200 mb-4" style="height: 400px;"></div>
                            <div id="map-error" class="hidden rounded-2xl border border-red-200 bg-red-50 p-4 text-red-700 mb-4"></div>
                            
                            <div class="grid gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                                    <textarea name="shipping_address" rows="3" placeholder="Jl. Contoh No. 123, Kelurahan, Kecamatan, Kota" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shipping_address') border-red-500 @enderror" required>{{ old('shipping_address') }}</textarea>
                                    @error('shipping_address')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Latitude</label>
                                        <input type="number" step="0.0000001" name="shipping_latitude" id="shipping_latitude" placeholder="contoh: -6.2088" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ old('shipping_latitude', -6.2088) }}" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Longitude</label>
                                        <input type="number" step="0.0000001" name="shipping_longitude" id="shipping_longitude" placeholder="contoh: 106.8456" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ old('shipping_longitude', 106.8456) }}" required>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500">💡 Klik pada peta untuk otomatis mengisi koordinat</p>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-gray-200 p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Unggah Bukti Transfer</h2>
                            <p class="text-sm text-gray-600 mb-4">File harus berupa gambar JPG / PNG / WEBP maksimal 4MB.</p>
                            <input type="file" name="proof_photo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-full file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-blue-700" />
                            @error('proof_photo')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                        @foreach($checkoutItems as $item)
                            <input type="hidden" name="selected_items[]" value="{{ $item->id }}" />
                        @endforeach
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <a href="{{ route('cart') }}" class="inline-flex items-center justify-center rounded-full border border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Kembali ke Keranjang</a>
                            <button type="submit" class="inline-flex items-center justify-center rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">Bayar Sekarang</button>
                        </div>
                    </div>
                </form>
                <script>
                    const paymentMethodRadios = document.querySelectorAll('.payment-method');
                    const paymentInfoPanels = document.querySelectorAll('.payment-info');
                    const paymentInfoEmpty = document.getElementById('payment-info-empty');

                    function showPaymentInfo(selectedMethod) {
                        paymentInfoPanels.forEach(panel => panel.classList.add('hidden'));
                        if (paymentInfoEmpty) paymentInfoEmpty.classList.add('hidden');

                        if (selectedMethod === 'bank') {
                            document.getElementById('payment-info-bank').classList.remove('hidden');
                            return;
                        }

                        if (selectedMethod === 'e-wallet') {
                            document.getElementById('payment-info-ewallet').classList.remove('hidden');
                            return;
                        }

                        if (selectedMethod === 'qris') {
                            document.getElementById('payment-info-qris').classList.remove('hidden');
                            return;
                        }

                        if (paymentInfoEmpty) paymentInfoEmpty.classList.remove('hidden');
                    }

                    paymentMethodRadios.forEach(radio => {
                        radio.addEventListener('change', function () {
                            showPaymentInfo(this.value);
                        });
                    });

                    document.addEventListener('DOMContentLoaded', function() {
                        const checked = document.querySelector('.payment-method:checked');
                        if (checked) {
                            showPaymentInfo(checked.value);
                        }
                    });
                </script>
            </div>

            <aside class="lg:w-1/3 space-y-4">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Catatan Pembayaran</h3>
                    <ul class="space-y-3 text-sm text-gray-600">
                        <li>1. Pilih metode pembayaran yang tersedia.</li>
                        <li>2. Unggah bukti transfer setelah melakukan pembayaran.</li>
                        <li>3. Pesanan akan diproses setelah admin memverifikasi bukti pembayaran.</li>
                    </ul>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Status Checkout</h3>
                    <p class="text-sm text-gray-600">Setelah mengunggah bukti transfer, pesanan Anda akan otomatis ditandai <strong>Payment Submitted</strong> dan dapat diverifikasi admin.</p>
                </div>
            </aside>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let map;
    let marker;

    function showMapError(message) {
        const mapContainer = document.getElementById('map');
        const mapError = document.getElementById('map-error');
        if (mapContainer) mapContainer.style.display = 'none';
        if (mapError) {
            mapError.textContent = message;
            mapError.classList.remove('hidden');
        }
    }

    function initMap() {
        const latInput = document.getElementById('shipping_latitude');
        const lngInput = document.getElementById('shipping_longitude');

        if (!latInput || !lngInput) {
            showMapError('Input koordinat tidak ditemukan.');
            return;
        }

        const defaultCenter = {
            lat: parseFloat(latInput.value) || -6.2088,
            lng: parseFloat(lngInput.value) || 106.8456
        };

        map = L.map('map').setView([defaultCenter.lat, defaultCenter.lng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        marker = L.marker([defaultCenter.lat, defaultCenter.lng], { draggable: true }).addTo(map);
        marker.on('dragend', function() {
            const pos = marker.getLatLng();
            latInput.value = pos.lat.toFixed(7);
            lngInput.value = pos.lng.toFixed(7);
            updateAddressFromCoordinates(pos.lat, pos.lng);
        });

        map.on('click', function(e) {
            placeMarker(e.latlng);
        });

        setTimeout(() => {
            map.invalidateSize();
        }, 0);
    }

    function placeMarker(location) {
        const latInput = document.getElementById('shipping_latitude');
        const lngInput = document.getElementById('shipping_longitude');

        if (marker) {
            marker.setLatLng(location);
        } else {
            marker = L.marker(location, { draggable: true }).addTo(map);
            marker.on('dragend', function() {
                const pos = marker.getLatLng();
                latInput.value = pos.lat.toFixed(7);
                lngInput.value = pos.lng.toFixed(7);
                updateAddressFromCoordinates(pos.lat, pos.lng);
            });
        }

        latInput.value = location.lat.toFixed(7);
        lngInput.value = location.lng.toFixed(7);
        map.panTo(location);
        updateAddressFromCoordinates(location.lat, location.lng);
    }

    function updateAddressFromCoordinates(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.display_name) {
                    const addressField = document.querySelector('textarea[name="shipping_address"]');
                    if (addressField) {
                        addressField.value = data.display_name;
                    }
                }
            })
            .catch(() => {
                // ignore errors, address can still be entered manually
            });
    }

    window.addEventListener('load', function() {
        if (typeof L === 'undefined') {
            showMapError('Gagal memuat peta. Pastikan koneksi internet tersedia dan Leaflet dapat diakses.');
            return;
        }

        initMap();
    });
</script>
@endpush
@endsection

@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail Pesanan</h1>
                <p class="text-gray-600 mt-2">Lihat detail pesanan anda dan status terkini.</p>
            </div>
            <a href="{{ route('orders.index') }}" class="inline-flex items-center justify-center rounded-full border border-gray-300 bg-white px-5 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
                Kembali ke Riwayat Pesanan
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 p-4 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-4 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="rounded-3xl border border-gray-200 bg-white shadow-sm p-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.18em] text-blue-600">Order #{{ $order->id }}</p>
                    <h2 class="text-2xl font-semibold text-gray-900">{{ $order->status_label }}</h2>
                    <p class="mt-2 text-sm text-gray-500">Dibuat pada {{ $order->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 px-4 py-3 text-right">
                    <p class="text-sm text-gray-600">Total Tagihan</p>
                    <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-2xl border border-gray-200 bg-slate-50 p-4">
                    <p class="text-sm text-gray-600">Metode Pembayaran</p>
                    <p class="mt-2 font-semibold text-gray-900">{{ ucwords(str_replace('-', ' ', $order->payment_method)) }}</p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-slate-50 p-4">
                    <p class="text-sm text-gray-600">Total Item</p>
                    <p class="mt-2 font-semibold text-gray-900">{{ $order->total_items }}</p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-slate-50 p-4">
                    <p class="text-sm text-gray-600">Bukti Transfer</p>
                    @if($order->proof_photo)
                        <a href="{{ Storage::url($order->proof_photo) }}" target="_blank" class="mt-2 inline-block text-blue-600 hover:underline">Lihat file</a>
                    @else
                        <p class="mt-2 text-sm text-gray-500">Belum tersedia</p>
                    @endif
                </div>
            </div>

            @if($order->shipping_address || $order->tracking_number)
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    @if($order->shipping_address)
                        <div class="rounded-2xl border border-gray-200 bg-slate-50 p-4">
                            <p class="text-sm text-gray-600">Alamat Pengiriman</p>
                            <p class="mt-2 text-gray-900">{{ $order->shipping_address }}</p>
                        </div>
                    @endif
                    @if($order->tracking_number)
                        <div class="rounded-2xl border border-gray-200 bg-slate-50 p-4">
                            <p class="text-sm text-gray-600">Nomor Resi</p>
                            <p class="mt-2 font-semibold text-gray-900">{{ $order->tracking_number }}</p>
                        </div>
                    @endif
                </div>
            @endif

            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-900">Progres Pesanan</h3>
                <p class="text-sm text-gray-600 mt-2">Status di sini hanya menunjukkan langkah pemrosesan sampai pengiriman. Keputusan selesai atau refund dibuat oleh pembeli.</p>

                <div class="mt-6 space-y-4">
                    @foreach($order->timeline_steps as $index => $step)
                        @php
                            $activeIndex = $order->active_step_index;
                            $isActive = $index <= $activeIndex;
                        @endphp
                        <div class="flex items-start gap-4">
                            <div class="mt-1 flex h-10 w-10 items-center justify-center rounded-2xl border {{ $isActive ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-200 bg-white text-gray-400' }}">
                                <span class="font-semibold">{{ $index + 1 }}</span>
                            </div>
                            <div class="flex-1 border-l-4 {{ $isActive ? 'border-blue-600' : 'border-gray-200' }} pl-5 py-2">
                                <p class="text-sm font-semibold {{ $isActive ? 'text-gray-900' : 'text-gray-500' }}">{{ $step['label'] }}</p>
                                @if($step['key'] === \App\Models\Order::STATUS_PAYMENT_SUBMITTED && $order->status === \App\Models\Order::STATUS_PAYMENT_SUBMITTED)
                                    <p class="text-sm text-gray-600">Bukti pembayaran telah diterima dan sedang menunggu verifikasi.</p>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    @if($order->status === \App\Models\Order::STATUS_COMPLETED)
                        <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-green-800">
                            Pesanan telah selesai dan diterima. Keputusan selesai dibuat oleh pembeli.
                        </div>
                    @endif

                    @if($order->isRefunded())
                        <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800">
                            Pesanan ini telah direfund oleh pembeli. Silakan hubungi admin jika ada pertanyaan.
                        </div>
                    @endif

                    @if($order->isCancelled())
                        <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-red-800">
                            Pesanan ini telah dibatalkan oleh pembeli sebelum barang dikirim.
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                @if($order->status === \App\Models\Order::STATUS_SHIPPED)
                    <form action="{{ route('orders.complete', $order) }}" method="POST" class="rounded-3xl border border-gray-200 bg-white p-4">
                        @csrf
                        <p class="text-sm text-gray-600">Barang sudah sampai? Pilih ini jika pesanan sudah lengkap diterima.</p>
                        <button type="submit" class="mt-3 inline-flex w-full items-center justify-center rounded-full bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700">
                            Tandai Selesai
                        </button>
                    </form>

                    <form action="{{ route('orders.refund', $order) }}" method="POST" class="rounded-3xl border border-gray-200 bg-white p-4">
                        @csrf
                        <p class="text-sm text-gray-600">Masih ingin refund setelah barang sampai? Ajukan refund sekarang.</p>
                        <button type="submit" class="mt-3 inline-flex w-full items-center justify-center rounded-full bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                            Minta Refund
                        </button>
                    </form>
                @elseif($order->status !== \App\Models\Order::STATUS_COMPLETED && $order->status !== \App\Models\Order::STATUS_REFUNDED)
                    @if($order->canBeCancelled())
                        <form action="{{ route('orders.cancel', $order) }}" method="POST" class="rounded-3xl border border-gray-200 bg-white p-4" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?');">
                            @csrf
                            <p class="text-sm text-gray-600">Pesanan belum dikirim. Anda masih bisa membatalkan pesanan ini.</p>
                            <button type="submit" class="mt-3 inline-flex w-full items-center justify-center rounded-full bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                                Batalkan Pesanan
                            </button>
                        </form>
                    @endif

                    @if(! $order->isCancelled())
                        <div class="rounded-3xl border border-gray-200 bg-white p-4 text-gray-600">
                            <p class="text-sm">Tombol selesai dan refund akan muncul setelah pesanan dikirim.</p>
                        </div>
                    @endif
                @endif
            </div>

            <div class="mt-10 rounded-3xl border border-gray-200 bg-slate-50 p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Detail Produk</h3>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="rounded-2xl border border-gray-200 bg-white p-4">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                                <div class="flex-shrink-0 w-20 h-20 overflow-hidden rounded-2xl bg-gray-100">
                                    <img src="{{ $item->product_photo ? Storage::url($item->product_photo) : 'https://via.placeholder.com/200x200?text=No+Image' }}" alt="{{ $item->product_name }}" class="h-full w-full object-cover" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900">{{ $item->product_name }}</p>
                                    <p class="text-sm text-gray-600">Qty: {{ $item->quantity }}</p>
                                    @if($item->notes)
                                        <p class="text-sm text-gray-500">Catatan: {{ $item->notes }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Harga satuan</p>
                                    <p class="font-semibold text-gray-900">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    <p class="text-sm text-gray-500 mt-1">Subtotal</p>
                                    <p class="font-semibold text-blue-600">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            @if(in_array($order->status, [\App\Models\Order::STATUS_COMPLETED, \App\Models\Order::STATUS_REFUNDED], true))
                                <div class="mt-6 rounded-2xl border border-gray-200 bg-slate-50 p-4">
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                        <h4 class="text-lg font-semibold text-gray-900">Review Produk</h4>
                                        <button type="button"
                                            data-review-toggle="review-form-{{ $item->id }}"
                                            data-review-exists="{{ $item->review ? 'true' : 'false' }}"
                                            class="inline-flex items-center justify-center rounded-full border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100"
                                        >
                                            {{ $item->review ? 'Edit Review' : 'Beri Review' }}
                                        </button>
                                    </div>

                                    <div id="review-form-{{ $item->id }}" class="mt-4 {{ $item->review ? '' : 'hidden' }}">
                                        @if($item->review)
                                            <div class="rounded-2xl border border-gray-200 bg-white p-4">
                                                <div class="flex items-center justify-between gap-4">
                                                    <div>
                                                        <p class="font-semibold text-gray-900">Review sudah dikirim</p>
                                                        <p class="text-sm text-gray-500">Dikirim pada {{ $item->review->created_at->format('d M Y') }}</p>
                                                    </div>
                                                    <div class="flex items-center gap-1 text-yellow-500">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="bi {{ $i <= $item->review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                                        @endfor
                                                        <span class="ml-2 text-sm text-gray-700">{{ $item->review->rating }}/5</span>
                                                    </div>
                                                </div>

                                                @if($item->review->comment)
                                                    <p class="mt-3 text-gray-700">{{ $item->review->comment }}</p>
                                                @endif

                                                @if($item->review->photo)
                                                    <div class="mt-4 overflow-hidden rounded-2xl border border-gray-200">
                                                        <img src="{{ $item->review->photo_url }}" alt="Foto review {{ $item->product_name }}" class="w-full object-cover max-h-72" />
                                                    </div>
                                                @endif

                                                <p class="mt-3 text-sm text-gray-500">Anda dapat memperbarui review di bawah ini jika ingin mengganti komentar atau foto.</p>
                                            </div>
                                        @endif

                                        <form action="{{ route('orders.items.review.store', [$order, $item]) }}" method="POST" class="mt-4 space-y-4" enctype="multipart/form-data">
                                            @csrf

                                            <div class="grid gap-4 sm:grid-cols-2">
                                                <div>
                                                    <label class="block text-sm font-semibold text-gray-700">Rating</label>
                                                    <div class="mt-1 flex items-center gap-2">
                                                        <input type="hidden" name="rating" value="{{ old('rating', $item->review->rating ?? 0) }}" data-item-id="{{ $item->id }}" class="rating-value" />
                                                        @php $currentRating = (int) old('rating', $item->review->rating ?? 0); @endphp
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <button type="button" data-role="review-star" data-item-id="{{ $item->id }}" data-rating="{{ $i }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-400 hover:text-yellow-500 focus:outline-none">
                                                                <i class="bi {{ $i <= $currentRating ? 'bi-star-fill text-yellow-500' : 'bi-star' }}"></i>
                                                            </button>
                                                        @endfor
                                                    </div>
                                                    @error('rating') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-semibold text-gray-700">Foto Bukti Barang</label>
                                                    <input type="file" name="photo" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:border-0 file:bg-gray-100 file:px-3 file:py-2 file:rounded-full" />
                                                    @error('photo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700">Komentar</label>
                                                <textarea name="comment" rows="3" class="mt-1 block w-full rounded-2xl border border-gray-300 bg-white px-3 py-2 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-blue-200">{{ old('comment', $item->review->comment ?? '') }}</textarea>
                                                @error('comment') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                            </div>

                                            <button type="submit" class="inline-flex items-center justify-center rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                                                {{ $item->review ? 'Perbarui Review' : 'Kirim Review' }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-role="review-star"]').forEach(function (button) {
            button.addEventListener('click', function () {
                const itemId = this.dataset.itemId;
                const rating = parseInt(this.dataset.rating, 10);
                const input = document.querySelector('input[name="rating"][data-item-id="' + itemId + '"]');
                if (!input) {
                    return;
                }
                input.value = rating;

                document.querySelectorAll('[data-role="review-star"][data-item-id="' + itemId + '"]').forEach(function (starButton) {
                    const starRating = parseInt(starButton.dataset.rating, 10);
                    const icon = starButton.querySelector('i');
                    if (starRating <= rating) {
                        icon.classList.remove('bi-star');
                        icon.classList.add('bi-star-fill', 'text-yellow-500');
                    } else {
                        icon.classList.remove('bi-star-fill', 'text-yellow-500');
                        icon.classList.add('bi-star');
                    }
                });
            });
        });

        document.querySelectorAll('[data-review-toggle]').forEach(function (button) {
            button.addEventListener('click', function () {
                const targetId = this.dataset.reviewToggle;
                const target = document.getElementById(targetId);
                if (!target) {
                    return;
                }

                const isHidden = target.classList.toggle('hidden');
                if (isHidden) {
                    this.textContent = this.dataset.reviewExists === 'true' ? 'Edit Review' : 'Beri Review';
                } else {
                    this.textContent = 'Tutup Review';
                }
            });
        });
    });
</script>
@endsection

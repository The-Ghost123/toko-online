@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Riwayat Pesanan</h1>
            <p class="text-gray-600 mt-2">Lihat status pesanan dan pelacakan visual dari Pending Payment hingga Refund.</p>
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

        @if($orders->isEmpty())
            <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-10 text-center">
                <p class="text-lg font-semibold text-gray-900">Belum ada pesanan.</p>
                <p class="mt-2 text-gray-600">Mulai belanja sekarang dan cek halaman ini untuk melihat riwayat pesanan Anda.</p>
                <a href="{{ route('products') }}" class="mt-6 inline-flex rounded-full bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700">Belanja Sekarang</a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="rounded-3xl border border-gray-200 bg-white shadow-sm p-6">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <p class="text-sm uppercase tracking-[0.18em] text-blue-600">Order #{{ $order->id }}</p>
                                <h2 class="text-xl font-semibold text-gray-900">{{ $order->status_label }}</h2>
                                <p class="mt-2 text-sm text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-1 text-right">
                                <div>
                                    <p class="text-sm text-gray-500">Total Tagihan</p>
                                    <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Total Item</p>
                                    <p class="font-semibold text-gray-900">{{ $order->total_items }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            <div class="rounded-2xl border border-gray-200 bg-slate-50 p-4">
                                <p class="text-sm text-gray-600">Metode Pembayaran</p>
                                <p class="mt-2 font-semibold text-gray-900">{{ ucwords(str_replace('-', ' ', $order->payment_method)) }}</p>
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-slate-50 p-4">
                                <p class="text-sm text-gray-600">Status</p>
                                <p class="mt-2 font-semibold {{ $order->isRefunded() || $order->isCancelled() ? 'text-red-600' : 'text-green-700' }}">{{ $order->status_label }}</p>
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-slate-50 p-4">
                                <p class="text-sm text-gray-600">Bukti Transfer</p>
                                @if($order->proof_photo)
                                    <a href="{{ Storage::url($order->proof_photo) }}" target="_blank" class="mt-2 inline-block text-blue-600 hover:underline">Lihat file</a>
                                @else
                                    <p class="mt-2 text-sm text-gray-500">Belum tersedia</p>
                                @endif
                            </div>
                            <div class="rounded-2xl border border-gray-200 bg-slate-50 p-4">
                                <p class="text-sm text-gray-600">Aksi</p>
                                <a href="{{ route('orders.show', $order) }}" class="mt-2 inline-flex w-full items-center justify-center rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Detail</a>
                                @if($order->canBeCancelled())
                                    <form action="{{ route('orders.cancel', $order) }}" method="POST" class="mt-2" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?');">
                                        @csrf
                                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-full bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                                            Batalkan Pesanan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

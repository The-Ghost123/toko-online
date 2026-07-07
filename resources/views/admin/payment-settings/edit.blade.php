@extends('layouts.admin')
@section('title', 'Pengaturan Pembayaran')
@section('page-title', 'Pengaturan Pembayaran')

@section('content')
<div class="card border-0 shadow-sm rounded-3" style="max-width: 760px;">
    <div class="card-body p-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('admin.payment-settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <h5 class="fw-semibold mb-3">Bank Transfer</h5>
                <div class="mb-3">
                    <label class="form-label fw-medium">Nama Bank</label>
                    <input type="text" name="payment_bank_name" class="form-control @error('payment_bank_name') is-invalid @enderror" value="{{ old('payment_bank_name', $paymentSettings['payment_bank_name']) }}" placeholder="Contoh: Bank BCA">
                    @error('payment_bank_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Nomor Rekening</label>
                    <input type="text" name="payment_bank_account" class="form-control @error('payment_bank_account') is-invalid @enderror" value="{{ old('payment_bank_account', $paymentSettings['payment_bank_account']) }}" placeholder="Contoh: 1234567890">
                    @error('payment_bank_account')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label class="form-label fw-medium">Atas Nama</label>
                    <input type="text" name="payment_bank_account_name" class="form-control @error('payment_bank_account_name') is-invalid @enderror" value="{{ old('payment_bank_account_name', $paymentSettings['payment_bank_account_name']) }}" placeholder="Contoh: FashionStore">
                    @error('payment_bank_account_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-4">
                <h5 class="fw-semibold mb-3">E-Wallet</h5>
                <div class="mb-3">
                    <label class="form-label fw-medium">Provider E-Wallet</label>
                    <input type="text" name="payment_ewallet_provider" class="form-control @error('payment_ewallet_provider') is-invalid @enderror" value="{{ old('payment_ewallet_provider', $paymentSettings['payment_ewallet_provider']) }}" placeholder="Contoh: DANA / GoPay / OVO">
                    @error('payment_ewallet_provider')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label class="form-label fw-medium">Nomor E-Wallet</label>
                    <input type="text" name="payment_ewallet_number" class="form-control @error('payment_ewallet_number') is-invalid @enderror" value="{{ old('payment_ewallet_number', $paymentSettings['payment_ewallet_number']) }}" placeholder="Contoh: 081234567890">
                    @error('payment_ewallet_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-4">
                <h5 class="fw-semibold mb-3">QRIS</h5>
                <div class="mb-3">
                    <label class="form-label fw-medium">Unggah QRIS</label>
                    <input type="file" name="payment_qris_image" accept="image/*" class="form-control @error('payment_qris_image') is-invalid @enderror">
                    @error('payment_qris_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                @if($paymentSettings['payment_qris_image'])
                    <div class="rounded-3 border border-gray-200 p-3 bg-light">
                        <p class="text-sm text-muted mb-2">Preview QRIS saat ini:</p>
                        <img src="{{ Storage::url($paymentSettings['payment_qris_image']) }}" alt="Preview QRIS" class="img-fluid rounded-3">
                    </div>
                @else
                    <div class="text-muted small">Belum ada QRIS yang diunggah.</div>
                @endif
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-dark">
                    <i class="bi bi-save me-1"></i> Simpan Pengaturan
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

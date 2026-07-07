@extends('layouts.admin')
@section('title', 'Sosial Media')
@section('page-title', 'Pengaturan Sosial Media')

@section('content')
<div class="card border-0 shadow-sm rounded-3" style="max-width: 760px;">
    <div class="card-body p-4">
        <form action="{{ auth()->user()->isOwner() ? route('owner.social-media.update') : route('admin.social-media.update') }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-medium">Instagram</label>
                <input type="url" name="social_instagram"
                       class="form-control @error('social_instagram') is-invalid @enderror"
                       value="{{ old('social_instagram', $socialLinks['social_instagram']) }}"
                       placeholder="https://instagram.com/username">
                @error('social_instagram')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Facebook</label>
                <input type="url" name="social_facebook"
                       class="form-control @error('social_facebook') is-invalid @enderror"
                       value="{{ old('social_facebook', $socialLinks['social_facebook']) }}"
                       placeholder="https://facebook.com/username">
                @error('social_facebook')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Twitter</label>
                <input type="url" name="social_twitter"
                       class="form-control @error('social_twitter') is-invalid @enderror"
                       value="{{ old('social_twitter', $socialLinks['social_twitter']) }}"
                       placeholder="https://twitter.com/username">
                @error('social_twitter')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-medium">WhatsApp</label>
                <input type="url" name="social_whatsapp"
                       class="form-control @error('social_whatsapp') is-invalid @enderror"
                       value="{{ old('social_whatsapp', $socialLinks['social_whatsapp']) }}"
                       placeholder="https://wa.me/6281234567890">
                @error('social_whatsapp')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-dark">
                    <i class="bi bi-save me-1"></i> Simpan
                </button>
                <a href="{{ auth()->user()->isOwner() ? route('owner.dashboard') : route('admin.dashboard') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

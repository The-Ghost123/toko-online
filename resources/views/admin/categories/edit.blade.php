{{-- edit.blade.php --}}
@extends('layouts.admin')
@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')

@section('content')
<div class="card border-0 shadow-sm rounded-3" style="max-width: 520px;">
    <div class="card-body p-4">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-medium">Nama Kategori <span class="text-danger">*</span></label>
                <input type="text" name="nama_kategori"
                       class="form-control @error('nama_kategori') is-invalid @enderror"
                       value="{{ old('nama_kategori', $category->nama_kategori) }}">
                @error('nama_kategori')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-medium">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                          class="form-control">{{ old('deskripsi', $category->deskripsi) }}</textarea>
            </div>
            <div class="mb-4">
                <label class="form-label fw-medium">Foto Kategori</label>
                <input type="file" name="foto_kategori" class="form-control @error('foto_kategori') is-invalid @enderror">
                @error('foto_kategori')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @if($category->foto_kategori)
                    <div class="mt-3">
                        <img src="{{ asset('storage/' . $category->foto_kategori) }}" alt="{{ $category->nama_kategori }}" class="img-fluid rounded" style="max-height: 180px;">
                    </div>
                @endif
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-dark">
                    <i class="bi bi-check-lg me-1"></i> Update
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
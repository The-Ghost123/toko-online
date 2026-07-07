@extends('layouts.admin')
@section('title', 'Edit Tentang Kami')
@section('page-title', 'Edit Tentang Kami')

@section('content')
<div class="card border-0 shadow-sm rounded-3" style="max-width: 800px;">
    <div class="card-body p-4">
        <form action="{{ auth()->user()->isOwner() ? route('owner.pages.about.update') : route('admin.pages.about.update') }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-medium">Judul Halaman <span class="text-danger">*</span></label>
                <input type="text" name="title"
                       class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title', $page->title) }}">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium">Gambar Header</label>
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @if($page->image)
                    <div class="mt-3">
                        <img src="{{ str_starts_with($page->image, 'http') ? $page->image : asset('storage/' . $page->image) }}" alt="Header {{ $page->title }}" class="img-fluid rounded" style="max-height: 240px; width: 100%; object-fit: cover;">
                    </div>
                @endif
            </div>

            <div class="mb-4">
                <label class="form-label fw-medium">Konten Halaman <span class="text-danger">*</span></label>
                <textarea id="editor" name="content" rows="8"
                          class="form-control @error('content') is-invalid @enderror">{{ old('content', $page->content) }}</textarea>
                @error('content')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-dark">
                    <i class="bi bi-check-lg me-1"></i> Simpan
                </button>
                <a href="{{ auth()->user()->isOwner() ? route('owner.dashboard') : route('admin.dashboard') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#editor',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | formatselect | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat',
        height: 400,
        menubar: false,
        content_style: `
            body { 
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                font-size: 14px;
                line-height: 1.6;
                color: #333;
            }
            p { margin: 0.75rem 0; }
            h1, h2, h3, h4, h5, h6 { margin-top: 1rem; margin-bottom: 0.5rem; }
        `
    });
</script>
@endsection

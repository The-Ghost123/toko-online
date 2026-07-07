<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — FashionStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; }

        /* Sidebar */
        #sidebar {
            width: 240px; min-height: 100vh;
            background: #1a1a2e;
            position: fixed; top: 0; left: 0;
            z-index: 100; transition: all .3s;
        }
        #sidebar .brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        #sidebar .brand span { color: #f5c842; font-weight: 700; font-size: 1.1rem; }
        #sidebar .nav-link {
            color: rgba(255,255,255,.65);
            padding: .65rem 1.25rem;
            border-radius: 8px; margin: 2px 8px;
            font-size: .9rem; transition: all .2s;
        }
        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {
            background: rgba(245,200,66,.12);
            color: #f5c842;
        }
        #sidebar .nav-link i { width: 20px; }

        /* Main content */
        #main { margin-left: 240px; min-height: 100vh; }
        .topbar {
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            padding: .75rem 1.5rem;
        }

        /* Cards */
        .stat-card {
            border: none; border-radius: 12px;
            transition: transform .2s;
        }
        .stat-card:hover { transform: translateY(-3px); }

        @media (max-width: 768px) {
            #sidebar { width: 100%; min-height: auto; position: relative; }
            #main { margin-left: 0; }
        }
    </style>
    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

{{-- SIDEBAR --}}
<div id="sidebar">
    <div class="brand d-flex align-items-center gap-2">
        <i class="bi bi-bag-heart-fill text-warning fs-5"></i>
        <span>FashionStore</span>
    </div>

    <nav class="nav flex-column mt-2">
        <a href="{{ auth()->user()->isOwner() ? route('owner.dashboard') : route('admin.dashboard') }}"
           class="nav-link {{ request()->routeIs('admin.dashboard','owner.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.categories.index') }}"
           class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="bi bi-tag me-2"></i> Kategori
        </a>
        <a href="{{ route('admin.products.index') }}"
           class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam me-2"></i> Produk
        </a>
        <a href="{{ route('admin.orders.index') }}"
           class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <i class="bi bi-journal-check me-2"></i> Transaksi Pelanggan
        </a>
        <a href="{{ route('admin.payment-settings.edit') }}"
           class="nav-link {{ request()->routeIs('admin.payment-settings.*') ? 'active' : '' }}">
            <i class="bi bi-credit-card-2-front me-2"></i> Pengaturan Pembayaran
        </a>
        <a href="{{ route('admin.carts.insights') }}"
           class="nav-link {{ request()->routeIs('admin.carts.*') ? 'active' : '' }}">
            <i class="bi bi-graph-up-arrow me-2"></i> Analitik Keranjang
        </a>
        @endif
        @if(auth()->user()->isOwner())
        <a href="{{ route('owner.social-media.edit') }}"
           class="nav-link {{ request()->routeIs('owner.social-media.*') ? 'active' : '' }}">
            <i class="bi bi-share-fill me-2"></i> Sosial Media
        </a>
        @endif
        <a href="{{ auth()->user()->isOwner() ? route('owner.product-feedback.index') : route('admin.product-feedback.index') }}"
           class="nav-link {{ request()->routeIs('admin.product-feedback.*','owner.product-feedback.*') ? 'active' : '' }}">
            <i class="bi bi-heart-fill me-2"></i> Ulasan & Favorit
        </a>
        <a href="{{ auth()->user()->isOwner() ? route('owner.pages.about.edit') : route('admin.pages.about.edit') }}"
           class="nav-link {{ request()->routeIs('admin.pages.about.*','owner.pages.about.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text me-2"></i> Tentang Kami
        </a>
        <hr style="border-color: rgba(255,255,255,.1); margin: .5rem 1rem;">
        <a href="{{ route('home') }}" class="nav-link" target="_blank">
            <i class="bi bi-eye me-2"></i> Lihat Website
        </a>
        <form action="{{ route('logout') }}" method="POST" class="px-2 mt-1">
            @csrf
            <button type="submit" class="nav-link btn btn-link w-100 text-start text-danger">
                <i class="bi bi-box-arrow-left me-2"></i> Logout
            </button>
        </form>
    </nav>
</div>

{{-- MAIN CONTENT --}}
<div id="main">
    {{-- Topbar --}}
    <div class="topbar d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-semibold text-muted">@yield('page-title', 'Dashboard')</h6>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.orders.index') }}" class="position-relative text-decoration-none text-dark" id="admin-notification-button" aria-label="Notifikasi Transaksi">
                    <i class="bi bi-bell fs-5"></i>
                    <span id="admin-notification-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem; display: none;">0</span>
                </a>
        </div>
    </div>

    {{-- Content --}}
    <div class="p-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
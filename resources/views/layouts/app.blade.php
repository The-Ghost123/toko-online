<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FashionStore')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --cream: #faf7f2;
            --dark: #1c1917;
            --accent: #c8a97e;
            --muted: #78716c;
            --border: #e7e3dc;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--dark);
        }

        /* ── Navbar ── */
        .navbar-main {
            background: rgba(250,247,242,.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-brand-custom {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark) !important;
            letter-spacing: .04em;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .navbar-brand-custom .dot {
            width: 8px; height: 8px;
            background: var(--accent);
            border-radius: 50%;
            display: inline-block;
        }

        /* Search */
        .search-form {
            flex: 1;
            min-width: 0;
            max-width: 420px;
            display: flex !important;
        }

        .search-form .form-control {
            border: 1px solid var(--border);
            border-radius: 50px 0 0 50px;
            background: #fff;
            font-size: .85rem;
            padding: .5rem 1.1rem;
            color: var(--dark);
            width: 100%;
            min-width: 0;
            transition: border-color .2s, box-shadow .2s;
            flex: 1;
        }

        .search-form .form-control:focus {
            border-color: var(--accent);
            box-shadow: none;
            outline: none;
        }

        .search-form .btn-search {
            border-radius: 0 50px 50px 0;
            background: var(--dark);
            border: none;
            color: #fff;
            padding: .5rem 1rem;
            font-size: .85rem;
            transition: background .2s;
            flex-shrink: 0;
        }

        @media (max-width: 768px) {
            .navbar-main {
                padding: 0.75rem 0;
            }

            .navbar-main .container {
                flex-wrap: wrap;
                justify-content: flex-start;
                align-items: center;
                gap: 0.5rem;
                row-gap: 0.75rem;
            }

            .navbar-brand-custom {
                flex: 0 0 auto;
                margin-right: auto;
            }

            .nav-links {
                display: none !important;
            }

            .search-form {
                order: 2;
                width: 100%;
                max-width: none;
                flex: 0 0 100%;
                min-width: 0;
                display: flex !important;
                margin: 0;
            }

            .search-form .form-control {
                min-width: 0;
                flex: 1;
                padding: 0.5rem 0.9rem;
                font-size: 0.8rem;
                border-radius: 50px 0 0 50px;
            }

            .search-form .btn-search {
                flex-shrink: 0;
                white-space: nowrap;
                padding: 0.5rem 0.8rem;
                font-size: 0.75rem;
                border-radius: 0 50px 50px 0;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }

        .search-form .btn-search:hover {
            background: var(--accent);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-left: auto;
        }

        .nav-icon-btn,
        .user-btn {
            border: 1px solid var(--border);
            background: #fff;
            color: var(--dark);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: border-color .2s, color .2s, background .2s;
        }

        .nav-icon-btn {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            position: relative;
        }

        .nav-icon-btn .bi {
            font-size: 1.2rem;
        }

        .nav-icon-btn:hover,
        .user-btn:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .nav-icon-btn::after {
            content: '';
            position: absolute;
            top: 10px;
            right: 10px;
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #ef4444;
            border: 2px solid #fff;
        }

        .user-btn {
            gap: .5rem;
            padding: .55rem .9rem;
            border-radius: 999px;
            font-weight: 600;
        }

        .user-btn .bi {
            font-size: 1.15rem;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-left: 1.5rem;
        }

        .nav-icon-btn,
        .user-btn {
            border: 1px solid var(--border);
            background: #fff;
            color: var(--dark);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: border-color .2s, color .2s, background .2s;
        }

        .nav-icon-btn {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            position: relative;
        }

        .nav-icon-btn .bi {
            font-size: 1.2rem;
        }

        .nav-icon-btn:hover,
        .user-btn:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .nav-icon-btn::after {
            content: '';
            position: absolute;
            top: 10px;
            right: 10px;
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #ef4444;
            border: 2px solid #fff;
        }

        .user-btn {
            gap: .5rem;
            padding: .55rem .9rem;
            border-radius: 999px;
            font-weight: 600;
        }

        .user-btn .bi {
            font-size: 1.15rem;
        }

        .nav-link {
            color: var(--dark);
            text-decoration: none;
            font-weight: 600;
            transition: color .2s;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--accent);
        }

        .footer-links {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: var(--muted);
            text-decoration: none;
            display: inline-flex;
            gap: .35rem;
            align-items: center;
            font-size: .92rem;
        }

        .footer-links a:hover {
            color: var(--dark);
        }

        /* ── Category Pills ── */
        .category-bar {
            padding: 1rem 0;
            border-bottom: 1px solid var(--border);
            background: #fff;
        }

        .category-bar .pill {
            display: inline-block;
            padding: .35rem 1rem;
            border-radius: 50px;
            font-size: .85rem;
            font-weight: 600;
            letter-spacing: .01em;
            text-decoration: none;
            border: 1px solid var(--border);
            color: var(--muted);
            background: #fff;
            transition: all .2s;
            white-space: nowrap;
        }

        .category-bar-title {
            font-size: 1.05rem;
            font-weight: 700;
            margin: 0 0 .25rem;
            color: var(--dark);
        }

        .category-bar-text {
            margin: 0;
            color: var(--muted);
            font-size: .92rem;
        }

        .category-select-form {
            min-width: 220px;
        }

        .category-select {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: .6rem .9rem;
            color: var(--dark);
            background: #fff;
            font-size: .94rem;
            transition: border-color .2s, box-shadow .2s;
        }

        .category-select-label {
            display: block;
            margin-bottom: .45rem;
            color: var(--muted);
            font-size: .85rem;
            font-weight: 500;
        }

        .category-select:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(200, 169, 126, .12);
        }

        .catalog-hero {
            background: linear-gradient(135deg, rgba(248, 230, 194, .96) 0%, rgba(255, 255, 255, .98) 100%);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 2rem;
            margin-bottom: 2rem;
            overflow: hidden;
            position: relative;
        }

        .catalog-hero::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 160px;
            height: 160px;
            background: rgba(200, 169, 126, .18);
            border-radius: 50%;
        }

        .catalog-hero::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: -20px;
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, .6);
            border-radius: 50%;
        }

        .catalog-hero h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.5rem;
            margin-bottom: .75rem;
            color: var(--dark);
        }

        .catalog-hero p {
            color: var(--muted);
            max-width: 560px;
            font-size: .98rem;
            line-height: 1.8;
            margin-bottom: 1.4rem;
        }

        .feature-grid {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        @media (max-width: 991px) {
            .feature-grid {
                grid-template-columns: 1fr;
            }
        }

        .feature-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 1.2rem 1.3rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            transition: transform .2s, box-shadow .2s;
        }

        .feature-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(28,25,23,.08);
        }

        .feature-card .icon {
            width: 48px;
            height: 48px;
            display: grid;
            place-items: center;
            border-radius: 14px;
            background: rgba(200, 169, 126, .16);
            color: var(--accent);
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .feature-card .feature-body {
            min-width: 0;
        }

        .feature-card .feature-title {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: .25rem;
            font-size: .97rem;
        }

        .feature-card .feature-text {
            font-size: .9rem;
            color: var(--muted);
            line-height: 1.6;
        }

        .feature-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(28,25,23,.08);
        }

        .feature-card .icon {
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            border-radius: 14px;
            background: rgba(200, 169, 126, .16);
            color: var(--accent);
            font-size: 1.1rem;
        }

        .feature-card .text {
            font-size: .92rem;
            color: var(--dark);
            line-height: 1.4;
        }

        .catalog-bottom {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 1.75rem 1.5rem;
            margin-top: 2rem;
        }

        .catalog-bottom h3 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.5rem;
            margin-bottom: .6rem;
        }

        .catalog-bottom p {
            color: var(--muted);
            font-size: .95rem;
            line-height: 1.7;
        }

        .catalog-bottom .btn-secondary {
            background: var(--dark);
            border: none;
            color: #fff;
            padding: .7rem 1.3rem;
            border-radius: 10px;
            font-weight: 500;
            transition: background .2s;
        }

        .catalog-bottom .btn-secondary:hover {
            background: #2e2c2a;
        }

        .category-bar .pill:hover,
        .category-bar .pill.active {
            background: var(--dark);
            border-color: var(--dark);
            color: #fff;
        }

        /* ── Product Card ── */
        .product-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            transition: transform .25s, box-shadow .25s;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(28,25,23,.1);
        }

        .product-card .img-wrap {
            position: relative;
            height: 240px;
            overflow: hidden;
            background: #f5f1eb;
        }

        .product-card .img-wrap img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform .4s ease;
        }

        .product-card:hover .img-wrap img {
            transform: scale(1.06);
        }

        .product-card .stok-badge {
            position: absolute;
            top: .75rem; left: .75rem;
            font-size: .68rem;
            font-weight: 500;
            letter-spacing: .06em;
            text-transform: uppercase;
            padding: .25rem .65rem;
            border-radius: 4px;
        }

        .product-card .card-body {
            padding: 1rem 1.1rem;
        }

        .product-card .kategori-label {
            font-size: .72rem;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--accent);
            font-weight: 500;
            margin-bottom: .25rem;
        }

        .product-card .product-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: .1rem;
            line-height: 1.3;
        }

        .product-card .product-price {
            font-size: .95rem;
            font-weight: 500;
            color: var(--dark);
            margin-bottom: .9rem;
        }

        .btn-wa {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            width: 100%;
            padding: .6rem;
            background: #25D366;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: .82rem;
            font-weight: 500;
            text-decoration: none;
            transition: background .2s, transform .15s;
        }

        .btn-wa:hover {
            background: #1ebe5d;
            color: #fff;
            transform: translateY(-1px);
        }

        /* ── Empty State ── */
        .empty-state {
            text-align: center;
            padding: 5rem 1rem;
        }

        .empty-state .icon {
            font-size: 3rem;
            color: var(--border);
            margin-bottom: 1rem;
        }

        .empty-state p {
            color: var(--muted);
            font-size: .9rem;
        }

        /* ── Footer ── */
        footer {
            border-top: 1px solid var(--border);
            padding: 2rem 0;
            margin-top: 4rem;
            background: #fff;
        }

        footer .brand {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark);
        }

        footer small {
            color: var(--muted);
            font-size: .8rem;
        }

        /* Animations */
        .fade-in {
            animation: fadeIn .5s ease both;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Hero ── */
        .catalog-hero {
            background: linear-gradient(135deg, #f8eed8 0%, #fff 100%);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.75rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .hero-tag {
            display: inline-block;
            background: var(--accent);
            color: #fff;
            font-size: .7rem;
            letter-spacing: .1em;
            text-transform: uppercase;
            padding: .25rem .7rem;
            border-radius: 4px;
            margin-bottom: .75rem;
        }

        .catalog-hero h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.9rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: .5rem;
            line-height: 1.2;
        }

        .catalog-hero p {
            color: var(--muted);
            font-size: .9rem;
            margin-bottom: 1rem;
        }

        .btn-hero-primary {
            display: inline-block;
            background: var(--dark);
            color: #fff;
            padding: .55rem 1.4rem;
            border-radius: 50px;
            font-size: .85rem;
            font-weight: 500;
            text-decoration: none;
            transition: background .2s;
        }

        .btn-hero-primary:hover {
            background: var(--accent);
            color: #fff;
        }

        .hero-features {
            display: flex;
            gap: .75rem;
            flex-shrink: 0;
        }

        .feat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .4rem;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: .85rem 1rem;
            font-size: .75rem;
            color: var(--muted);
            min-width: 80px;
            text-align: center;
        }

        .feat-item i {
            font-size: 1.3rem;
            color: var(--accent);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 4rem 1rem;
            color: var(--muted);
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--border);
            display: block;
            margin-bottom: .75rem;
        }

        @media (max-width: 576px) {
            .catalog-hero { padding: 1.25rem; }
            .catalog-hero h2 { font-size: 1.4rem; }
            .hero-features { width: 100%; justify-content: space-between; }
            .feat-item { flex: 1; }
        }

    </style>
    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/public.js'])
</head>
<body>

{{-- Navbar --}}
<nav class="navbar-main">
    <div class="container d-flex align-items-center justify-content-between gap-3">
        <a href="{{ route('home') }}" class="navbar-brand-custom">
            <span class="dot"></span> FashionStore
        </a>
        <div class="nav-links d-none d-md-flex">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('products') }}" class="nav-link {{ request()->routeIs('products') ? 'active' : '' }}">Produk</a>
            <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">Tentang Kami</a>
        </div>
        <form class="search-form d-flex" action="{{ route('products') }}" method="GET">
            @if(request('kategori'))
                <input type="hidden" name="kategori" value="{{ request('kategori') }}">
            @endif
            <input type="search" name="search" class="form-control"
                   placeholder="Cari produk..." value="{{ request('search') }}">
            <button type="submit" class="btn-search">
                <i class="bi bi-search"></i>
            </button>
        </form>
        <div class="nav-actions">
            <a href="{{ route('orders.index') }}" class="nav-icon-btn position-relative" aria-label="Notifikasi" id="notification-btn">
                <i class="bi bi-bell"></i>
                <span id="notification-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem; display: none;">
                    0
                </span>
            </a>

            <a href="{{ route('cart') }}" class="nav-icon-btn position-relative" aria-label="Keranjang Belanja" id="cart-btn">
                <i class="bi bi-cart3"></i>
                <span id="cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem; display: none;">
                    0
                </span>
            </a>

            <div class="dropdown">
                @php $user = auth()->user(); @endphp
                <button class="user-btn dropdown-toggle d-flex align-items-center" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                    @if($user && $user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}?v={{ optional($user->updated_at)->timestamp }}" alt="avatar" class="rounded-circle" style="width:36px;height:36px;object-fit:cover;margin-right:.5rem;border:1px solid var(--border);">
                    @else
                        <i class="bi bi-person-circle" style="font-size:1.6rem;margin-right:.4rem"></i>
                    @endif
                    <span>{{ $user->name ?? 'Akun' }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
                    <li><a class="dropdown-item" href="{{ route('orders.index') }}">Riwayat Pesanan</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item">Keluar</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

{{-- Category Bar --}}
@hasSection('show-categories')
<div class="category-bar">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-3">
            <div>
                <h3 class="category-bar-title">Pilih Kategori</h3>
                <p class="category-bar-text">Pilih kategori baju yang ingin Anda lihat untuk mempercepat pencarian.</p>
            </div>
            <form action="{{ route('home') }}" method="GET" class="category-select-form">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                <label for="kategori-select" class="category-select-label">Lihat berdasarkan kategori</label>
                <select id="kategori-select" name="kategori" class="form-select category-select" onchange="this.form.submit()">
                    <option value="" {{ !request('kategori') ? 'selected' : '' }}>Semua Kategori</option>
                    @foreach($categories ?? [] as $cat)
                        <option value="{{ $cat->slug }}" {{ request('kategori') == $cat->slug ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('home', ['search' => request('search')]) }}"
               class="pill {{ !request('kategori') ? 'active' : '' }}">
                Semua
            </a>
            @foreach($categories ?? [] as $cat)
                <a href="{{ route('home', ['kategori' => $cat->slug, 'search' => request('search')]) }}"
                   class="pill {{ request('kategori') == $cat->slug ? 'active' : '' }}">
                    {{ $cat->nama_kategori }}
                </a>
            @endforeach
        </div>
    </div>
</div>
@endif

<div id="notification-alert-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1100; width: min(100%, 360px);"></div>

<main class="container py-4 fade-in">
    @yield('content')
</main>

<footer>
    <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <span class="brand">FashionStore</span>
                <div class="footer-links mt-2">
                    <a href="{{ $socialLinks['social_instagram'] ?? 'https://instagram.com' }}" target="_blank"><i class="bi bi-instagram"></i> Instagram</a>
                    <a href="{{ $socialLinks['social_facebook'] ?? 'https://facebook.com' }}" target="_blank"><i class="bi bi-facebook"></i> Facebook</a>
                    <a href="{{ $socialLinks['social_twitter'] ?? 'https://twitter.com' }}" target="_blank"><i class="bi bi-twitter"></i> Twitter</a>
                    <a href="{{ $socialLinks['social_whatsapp'] ?? 'https://wa.me/6281234567890' }}" target="_blank"><i class="bi bi-whatsapp"></i> WhatsApp</a>
                </div>
            </div>
            <small>&copy; {{ date('Y') }} FashionStore. All rights reserved kelompok 3.</small>
        </div>
    </footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function updateCartBadge(count) {
        const badge = document.getElementById('cart-badge');
        if (badge) {
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }
        }
    }

    function updateNotificationBadge(count) {
        const badge = document.getElementById('notification-badge');
        if (badge) {
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }
        }
    }

    function showNotificationAlert(alerts) {
        const container = document.getElementById('notification-alert-container');
        if (!container || alerts.length === 0) {
            return;
        }

        const alertBox = document.createElement('div');
        alertBox.className = 'alert alert-info border border-info rounded-3 shadow-sm position-relative';
        alertBox.style.minWidth = '300px';
        alertBox.style.marginBottom = '0.75rem';
        alertBox.innerHTML = `
            <strong>Notifikasi terbaru</strong>
            <ul class="mb-0 mt-2" style="padding-left: 1rem;">
                ${alerts.slice(0, 3).map(alert => `<li>${alert.message}</li>`).join('')}
            </ul>
        `;

        const closeButton = document.createElement('button');
        closeButton.type = 'button';
        closeButton.className = 'btn-close position-absolute';
        closeButton.style.top = '0.5rem';
        closeButton.style.right = '0.5rem';
        closeButton.setAttribute('aria-label', 'Tutup');
        closeButton.onclick = () => alertBox.remove();
        alertBox.appendChild(closeButton);

        container.innerHTML = '';
        container.appendChild(alertBox);

        setTimeout(() => {
            if (container.contains(alertBox)) {
                alertBox.remove();
            }
        }, 10000);
    }

    function startNotificationPolling() {
        const notificationsEndpoint = '{{ route('notifications.index') }}';
        let lastNotificationKey = sessionStorage.getItem('latestNotificationKey') || '';

        async function refreshNotifications() {
            try {
                const response = await fetch(notificationsEndpoint, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                });
                if (!response.ok) {
                    return;
                }

                const data = await response.json();
                updateNotificationBadge(data.count);

                const key = data.alerts.map(alert => alert.key).join('|');
                if (key && key !== lastNotificationKey) {
                    showNotificationAlert(data.alerts);
                    lastNotificationKey = key;
                    sessionStorage.setItem('latestNotificationKey', key);
                }
            } catch (error) {
                console.error('Gagal memuat notifikasi:', error);
            }
        }

        refreshNotifications();
        setInterval(refreshNotifications, 10000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initial cart load
        if (window.cart) {
            cart.getCart().catch(() => {});

            // Listen for cart changes and update badge
            cart.onChange(function(cartData) {
                updateCartBadge(cartData.total_items || 0);
            });
        }

        startNotificationPolling();
    });
</script>
@stack('scripts')
</body>
</html>

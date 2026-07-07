<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — FashionStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --cream: #faf7f2;
            --dark: #1c1917;
            --accent: #c8a97e;
            --muted: #78716c;
            --border: #e7e3dc;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            min-height: 100vh;
            display: flex;
        }

        /* ── Left Panel ── */
        .left-panel {
            width: 52%;
            position: relative;
            overflow: hidden;
        }

        .left-panel img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .left-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                160deg,
                rgba(28,25,23,.15) 0%,
                rgba(28,25,23,.55) 100%
            );
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 3rem;
        }

        .left-overlay h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 3rem;
            font-weight: 600;
            color: #fff;
            line-height: 1.1;
            margin-bottom: .75rem;
        }

        .left-overlay p {
            color: rgba(255,255,255,.75);
            font-size: .9rem;
            font-weight: 300;
            letter-spacing: .02em;
        }

        .left-overlay .tag {
            display: inline-block;
            background: var(--accent);
            color: #fff;
            font-size: .7rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            padding: .3rem .8rem;
            border-radius: 2px;
            margin-bottom: 1.25rem;
        }

        /* ── Right Panel ── */
        .right-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-box {
            width: 100%;
            max-width: 380px;
            animation: fadeUp .6s ease both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .brand-mark {
            display: flex;
            align-items: center;
            gap: .6rem;
            margin-bottom: 2.5rem;
        }

        .brand-mark .dot {
            width: 10px; height: 10px;
            background: var(--accent);
            border-radius: 50%;
        }

        .brand-mark span {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--dark);
            letter-spacing: .04em;
        }

        .login-box h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: .4rem;
        }

        .login-box .subtitle {
            color: var(--muted);
            font-size: .85rem;
            margin-bottom: 2rem;
        }

        .form-label {
            font-size: .78rem;
            font-weight: 500;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: .4rem;
        }

        .form-control {
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: .7rem 1rem;
            font-size: .9rem;
            background: #fff;
            color: var(--dark);
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(200,169,126,.15);
            outline: none;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .btn-login {
            width: 100%;
            background: var(--dark);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: .8rem;
            font-size: .9rem;
            font-weight: 500;
            letter-spacing: .04em;
            cursor: pointer;
            transition: background .2s, transform .15s;
            margin-top: .5rem;
        }

        .btn-login:hover {
            background: #2c2927;
            transform: translateY(-1px);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .divider {
            height: 1px;
            background: var(--border);
            margin: 1.75rem 0;
        }

        .back-link {
            display: flex;
            align-items: center;
            gap: .4rem;
            color: var(--muted);
            font-size: .82rem;
            text-decoration: none;
            transition: color .2s;
        }

        .back-link:hover { color: var(--dark); }

        .back-link svg {
            width: 14px; height: 14px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .left-panel { display: none; }
            .right-panel { padding: 1.5rem; }
        }
    </style>
</head>
<body>

    {{-- Left: fashion image --}}
    <div class="left-panel">
        <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=900&q=80"
             alt="Fashion">
        <div class="left-overlay">
            <span class="tag">Login Pembeli</span>
            <h1>Fashion<br>Store</h1>
            <p>Masuk untuk akses yang lebih baik<br>saat berbelanja di website kami.</p>
        </div>
    </div>

    {{-- Right: login form --}}
    <div class="right-panel">
        <div class="login-box">

            <div class="brand-mark">
                <div class="dot"></div>
                <span>FashionStore</span>
            </div>

            <h2>Selamat datang</h2>
            <p class="subtitle">Masuk untuk pengalaman berbelanja yang lebih baik.</p>

            {{-- Session Error --}}
            @if ($errors->any())
                <div class="alert alert-danger py-2 px-3 mb-3" style="font-size:.85rem; border-radius:6px; border:none; background:#fef2f2; color:#b91c1c;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           placeholder="email@example.com"
                           autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="••••••••">
                </div>

                <button type="submit" class="btn-login">
                    Masuk →
                </button>
            </form>

            <div class="mt-4 text-center">
                <p class="mb-2 text-muted">Belum punya akun?</p>
                <a href="{{ route('register') }}" class="btn-register">Daftar Sekarang</a>
            </div>

            <p class="text-center mt-3 text-muted">
                Ke Dashboard pembelian? <a href="{{ route('home') }}" class="fw-semibold">Katalog Produk</a>
            </p>

            <div class="divider"></div>

            <a href="{{ route('home') }}" class="back-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 5l-7 7 7 7"/>
                </svg>
                Kembali ke Website
            </a>

        </div>
    </div>

</body>
</html>
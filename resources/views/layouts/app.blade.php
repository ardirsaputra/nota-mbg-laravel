<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        use App\Models\Setting;
        $companyName = Setting::get('company_name', 'CV Mia Jaya Abadi');
        $companyLogo = Setting::get('company_logo');
    @endphp
    <title>
        @hasSection('title')
            @yield('title') - {{ $companyName }}
        @else
            {{ $companyName }}
        @endif
    </title>

    {{-- favicon — use company logo if set, otherwise fallback to public/favicon.ico --}}
    <link rel="icon" href="{{ $companyLogo ? asset('storage/' . $companyLogo) : asset('favicon.ico') }}" />

    <meta name="application-name" content="{{ $companyName }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f7fa;
            min-height: 100vh;
        }

        /* Navbar */
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .navbar .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .nav-logo img {
            height: 36px;
            border-radius: 6px;
            object-fit: contain;
            display: inline-block;
            flex-shrink: 0;
        }

        .brand-text {
            font-size: 1.1rem;
            font-weight: 700;
            color: #667eea;
            letter-spacing: 0.5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 4px;
            align-items: center;
            flex-wrap: nowrap;
        }

        .nav-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding: 5px 7px;
            border-radius: 8px;
        }

        .nav-group-items {
            display: flex;
            gap: 4px;
            flex-wrap: nowrap;
        }

        .nav-group-label {
            font-size: 10px;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            font-weight: 700;
        }

        .nav-menu a {
            color: #333;
            text-decoration: none;
            padding: 5px 8px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s;
            font-size: 0.85rem;
            white-space: nowrap;
        }

        .nav-menu a:hover,
        .nav-menu a.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        /* Skala medium — kurangi padding & font sebelum hamburger muncul */
        @media (max-width: 1200px) {
            .nav-menu a {
                font-size: 0.78rem;
                padding: 4px 6px;
            }

            .nav-group {
                padding: 4px 5px;
            }

            .nav-group-label {
                font-size: 9px;
            }

            .brand-text {
                font-size: 0.95rem;
            }
        }

        /* Logout button — red gradient, matching primary nav style */
        .logout-btn {
            background: linear-gradient(135deg, #ff7966 0%, #d94b3a 100%);
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 700;
            transition: transform 0.12s ease, box-shadow 0.12s ease;
            box-shadow: 0 6px 18px rgba(217, 75, 58, 0.12);
        }

        .logout-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 26px rgba(217, 75, 58, 0.18);
            background: linear-gradient(135deg, #ff5f4a 0%, #c73724 100%);
        }

        /* Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px 24px;
        }

        /* Global button (uniform UI) */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 8px;
            font-weight: 700;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: transform .12s ease, box-shadow .12s ease, opacity .12s ease;
            line-height: 1;
            font-size: 0.95rem;
            box-sizing: border-box;
        }

        .btn:active {
            transform: translateY(1px)
        }

        .btn[disabled],
        .btn.disabled {
            opacity: .6;
            pointer-events: none
        }

        /* Color modifiers */
        .btn-primary {
            background: linear-gradient(135deg, #667eea, #5a4ed6);
            color: #fff
        }

        .btn-success {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: #fff
        }

        .btn-secondary {
            background: #95a5a6;
            color: #fff
        }

        .btn-warning {
            background: linear-gradient(135deg, #f39c12, #d35400);
            color: #fff
        }

        .btn-danger {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: #fff
        }

        .btn:hover {
            transform: translateY(-2px)
        }


        /* Alert Messages */
        .alert {
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            border-left: 4px solid;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-color: #28a745;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-color: #dc3545;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border-color: #17a2b8;
        }

        /* Hamburger menu — tablet & mobile */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 6px;
            flex-shrink: 0;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .hamburger:hover {
            background: #f0f0f0;
        }

        .hamburger span {
            width: 24px;
            height: 3px;
            background: #333;
            border-radius: 2px;
            transition: all 0.3s;
            display: block;
        }

        /* Hamburger aktif — animasi X */
        .hamburger.open span:nth-child(1) {
            transform: translateY(8px) rotate(45deg);
        }

        .hamburger.open span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.open span:nth-child(3) {
            transform: translateY(-8px) rotate(-45deg);
        }

        @media (max-width: 1024px) {
            .hamburger {
                display: flex;
            }

            .nav-menu {
                position: absolute;
                top: 70px;
                left: 0;
                right: 0;
                width: 100%;
                background: white;
                flex-direction: column;
                padding: 8px 20px 16px;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
                display: none;
                gap: 0;
                border-top: 2px solid #667eea;
                align-items: stretch;
                max-height: calc(100vh - 70px);
                overflow-y: auto;
            }

            .nav-menu.active {
                display: flex;
            }

            .nav-group {
                flex-direction: column;
                padding: 10px 4px;
                border-bottom: 1px solid #f0f0f0;
                gap: 6px;
            }

            .nav-group:last-of-type {
                border-bottom: none;
            }

            .nav-group-items {
                flex-wrap: wrap;
                gap: 6px;
            }

            .nav-group-label {
                font-size: 10px;
            }

            .nav-menu a {
                font-size: 0.9rem;
                padding: 8px 12px;
                white-space: normal;
            }

            .nav-menu>li:last-child {
                padding-top: 10px;
            }

            .logout-btn {
                width: 100%;
                text-align: center;
                justify-content: center;
            }
        }

        /* Layar sangat kecil — sembunyikan teks brand */
        @media (max-width: 360px) {
            .brand-text {
                display: none;
            }
        }
    </style>
</head>

<body>
    @if (Auth::check())
        <!-- Authenticated Navbar -->
        <nav class="navbar">
            <div class="container">
                <div class="nav-logo">
                    @if (!empty($companyLogo))
                        <img src="{{ asset('storage/' . $companyLogo) }}" alt="{{ $companyName }}">
                    @endif
                    <span class="brand-text">{{ $companyName }}</span>
                </div>
                <ul class="nav-menu" id="navMenu">
                    @if (Auth::user()->isAdmin())
                        <!-- Admin grouped menu -->
                        <li class="nav-group">
                            <div class="nav-group-label">Main</div>
                            <div class="nav-group-items">
                                <a href="{{ route('admin') }}"
                                    class="{{ request()->routeIs('admin') ? 'active' : '' }}"><i
                                        class="fas fa-home"></i> Dashboard</a>
                            </div>
                        </li>

                        <li class="nav-group">
                            <div class="nav-group-label">Master Data</div>
                            <div class="nav-group-items">
                                <a href="{{ route('harga-barang-pokok.index') }}"
                                    class="{{ request()->routeIs('harga-barang-pokok.*') ? 'active' : '' }}"><i
                                        class="fas fa-dollar-sign"></i> Harga</a>
                                <a href="{{ route('satuan.index') }}"
                                    class="{{ request()->routeIs('satuan.*') ? 'active' : '' }}"><i
                                        class="fas fa-ruler"></i> Satuan</a>
                                <a href="{{ route('kategori.index') }}"
                                    class="{{ request()->routeIs('kategori.*') ? 'active' : '' }}"><i
                                        class="fas fa-tags"></i> Kategori</a>
                                <a href="{{ route('toko.index') }}"
                                    class="{{ request()->routeIs('toko.*') ? 'active' : '' }}"><i
                                        class="fas fa-store"></i> Toko</a>
                            </div>
                        </li>

                        <li class="nav-group">
                            <div class="nav-group-label">Transaksi</div>
                            <div class="nav-group-items">
                                <a href="{{ route('nota.index') }}"
                                    class="{{ request()->routeIs('nota.*') ? 'active' : '' }}"><i
                                        class="fas fa-file-invoice"></i> Nota</a>
                            </div>
                        </li>

                        <li class="nav-group">
                            <div class="nav-group-label">Pengguna</div>
                            <div class="nav-group-items">
                                <a href="{{ route('users.index') }}"
                                    class="{{ request()->routeIs('users.*') ? 'active' : '' }}"><i
                                        class="fas fa-users"></i> Pengguna</a>
                            </div>
                        </li>
                    @else
                        <!-- User grouped menu -->
                        <li class="nav-group">
                            <div class="nav-group-label">Akun</div>
                            <div class="nav-group-items">
                                <a href="{{ route('nota.index') }}"
                                    class="{{ request()->routeIs('nota.*') ? 'active' : '' }}"><i
                                        class="fas fa-file-invoice"></i> Nota Saya</a>
                                <a href="{{ route('barang-saya.index') }}"
                                    class="{{ request()->routeIs('barang-saya.*') ? 'active' : '' }}"><i
                                        class="fas fa-box-open"></i> Daftar Barang</a>
                                <a href="{{ route('profile.edit') }}"
                                    class="{{ request()->routeIs('profile.*') ? 'active' : '' }}"><i
                                        class="fas fa-user-cog"></i> Edit Profil</a>
                            </div>
                        </li>

                        <li class="nav-group">
                            <div class="nav-group-label">Jelajahi</div>
                            <div class="nav-group-items">
                                <a href="{{ route('home') }}">Beranda</a>
                                <a href="{{ route('contact') }}">Kontak</a>
                            </div>
                        </li>

                        <li style="display:flex; align-items:center; padding-left:8px; color:#666"><i
                                class="fas fa-user" style="margin-right:8px"></i> {{ Auth::user()->name }}</li>
                    @endif

                    <li>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i>
                                Logout</button>
                        </form>
                    </li>
                </ul>
                <div class="hamburger" onclick="toggleMenu()">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </nav>
    @else
        <!-- Public Navbar -->
        <nav class="navbar">
            <div class="container">
                <div class="nav-logo">
                    @if (!empty($companyLogo))
                        <img src="{{ asset('storage/' . $companyLogo) }}" alt="{{ $companyName }}">
                    @endif
                    <span class="brand-text">{{ $companyName }}</span>
                </div>
                <ul class="nav-menu" id="navMenu">
                    <li class="nav-group">
                        <div class="nav-group-label">Jelajahi</div>
                        <div class="nav-group-items">
                            <a href="{{ route('home') }}"
                                class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                            <a href="{{ route('harga-barang-pokok.index') }}"
                                class="{{ request()->routeIs('harga-barang-pokok.*') ? 'active' : '' }}">Daftar
                                Harga</a>
                            <a href="{{ route('contact') }}"
                                class="{{ request()->routeIs('contact') ? 'active' : '' }}">Kontak</a>
                        </div>
                    </li>

                    <li class="nav-group">
                        <div class="nav-group-label">Akun</div>
                        <div class="nav-group-items">
                            <a href="{{ route('login') }}"
                                class="{{ request()->routeIs('login') ? 'active' : '' }}">Login</a>
                            <a href="{{ route('register') }}"
                                class="{{ request()->routeIs('register') ? 'active' : '' }}">Daftar</a>
                        </div>
                    </li>
                </ul>
                <div class="hamburger" onclick="toggleMenu()">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </nav>
    @endif

    <!-- Flash Messages -->
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success">
                @if (session('status') == 'created')
                    ✓ Data berhasil ditambahkan!
                @elseif(session('status') == 'updated')
                    ✓ Data berhasil diupdate!
                @elseif(session('status') == 'deleted')
                    ✓ Data berhasil dihapus!
                @else
                    ✓ {{ session('status') }}
                @endif
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    @yield('content')

    <script>
        function toggleMenu() {
            const nav = document.getElementById('navMenu');
            const hamburger = document.querySelector('.hamburger');
            nav.classList.toggle('active');
            hamburger.classList.toggle('open');
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const nav = document.getElementById('navMenu');
            const hamburger = document.querySelector('.hamburger');

            if (!nav.contains(event.target) && !hamburger.contains(event.target)) {
                nav.classList.remove('active');
                hamburger.classList.remove('open');
            }
        });

        // Close menu on resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 1024) {
                document.getElementById('navMenu').classList.remove('active');
                document.querySelector('.hamburger').classList.remove('open');
            }
        });
    </script>

    <script>
        // global handler for three-dot action menus
        document.addEventListener('click', function(e) {
            const toggle = e.target.closest('.action-menu-button');
            if (toggle) {
                const menu = toggle.closest('.action-menu').querySelector('.action-menu-list');
                document.querySelectorAll('.action-menu-list.show').forEach(function(m) {
                    if (m !== menu) m.classList.remove('show');
                });
                menu.classList.toggle('show');
                return;
            }
            if (!e.target.closest('.action-menu')) {
                document.querySelectorAll('.action-menu-list.show').forEach(function(m) {
                    m.classList.remove('show');
                });
            }
        });
        // close on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') document.querySelectorAll('.action-menu-list.show').forEach(function(m) {
                m.classList.remove('show');
            });
        });
    </script>
    @stack('scripts')
</body>

</html>

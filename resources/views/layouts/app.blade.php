<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CV Mia Jaya Abadi')</title>
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
        }

        .brand-text {
            font-size: 1.2rem;
            font-weight: 700;
            color: #667eea;
            letter-spacing: 0.5px;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 8px;
            align-items: center;
        }

        .nav-menu a {
            color: #333;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s;
            font-size: 0.95rem;
        }

        .nav-menu a:hover,
        .nav-menu a.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .logout-btn {
            background: #e74c3c;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #c0392b;
        }

        /* Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px 24px;
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

        /* Hamburger menu for mobile */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 4px;
            cursor: pointer;
        }

        .hamburger span {
            width: 25px;
            height: 3px;
            background: #333;
            border-radius: 2px;
            transition: all 0.3s;
        }

        @media (max-width: 768px) {
            .hamburger {
                display: flex;
            }

            .nav-menu {
                position: absolute;
                top: 70px;
                left: 0;
                right: 0;
                background: white;
                flex-direction: column;
                padding: 20px;
                box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
                display: none;
            }

            .nav-menu.active {
                display: flex;
            }

            .nav-menu a {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    @if (Auth::check())
        <!-- Admin Navbar -->
        <nav class="navbar">
            <div class="container">
                <div class="nav-logo">
                    <span class="brand-text">CV Mia Jaya Abadi</span>
                </div>
                <ul class="nav-menu" id="navMenu">
                    <li><a href="{{ route('admin') }}" class="{{ request()->routeIs('admin') ? 'active' : '' }}"><i
                                class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="{{ route('harga-barang-pokok.index') }}"
                            class="{{ request()->routeIs('harga-barang-pokok.*') ? 'active' : '' }}"><i
                                class="fas fa-dollar-sign"></i> Harga Barang</a></li>
                    <li><a href="{{ route('nota.index') }}"
                            class="{{ request()->routeIs('nota.*') ? 'active' : '' }}"><i
                                class="fas fa-file-invoice"></i> Nota</a></li>
                    <li><a href="{{ route('satuan.index') }}"
                            class="{{ request()->routeIs('satuan.*') ? 'active' : '' }}"><i class="fas fa-ruler"></i>
                            Satuan</a></li>
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
                    <span class="brand-text">CV Mia Jaya Abadi</span>
                </div>
                <ul class="nav-menu" id="navMenu">
                    <li><a href="{{ route('home') }}"
                            class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
                    <li><a href="{{ route('contact') }}"
                            class="{{ request()->routeIs('contact') ? 'active' : '' }}">Kontak</a></li>
                    <li><a href="{{ route('login') }}"
                            class="{{ request()->routeIs('login') ? 'active' : '' }}">Login</a></li>
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
            document.getElementById('navMenu').classList.toggle('active');
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const nav = document.getElementById('navMenu');
            const hamburger = document.querySelector('.hamburger');

            if (!nav.contains(event.target) && !hamburger.contains(event.target)) {
                nav.classList.remove('active');
            }
        });
    </script>

    @stack('scripts')
</body>

</html>

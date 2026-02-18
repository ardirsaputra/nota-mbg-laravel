@php
    use App\Models\Setting;
    use App\Models\Gallery;

    $websiteName = Setting::get('website_name', 'CV Mia Jaya Abadi');
    $companyName = Setting::get('company_name', 'CV Mia Jaya Abadi');
    $companyLogo = Setting::get('company_logo');
    $heroTitle = Setting::get('hero_title', 'Solusi Terpercaya untuk Kebutuhan Anda');
    $heroDescription = Setting::get('hero_description', 'Kami menyediakan produk berkualitas dengan harga kompetitif');
    $heroImage = Setting::get('hero_image');
    $aboutTitle = Setting::get('about_title', 'Tentang Kami');
    $aboutDescription = Setting::get(
        'about_description',
        'CV Mia Jaya Abadi adalah perusahaan yang bergerak di bidang distribusi barang dengan komitmen memberikan pelayanan terbaik.',
    );
    $features = Setting::get('features', [
        ['icon' => 'fa-shield-alt', 'title' => 'Kualitas Terjamin', 'description' => 'Produk berkualitas tinggi'],
        ['icon' => 'fa-shipping-fast', 'title' => 'Pengiriman Cepat', 'description' => 'Pengiriman 24 jam'],
        ['icon' => 'fa-tags', 'title' => 'Harga Kompetitif', 'description' => 'Harga terbaik di kelasnya'],
        ['icon' => 'fa-headset', 'title' => 'Layanan 24/7', 'description' => 'Siap melayani kapan saja'],
    ]);
    $services = Setting::get('services', [
        [
            'title' => 'Distribusi Barang',
            'description' => 'Kami menyediakan layanan distribusi barang ke seluruh Indonesia',
            'image' => '',
        ],
        [
            'title' => 'Konsultasi Bisnis',
            'description' => 'Tim kami siap membantu mengembangkan bisnis Anda',
            'image' => '',
        ],
        ['title' => 'Layanan Custom', 'description' => 'Solusi khusus sesuai kebutuhan bisnis Anda', 'image' => ''],
    ]);
    $phone1 = Setting::get('phone_1', '');
    $phone2 = Setting::get('phone_2', '');
    $address = Setting::get('address', '');
    $operatingHours = Setting::get('operating_hours', [
        'Senin' => '08:00 - 17:00',
        'Selasa' => '08:00 - 17:00',
        'Rabu' => '08:00 - 17:00',
        'Kamis' => '08:00 - 17:00',
        'Jumat' => '08:00 - 17:00',
        'Sabtu' => '08:00 - 14:00',
        'Minggu' => 'Tutup',
    ]);

    // Defensive: return empty collection when galleries table is missing on the host
    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('galleries')) {
            $galleries = Gallery::ordered()->get();
        } else {
            $galleries = collect();
        }
    } catch (Throwable $e) {
        $galleries = collect();
    }

    // Dashboard/landing metrics (defensive: avoid DB queries when migrations/tables are missing)
    $notaCount = 0;
    $customerCount = 0;
    $recentProducts = collect();

    try {
        if (class_exists(\App\Models\Nota::class) && \Illuminate\Support\Facades\Schema::hasTable('nota')) {
            $notaCount = \App\Models\Nota::count();
        }

        if (class_exists(\App\Models\User::class) && \Illuminate\Support\Facades\Schema::hasTable('users')) {
            $customerCount = \App\Models\User::where('role', 'user')->count();
        }

        if (
            class_exists(\App\Models\HargaBarangPokok::class) &&
            \Illuminate\Support\Facades\Schema::hasTable('harga_barang_pokok')
        ) {
            $recentProducts = \App\Models\HargaBarangPokok::where('updated_at', '>=', now()->subDays(5))
                ->orderBy('updated_at', 'desc')
                ->take(10)
                ->get();
        }
    } catch (Throwable $e) {
        $notaCount = 0;
        $customerCount = 0;
        $recentProducts = collect();
    }
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $websiteName }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 24px;
            font-weight: bold;
        }

        .logo img {
            height: 50px;
            width: auto;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 30px;
            align-items: center;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            transition: opacity 0.3s;
        }

        .nav-links a:hover {
            opacity: 0.8;
        }

        .btn-login {
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 20px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .btn-login:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Hero Section */
        .hero {
            margin-top: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('{{ $heroImage ? \App\Models\Setting::storageUrl($heroImage) : '' }}');
            background-size: cover;
            background-position: center;
            opacity: 0.2;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
            animation: fadeInUp 1s;
        }

        .hero p {
            font-size: 20px;
            margin-bottom: 30px;
            animation: fadeInUp 1.2s;
        }

        .btn-primary {
            background: white;
            color: #667eea;
            padding: 15px 40px;
            border-radius: 30px;
            text-decoration: none;
            display: inline-block;
            font-weight: bold;
            transition: transform 0.3s, box-shadow 0.3s;
            animation: fadeInUp 1.4s;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        /* Stats Section */
        .stats {
            padding: 40px 20px;
            background: #fff;
        }

        .stats-grid {
            max-width: 1200px;
            margin: 0 auto 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .stat-card {
            background: linear-gradient(180deg, #fff, #fbfdff);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 6px 18px rgba(2, 6, 23, 0.06);
            text-align: center;
        }

        .stat-card h4 {
            font-size: 18px;
            margin-bottom: 8px;
            color: #475569;
        }

        .stat-card .value {
            font-size: 28px;
            font-weight: 800;
            color: #1f2937;
        }

        .stat-card .muted {
            color: #64748b;
            font-size: 13px;
        }

        /* Features Section */
        .features {
            padding: 80px 20px;
            background: #f8f9fa;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            font-size: 36px;
            margin-bottom: 50px;
            color: #2d3748;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            font-size: 48px;
            color: #667eea;
            margin-bottom: 20px;
        }

        .feature-card h3 {
            font-size: 22px;
            margin-bottom: 15px;
            color: #2d3748;
        }

        .feature-card p {
            color: #64748b;
        }

        /* About Section */
        .about {
            padding: 80px 20px;
            background: white;
        }

        .about-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }

        .about-content p {
            font-size: 18px;
            line-height: 1.8;
            color: #475569;
        }

        /* Services Section */
        .services {
            padding: 80px 20px;
            background: #f8f9fa;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .service-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s;
        }

        .service-card:hover {
            transform: translateY(-10px);
        }

        .service-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .service-content {
            padding: 25px;
        }

        .service-content h3 {
            font-size: 22px;
            margin-bottom: 15px;
            color: #2d3748;
        }

        .service-content p {
            color: #64748b;
            line-height: 1.6;
        }

        /* Gallery Section */
        .gallery {
            padding: 80px 20px;
            background: white;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            aspect-ratio: 1;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .gallery-item:hover img {
            transform: scale(1.1);
        }

        /* Contact Section */
        .contact {
            padding: 80px 20px;
            background: #f8f9fa;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .contact-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .contact-card h3 {
            font-size: 22px;
            margin-bottom: 20px;
            color: #2d3748;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .contact-info div {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #475569;
        }

        .contact-info i {
            color: #667eea;
            width: 20px;
        }

        .hours-table {
            width: 100%;
        }

        .hours-table tr {
            border-bottom: 1px solid #e2e8f0;
        }

        .hours-table td {
            padding: 10px 0;
            color: #475569;
        }

        .hours-table td:first-child {
            font-weight: 500;
        }

        .hours-table td:last-child {
            text-align: right;
        }

        /* Footer */
        .footer {
            background: #1e293b;
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .footer p {
            margin-bottom: 10px;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .hero h1 {
                font-size: 32px;
            }

            .hero p {
                font-size: 16px;
            }

            .section-title {
                font-size: 28px;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                @if ($companyLogo)
                    <img src="{{ \App\Models\Setting::storageUrl($companyLogo) ?? asset('favicon.ico') }}" alt="{{ $companyName }}">
                @else
                    <i class="fas fa-building"></i>
                @endif
                <span>{{ $companyName }}</span>
            </div>
            <ul class="nav-links">
                <li><a href="#home">Beranda</a></li>
                <li><a href="#about">Tentang</a></li>
                <li><a href="#services">Layanan</a></li>
                <li><a href="#gallery">Galeri</a></li>
                <li><a href="#contact">Kontak</a></li>
                @auth
                    <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                @else
                    <li><a href="{{ route('login') }}" class="btn-login">Login</a></li>
                @endauth
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
            <h1>{{ $heroTitle }}</h1>
            <p>{{ $heroDescription }}</p>
            @guest
                <a href="{{ route('register') }}" class="btn-primary">Mulai Sekarang</a>
            @endguest
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats" aria-label="Site statistics">
        <div class="stats-grid">
            <div class="stat-card">
                <h4>Jumlah Nota</h4>
                <div class="value">{{ number_format($notaCount ?? 0) }}</div>
                <div class="muted">Total nota di sistem</div>
            </div>
            <div class="stat-card">
                <h4>Jumlah Pelanggan</h4>
                <div class="value">{{ number_format($customerCount ?? 0) }}</div>
                <div class="muted">Pengguna terdaftar (role: user)</div>
            </div>
            <div class="stat-card">
                <h4>Produk Terupdate</h4>
                <div class="value">{{ ($recentProducts ?? collect())->count() }} item</div>
                <div class="muted">Produk terakhir diperbarui</div>
            </div>
        </div>

        @if (isset($recentProducts) && $recentProducts->count())
            <div class="container" style="max-width:1100px;margin-top:18px">
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px">
                    @foreach ($recentProducts as $bp)
                        <div
                            style="background:#fff;border-radius:8px;padding:12px;box-shadow:0 6px 18px rgba(2,6,23,0.06);">
                            <div style="font-weight:700;color:#1f2937">{{ $bp->uraian }}</div>
                            <div style="color:#64748b;font-size:13px">Rp {{ number_format($bp->harga) }}</div>
                            <div style="color:#94a3b8;font-size:12px;margin-top:6px">
                                {{ $bp->updated_at->diffForHumans() }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2 class="section-title">Keunggulan Kami</h2>
            <div class="features-grid">
                @foreach ($features as $feature)
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas {{ $feature['icon'] ?? 'fa-star' }}"></i>
                        </div>
                        <h3>{{ $feature['title'] ?? '' }}</h3>
                        <p>{{ $feature['description'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <div class="container">
            <h2 class="section-title">{{ $aboutTitle }}</h2>
            <div class="about-content">
                <p>{{ $aboutDescription }}</p>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="services">
        <div class="container">
            <h2 class="section-title">Layanan Kami</h2>
            <div class="services-grid">
                @foreach ($services as $service)
                    <div class="service-card">
                        @if (!empty($service['image']))
                            <img src="{{ \App\Models\Setting::storageUrl($service['image']) ?? '' }}" alt="{{ $service['title'] ?? '' }}"
                                class="service-image">
                        @else
                            <div class="service-image"></div>
                        @endif
                        <div class="service-content">
                            <h3>{{ $service['title'] ?? '' }}</h3>
                            <p>{{ $service['description'] ?? '' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    @if ($galleries->count() > 0)
        <section id="gallery" class="gallery">
            <div class="container">
                <h2 class="section-title">Galeri</h2>
                <div class="gallery-grid">
                    @foreach ($galleries as $gallery)
                        <div class="gallery-item">
                            <img src="{{ \App\Models\Setting::storageUrl($gallery->image_path) ?? '' }}" alt="{{ $gallery->title }}">
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Contact Section -->
    <section id="contact" class="contact">
        <div class="container">
            <h2 class="section-title">Hubungi Kami</h2>
            <div class="contact-grid">
                <div class="contact-card">
                    <h3><i class="fas fa-phone"></i> Telepon</h3>
                    <div class="contact-info">
                        @if ($phone1)
                            <div>
                                <i class="fas fa-phone"></i>
                                <span>{{ $phone1 }}</span>
                            </div>
                        @endif
                        @if ($phone2)
                            <div>
                                <i class="fas fa-phone"></i>
                                <span>{{ $phone2 }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($address)
                    <div class="contact-card">
                        <h3><i class="fas fa-map-marker-alt"></i> Alamat</h3>
                        <div class="contact-info">
                            <div>
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $address }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="contact-card">
                    <h3><i class="fas fa-clock"></i> Jam Operasional</h3>
                    <table class="hours-table">
                        @foreach ($operatingHours as $day => $hours)
                            <tr>
                                <td>{{ $day }}</td>
                                <td>{{ $hours }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} {{ $companyName }}. All rights reserved.</p>
            <p>{{ $websiteName }}</p>
        </div>
    </footer>
</body>

</html>

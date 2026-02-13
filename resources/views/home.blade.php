@extends('layouts.app')

@section('title', 'CV Mia Jaya Abadi - Beranda')

@push('styles')
    <style>
        /* Hero + sections styles (page-specific) */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 24px;
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.08)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.35;
        }

        .hero h1 {
            font-size: 2.4rem;
            margin-bottom: 14px;
            font-weight: 800;
        }

        .hero p {
            font-size: 1.05rem;
            margin-bottom: 24px;
            opacity: 0.95
        }

        .hero .hero-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap
        }

        .btn-primary {
            background: white;
            color: #667eea;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 700;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.12);
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            border: 2px solid rgba(255, 255, 255, 0.12)
        }

        .stats {
            background: white;
            padding: 48px 24px;
            margin-top: -40px;
            position: relative;
            z-index: 2
        }

        .stats .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 28px;
            border-radius: 12px;
            text-align: center
        }

        .stat-card h3 {
            font-size: 2rem;
            margin-bottom: 6px
        }

        section {
            padding: 60px 24px
        }

        h2 {
            font-size: 2rem;
            margin-bottom: 28px;
            text-align: center
        }

        .about,
        .services {
            background: #f8f9fa
        }

        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 36px;
            align-items: start;
            max-width: 1200px;
            margin: 0 auto
        }

        .feature-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            display: flex;
            gap: 16px;
            align-items: center;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06)
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 24px;
            max-width: 1200px;
            margin: 0 auto
        }

        .product-category {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06)
        }

        .product-list {
            list-style: none;
            padding: 18px
        }

        .product-list li {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9
        }

        .product-list li:last-child {
            border-bottom: none
        }

        .product-price .price-amount {
            font-weight: 700;
            color: #27ae60
        }

        .contact .info-item {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            display: flex;
            gap: 20px;
            align-items: flex-start
        }

        .footer {
            background: #2c3e50;
            color: white;
            padding: 48px 24px
        }

        .footer a {
            color: #bdc3c7
        }

        @media (max-width: 768px) {
            .about-content {
                grid-template-columns: 1fr
            }

            .hero h1 {
                font-size: 1.6rem
            }
        }
    </style>
@endpush

@section('content')
    <!-- Hero -->
    <section id="home" class="hero">
        <div class="hero-content">
            <h1>Pemasok Terpercaya untuk Kebutuhan Bisnis Anda</h1>
            <p>Menyediakan bahan pokok segar &amp; material bangunan berkualitas tinggi dengan harga terbaik.</p>
            <div class="hero-buttons">
                <a href="#products" class="btn btn-primary"><i class="fas fa-shopping-cart"></i>&nbsp; Lihat Produk</a>
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin') : route('nota.index') }}" class="btn btn-primary">
                        <i class="fas fa-tachometer-alt"></i>&nbsp; Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary"><i class="fas fa-user-plus"></i>&nbsp; Daftar
                        Akun</a>
                @endauth
                <a href="{{ route('contact') }}" class="btn btn-secondary"><i class="fas fa-phone"></i>&nbsp; Hubungi
                    Kami</a>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="stats">
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-box" style="font-size:28px;margin-bottom:8px"></i>
                <h3>{{ $total }}</h3>
                <p>Produk Tersedia</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-truck" style="font-size:28px;margin-bottom:8px"></i>
                <h3>24 Jam</h3>
                <p>Pengiriman Cepat</p>
            </div>
        </div>
    </section>

    <!-- About -->
    <section id="about" class="about">
        <div class="container">
            <h2>Tentang CV Mia Jaya Abadi</h2>
            <div class="about-content">
                <div class="about-text">
                    <p><strong>CV Mia Jaya Abadi</strong> adalah distributor bahan pokok dan material bangunan yang telah
                        dipercaya oleh banyak usaha. Kami menyediakan produk berkualitas dan layanan pengiriman cepat.</p>
                    <p>Prioritas kami adalah kepuasan pelanggan — kualitas produk, pengemasan yang baik, dan layanan tepat
                        waktu.</p>
                </div>
                <div class="about-features">
                    <div class="feature-card">
                        <i class="fas fa-check-circle" style="font-size:28px;color:#667eea"></i>
                        <div>
                            <h3>Kualitas Terjamin</h3>
                            <p>Produk pilihan dengan standar kualitas tertinggi.</p>
                        </div>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-truck-fast" style="font-size:28px;color:#667eea"></i>
                        <div>
                            <h3>Pengiriman Cepat</h3>
                            <p>Layanan pengiriman 24 jam untuk area yang tersedia.</p>
                        </div>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-hand-holding-dollar" style="font-size:28px;color:#667eea"></i>
                        <div>
                            <h3>Harga Kompetitif</h3>
                            <p>Penawaran harga yang kompetitif untuk pembelian besar.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products -->
    <section id="products" class="products">
        <div class="container">
            <h2>Produk Kami</h2>
            <div class="products-grid">
                <div class="product-category">
                    <div class="product-category-header sayur"
                        style="padding:20px;background:linear-gradient(135deg,#27ae60,#229954);color:white">
                        <i class="fas fa-carrot" style="font-size:34px"></i>
                        <h3 style="margin:8px 0;color:white">Bahan Pokok Segar</h3>
                        <p style="color:rgba(255,255,255,0.9)">Sayuran dan bahan pokok berkualitas premium</p>
                    </div>
                    <ul class="product-list">
                        @forelse($barang_pokok as $b)
                            <li>
                                <div class="product-name"><i class="fas fa-leaf"></i>&nbsp; {{ $b->uraian }}</div>
                                <div class="product-price">
                                    <span class="price-amount">Rp {{ number_format($b->harga_satuan, 0, ',', '.') }}</span>
                                    <small class="price-unit">per {{ $b->satuan }}</small>
                                </div>
                            </li>
                        @empty
                            <li>Tidak ada produk tersedia.</li>
                        @endforelse

                        <li
                            style="border-top:2px solid #667eea; margin-top:8px; padding-top:12px; font-weight:600; color:#667eea">
                            <i class="fas fa-info-circle"></i>&nbsp; Update terakhir:
                            {{ $last_update ? $last_update->updated_at->format('d M Y') : '-' }}
                        </li>
                        <li
                            style="border-top:2px solid #27ae60; margin-top:8px; padding-top:12px; display:flex; justify-content:space-between; align-items:center">
                            <div style="font-weight:700; color:#27ae60"><i class="fas fa-box"></i>&nbsp; Total Produk:
                                {{ $total }}</div>
                            <div><a href="{{ route('harga-barang-pokok.index') }}"
                                    style="color:#667eea; text-decoration:none; font-weight:700">Lihat Semua Produk
                                    &rarr;</a></div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Services -->
    <section id="services" class="services">
        <div class="container">
            <h2>Layanan Kami</h2>
            <div class="services-grid"
                style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;max-width:1200px;margin:0 auto">
                <div class="service-card"><i class="fas fa-shipping-fast" style="font-size:28px;color:#667eea"></i>
                    <h3>Pengiriman Ekspres</h3>
                    <p>Pengiriman cepat 24 jam untuk area tertentu.</p>
                </div>
                <div class="service-card"><i class="fas fa-calendar-check" style="font-size:28px;color:#667eea"></i>
                    <h3>Pesanan Rutin</h3>
                    <p>Langganan dan jadwal pengiriman reguler.</p>
                </div>
                <div class="service-card"><i class="fas fa-headset" style="font-size:28px;color:#667eea"></i>
                    <h3>Konsultasi Gratis</h3>
                    <p>Konsultasi kebutuhan produk dan volume pesanan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact -->
    <section id="contact" class="contact">
        <div class="container">
            <h2>Hubungi Kami</h2>
            <div class="contact-content">
                <div class="contact-info" style="display:grid;gap:18px;max-width:900px;margin:0 auto">
                    <div class="info-item"><i class="fas fa-map-marker-alt" style="font-size:28px;color:#667eea"></i>
                        <div>
                            <h3>Alamat</h3>
                            <p>Jalan Metro-Gotong Royong, Dusun III, Pujodadi, Trimurjo<br>Lampung Tengah, Lampung 34173</p>
                        </div>
                    </div>
                    <div class="info-item"><i class="fas fa-phone" style="font-size:28px;color:#667eea"></i>
                        <div>
                            <h3>Telepon & WhatsApp</h3>
                            <p>+62 812-3456-7890<br>+62 857-1234-5678</p>
                        </div>
                    </div>
                    <div class="info-item"><i class="fas fa-clock" style="font-size:28px;color:#667eea"></i>
                        <div>
                            <h3>Jam Operasional</h3>
                            <p>Senin - Sabtu: 07:00 - 17:00 WIB<br>Minggu & Hari Libur: 08:00 - 14:00 WIB</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content"
            style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:28px;max-width:1200px;margin:0 auto;">
            <div class="footer-section">
                <h3>CV Mia Jaya Abadi</h3>
                <p>Pemasok terpercaya untuk bahan pokok segar dan material bangunan berkualitas tinggi sejak 2024.</p>
            </div>
            <div class="footer-section">
                <h4>Navigasi</h4>
                <ul style="list-style:none;padding-left:0">
                    <li><a href="#home">Beranda</a></li>
                    <li><a href="#about">Tentang Kami</a></li>
                    <li><a href="#products">Produk</a></li>
                    <li><a href="#services">Layanan</a></li>
                    <li><a href="#contact">Kontak</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Produk & Harga</h4>
                <ul style="list-style:none;padding-left:0">
                    <li><a href="{{ route('harga-barang-pokok.index') }}">Daftar Harga</a></li>
                    <li><a href="#products">Material Bangunan</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Kontak Kami</h4>
                <p><i class="fas fa-map-marker-alt"></i> Jalan Metro-Gotong Royong, Pujodadi, Trimurjo</p>
                <p><i class="fas fa-phone"></i> +62 852-1903-4328</p>
                <p><i class="fas fa-envelope"></i> info@miajayaabadi.com</p>
            </div>
        </div>
        <div class="footer-bottom"
            style="text-align:center;padding-top:20px;color:#bdc3c7;max-width:1200px;margin:24px auto 0;border-top:1px solid rgba(255,255,255,0.05)">
            <p>&copy; 2026 CV Mia Jaya Abadi. All Rights Reserved.</p>
        </div>
    </footer>

    @push('scripts')
        <script>
            // Smooth scrolling for internal anchors
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href && href.startsWith('#')) {
                        e.preventDefault();
                        const target = document.querySelector(href);
                        if (target) target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Highlight active nav on scroll for sections
            window.addEventListener('scroll', () => {
                const sections = document.querySelectorAll('section[id]');
                const navLinks = document.querySelectorAll('.nav-menu a[href^="#"]');
                let current = '';
                sections.forEach(section => {
                    const top = section.offsetTop - 220;
                    if (pageYOffset >= top) current = section.getAttribute('id');
                });
                navLinks.forEach(link => {
                    link.classList.toggle('active', link.getAttribute('href') === '#' + current);
                });
            });
        </script>
    @endpush
@endsection

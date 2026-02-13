@extends('layouts.app')

@section('title', 'Dashboard Admin - CV Mia Jaya Abadi')

@push('styles')
    <style>
        .header {
            text-align: center;
            margin-bottom: 50px;
            padding: 40px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            color: white;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .header h1 i {
            font-size: 2.8rem;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        }

        .card-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 70px;
            height: 70px;
            border-radius: 10px;
        }

        .card-icon.green {
            background: rgba(46, 204, 113, 0.1);
            color: #2ecc71;
        }

        .card-icon.blue {
            background: rgba(52, 152, 219, 0.1);
            color: #3498db;
        }

        .card-icon.orange {
            background: rgba(243, 156, 18, 0.1);
            color: #f39c12;
        }

        .card-icon.lightblue {
            background: rgba(93, 173, 226, 0.12);
            color: #5dade2;
        }

        .card h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .card p {
            color: #7f8c8d;
            font-size: 0.95rem;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .btn i {
            margin-right: 8px;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="header">
            <h1>
                <i class="fas fa-tachometer-alt"></i>
                Dashboard Admin
            </h1>
            <p>Selamat datang di Panel Admin {{ $companyName ?? '' }}</p>
        </div>

        <!-- Top stats -->
        <div class="dashboard-grid">
            <div class="card">
                <div class="card-icon blue">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <h3>Jumlah Nota</h3>
                <p class="mb-3">Total semua nota yang tercatat dalam sistem.</p>
                <div style="font-size:28px;font-weight:800;color:#2c3e50">{{ number_format($notaCount ?? 0) }}</div>
            </div>

            <div class="card">
                <div class="card-icon green">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Jumlah Pelanggan</h3>
                <p class="mb-3">Total pengguna dengan role <code>user</code>.</p>
                <div style="font-size:28px;font-weight:800;color:#2c3e50">{{ number_format($customerCount ?? 0) }}</div>
            </div>

            <div class="card">
                <div class="card-icon orange">
                    <i class="fas fa-box-open"></i>
                </div>
                <h3>Produk Terupdate (5 hari)</h3>
                <p class="mb-3">Produk yang diperbarui dalam 5 hari terakhir.</p>
                <div style="font-size:20px;font-weight:700;color:#2c3e50">{{ ($recentProducts ?? collect())->count() }}
                    produk</div>
            </div>
        </div>

        <!-- Recent products list + existing action cards -->
        <div class="dashboard-grid" style="margin-top:20px;">
            <div class="card" style="grid-column: span 2;">
                <h3>Produk yang diperbarui dalam 5 hari terakhir</h3>
                <p class="text-muted">Daftar perubahan terbaru pada master harga barang pokok.</p>

                @if (isset($recentProducts) && $recentProducts->count())
                    <table style="width:100%;margin-top:16px;border-collapse:collapse">
                        <thead>
                            <tr style="text-align:left;border-bottom:1px solid #e6e9ee">
                                <th style="padding:10px">Uraian</th>
                                <th style="padding:10px;width:140px">Harga</th>
                                <th style="padding:10px;width:180px">Terakhir diubah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentProducts as $bp)
                                <tr>
                                    <td style="padding:12px 10px">{{ $bp->uraian }}</td>
                                    <td style="padding:12px 10px">Rp {{ number_format($bp->harga) }}</td>
                                    <td style="padding:12px 10px">{{ $bp->updated_at->format('d M Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted" style="margin-top:12px">Tidak ada produk yang diperbarui dalam 5 hari terakhir.
                    </p>
                @endif
            </div>

            <!-- keep existing quick-action cards -->
            <div class="card">
                <div class="card-icon green">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <h3>Harga Barang Pokok</h3>
                <p>Kelola daftar harga bahan pokok dan material bangunan.</p>
                <a href="{{ route('harga-barang-pokok.index') }}" class="btn">
                    <i class="fas fa-arrow-right"></i> Kelola Harga
                </a>
            </div>

            <div class="card">
                <div class="card-icon blue">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <h3>Nota Penjualan</h3>
                <p>Buat dan kelola nota penjualan untuk pelanggan.</p>
                <a href="{{ route('nota.index') }}" class="btn">
                    <i class="fas fa-arrow-right"></i> Kelola Nota
                </a>
            </div>

            <div class="card">
                <div class="card-icon orange">
                    <i class="fas fa-ruler"></i>
                </div>
                <h3>Satuan</h3>
                <p>Kelola master data satuan barang (Kg, Liter, Pcs, dll).</p>
                <a href="{{ route('satuan.index') }}" class="btn">
                    <i class="fas fa-arrow-right"></i> Kelola Satuan
                </a>
            </div>

            <div class="card">
                <div class="card-icon lightblue">
                    <i class="fas fa-tags"></i>
                </div>
                <h3>Kategori</h3>
                <p>Kelola kategori produk yang muncul saat menambah atau mengedit harga barang.</p>
                <a href="{{ route('kategori.index') }}" class="btn">
                    <i class="fas fa-arrow-right"></i> Kelola Kategori
                </a>
            </div>

            <div class="card">
                <div class="card-icon orange">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Pengguna Terdaftar</h3>
                <p>Kelola pengguna yang terdaftar dan clone nota mereka.</p>
                <a href="{{ route('users.index') }}" class="btn">
                    <i class="fas fa-arrow-right"></i> Kelola Pengguna
                </a>
            </div>

            <div class="card">
                <div class="card-icon purple">
                    <i class="fas fa-cog"></i>
                </div>
                <h3>Pengaturan Website</h3>
                <p>Atur tampilan landing page, galeri, kontak dan informasi perusahaan.</p>
                <a href="{{ route('settings.edit') }}" class="btn">
                    <i class="fas fa-arrow-right"></i> Buka Pengaturan
                </a>
            </div>

            <div class="card">
                <div class="card-icon lightblue">
                    <i class="fas fa-home"></i>
                </div>
                <h3>Beranda</h3>
                <p>Kembali ke halaman beranda website.</p>
                <a href="{{ route('home') }}" class="btn">
                    <i class="fas fa-arrow-right"></i> Lihat Beranda
                </a>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="settings-header">
            <h1><i class="fas fa-cog"></i> Pengaturan Website</h1>
            <p>Kelola tampilan landing page dan informasi perusahaan</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="settings-tabs">
            <button class="tab-btn active" data-tab="general">
                <i class="fas fa-building"></i> Umum
            </button>
            <button class="tab-btn" data-tab="hero">
                <i class="fas fa-image"></i> Hero Section
            </button>
            <button class="tab-btn" data-tab="about">
                <i class="fas fa-info-circle"></i> Tentang
            </button>
            <button class="tab-btn" data-tab="features">
                <i class="fas fa-star"></i> Fitur
            </button>
            <button class="tab-btn" data-tab="services">
                <i class="fas fa-briefcase"></i> Layanan
            </button>
            <button class="tab-btn" data-tab="contact">
                <i class="fas fa-phone"></i> Kontak
            </button>
            <button class="tab-btn" data-tab="gallery">
                <i class="fas fa-images"></i> Galeri
            </button>
        </div>

        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Tab: General -->
            <div class="tab-content active" id="general">
                <div class="settings-card">
                    <h2>Informasi Umum</h2>

                    <div class="form-group">
                        <label for="website_name">Nama Website</label>
                        <input type="text" id="website_name" name="website_name" class="form-control"
                            value="{{ old('website_name', App\Models\Setting::get('website_name', 'CV Mia Jaya Abadi')) }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="company_name">Nama Perusahaan</label>
                        <input type="text" id="company_name" name="company_name" class="form-control"
                            value="{{ old('company_name', App\Models\Setting::get('company_name', 'CV Mia Jaya Abadi')) }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="company_logo">Logo Perusahaan</label>
                        @php
                            $currentLogo = App\Models\Setting::get('company_logo');
                        @endphp
                        @if ($currentLogo)
                            <div class="current-image">
                                <img src="{{ asset('storage/' . $currentLogo) }}" alt="Logo">
                            </div>
                        @endif
                        <input type="file" id="company_logo" name="company_logo" class="form-control" accept="image/*">
                        <small>Format: JPG, PNG. Maksimal 2MB</small>
                    </div>

                    <div class="form-group">
                        <label for="director_name">Nama Direktur (untuk tanda tangan nota)</label>
                        <input type="text" id="director_name" name="director_name" class="form-control"
                            value="{{ old('director_name', App\Models\Setting::get('director_name', 'Mia Astuti')) }}">
                    </div>

                    <div class="form-group">
                        <label>Catatan Nota (maks. 5 baris)</label>
                        @php
                            $currentNotaNotes = App\Models\Setting::get('nota_notes', [
                                'Barang yang sudah diterima tidak bisa ditukar atau dikembalikan.',
                            ]);
                        @endphp

                        @for ($i = 0; $i < 5; $i++)
                            <input type="text" name="nota_notes[{{ $i }}]" class="form-control"
                                placeholder="Catatan {{ $i + 1 }}"
                                value="{{ old('nota_notes.' . $i, $currentNotaNotes[$i] ?? '') }}"
                                style="margin-bottom:8px">
                        @endfor
                        <small class="text-muted">Isi hanya catatan yang diperlukan, sisanya dikosongkan.</small>
                    </div>
                </div>
            </div>

            <!-- Tab: Hero -->
            <div class="tab-content" id="hero">
                <div class="settings-card">
                    <h2>Hero Section</h2>

                    <div class="form-group">
                        <label for="hero_title">Judul Hero</label>
                        <input type="text" id="hero_title" name="hero_title" class="form-control"
                            value="{{ old('hero_title', App\Models\Setting::get('hero_title', 'Solusi Terpercaya untuk Kebutuhan Anda')) }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="hero_description">Deskripsi Hero</label>
                        <textarea id="hero_description" name="hero_description" class="form-control" rows="4">{{ old('hero_description', App\Models\Setting::get('hero_description', 'Kami menyediakan produk berkualitas dengan harga kompetitif')) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="hero_image">Gambar Hero</label>
                        @php
                            $currentHero = App\Models\Setting::get('hero_image');
                        @endphp
                        @if ($currentHero)
                            <div class="current-image">
                                <img src="{{ asset('storage/' . $currentHero) }}" alt="Hero">
                            </div>
                        @endif
                        <input type="file" id="hero_image" name="hero_image" class="form-control" accept="image/*">
                        <small>Format: JPG, PNG. Maksimal 2MB</small>
                    </div>
                </div>
            </div>

            <!-- Tab: About -->
            <div class="tab-content" id="about">
                <div class="settings-card">
                    <h2>Tentang Perusahaan</h2>

                    <div class="form-group">
                        <label for="about_title">Judul Tentang</label>
                        <input type="text" id="about_title" name="about_title" class="form-control"
                            value="{{ old('about_title', App\Models\Setting::get('about_title', 'Tentang Kami')) }}">
                    </div>

                    <div class="form-group">
                        <label for="about_description">Deskripsi Tentang</label>
                        <textarea id="about_description" name="about_description" class="form-control" rows="6">{{ old('about_description', App\Models\Setting::get('about_description', 'CV Mia Jaya Abadi adalah perusahaan yang bergerak di bidang distribusi barang dengan komitmen memberikan pelayanan terbaik.')) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Tab: Features -->
            <div class="tab-content" id="features">
                <div class="settings-card">
                    <h2>Fitur & Keunggulan</h2>
                    <p class="text-muted">Tampilkan keunggulan perusahaan Anda</p>

                    @php
                        $features = old(
                            'features',
                            App\Models\Setting::get('features', [
                                [
                                    'icon' => 'fa-shield-alt',
                                    'title' => 'Kualitas Terjamin',
                                    'description' => 'Produk berkualitas tinggi',
                                ],
                                [
                                    'icon' => 'fa-shipping-fast',
                                    'title' => 'Pengiriman Cepat',
                                    'description' => 'Pengiriman 24 jam',
                                ],
                                [
                                    'icon' => 'fa-tags',
                                    'title' => 'Harga Kompetitif',
                                    'description' => 'Harga terbaik di kelasnya',
                                ],
                                [
                                    'icon' => 'fa-headset',
                                    'title' => 'Layanan 24/7',
                                    'description' => 'Siap melayani kapan saja',
                                ],
                            ]),
                        );
                    @endphp

                    @foreach ($features as $index => $feature)
                        <div class="feature-item">
                            <h4>Fitur {{ $index + 1 }}</h4>

                            <div class="form-group">
                                <label>Icon (Font Awesome)</label>
                                <input type="text" name="features[{{ $index }}][icon]" class="form-control"
                                    value="{{ $feature['icon'] ?? '' }}" placeholder="fa-shield-alt">
                                <small>Lihat icons di <a href="https://fontawesome.com/icons"
                                        target="_blank">fontawesome.com</a></small>
                            </div>

                            <div class="form-group">
                                <label>Judul</label>
                                <input type="text" name="features[{{ $index }}][title]" class="form-control"
                                    value="{{ $feature['title'] ?? '' }}">
                            </div>

                            <div class="form-group">
                                <label>Deskripsi</label>
                                <input type="text" name="features[{{ $index }}][description]"
                                    class="form-control" value="{{ $feature['description'] ?? '' }}">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Tab: Services -->
            <div class="tab-content" id="services">
                <div class="settings-card">
                    <h2>Layanan Kami</h2>
                    <p class="text-muted">Maksimal 3 layanan utama</p>

                    @php
                        $services = old(
                            'services',
                            App\Models\Setting::get('services', [
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
                                [
                                    'title' => 'Layanan Custom',
                                    'description' => 'Solusi khusus sesuai kebutuhan bisnis Anda',
                                    'image' => '',
                                ],
                            ]),
                        );
                    @endphp

                    @for ($i = 0; $i < 3; $i++)
                        <div class="service-item">
                            <h4>Layanan {{ $i + 1 }}</h4>

                            <div class="form-group">
                                <label>Judul Layanan</label>
                                <input type="text" name="services[{{ $i }}][title]" class="form-control"
                                    value="{{ $services[$i]['title'] ?? '' }}">
                            </div>

                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="services[{{ $i }}][description]" class="form-control" rows="3">{{ $services[$i]['description'] ?? '' }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Gambar Layanan</label>
                                @if (!empty($services[$i]['image']))
                                    <div class="current-image">
                                        <img src="{{ asset('storage/' . $services[$i]['image']) }}"
                                            alt="Service {{ $i + 1 }}">
                                    </div>
                                @endif
                                <input type="file" name="service_image_{{ $i }}"
                                    class="form-control service-image-upload" data-index="{{ $i }}"
                                    accept="image/*">
                                <input type="hidden" name="services[{{ $i }}][image]"
                                    value="{{ $services[$i]['image'] ?? '' }}"
                                    id="service_image_path_{{ $i }}">
                                <small>Format: JPG, PNG. Maksimal 2MB</small>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Tab: Contact -->
            <div class="tab-content" id="contact">
                <div class="settings-card">
                    <h2>Informasi Kontak</h2>

                    <div class="form-group">
                        <label for="phone_1">Telepon 1</label>
                        <input type="text" id="phone_1" name="phone_1" class="form-control"
                            value="{{ old('phone_1', App\Models\Setting::get('phone_1', '')) }}"
                            placeholder="0812-3456-7890">
                    </div>

                    <div class="form-group">
                        <label for="phone_2">Telepon 2</label>
                        <input type="text" id="phone_2" name="phone_2" class="form-control"
                            value="{{ old('phone_2', App\Models\Setting::get('phone_2', '')) }}"
                            placeholder="0813-9876-5432">
                    </div>

                    <div class="form-group">
                        <label for="address">Alamat</label>
                        <textarea id="address" name="address" class="form-control" rows="3">{{ old('address', App\Models\Setting::get('address', '')) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="address_2">Alamat (Baris 2) — opsional</label>
                        <textarea id="address_2" name="address_2" class="form-control" rows="2">{{ old('address_2', App\Models\Setting::get('address_2', '')) }}</textarea>
                        <small class="text-muted">Baris kedua alamat (mis. kecamatan / kabupaten) — tampil di nota dan
                            daftar barang cetak jika diisi.</small>
                    </div>

                    <h3>Jam Operasional</h3>
                    @php
                        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                        $operatingHours = old(
                            'operating_hours',
                            App\Models\Setting::get('operating_hours', [
                                'Senin' => '08:00 - 17:00',
                                'Selasa' => '08:00 - 17:00',
                                'Rabu' => '08:00 - 17:00',
                                'Kamis' => '08:00 - 17:00',
                                'Jumat' => '08:00 - 17:00',
                                'Sabtu' => '08:00 - 14:00',
                                'Minggu' => 'Tutup',
                            ]),
                        );
                    @endphp

                    @foreach ($days as $day)
                        <div class="form-group">
                            <label for="operating_{{ $day }}">{{ $day }}</label>
                            <input type="text" id="operating_{{ $day }}"
                                name="operating_hours[{{ $day }}]" class="form-control"
                                value="{{ $operatingHours[$day] ?? '' }}" placeholder="08:00 - 17:00 atau Tutup">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Tab: Gallery (dipindahkan ke bawah untuk menghindari nested form) -->

            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>

        <!-- Tab: Gallery -->
        <div class="tab-content" id="gallery">
            <div class="settings-card">
                <h2>Galeri Foto</h2>
                <p class="text-muted">Foto-foto yang ditampilkan di landing page</p>

                <div class="gallery-grid">
                    @foreach ($galleries as $gallery)
                        <div class="gallery-item">
                            <img src="{{ asset('storage/' . $gallery->image_path) }}" alt="{{ $gallery->title }}">
                            <div class="gallery-overlay">
                                <p>{{ $gallery->title }}</p>
                                <form action="{{ route('settings.gallery.delete', $gallery) }}" method="POST"
                                    onsubmit="return confirm('Hapus foto ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Form Upload Galeri (di luar form utama) -->
        <div class="settings-card" style="margin-top: 20px;">
            <h3>Tambah Foto ke Galeri</h3>
            <form action="{{ route('settings.gallery.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label for="gallery_title">Judul (opsional)</label>
                        <input type="text" id="gallery_title" name="title" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="gallery_image">Pilih Foto</label>
                        <input type="file" id="gallery_image" name="image" class="form-control" accept="image/*"
                            required>
                    </div>
                    <button type="submit" class="btn-secondary">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .settings-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }

        .settings-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .settings-header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
        }

        .settings-header p {
            margin: 0;
            opacity: 0.9;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .settings-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            overflow-x: auto;
            padding-bottom: 10px;
        }

        .tab-btn {
            padding: 12px 24px;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            color: #64748b;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .tab-btn:hover {
            border-color: #667eea;
            color: #667eea;
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
        }

        .tab-btn i {
            margin-right: 8px;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .settings-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .settings-card h2 {
            margin: 0 0 20px 0;
            font-size: 22px;
            color: #1e293b;
        }

        .settings-card h3 {
            margin: 30px 0 15px 0;
            font-size: 18px;
            color: #334155;
        }

        .settings-card h4 {
            margin: 20px 0 15px 0;
            font-size: 16px;
            color: #475569;
            padding-bottom: 10px;
            border-bottom: 2px solid #f1f5f9;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #334155;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-control small {
            display: block;
            margin-top: 5px;
            color: #64748b;
            font-size: 12px;
        }

        .current-image {
            margin-bottom: 15px;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid #e2e8f0;
            max-width: 300px;
        }

        .current-image img {
            width: 100%;
            height: auto;
            display: block;
        }

        .text-muted {
            color: #64748b;
            font-size: 14px;
        }

        .feature-item,
        .service-item {
            padding: 20px;
            background: #f8fafc;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .gallery-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            aspect-ratio: 1;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .gallery-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }

        .gallery-overlay p {
            color: white;
            margin: 0 0 10px 0;
            font-weight: 500;
        }

        .btn-delete {
            background: #ef4444;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-delete:hover {
            background: #dc2626;
        }

        .form-row {
            display: flex;
            gap: 15px;
            align-items: flex-end;
        }

        .form-row .form-group {
            flex: 1;
        }

        .form-actions {
            margin-top: 30px;
            text-align: right;
        }

        .btn-primary,
        .btn-secondary {
            padding: 14px 32px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #64748b;
            color: white;
        }

        .btn-secondary:hover {
            background: #475569;
        }

        .btn-primary i,
        .btn-secondary i {
            margin-right: 8px;
        }

        @media (max-width: 768px) {
            .settings-tabs {
                flex-wrap: wrap;
            }

            .tab-btn {
                flex: 1;
                min-width: 120px;
            }

            .form-row {
                flex-direction: column;
                align-items: stretch;
            }

            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab switching
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetTab = this.dataset.tab;

                    // Remove active class from all
                    tabBtns.forEach(b => b.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));

                    // Add active class to clicked
                    this.classList.add('active');
                    document.getElementById(targetTab).classList.add('active');
                });
            });

            // Service image upload
            const serviceImageInputs = document.querySelectorAll('.service-image-upload');

            serviceImageInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const index = this.dataset.index;
                    const file = this.files[0];

                    if (file) {
                        const formData = new FormData();
                        formData.append('image', file);
                        formData.append('service_index', index);
                        formData.append('_token', '{{ csrf_token() }}');

                        fetch('{{ route('settings.service.upload') }}', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById('service_image_path_' + index)
                                        .value = data.path;
                                    alert('Gambar berhasil diupload!');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Gagal mengupload gambar');
                            });
                    }
                });
            });
        });
    </script>
@endsection

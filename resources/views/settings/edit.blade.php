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
                                <img src="{{ \App\Models\Setting::storageUrl($currentLogo) ?? '' }}" alt="Logo">
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
                        <label for="nota_number_start">Mulai Nomor Nota (ID awal)</label>
                        <input type="number" id="nota_number_start" name="nota_number_start" class="form-control"
                            value="{{ old('nota_number_start', App\Models\Setting::get('nota_number_start', 1)) }}"
                            min="1" step="1" style="max-width:180px">
                        <small class="text-muted">Nomor urut nota akan dimulai dari angka ini jika lebih besar dari nomor
                            terakhir di database. Berguna saat migrasi dari sistem manual.</small>
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
                                <img src="{{ \App\Models\Setting::storageUrl($currentHero) ?? '' }}" alt="Hero">
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
                                        <img src="{{ \App\Models\Setting::storageUrl($services[$i]['image']) ?? '' }}"
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

                    <div class="form-row-2">
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

                    <div class="hours-grid">
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
            </div>

            <!-- Tab: Gallery (dipindahkan ke bawah untuk menghindari nested form) -->

            <div class="form-actions">
                <button type="submit" class="btn-save">
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
                            <img src="{{ \App\Models\Setting::storageUrl($gallery->image_path) ?? '' }}"
                                alt="{{ $gallery->title }}">
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
        <div class="gallery-upload-card">
            <h3><i class="fas fa-upload" style="margin-right:8px;color:#667eea"></i>Tambah Foto ke Galeri</h3>
            <form action="{{ route('settings.gallery.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="gallery-upload-row">
                    <div class="form-group">
                        <label for="gallery_title">Judul (opsional)</label>
                        <input type="text" id="gallery_title" name="title" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="gallery_image">Pilih Foto</label>
                        <input type="file" id="gallery_image" name="image" class="form-control" accept="image/*"
                            required>
                    </div>
                    <button type="submit" class="btn-upload">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* ===== HEADER ===== */
        .settings-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 22px 28px;
            border-radius: 12px;
            margin-bottom: 24px;
        }

        .settings-header h1 {
            margin: 0 0 4px 0;
            font-size: 21px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .settings-header p {
            margin: 0;
            opacity: 0.85;
            font-size: 13px;
        }

        /* ===== ALERT ===== */
        .alert-success {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
            padding: 13px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        /* ===== TABS ===== */
        .settings-tabs {
            display: flex;
            gap: 0;
            margin-bottom: 24px;
            overflow-x: auto;
            border-bottom: 2px solid #e2e8f0;
            -webkit-overflow-scrolling: touch;
        }

        .settings-tabs::-webkit-scrollbar {
            height: 4px;
        }

        .settings-tabs::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 2px;
        }

        .tab-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 11px 18px;
            background: transparent;
            border: none;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            transition: color 0.2s, border-color 0.2s, background 0.2s;
            white-space: nowrap;
        }

        .tab-btn:hover {
            color: #667eea;
            border-bottom-color: #a5b4fc;
            background: #f8f9ff;
        }

        .tab-btn.active {
            color: #667eea;
            border-bottom-color: #667eea;
            background: transparent;
        }

        .tab-btn i {
            font-size: 12px;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* ===== CARDS ===== */
        .settings-card {
            background: white;
            border-radius: 12px;
            padding: 26px 28px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 0 0 1px rgba(0, 0, 0, 0.04);
            margin-bottom: 20px;
        }

        .settings-card h2 {
            margin: 0 0 22px 0;
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
            padding-left: 12px;
            border-left: 4px solid #667eea;
        }

        .settings-card h3 {
            margin: 26px 0 14px 0;
            font-size: 12px;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f1f5f9;
        }

        .settings-card h4 {
            margin: 18px 0 12px 0;
            font-size: 12px;
            font-weight: 700;
            color: #667eea;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            border-bottom: none;
        }

        /* ===== FORM ===== */
        .form-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            font-size: 12px;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.35px;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            color: #1e293b;
            background: #fafafa;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.12);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        small {
            display: block;
            margin-top: 5px;
            color: #94a3b8;
            font-size: 11.5px;
            line-height: 1.4;
        }

        .text-muted {
            color: #94a3b8;
            font-size: 13px;
            margin: -10px 0 18px 0;
        }

        /* ===== IMAGE PREVIEW ===== */
        .current-image {
            margin-bottom: 10px;
            border-radius: 8px;
            overflow: hidden;
            border: 1.5px solid #e2e8f0;
            max-width: 200px;
            background: #f8fafc;
        }

        .current-image img {
            width: 100%;
            height: 100px;
            object-fit: contain;
            display: block;
            padding: 8px;
            box-sizing: border-box;
        }

        /* ===== FEATURE & SERVICE ITEMS ===== */
        .feature-item,
        .service-item {
            padding: 18px 20px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 3px solid #667eea;
            border-radius: 0 8px 8px 0;
            margin-bottom: 14px;
        }

        /* ===== OPERATING HOURS ===== */
        .hours-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(195px, 1fr));
            gap: 12px;
        }

        .hours-grid .form-group {
            margin-bottom: 0;
        }

        /* ===== GALLERY ===== */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 14px;
            margin-top: 14px;
        }

        .gallery-item {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            aspect-ratio: 1;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .gallery-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.62);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            opacity: 0;
            transition: opacity 0.25s;
        }

        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }

        .gallery-overlay p {
            color: white;
            margin: 0;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            padding: 0 8px;
        }

        .btn-delete {
            background: #ef4444;
            color: white;
            border: none;
            padding: 7px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: background 0.2s;
        }

        .btn-delete:hover {
            background: #dc2626;
        }

        /* ===== GALLERY UPLOAD ===== */
        .gallery-upload-card {
            background: white;
            border-radius: 12px;
            padding: 22px 28px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 0 0 1px rgba(0, 0, 0, 0.04);
            margin-top: 16px;
        }

        .gallery-upload-card h3 {
            margin: 0 0 16px 0;
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding-left: 10px;
            border-left: 3px solid #667eea;
        }

        .gallery-upload-row {
            display: flex;
            gap: 14px;
            align-items: flex-end;
        }

        .gallery-upload-row .form-group {
            flex: 1;
            margin-bottom: 0;
        }

        /* ===== FORM ACTIONS (STICKY) ===== */
        .form-actions {
            position: sticky;
            bottom: 0;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(8px);
            padding: 14px 0;
            border-top: 1px solid #e2e8f0;
            text-align: right;
            z-index: 100;
            margin-top: 8px;
        }

        /* ===== BUTTONS ===== */
        .btn-save {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s;
            box-shadow: 0 4px 14px rgba(102, 126, 234, 0.35);
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.45);
        }

        .btn-upload {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #475569;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            white-space: nowrap;
        }

        .btn-upload:hover {
            background: #334155;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .form-row-2 {
                grid-template-columns: 1fr;
            }

            .hours-grid {
                grid-template-columns: 1fr 1fr;
            }

            .gallery-upload-row {
                flex-direction: column;
                align-items: stretch;
            }

            .settings-card {
                padding: 18px;
            }

            .tab-btn {
                padding: 10px 14px;
                font-size: 12px;
            }
        }

        @media (max-width: 480px) {
            .hours-grid {
                grid-template-columns: 1fr;
            }

            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            }

            .settings-header h1 {
                font-size: 18px;
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

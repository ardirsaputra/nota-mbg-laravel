@extends('layouts.app')

@section('title', 'Edit Profil')

@push('styles')
    <style>
        /* ── Header bar ── */
        .settings-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 24px 28px;
            border-radius: 14px;
            margin-bottom: 22px;
        }

        .settings-header h1 {
            margin: 0 0 4px;
            font-family: 'Poppins', sans-serif;
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .settings-header p {
            margin: 0;
            opacity: .85;
            font-size: .9rem;
        }

        /* ── Alert ── */
        .alert {
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 16px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: .92rem;
        }

        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border-left: 4px solid #22c55e;
        }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .alert ul {
            margin: 0;
            padding-left: 16px;
        }

        /* ── Underline tabs ── */
        .settings-tabs {
            display: flex;
            gap: 0;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 22px;
            overflow-x: auto;
        }

        .tab-btn {
            padding: 10px 24px;
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            cursor: pointer;
            font-size: .95rem;
            font-weight: 600;
            color: #64748b;
            transition: all .2s;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .tab-btn.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* ── Card ── */
        .settings-card {
            background: #fff;
            border-radius: 12px;
            padding: 24px 28px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
            margin-bottom: 20px;
        }

        .settings-card .section-title {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            border-left: 4px solid #667eea;
            padding-left: 12px;
            margin: 0 0 20px;
        }

        .text-muted {
            color: #64748b;
            font-size: .88rem;
            margin: -12px 0 18px 16px;
        }

        /* ── Form grid ── */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        .form-group label {
            font-size: .8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #475569;
        }

        .form-group input,
        .form-group textarea {
            padding: 10px 13px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: .93rem;
            transition: border .2s, box-shadow .2s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, .15);
        }

        .form-group .hint {
            font-size: .77rem;
            color: #94a3b8;
        }

        /* ── Sticky save bar ── */
        .form-actions {
            position: sticky;
            bottom: 0;
            background: #fff;
            border-top: 1px solid #e2e8f0;
            padding: 14px 0;
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            z-index: 100;
            margin-top: 10px;
        }

        .btn {
            padding: 10px 22px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: .92rem;
            text-decoration: none;
            transition: all .2s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
        }

        .btn-primary:hover {
            opacity: .9;
            color: #fff;
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            color: #334155;
        }

        /* ── Toko status pill ── */
        .toko-status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: .82rem;
            font-weight: 600;
        }

        .toko-status.new {
            background: #fef3c7;
            color: #92400e;
        }

        .toko-status.edit {
            background: #dcfce7;
            color: #166534;
        }

        @media (max-width: 640px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .settings-header {
                border-radius: 10px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container">

        <!-- Header -->
        <div class="settings-header">
            <h1><i class="fas fa-user-cog"></i> Edit Profil</h1>
            <p>Perbarui informasi akun dan toko Anda</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tabs -->
        <div class="settings-tabs">
            <button class="tab-btn active" data-tab="account"><i class="fas fa-user"></i> Akun</button>
            <button class="tab-btn" data-tab="toko"><i class="fas fa-store"></i> Toko</button>
        </div>

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            {{-- ── TAB: Akun ── --}}
            <div class="tab-content active" id="account">
                <div class="settings-card">
                    <div class="section-title"><i class="fas fa-user" style="color:#667eea; margin-right:6px;"></i>
                        Informasi Akun</div>

                    <div class="form-grid">
                        <div class="form-group full">
                            <label for="name">Nama Lengkap *</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                required placeholder="Nama tampilan Anda">
                        </div>

                        <div class="form-group full">
                            <label for="email">Alamat Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                required placeholder="email@contoh.com">
                        </div>
                    </div>
                </div>

                <div class="settings-card">
                    <div class="section-title"><i class="fas fa-lock" style="color:#667eea; margin-right:6px;"></i> Ganti
                        Password</div>
                    <p class="text-muted">Kosongkan kedua kolom di bawah jika tidak ingin mengganti password.</p>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="password">Password Baru</label>
                            <input type="password" id="password" name="password" placeholder="Minimal 6 karakter">
                            <span class="hint">Biarkan kosong agar password tidak berubah</span>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── TAB: Toko ── --}}
            <div class="tab-content" id="toko">
                <div class="settings-card">
                    <div class="section-title"><i class="fas fa-store" style="color:#667eea; margin-right:6px;"></i>
                        Informasi Toko</div>
                    <p class="text-muted">Data toko akan tampil di nota Anda. Mengisi kolom di bawah akan membuat atau
                        memperbarui toko terkait akun Anda.</p>

                    @if ($toko)
                        <div style="margin-bottom:16px;">
                            <span class="toko-status edit">
                                <i class="fas fa-check-circle"></i> Toko aktif: {{ $toko->nama_toko }}
                            </span>
                        </div>
                    @else
                        <div style="margin-bottom:16px;">
                            <span class="toko-status new">
                                <i class="fas fa-info-circle"></i> Belum memiliki toko — mengisi data di bawah akan membuat
                                toko baru
                            </span>
                        </div>
                    @endif

                    <div class="form-grid">
                        <div class="form-group full">
                            <label for="nama_toko">Nama Toko</label>
                            <input type="text" id="nama_toko" name="nama_toko"
                                value="{{ old('nama_toko', optional($toko)->nama_toko) }}"
                                placeholder="Contoh: Toko Maju Jaya">
                        </div>

                        <div class="form-group full">
                            <label for="alamat_toko">Alamat Toko</label>
                            <textarea id="alamat_toko" name="alamat_toko" rows="3" placeholder="Jl. Contoh No. 1, Kota...">{{ old('alamat_toko', optional($toko)->alamat) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sticky save bar -->
            <div class="form-actions">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Batal</a>
                <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Simpan Perubahan</button>
            </div>
        </form>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    tabBtns.forEach(b => b.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    document.getElementById(this.dataset.tab).classList.add('active');
                });
            });
        });
    </script>
@endpush

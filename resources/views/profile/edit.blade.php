@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
    <div class="container">
        <div class="settings-header" style="margin-bottom:18px;">
            <h1><i class="fas fa-user-cog"></i> Edit Profil</h1>
            <p>Perbarui informasi akun dan toko Anda </p>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tabs (Akun / Toko) -->
        <div class="settings-tabs" style="margin-bottom:18px;">
            <button class="tab-btn active" data-tab="account"><i class="fas fa-user"></i> Akun</button>
            <button class="tab-btn" data-tab="toko"><i class="fas fa-store"></i> Toko</button>
        </div>

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="tab-content active" id="account">
                <div class="settings-card">
                    <h2>Informasi Akun</h2>

                    <table class="profile-table">
                        <tr>
                            <td class="label">Nama</td>
                            <td class="input"><input type="text" name="name" value="{{ old('name', $user->name) }}"
                                    required class="form-control"></td>
                        </tr>

                        <tr>
                            <td class="label">Email</td>
                            <td class="input"><input type="email" name="email" value="{{ old('email', $user->email) }}"
                                    required class="form-control"></td>
                        </tr>

                        <tr>
                            <td class="label">Password <div class="text-muted" style="font-weight:400;">(kosongkan bila
                                    tidak ingin mengganti)</div>
                            </td>
                            <td class="input"><input type="password" name="password" class="form-control"></td>
                        </tr>

                        <tr>
                            <td class="label">Konfirmasi Password</td>
                            <td class="input"><input type="password" name="password_confirmation" class="form-control">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="tab-content" id="toko">
                <div class="settings-card">
                    <h2>Informasi Toko</h2>
                    <p class="text-muted">Data toko akan dibuat atau diperbarui untuk toko yang dimiliki pengguna.</p>

                    <table class="profile-table">
                        <tr>
                            <td class="label">Nama Toko</td>
                            <td class="input"><input type="text" name="nama_toko"
                                    value="{{ old('nama_toko', optional($toko)->nama_toko) }}" class="form-control"></td>
                        </tr>

                        <tr>
                            <td class="label">Alamat Toko</td>
                            <td class="input"><input type="text" name="alamat_toko"
                                    value="{{ old('alamat_toko', optional($toko)->alamat) }}" class="form-control"></td>
                        </tr>

                        @if (!$toko)
                            <tr>
                                <td class="label">Status Toko</td>
                                <td class="input"><span class="text-muted">Belum memiliki toko — mengisi data di atas akan
                                        membuat toko baru terkait akun Anda.</span></td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Batal</a>
                <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>

    <style>
        /* Minimal styles copied from settings view so profile page matches */
        .settings-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 22px;
            border-radius: 12px;
        }

        .settings-card {
            background: white;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            margin-bottom: 18px;
        }

        .settings-card h2 {
            margin: 0 0 18px 0;
            font-size: 18px;
            color: #1e293b;
        }

        .form-actions {
            margin-top: 18px;
            text-align: right;
        }

        .text-muted {
            color: #64748b;
            font-size: 13px;
        }

        /* Tab styles copied from settings for consistency */
        .settings-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 12px;
            overflow-x: auto;
            padding-bottom: 6px;
        }

        .tab-btn {
            padding: 10px 20px;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            color: #64748b;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Profile form table */
        .profile-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .profile-table td.label {
            width: 220px;
            padding: 14px 18px;
            vertical-align: top;
            color: #475569;
            font-weight: 700;
            font-size: 14px;
        }

        .profile-table td.input {
            padding: 12px 18px;
        }

        .profile-table tr+tr td.input {
            border-top: 1px solid #f1f5f9;
        }

        @media (max-width: 768px) {
            .profile-table td.label {
                display: block;
                width: 100%;
                padding: 10px 0 6px 0;
                font-weight: 700;
            }

            .profile-table td.input {
                display: block;
                width: 100%;
                padding: 6px 0 16px 0;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetTab = this.dataset.tab;

                    tabBtns.forEach(b => b.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));

                    this.classList.add('active');
                    document.getElementById(targetTab).classList.add('active');
                });
            });
        });
    </script>
@endsection

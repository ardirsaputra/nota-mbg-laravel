@extends('layouts.app')

@section('title', 'Login - CV Mia Jaya Abadi')

@push('styles')
    <style>
        /* Make login visually consistent with home: hero + centered card */
        .login-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 24px 40px;
            text-align: center;
        }

        .login-card {
            max-width: 880px;
            margin: -40px auto 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
            display: grid;
            grid-template-columns: 1fr 420px;
            overflow: hidden;
        }

        .login-intro {
            padding: 36px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            align-items: flex-start
        }

        .login-intro h2 {
            margin: 0;
            color: #2c3e50
        }

        .login-intro p {
            margin: 0;
            color: #6b7280
        }

        .login-form {
            padding: 28px;
            background: #ffffff;
        }

        .form-group {
            margin-bottom: 14px
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #2c3e50
        }

        .form-group input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e6eef6;
            border-radius: 8px
        }

        .btn-primary {
            display: inline-flex;
            gap: 8px;
            align-items: center;
            padding: 10px 16px;
            border-radius: 8px;
            background: #667eea;
            color: white;
            border: none;
            font-weight: 700
        }

        .helper {
            font-size: 0.92rem;
            color: #6b7280
        }

        .small-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 600
        }

        @media (max-width: 900px) {
            .login-card {
                grid-template-columns: 1fr;
                margin: -24px 16px 24px
            }

            .login-hero {
                padding: 48px 16px
            }
        }
    </style>
@endpush

@section('content')
    <section class="login-hero">
        <div class="container">
            <h1>Selamat Datang di Website <p>{{ $websiteName }}</p>
            </h1>
            <p style="max-width:780px; margin:10px auto 0; opacity:0.95">Masuk untuk mengelola nota pembelian.</p>
        </div>
    </section>

    <div class="login-card container">
        <div class="login-intro">
            <h2>Selamat datang kembali!</h2>
            <p class="helper">Masukkan kredensial Anda untuk mengakses dashboard admin.</p>

            <div style="margin-top:18px; display:flex; gap:12px; flex-wrap:wrap">

                <div
                    style="background:#f8fafc;padding:10px 12px;border-radius:8px;font-weight:700;color:#667eea;display:flex;align-items:center;gap:10px">
                    <i class="fas fa-file-invoice"></i> Buat & Cetak Nota
                </div>
            </div>
        </div>

        <div class="login-form">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div style="color:#e02424;margin-top:6px">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                    @error('password')
                        <div style="color:#e02424;margin-top:6px">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px">
                    <div class="helper"><label style="font-weight:600"><input type="checkbox" name="remember"> &nbsp; Ingat
                            saya</label></div>
                    <div><a href="{{ route('register') }}" class="small-link">Daftar Akun</a></div>
                </div>

                <button type="submit" class="btn-primary" style="width:100%"><i class="fas fa-sign-in-alt"></i>&nbsp; Masuk
                    ke Dashboard</button>

                <div style="text-align: center; margin-top: 16px;">
                    <a href="{{ route('home') }}" class="helper" style="text-decoration: none;">← Kembali ke Beranda</a>
                </div>
            </form>
        </div>
    </div>
@endsection

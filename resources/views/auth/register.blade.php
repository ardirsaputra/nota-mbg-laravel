@extends('layouts.app')

@section('title', 'Daftar Akun')

@section('content')
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <h2>Daftar Akun Baru</h2>
                <p>Buat akun untuk mulai membuat nota</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="register-form">
                @csrf

                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <small>Minimal 6 karakter</small>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                </div>

                <!-- Shop info for user (optional) -->
                <div class="form-group">
                    <label for="nama_toko">Nama Toko (opsional)</label>
                    <input type="text" id="nama_toko" name="nama_toko" value="{{ old('nama_toko') }}"
                        placeholder="Nama toko / usaha Anda">
                </div>

                <div class="form-group">
                    <label for="alamat_toko">Alamat Toko (opsional)</label>
                    <input type="text" id="alamat_toko" name="alamat_toko" value="{{ old('alamat_toko') }}"
                        placeholder="Contoh: Jl. Merdeka No. 12">
                </div>

                <button type="submit" class="btn-primary">Daftar</button>
            </form>

            <div class="register-footer">
                <p>Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }

        .register-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 450px;
            padding: 40px;
        }

        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .register-header h2 {
            margin: 0 0 8px 0;
            color: #2d3748;
            font-size: 28px;
        }

        .register-header p {
            margin: 0;
            color: #718096;
            font-size: 14px;
        }

        .alert-error {
            background: #fee;
            border: 1px solid #fcc;
            color: #c33;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .alert-error ul {
            margin: 0;
            padding-left: 20px;
        }

        .register-form {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: #4a5568;
            font-weight: 500;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group small {
            display: block;
            margin-top: 4px;
            color: #718096;
            font-size: 12px;
        }

        .btn-primary {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .register-footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .register-footer p {
            margin: 0;
            color: #718096;
            font-size: 14px;
        }

        .register-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        .register-footer a:hover {
            text-decoration: underline;
        }
    </style>
@endpush

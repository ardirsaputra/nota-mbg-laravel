@extends('layouts.app')

@section('title', 'Tambah Toko')

@push('styles')
    <style>
        .form-card {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(12, 33, 55, 0.06);
        }

        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        .form-row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .form-row.full {
            flex-direction: column;
        }

        .form-group {
            flex: 1;
            min-width: 160px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #374151;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #e6eef6;
            background: #fbfdff;
            min-height: 40px;
        }

        .form-group textarea {
            min-height: 100px;
        }

        .form-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            margin-top: 16px;
        }

        .btn {
            padding: 8px 14px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            gap: 8px;
            align-items: center;
            border: none;
        }

        .btn-primary {
            background: #3498db;
            color: #fff;
        }

        .btn-secondary {
            background: #95a5a6;
            color: #fff;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-store"></i> Tambah Toko Baru</h1>
            <a href="{{ route('toko.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Terdapat kesalahan:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-card">
            <form action="{{ route('toko.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="nama_toko">Nama Toko <span style="color: red">*</span></label>
                    <input type="text" id="nama_toko" name="nama_toko" class="form-control"
                        placeholder="Masukkan nama toko" value="{{ old('nama_toko') }}" required>
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea id="alamat" name="alamat" class="form-control" rows="4" placeholder="Masukkan alamat toko">{{ old('alamat') }}</textarea>
                </div>

                <div class="form-actions">
                    <a href="{{ route('toko.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

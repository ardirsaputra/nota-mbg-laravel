@extends('layouts.app')

@section('title', 'Tambah Satuan — CV Mia Jaya Abadi')

@push('styles')
    <style>
        /* Material form for tambah satuan — consistent with app theme */
        .page {
            max-width: 920px;
            margin: 28px auto;
            padding: 0 16px
        }

        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 12px 30px rgba(2, 6, 23, 0.06);
            padding: 22px
        }

        .md-field {
            position: relative;
            margin-bottom: 16px
        }

        .md-field input,
        .md-field textarea {
            width: 100%;
            padding: 18px 12px 6px 12px;
            border: none;
            border-bottom: 2px solid #eef2ff;
            outline: none
        }

        .md-field label {
            position: absolute;
            left: 12px;
            top: 18px;
            color: #6b7280;
            transition: all .18s ease
        }

        .md-field input:focus+label,
        .md-field input:not(:placeholder-shown)+label,
        .md-field textarea:focus+label,
        .md-field textarea:not(:placeholder-shown)+label {
            transform: translateY(-12px) scale(.86);
            top: 6px;
            color: #667eea
        }

        .actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 14px
        }

        .btn-primary {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
            border-radius: 10px;
            border: none;
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
            border-radius: 10px;
            border: none;
        }

        @media (max-width:640px) {
            .actions {
                flex-direction: column-reverse
            }
        }
    </style>
@endpush

@section('content')
    <div class="page">
        <div class="card">
            <h2 style="margin:0 0 12px"><i class="fas fa-plus" style="color:#27ae60;margin-right:8px"></i> Tambah Satuan</h2>

            <form action="{{ route('satuan.store') }}" method="POST" novalidate>
                @csrf

                <div class="md-field">
                    <input id="nama_satuan" name="nama_satuan" type="text" value="{{ old('nama_satuan') }}" required
                        placeholder=" ">
                    <label for="nama_satuan">Nama Satuan *</label>
                    @error('nama_satuan')
                        <div style="color:#e02424;margin-top:6px">{{ $message }}</div>
                    @enderror
                </div>

                <div class="md-field">
                    <textarea id="keterangan" name="keterangan" rows="3" placeholder=" ">{{ old('keterangan') }}</textarea>
                    <label for="keterangan">Keterangan</label>
                    @error('keterangan')
                        <div style="color:#e02424;margin-top:6px">{{ $message }}</div>
                    @enderror
                </div>

                <div class="actions">
                    <a href="{{ route('satuan.index') }}" class="btn btn-secondary">Batal</a>
                    <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

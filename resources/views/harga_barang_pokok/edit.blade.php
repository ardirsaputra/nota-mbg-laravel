@extends('layouts.app')

@section('title', 'Edit Barang Pokok')

@push('styles')
    <style>
        .page-header-bar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 22px 28px;
            border-radius: 14px;
            margin-bottom: 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 14px;
        }

        .page-header-bar h1 {
            margin: 0;
            font-size: 1.35rem;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-header-bar p {
            margin: 4px 0 0;
            opacity: .85;
            font-size: .9rem;
        }

        .alert {
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 16px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: .92rem;
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

        .form-card {
            max-width: 820px;
            margin: 0 auto 32px;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, .08);
            overflow: hidden;
        }

        .form-card-header {
            padding: 18px 28px;
            border-bottom: 1px solid #f1f5f9;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            border-left: 4px solid #667eea;
            padding-left: 12px;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-body {
            padding: 24px 28px;
        }

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
        .form-group select {
            padding: 10px 13px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: .93rem;
            transition: border .2s, box-shadow .2s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, .15);
        }

        .form-group .hint {
            font-size: .77rem;
            color: #94a3b8;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            padding: 18px 28px;
            background: #f8fafc;
            border-top: 1px solid #f1f5f9;
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

        @media (max-width: 640px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .page-header-bar {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container">

        <div class="page-header-bar">
            <div>
                <h1><i class="fas fa-edit"></i> Edit Barang Pokok</h1>
                <p>Perbarui data barang dalam daftar harga master</p>
            </div>
            <a href="{{ route('harga-barang-pokok.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i>
                Kembali</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-error" style="max-width:820px; margin: 0 auto 16px;">
                <i class="fas fa-exclamation-circle"></i>
                <ul>
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-card">
            <div class="form-card-header">
                <div class="section-title">
                    <i class="fas fa-box" style="color:#667eea;"></i>
                    Data Barang: <em style="font-style:normal; color:#667eea; margin-left:4px;">{{ $barang->uraian }}</em>
                </div>
            </div>

            <form action="{{ route('harga-barang-pokok.update', $barang->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-body">
                    <div class="form-grid">
                        <div class="form-group full">
                            <label for="uraian">Nama Barang / Uraian *</label>
                            <input type="text" id="uraian" name="uraian" value="{{ old('uraian', $barang->uraian) }}"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="kategori">Kategori *</label>
                            <select id="kategori" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori_list as $kat)
                                    <option value="{{ $kat }}"
                                        {{ old('kategori', $barang->kategori) == $kat ? 'selected' : '' }}>
                                        {{ $kat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="satuan">Satuan *</label>
                            <select id="satuan" name="satuan" required>
                                <option value="">Pilih Satuan</option>
                                @foreach (['Kg', 'Gram', 'Liter', 'Ml', 'Pcs', 'Pack', 'Dus', 'Karung', 'Ball'] as $s)
                                    <option value="{{ $s }}"
                                        {{ old('satuan', $barang->satuan) == $s ? 'selected' : '' }}>
                                        {{ $s }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="nilai_satuan">Nilai Satuan *</label>
                            <input type="number" step="0.01" id="nilai_satuan" name="nilai_satuan"
                                value="{{ old('nilai_satuan', $barang->nilai_satuan) }}" required>
                            <span class="hint">Contoh: 1 Kg=1 · 500 Gram=0.5 · 1 Liter=1</span>
                        </div>

                        <div class="form-group">
                            <label for="harga_satuan">Harga Satuan (Rp) *</label>
                            <input type="number" id="harga_satuan" name="harga_satuan"
                                value="{{ old('harga_satuan', $barang->harga_satuan) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="profit_per_unit">Keuntungan per Satuan (Rp)</label>
                            <input type="number" id="profit_per_unit" name="profit_per_unit"
                                value="{{ old('profit_per_unit', $barang->profit_per_unit ?? 0) }}" min="0">
                            <span class="hint">Opsional — keuntungan tetap per unit dalam nota</span>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                    <a href="{{ route('harga-barang-pokok.index') }}" class="btn btn-secondary"><i
                            class="fas fa-times"></i> Batal</a>
                </div>
            </form>
        </div>

    </div>
@endsection

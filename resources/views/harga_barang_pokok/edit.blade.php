@extends('layouts.app')

@section('title', 'Edit Barang Pokok')

@push('styles')
    <style>
        .form-container {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-header h1 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 600;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #3498db;
            color: white;
            flex: 1;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <h1><i class="fas fa-edit"></i> Edit Barang Pokok</h1>
            </div>

            <form action="{{ route('harga-barang-pokok.update', $barang->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="uraian">Uraian / Nama Barang *</label>
                    <input type="text" id="uraian" name="uraian" value="{{ old('uraian', $barang->uraian) }}" required>
                </div>

                <div class="form-group">
                    <label for="kategori">Kategori *</label>
                    <select id="kategori" name="kategori" required>
                        <option value="">Pilih Kategori</option>
                        @foreach ($kategori_list as $kat)
                            <option value="{{ $kat }}"
                                {{ old('kategori', $barang->kategori) == $kat ? 'selected' : '' }}>{{ $kat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="satuan">Satuan *</label>
                    <select id="satuan" name="satuan" required>
                        <option value="">Pilih Satuan</option>
                        <option value="Kg" {{ old('satuan', $barang->satuan) == 'Kg' ? 'selected' : '' }}>Kg</option>
                        <option value="Gram" {{ old('satuan', $barang->satuan) == 'Gram' ? 'selected' : '' }}>Gram
                        </option>
                        <option value="Liter" {{ old('satuan', $barang->satuan) == 'Liter' ? 'selected' : '' }}>Liter
                        </option>
                        <option value="Ml" {{ old('satuan', $barang->satuan) == 'Ml' ? 'selected' : '' }}>Ml</option>
                        <option value="Pcs" {{ old('satuan', $barang->satuan) == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                        <option value="Pack" {{ old('satuan', $barang->satuan) == 'Pack' ? 'selected' : '' }}>Pack
                        </option>
                        <option value="Dus" {{ old('satuan', $barang->satuan) == 'Dus' ? 'selected' : '' }}>Dus</option>
                        <option value="Karung" {{ old('satuan', $barang->satuan) == 'Karung' ? 'selected' : '' }}>Karung
                        </option>
                        <option value="Ball" {{ old('satuan', $barang->satuan) == 'Ball' ? 'selected' : '' }}>Ball
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="nilai_satuan">Nilai Satuan (untuk konversi ke Kg) *</label>
                    <input type="number" step="0.01" id="nilai_satuan" name="nilai_satuan"
                        value="{{ old('nilai_satuan', $barang->nilai_satuan) }}" required>
                    <small style="color: #7f8c8d;">Contoh: 1 Kg = 1, 500 Gram = 0.5, 1 Liter = 1</small>
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
                    <small style="color:#7f8c8d;">Opsional — diisi jika ingin menetapkan keuntungan per unit untuk
                        perhitungan.</small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="{{ route('harga-barang-pokok.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

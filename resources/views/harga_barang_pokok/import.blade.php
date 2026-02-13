@extends('layouts.app')

@section('title', 'Import Harga Barang (CSV)')

@section('content')
    <div class="container" style="max-width:800px;">
        <h1><i class="fas fa-file-csv" style="color:#2ecc71;margin-right:8px;"></i> Import CSV - Harga Barang Pokok</h1>

        <div style="background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.06);margin-top:16px;">
            <p style="margin-bottom:12px;color:#7f8c8d;">Unggah file CSV dengan kolom: <strong>Uraian, Kategori, Satuan,
                    Nilai Satuan, Harga Satuan</strong>. Baris tanpa Uraian akan diabaikan.</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin:0;padding-left:18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('harga-barang-pokok.import.post') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
                    <input type="file" name="file" accept=".csv,text/csv" required>

                    <label style="display:flex;gap:8px;align-items:center;">
                        <select name="mode">
                            <option value="skip">Skip existing (default)</option>
                            <option value="overwrite">Overwrite existing</option>
                        </select>
                    </label>

                    <button class="btn btn-primary" type="submit"><i class="fas fa-upload"></i> Upload &amp;
                        Import</button>
                    <a href="{{ route('harga-barang-pokok.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>

            <hr style="margin:18px 0;">
            <p style="font-size:0.9rem;color:#95a5a6;">Tip: Buat cadangan data sebelum import besar; gunakan mode
                "Overwrite" hanya bila ingin memperbarui data yang sudah ada.</p>
        </div>
    </div>
@endsection

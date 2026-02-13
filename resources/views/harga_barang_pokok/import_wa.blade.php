@extends('layouts.app')

@section('title', 'Import Harga dari WhatsApp')

@section('content')
    <div class="container" style="max-width:900px;">
        <h1><i class="fas fa-paste" style="color:#2ecc71;margin-right:8px;"></i> Import dari WhatsApp (paste teks)</h1>

        <div style="background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.06);margin-top:16px;">
            <p style="margin-bottom:12px;color:#7f8c8d;">Tempel teks daftar harga yang Anda salin dari WhatsApp. Didukung dua
                format: <em>Nama — Rp 12.000 / Satuan</em> <strong>atau</strong> baris terformat sederhana seperti
                <code>1|Bawang Merah@25.000/Kg</code>. Sistem akan mengekstrak nama, harga, dan satuan.
            </p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin:0;padding-left:18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('harga-barang-pokok.import-wa.post') }}" method="POST">
                @csrf

                <div style="display:flex;flex-direction:column;gap:12px;">
                    <textarea name="wa_text" rows="12" placeholder="Tempel teks WhatsApp di sini..." required
                        style="width:100%;padding:12px;border:1px solid #e6eef6;border-radius:6px;font-family:inherit"></textarea>

                    <div style="display:flex;gap:8px;align-items:center;">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-upload"></i> Import</button>
                        <a href="{{ route('harga-barang-pokok.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </div>
            </form>

            <hr style="margin:18px 0;">
            <p style="font-size:0.9rem;color:#95a5a6;">Catatan: format yang paling dapat diproses adalah: <strong>Nama — Rp
                    12.000 / Satuan</strong> atau <code>1|Nama@25.000/Satuan</code>. Baris yang tidak dapat dikenali akan
                diabaikan.</p>
        </div>
    </div>
@endsection

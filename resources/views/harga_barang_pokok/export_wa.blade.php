@extends('layouts.app')

@section('title', 'Export WhatsApp — Salin Teks')

@section('content')
    <div class="container" style="max-width:900px;">
        <h1><i class="fab fa-whatsapp" style="color:#25D366;margin-right:8px;"></i> Export WA — Salin Teks</h1>

        <div style="background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.06);margin-top:16px;">
            <p style="color:#7f8c8d;margin-bottom:12px;">Teks telah dihasilkan sesuai format
                <code>index|Nama@25.000/Satuan</code>. Gunakan tombol <strong>Salin</strong> untuk menyalin seluruh teks
                atau unduh sebagai <em>.txt</em>.</p>

            <div style="display:flex;gap:8px;margin-bottom:10px;align-items:center;">
                <button id="copyBtn" class="btn btn-primary"><i class="fas fa-copy"></i> Salin ke clipboard</button>
                <a href="{{ route('harga-barang-pokok.export-wa') }}" class="btn btn-secondary"><i
                        class="fas fa-download"></i> Unduh .txt</a>
                <a href="{{ route('harga-barang-pokok.index') }}" class="btn btn-success">Kembali ke Daftar</a>
            </div>

            <textarea id="waText" rows="18"
                style="width:100%;padding:12px;border:1px solid #e6eef6;border-radius:6px;font-family:monospace;">{{ $content }}</textarea>

            <p style="font-size:0.9rem;color:#95a5a6;margin-top:12px;">Tip: Gunakan <strong>Ctrl/Cmd + C</strong> atau
                tombol <em>Salin</em> untuk menyalin seluruh daftar ke clipboard.</p>
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                var copyBtn = document.getElementById('copyBtn');
                var ta = document.getElementById('waText');
                copyBtn.addEventListener('click', function() {
                    ta.select();
                    try {
                        var ok = document.execCommand('copy');
                        copyBtn.textContent = ok ? 'Tersalin ✓' : 'Gagal';
                        setTimeout(function() {
                            copyBtn.innerHTML = '<i class="fas fa-copy"></i> Salin ke clipboard';
                        }, 2000);
                    } catch (e) {
                        // fallback: prompt
                        window.prompt('Salin teks berikut (Ctrl/Cmd+C, Enter):', ta.value);
                    }
                });
            })();
        </script>
    @endpush
@endsection

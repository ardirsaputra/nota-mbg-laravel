@php
    use App\Models\Setting;

    $companyName = Setting::get('company_name', 'CV Mia Jaya Abadi');
    $companyLogo = Setting::get('company_logo');
    $phone1 = Setting::get('phone_1', '');
    $phone2 = Setting::get('phone_2', '');
    $address = Setting::get('address', '');
    $address2 = Setting::get('address_2', '');
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Daftar Barang - {{ $companyName }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f5f5;
        }

        .toolbar {
            padding: 15px;
            display: flex;
            gap: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border: none;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-back {
            background-color: #3498db;
            color: white;
        }

        .btn-print {
            background-color: #27ae60;
            color: white;
        }

        .btn-toggle {
            background-color: #e67e22;
            color: white;
        }

        .btn-edit {
            background-color: #f39c12;
            color: white;
        }

        .btn-delete {
            background-color: #e74c3c;
            color: white;
        }

        .btn-delete:hover {
            background-color: #c0392b;
        }

        .btn-edit:hover {
            background-color: #e67e22;
        }

        .profit-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 15px 0;
            display: none;
            align-items: center;
            justify-content: space-between;
        }

        .profit-info.show {
            display: flex;
        }

        .nota {
            width: 210mm;
            margin: 20px auto;
            background: #fff;
            padding: 15px;
            position: relative;
            border: 1px solid #000;
            overflow: hidden;
        }

        @if ($companyLogo)
            .nota::before {
                content: "";
                position: absolute;
                top: 50%;
                left: 50%;
                width: 420px;
                height: 420px;
                background: url("{{ asset('storage/' . $companyLogo) }}") no-repeat center;
                background-size: contain;
                opacity: 0.07;
                transform: translate(-50%, -50%);
                z-index: 0;
            }
        @endif

        .content {
            position: relative;
            z-index: 1;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0px;
            font-size: 12px;
        }

        .header-table td {
            border: none;
            padding: 2px;
            vertical-align: middle;
        }

        .logo {
            width: 22%;
            text-align: center;
        }

        .logo img {
            height: 100px;
        }

        .judul {
            width: 43%;
            text-align: center;
        }

        .judul h1 {
            margin: 0;
            font-size: 22px;
            letter-spacing: 1px;
        }

        .judul p {
            margin: 4px 0 0;
            font-size: 12px;
            line-height: 1.3;
        }

        .pembeli {
            width: 30%;
            font-size: 12px;
            line-height: 1.2;
        }

        .pembeli table td {
            padding: 2px 6px;
            line-height: 1.15;
            vertical-align: top;
        }

        .nota-no {
            font-size: 13px;
            font-weight: bold;
            text-align: left;
            padding: 6px 0;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        table.data,
        table.data th,
        table.data td {
            border: 1px solid #000;
        }

        table.data th,
        table.data td {
            padding: 3px;
        }

        table.data th {
            background: #eee;
            text-align: center;
        }

        table.data td {
            text-align: center;
        }

        /* right-align Harga Satuan and Harga Baru (shifted right after adding Keuntungan column) */
        table.data td:nth-child(6),
        table.data td:nth-child(7) {
            text-align: right;
            padding-right: 8px;
        }

        table.data th:nth-child(6),
        table.data td:nth-child(6),
        table.data th:nth-child(7),
        table.data td:nth-child(7) {
            width: 14%;
        }

        .catatan {
            font-size: 11px;
            margin-top: 5px;
        }

        .ttd {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
            text-align: center;
            padding: 0 40px;
        }

        .ttd>div {
            width: 45%;
        }

        .col-harga-baru.hidden {
            display: none !important;
        }

        .col-harga-baru {
            display: table-cell;
        }

        .harga-baru-input {
            width: 100%;
            box-sizing: border-box;
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            text-align: right;
            font-weight: 700;
        }

        .left {
            text-align: left;
        }

        @media print {

            .navbar,
            .toolbar {
                display: none !important;
            }

            body {
                background: white;
                margin: 0;
                padding: 0;
            }

            .nota {
                width: 100%;
                margin: 0;
                border: none;
                padding: 0;
                box-shadow: none;
            }
        }

        @media (max-width:768px) {
            .toolbar {
                flex-wrap: wrap;
                gap: 8px;
                padding: 8px;
                justify-content: center;
            }

            .toolbar .btn {
                flex: 1 1 48%;
                min-width: 140px;
                padding: 10px 12px;
                font-size: 14px;
                justify-content: center;
                text-align: center;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .toolbar .btn i {
                margin-right: 8px;
            }
        }

        @media (max-width:480px) {
            .toolbar .btn {
                flex: 1 1 100%;
                min-width: 0;
                padding: 10px;
                font-size: 13px;
            }

            .toolbar .btn .btn-text {
                display: none;
            }

            .toolbar .btn i {
                margin-right: 0;
                font-size: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="toolbar">
        <a href="{{ route('harga-barang-pokok.index') }}" class="btn btn-back"><i class="fas fa-arrow-left"></i>
            Kembali</a>
        <button class="btn btn-print" onclick="printList()"><i class="fas fa-print"></i> Cetak / Simpan PDF</button>
        <button onclick="toggleHargaBaru()" class="btn btn-toggle"><i class="fas fa-eye-slash"></i> <span
                id="harga-toggle-text">Sembunyikan Harga Baru</span></button>
    </div>

    <div class="nota">
        <div class="content">
            <table class="header-table">
                <tr>
                    <td class="logo">
                        @if ($companyLogo)
                            <img src="{{ asset('storage/' . $companyLogo) }}" alt="Logo">
                        @endif
                    </td>

                    <td class="judul">
                        <h1>{{ strtoupper($companyName) }}</h1>
                        <p>
                            @if ($address)
                                {{ $address }}<br>
                                @endif @if ($address2)
                                    {{ $address2 }}<br>
                                    @endif @if ($phone1 || $phone2)
                                        Kontak:
                                        @if ($phone1)
                                            {{ $phone1 }}
                                        @endif
                                        @if ($phone1 && $phone2)
                                            |
                                        @endif
                                        @if ($phone2)
                                            {{ $phone2 }}
                                        @endif
                                    @endif
                        </p>
                    </td>

                    <td class="logo"></td>
                </tr>
            </table>

            <center>
                <h4>
                    DAFTAR BARANG POKOK<br>
                    <small>Terakhir diperbarui: {{ $last_update ? $last_update->format('d/m/Y H:i') : '-' }}</small>
                </h4>
            </center>

            <table class="data">
                <thead>
                    <tr>
                        <th style="width:60px">No</th>
                        <th>Barang</th>
                        <th style="width:120px">QTY</th>
                        <th style="width:120px">Satuan</th>
                        <th style="width:140px">Keuntungan/Satuan (Rp)</th>
                        <th style="width:140px">Harga Satuan (Rp)</th>
                        <th class="col-harga-baru">Harga Baru</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barang_pokok as $i => $b)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="left">{{ $b->uraian }}</td>
                            <td>1</td>
                            <td>{{ $b->satuan }}</td>
                            <td style="text-align:right; padding-right:8px">Rp
                                {{ number_format($b->profit_per_unit ?? 0, 0, ',', '.') }}</td>
                            <td style="text-align:right; padding-right:8px">Rp
                                {{ number_format($b->harga_satuan, 0, ',', '.') }}</td>
                            <td class="col-harga-baru">
                                @if (Auth::check() && Auth::user()->isAdmin())
                                    <input type="number" class="harga-baru-input" value="{{ $b->harga_satuan }}" />
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;padding:18px;color:#999">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="catatan">Catatan: Harga pada daftar ini dapat berubah sewaktu-waktu sesuai kondisi pasar dan
                kebijakan perusahaan.</div>

        </div>
    </div>

    <script>
        const searchPart = {!! json_encode($search ?? '') !!};
        const kategoriPart = {!! json_encode($kategori ?? '') !!};

        function sanitizeForFilename(str) {
            return String(str || '').normalize('NFKD').replace(/[\u0300-\u036F]/g, '').replace(/[^a-zA-Z0-9\-_. ]+/g, '-')
                .replace(/\s+/g, '-').replace(/-+/g, '-').replace(/^[-_.]+|[-_.]+$/g, '').toLowerCase();
        }

        function formatDateForFilename() {
            const d = new Date();
            const y = d.getFullYear();
            const m = String(d.getMonth() + 1).padStart(2, '0');
            const dd = String(d.getDate()).padStart(2, '0');
            return y + m + dd;
        }

        function printList() {
            const datePart = formatDateForFilename();
            const base = `daftar-barang-${sanitizeForFilename(searchPart)}-${sanitizeForFilename(kategoriPart)}-${datePart}`
                .replace(/-+/g, '-').replace(/^-|-$/g, '');
            const prev = document.title || '';
            document.title = base;

            function restore() {
                document.title = prev;
                window.removeEventListener('afterprint', restore);
            }
            window.addEventListener('afterprint', restore);
            window.print();
        }

        // Toggle Harga Baru column
        let hargaBaruVisible = true;

        function toggleHargaBaru() {
            const cells = document.querySelectorAll('.col-harga-baru');
            const toggleBtn = document.getElementById('harga-toggle-text');
            const icon = document.querySelector('.btn-toggle i');

            if (hargaBaruVisible) {
                cells.forEach(c => c.classList.add('hidden'));
                if (toggleBtn) toggleBtn.textContent = 'Tampilkan Harga Baru';
                if (icon) {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
                hargaBaruVisible = false;
            } else {
                cells.forEach(c => c.classList.remove('hidden'));
                if (toggleBtn) toggleBtn.textContent = 'Sembunyikan Harga Baru';
                if (icon) {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
                hargaBaruVisible = true;
            }
        }

        // Hide Harga Baru by default on page load
        toggleHargaBaru();

        // Ensure inputs are numeric-only and formatted when focus is lost (optional UX)
        document.addEventListener('input', function(ev) {
            if (ev.target && ev.target.classList && ev.target.classList.contains('harga-baru-input')) {
                // remove non-digits
                ev.target.value = ev.target.value.replace(/[^0-9]/g, '');
            }
        });
    </script>
</body>

</html>

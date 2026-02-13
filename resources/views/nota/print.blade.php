@php
    use App\Models\Setting;

    $companyName = Setting::get('company_name', 'CV Mia Jaya Abadi');
    $companyLogo = Setting::get('company_logo');
    $phone1 = Setting::get('phone_1', '');
    $phone2 = Setting::get('phone_2', '');
    $address = Setting::get('address', '');
    $directorName = Setting::get('director_name', 'Mia Astuti');
    $notaNotes = Setting::get('nota_notes', ['Barang yang sudah diterima tidak bisa ditukar atau dikembalikan.']);
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Nota {{ $nota->no }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            position: relative;
        }

        @if (auth()->user()->isAdmin())
            body::before {
                content: '';
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 500px;
                height: 500px;
                background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><text x="50%" y="50%" font-size="40" fill="%23667eea" opacity="0.1" text-anchor="middle" dominant-baseline="middle" font-weight="bold">{{ str_replace(' ', '%20', strtoupper($companyName)) }}</text></svg>');
                background-repeat: no-repeat;
                background-position: center;
                background-size: contain;
                opacity: 0.15;
                z-index: -1;
                pointer-events: none;
            }
        @endif

        .print-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
        }

        .header {
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .company-info {
            text-align: center;
            margin-bottom: 20px;
        }

        .company-logo {
            max-width: 150px;
            max-height: 80px;
            margin: 0 auto 10px;
        }

        .company-info h1 {
            color: #667eea;
            font-size: 28px;
            margin-bottom: 8px;
        }

        .company-info p {
            color: #666;
            font-size: 14px;
            margin: 4px 0;
        }

        .nota-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
            padding: 15px;
            background: #f7fafc;
            border-radius: 8px;
        }

        .detail-group h3 {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .detail-item {
            margin-bottom: 8px;
        }

        .detail-label {
            font-size: 13px;
            color: #666;
            display: inline-block;
            width: 100px;
        }

        .detail-value {
            font-size: 14px;
            font-weight: 600;
            color: #2d3748;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .items-table th {
            padding: 12px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
        }

        .items-table th.text-right,
        .items-table td.text-right {
            text-align: right;
        }

        .items-table th.text-center,
        .items-table td.text-center {
            text-align: center;
        }

        .items-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
        }

        .items-table td {
            padding: 10px 12px;
            font-size: 13px;
        }

        .total-section {
            margin-top: 20px;
            text-align: right;
        }

        .total-row {
            display: flex;
            justify-content: flex-end;
            gap: 20px;
            padding: 12px 20px;
            background: #f7fafc;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        /* Catatan & tanda tangan */
        .catatan {
            margin-top: 18px;
            font-size: 13px;
            color: #333;
        }

        .catatan ol {
            margin: 6px 0 0 18px;
            padding-left: 18px;
        }

        .ttd {
            margin-top: 12px;
            display: flex;
            justify-content: space-between;
            text-align: center;
            padding: 0 40px;
        }

        .ttd>div {
            width: 45%;
        }

        .total-row.grand {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 18px;
            font-weight: 700;
        }

        .total-label {
            font-weight: 600;
        }

        .total-value {
            min-width: 150px;
            font-weight: 700;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            color: #666;
            font-size: 12px;
        }

        @media print {

            html,
            body {
                padding: 0;
                margin: 0;
                background: #ffffff !important;
                background-color: #ffffff !important;
            }

            @page {
                margin: 10mm;
                background: #ffffff;
            }

            body::before {
                display: none !important;
            }

            .print-container {
                max-width: 100%;
                background: #ffffff !important;
                box-shadow: none;
            }

            .no-print {
                display: none !important;
            }

            /* Remove light-grey background on printed nota */
            .nota-details,
            .total-row {
                background: transparent !important;
                background-color: transparent !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }

            /* Make table header print-friendly (no gradient) */
            .items-table thead {
                background: none !important;
                background-color: transparent !important;
                color: #000 !important;
                border-bottom: 2px solid #000;
            }

            .total-row.grand {
                background: transparent !important;
                background-color: transparent !important;
                color: #000 !important;
                border: 2px solid #000;
            }

            .header {
                border-bottom: 2px solid #000;
            }

            .company-info h1 {
                color: #000;
            }
        }

        .toolbar {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 1000;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-secondary {
            background: #f7fafc;
            color: #4a5568;
            border: 1px solid #e2e8f0;
        }

        .btn:hover {
            opacity: 0.9;
        }

        @if (!auth()->user()->isAdmin())
            .user-nota-badge {
                background: #fef3c7;
                color: #92400e;
                padding: 8px 16px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 600;
                display: inline-block;
                margin-bottom: 10px;
            }
        @endif
    </style>
</head>

<body>
    <div class="toolbar no-print">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Cetak
        </button>
        <a href="{{ route('nota.show', $nota->id) }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> Tutup
        </a>
    </div>

    <div class="print-container">
        <div class="header">
            <div class="company-info">
                @if ($companyLogo)
                    <img src="{{ asset('storage/' . $companyLogo) }}" alt="{{ $companyName }}" class="company-logo">
                @endif
                <h1>{{ strtoupper($companyName) }}</h1>
                @if (!auth()->user()->isAdmin())
                    <div class="user-nota-badge">
                        <i class="fas fa-user"></i> Nota Pengguna
                    </div>
                @endif
                @if ($address || $address2)
                    <p>
                        @if ($address)
                            {{ $address }}<br>
                        @endif
                        @if ($address2)
                            {{ $address2 }}<br>
                        @endif
                    </p>
                @endif
                @if ($phone1 || $phone2)
                    <p>
                        @if ($phone1)
                            Telp: {{ $phone1 }}
                        @endif
                        @if ($phone1 && $phone2)
                            |
                        @endif
                        @if ($phone2)
                            {{ $phone2 }}
                        @endif
                    </p>
                @endif
            </div>
        </div>

        <div class="nota-details">
            <div class="detail-group">
                <h3>Informasi Nota</h3>
                <div class="detail-item">
                    <span class="detail-label">No. Nota:</span>
                    <span class="detail-value">{{ $nota->no }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Tanggal:</span>
                    <span class="detail-value">{{ $nota->tanggal->format('d F Y') }}</span>
                </div>
                @if (!$nota->is_admin_nota)
                    <div class="detail-item">
                        <span class="detail-label">Dibuat oleh:</span>
                        <span class="detail-value">{{ $nota->user->name }}</span>
                    </div>
                @endif
            </div>
            <div class="detail-group">
                <h3>Informasi Toko</h3>
                <div class="detail-item">
                    <span class="detail-label">Nama Toko:</span>
                    <span class="detail-value">{{ $nota->nama_toko ?? '-' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Alamat:</span>
                    <span class="detail-value">{{ $nota->alamat ?? '-' }}</span>
                </div>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 40px">No</th>
                    <th>Uraian</th>
                    <th class="text-center" style="width: 100px">Satuan</th>
                    <th class="text-right" style="width: 80px">Qty</th>
                    <th class="text-right" style="width: 120px">Harga</th>
                    <th class="text-right" style="width: 140px">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($nota->items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->uraian }}</td>
                        <td class="text-center">{{ $item->satuan }}</td>
                        <td class="text-right">{{ $item->qty }}</td>
                        <td class="text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-row grand">
                <div class="total-label">TOTAL:</div>
                <div class="total-value">Rp {{ number_format($nota->total, 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- Catatan (dari setting) --}}
        @if (!empty($notaNotes) && is_array($notaNotes))
            <div class="catatan">
                <strong>Catatan:</strong>
                <ol>
                    @foreach ($notaNotes as $n)
                        @if (trim($n) !== '')
                            <li>{{ $n }}</li>
                        @endif
                    @endforeach
                </ol>
            </div>
        @endif

        <div class="ttd">
            <div>
                Tanda Terima<br><br><br><br><br>
                ( ____________________ )
            </div>
            <div>
                Hormat Kami<br>
                Direktur<br><br><br><br>
                ( {{ $directorName }} )
            </div>
        </div>

        <div class="footer">
            <p>Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
            @if (!auth()->user()->isAdmin())
                <p>Nota ini dibuat oleh pengguna dan belum diverifikasi oleh admin</p>
            @endif
            <p>Terima kasih atas kepercayaan Anda</p>
        </div>
    </div>
</body>

</html>

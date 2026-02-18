@extends('layouts.app')

@section('title', 'Nota ' . $nota->no)

@push('styles')
    <!-- headline font for company title -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;800;900&display=swap" rel="stylesheet">
    <style>
        /* ====== TOOLBAR ====== */
        .nota-toolbar {
            padding: 15px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .nota-toolbar .btn {
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
            color: #fff;
        }

        .btn-back {
            background-color: #3498db;
        }

        .btn-back:hover {
            background-color: #2980b9;
        }

        .btn-print {
            background-color: #27ae60;
        }

        .btn-print:hover {
            background-color: #229954;
        }

        .btn-toggle {
            background-color: #e67e22;
        }

        .btn-toggle:hover {
            background-color: #d35400;
        }

        .btn-edit {
            background-color: #f39c12;
        }

        .btn-edit:hover {
            background-color: #e67e22;
        }

        .btn-delete {
            background-color: #e74c3c;
        }

        .btn-delete:hover {
            background-color: #c0392b;
        }

        .btn-lock {
            background-color: #f39c12;
        }

        .btn-lock:hover {
            background-color: #e67e22;
        }

        .btn-lock:disabled {
            background-color: #95a5a6;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .btn-profit-insight {
            background-color: #9b59b6;
        }

        .btn-profit-insight:hover {
            background-color: #8e44ad;
        }

        .lock-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .lock-badge.locked {
            background: #e74c3c;
            color: white;
        }

        .lock-badge.unlocked {
            background: #27ae60;
            color: white;
        }

        /* ====== PROFIT INFO ====== */
        .profit-info {
            width: 210mm;
            max-width: 100%;
            box-sizing: border-box;
            background: #fff;
            color: #2c3e50;
            padding: 12px 16px;
            border-radius: 6px;
            margin: 15px auto;
            display: none;
            align-items: center;
            justify-content: space-between;
            border-left: 3px solid #e0e0e0;
            border-right: 3px solid #e0e0e0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .profit-info.show {
            display: flex;
        }

        .profit-info h3 {
            margin: 0;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #2c3e50;
        }

        .profit-info .amount {
            font-size: 1.1rem;
            font-weight: 700;
            color: #27ae60;
        }

        /* ====== NOTA (PAPER) ====== */
        .nota {
            width: 210mm;
            margin: 20px auto;
            background: #fff;
            padding: 15px;
            position: relative;
            border: 1px solid #000;
            overflow: hidden;
            /* make invoice text thicker */
            font-weight: 600;
            color: #111;
        }

        /* emphasize title and totals */
        .nota .judul h1 {
            font-weight: 900;
            letter-spacing: 1px;
        }

        table.data th {
            font-weight: 700;
        }

        table.data td {
            font-weight: 600;
        }

        table.data tr:last-child td {
            font-weight: 800;
        }

        @php $__logo = App\Models\Setting::get('company_logo'); @endphp
        @if ($__logo)
            .nota::before {
                content: "";
                position: absolute;
                top: 50%;
                left: 50%;
                width: 420px;
                height: 420px;
                background: url('{{ asset('storage/' . $__logo) }}') no-repeat center;
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

        /* ====== HEADER TABLE ====== */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            font-size: 12px;
        }

        .header-table td {
            border: none;
            padding: 2px;
            vertical-align: middle;
        }

        .logo {
            width: 20%;
            text-align: center;
        }

        .logo img {
            height: 100px;
        }

        .judul {
            width: 41%;
            text-align: center;
        }

        .judul h1 {
            margin: 0;
            font-size: 22px;
            letter-spacing: 1px;
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            /* weight handled by .nota .judul h1 */
        }

        .judul p {
            margin: 4px 0 0;
            font-size: 12px;
            line-height: 1.3;
        }

        .pembeli {
            width: 39%;
            font-size: 12px;
            line-height: 1.2;
        }

        .pembeli table {
            border-collapse: collapse;
        }

        .pembeli table td {
            padding: 2px 6px;
            line-height: 1.15;
            vertical-align: top;
        }

        .pembeli strong {
            font-weight: bold;
        }

        /* ====== DATA TABLE ====== */
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

        /* compact item padding */
        table.data th,
        table.data td {
            padding: 2px 6px;
        }

        table.data th {
            background: #eee;
            text-align: center;
        }

        table.data td {
            text-align: center;
        }

        table.data td:nth-child(1) {
            text-align: center;
            width: 5%;
        }

        table.data td:nth-child(2) {
            text-align: center;
        }

        table.data td:nth-child(3) {
            width: 8%;
        }

        table.data td:nth-child(4) {
            width: 12%;
        }

        table.data td:nth-child(5) {
            width: 12%;
            text-align: right;
            padding-right: 6px;
        }

        table.data td:nth-child(6) {
            width: 12%;
            text-align: right;
            padding-right: 6px;
        }

        table.data td:nth-child(7) {
            width: 12%;
        }

        table.data tr:last-child td {
            text-align: right;
            padding-right: 6px;
        }

        .pf-column {
            display: table-cell;
        }

        .pf-column.hidden {
            display: none;
        }

        /* ====== FOOTER ====== */
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

        /* ====== PRINT STYLES ====== */
        @media print {

            /* tambahkan pengaturan untuk background lain berwarna putih */
            body {
                background: #fff !important;
            }

            .navbar {
                display: none !important;
            }

            .nota-toolbar {
                display: none !important;
            }

            /* hide small UI controls (checkboxes/buttons) from printed nota */
            .no-print { display: none !important; }

            .profit-info {
                display: none !important;
            }

            body {
                background: #fff;
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

        /* ====== RESPONSIVE TOOLBAR ====== */
        @media (max-width: 768px) {
            .nota-toolbar {
                flex-wrap: wrap;
                gap: 8px;
                padding: 8px;
                justify-content: center;
            }

            .nota-toolbar .btn {
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

            .profit-info {
                flex-direction: column;
                gap: 8px;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .nota-toolbar .btn {
                flex: 1 1 100%;
                min-width: 0;
                padding: 10px;
                font-size: 13px;
            }

            .nota-toolbar .btn .btn-text {
                display: none;
            }

            .nota-toolbar .btn i {
                margin-right: 0;
                font-size: 16px;
            }

            .nota {
                padding: 8px;
                font-size: 10px;
            }
        }
    </style>
@endpush

@section('content')
    @php
        use App\Models\Setting;

        $companyName = Setting::get('company_name', 'CV Mia Jaya Abadi');
        $companyLogo = Setting::get('company_logo');
        $phone1 = Setting::get('phone_1', '');
        $phone2 = Setting::get('phone_2', '');
        $address = Setting::get('address', '');
        $address2 = Setting::get('address_2', '');
        $directorName = Setting::get('director_name', 'Mia Astuti');
        $notaNotes = Setting::get('nota_notes', ['Barang yang sudah diterima tidak bisa ditukar atau dikembalikan.']);
    @endphp

    {{-- TOOLBAR --}}
    <div class="nota-toolbar">
        <a href="{{ route('nota.index') }}" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> <span class="btn-text">Kembali</span>
        </a>
        <a href="{{ route('nota.edit', $nota->id) }}" class="btn btn-edit">
            <i class="fas fa-edit"></i> <span class="btn-text">Edit</span>
        </a>
        <button type="button" id="btnLock" class="btn btn-lock" onclick="toggleLock()"
            title="{{ $nota->is_locked ? 'Buka kunci nota' : 'Kunci nota' }}">
            <i class="fas fa-{{ $nota->is_locked ? 'unlock' : 'lock' }}" id="btnLockIcon"></i>
            <span id="btnLockText" class="btn-text">{{ $nota->is_locked ? 'Buka Kunci' : 'Kunci Nota' }}</span>
        </button>
        <span id="lockBadge" class="lock-badge {{ $nota->is_locked ? 'locked' : 'unlocked' }}"
            title="{{ $nota->is_locked ? 'Nota dikunci' : 'Nota belum dikunci' }}">
            <i class="fas fa-{{ $nota->is_locked ? 'lock' : 'lock-open' }}" id="lockBadgeIcon"></i>
            <span id="lockBadgeText">{{ $nota->is_locked ? 'Dikunci' : 'Terbuka' }}</span>
        </span>

<label class="btn btn-toggle no-print" id="profitToggleLabel" title="Include in Profit" style="display:inline-flex;align-items:center;gap:8px;cursor:pointer;">
                    <input type="checkbox" id="profitCheckbox" style="width:16px;height:16px;margin:0;" {{ $nota->profit_insight ? 'checked' : '' }} onchange="profitCheckboxChanged(this)">
                    <i class="fas fa-chart-line" id="profitCheckboxIcon" style="margin-left:6px"></i>
                    <span id="btnProfitText" class="btn-text" style="margin-left:6px;">{{ $nota->profit_insight ? 'Included in Profit' : 'Excluded from Profit' }}</span>
                </label>

        <button onclick="togglePFColumn()" class="btn btn-toggle">
            <i class="fas fa-eye-slash" id="pfToggleIcon"></i> <span id="pf-toggle-text" class="btn-text">Sembunyikan
                PF</span>
        </button>

        <button id="btnProfitToggle" onclick="toggleProfitPanel()" class="btn btn-profit-insight" aria-controls="profitInfo"
            aria-expanded="false">
            <i class="fas fa-chart-line"></i> <span id="profit-toggle-text" class="btn-text">Tampilkan Profit</span>
        </button>

        {{-- <a href="{{ route('nota.print', $nota->id) }}" target="_blank" class="btn btn-print"
            title="Buka halaman print (multi-halaman)">
            <i class="fas fa-print"></i> <span class="btn-text">Buka Halaman Print Pengguna</span>
        </a> --}}

        <button type="button" onclick="printNota()" class="btn btn-print" title="Cetak / Simpan PDF">
            <i class="fas fa-file-pdf"></i> <span class="btn-text">Cetak / Simpan PDF</span>
        </button>

        {{-- <form action="{{ route('nota.destroy', $nota->id) }}" method="POST"
            onsubmit="return confirm('Yakin ingin menghapus nota ini?')" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-delete">
                <i class="fas fa-trash"></i> <span class="btn-text">Hapus</span>
            </button>
        </form> --}}

    </div>

    {{-- PROFIT INFO PANEL --}}
    <div class="profit-info" id="profitInfo">
        <div>
            <h3><i class="fas fa-chart-line"></i> Estimasi Profit</h3>
            <p style="margin: 5px 0 0 34px; font-size: 0.9rem; opacity: 0.9;">
                <i class="fas fa-info-circle"></i> Tiered: 20% (≤Rp10rb), 10% (>Rp50rb), 5% (>Rp150rb)
            </p>
        </div>
        <div class="amount">Rp {{ number_format($estimated_profit ?? 0, 0, ',', '.') }}</div>
    </div>

    {{-- NOTA PAPER --}}
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
                        <h1>{{ Str::upper($companyName) }}</h1>
                        <p>
                            @if ($address)
                                {{ $address }}<br>
                            @endif
                            @if ($address2)
                                {{ $address2 }}<br>
                            @endif
                            @if ($phone1 || $phone2)
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
                    <td class="pembeli">
                        <table>
                            <tr>
                                <td><strong>Nota No</strong></td>
                                <td>:</td>
                                <td>{{ $nota->no }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal</strong></td>
                                <td>:</td>
                                <td>{{ $nota->tanggal ? $nota->tanggal->format('d-m-Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tuan/Toko</strong></td>
                                <td>:</td>
                                <td>{{ $nota->nama_toko ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat</strong></td>
                                <td>:</td>
                                <td>{{ $nota->alamat ?? '-' }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <table class="data">
                <tr>
                    <th>No</th>
                    <th>Uraian</th>
                    <th>Qty</th>
                    <th>Satuan</th>
                    <th>Harga (Rp)</th>
                    <th>Total (Rp)</th>
                    <th class="pf-column">PF</th>
                </tr>
                @foreach ($nota->items as $i => $it)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td style="text-align:center;">{{ $it->uraian }}</td>
                        <td>{{ $it->qty }}</td>
                        <td>{{ $it->satuan ?? '-' }}</td>
                        <td>{{ number_format($it->harga_satuan, 0, ',', '.') }}</td>
                        <td>{{ number_format($it->subtotal, 0, ',', '.') }}</td>
                        <td class="pf-column" style="text-align:right;padding-right:8px;">
                            {{-- @if (isset($it->profit_percent))
                                {{ $it->profit_percent }}% — Rp
                                {{ number_format(($it->profit_per_unit ?? 0) * $it->qty, 0, ',', '.') }}
                            @endif --}}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="5"><strong>Jumlah</strong></td>
                    <td><strong>{{ number_format($nota->total, 0, ',', '.') }}</strong></td>
                    <td class="pf-column" style="text-align:right;padding-right:8px;">
                        {{-- <strong>Rp {{ number_format($estimated_profit ?? 0, 0, ',', '.') }}</strong> --}}
                    </td>
                </tr>
            </table>

            <div class="catatan">
                @if (!empty($notaNotes) && is_array($notaNotes))
                    <strong>Catatan:</strong>
                    <ol style="margin:8px 0 0 18px;">
                        @foreach ($notaNotes as $n)
                            @if (trim($n) !== '')
                                <li>{{ $n }}</li>
                            @endif
                        @endforeach
                    </ol>
                @else
                    Catatan: -
                @endif
            </div>
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
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const notaId = {{ $nota->id }};
        const notaNo = @json($nota->no);
        const notaTuan = @json($nota->nama_toko ?? '');
        const notaTanggal = @json($nota->tanggal ? $nota->tanggal->format('Y-m-d') : '');
        let isLocked = {{ $nota->is_locked ? 'true' : 'false' }};
        let pfVisible = true;

        /* ======== LOCK ======== */
        function updateLockUI(locked) {
            const lockBadge = document.getElementById('lockBadge');
            const lockBadgeIcon = document.getElementById('lockBadgeIcon');
            const lockBadgeText = document.getElementById('lockBadgeText');
            if (lockBadge) {
                lockBadge.className = 'lock-badge ' + (locked ? 'locked' : 'unlocked');
            }
            if (lockBadgeIcon) lockBadgeIcon.className = 'fas fa-' + (locked ? 'lock' : 'lock-open');
            if (lockBadgeText) lockBadgeText.textContent = locked ? 'Dikunci' : 'Terbuka';
            const btnLockIcon = document.getElementById('btnLockIcon');
            const btnLockText = document.getElementById('btnLockText');
            const btnLock = document.getElementById('btnLock');
            if (btnLockIcon) btnLockIcon.className = 'fas fa-' + (locked ? 'unlock' : 'lock');
            if (btnLockText) btnLockText.textContent = locked ? 'Buka Kunci' : 'Kunci Nota';
            if (btnLock) btnLock.title = locked ? 'Buka kunci nota' : 'Kunci nota';
        }

        function toggleLock() {
            if (!confirm(isLocked ? 'Apakah Anda yakin ingin membuka kunci nota ini?' :
                    'Apakah Anda yakin ingin mengunci nota ini?\n\nSetelah dikunci, harga tidak akan berubah meski harga di daftar produk diupdate.'
                )) return;
            const btnLock = document.getElementById('btnLock');
            const origHtml = btnLock ? btnLock.innerHTML : null;
            if (btnLock) {
                btnLock.disabled = true;
                btnLock.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            }

            fetch(`/nota/${notaId}/toggle-lock`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(r => r.json())
                .then(data => {
                    isLocked = data.is_locked;
                    updateLockUI(isLocked);
                    if (btnLock) {
                        btnLock.disabled = false;
                        btnLock.innerHTML = origHtml;
                        updateLockUI(isLocked);
                    }
                    alert(data.message);
                })
                .catch(err => {
                    console.error(err);
                    alert('Terjadi kesalahan: ' + err.message);
                    if (btnLock) {
                        btnLock.disabled = false;
                        if (origHtml) btnLock.innerHTML = origHtml;
                    }
                });
        }

        /* ======== PROFIT INSIGHT ======== */
        function profitCheckboxChanged(chk) {
            const checkbox = chk || document.getElementById('profitCheckbox');
            if (!checkbox) return;
            const label = document.getElementById('profitToggleLabel');
            const txt = document.getElementById('btnProfitText');
            const icon = document.getElementById('profitCheckboxIcon');
            const prevChecked = checkbox.checked;

            // set loading state
            checkbox.disabled = true;
            if (label) label.classList.add('loading');
            const origIconClass = icon ? icon.className : null;
            if (icon) icon.className = 'fas fa-spinner fa-spin';

            fetch(`/nota/${notaId}/toggle-profit`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(r => r.json())
                .then(data => {
                    checkbox.checked = !!data.profit_insight;
                    if (txt) txt.textContent = data.profit_insight ? 'Included in Profit' : 'Excluded from Profit';
                    if (icon && origIconClass) icon.className = origIconClass;
                    if (label) label.classList.remove('loading');
                    checkbox.disabled = false;
                })
                .catch(err => {
                    console.error(err);
                    // revert UI state on error
                    checkbox.checked = !prevChecked;
                    if (icon && origIconClass) icon.className = origIconClass;
                    if (label) label.classList.remove('loading');
                    checkbox.disabled = false;
                    alert('Terjadi kesalahan: ' + err.message);
                });
        }

        /* ======== PF COLUMN ======== */
        function togglePFColumn() {
            const pfCells = document.querySelectorAll('.pf-column');
            const toggleBtn = document.getElementById('pf-toggle-text');
            const icon = document.getElementById('pfToggleIcon');
            if (pfVisible) {
                pfCells.forEach(c => c.classList.add('hidden'));
                if (toggleBtn) toggleBtn.textContent = 'Tampilkan PF';
                if (icon) {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
                pfVisible = false;
            } else {
                pfCells.forEach(c => c.classList.remove('hidden'));
                if (toggleBtn) toggleBtn.textContent = 'Sembunyikan PF';
                if (icon) {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
                pfVisible = true;
            }
        }

        /* ======== PROFIT PANEL ======== */
        function toggleProfitPanel() {
            const profitInfo = document.getElementById('profitInfo');
            const toggleText = document.getElementById('profit-toggle-text');
            const btnToggle = document.getElementById('btnProfitToggle');
            if (profitInfo.classList.contains('show')) {
                profitInfo.classList.remove('show');
                if (toggleText) toggleText.textContent = 'Tampilkan Profit';
                if (btnToggle) btnToggle.setAttribute('aria-expanded', 'false');
            } else {
                profitInfo.classList.add('show');
                if (toggleText) toggleText.textContent = 'Sembunyikan Profit';
                if (btnToggle) btnToggle.setAttribute('aria-expanded', 'true');
            }
        }

        /* ======== PRINT / PDF ======== */
        function sanitizeForFilename(str) {
            return String(str || '').normalize('NFKD').replace(/[\u0300-\u036F]/g, '').replace(/[^a-zA-Z0-9\-_. ]+/g, '-')
                .replace(/\s+/g, '-').replace(/-+/g, '-').replace(/^[-_.]+|[-_.]+$/g, '').toLowerCase();
        }

        function formatDateForFilename(d) {
            try {
                const dt = new Date(d);
                if (!isNaN(dt)) {
                    return `${dt.getFullYear()}${String(dt.getMonth()+1).padStart(2,'0')}${String(dt.getDate()).padStart(2,'0')}`;
                }
            } catch (e) {}
            return String(d).replace(/[^0-9]/g, '');
        }

        function printNota() {
            const pfPart = pfVisible ? 'pf' : 'original';
            const datePart = formatDateForFilename(notaTanggal);
            const base = `nota-${sanitizeForFilename(notaNo)}-${sanitizeForFilename(notaTuan)}-${datePart}-${pfPart}`;
            const prevTitle = document.title || '';
            document.title = base;

            function restoreTitle() {
                document.title = prevTitle;
                window.removeEventListener('afterprint', restoreTitle);
            }
            window.addEventListener('afterprint', restoreTitle);
            window.print();
        }
    </script>
@endpush

@extends('layouts.app')

@section('title', 'Daftar Barang Saya')

@push('styles')
    <style>
        /* ── Page layout ── */
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

        /* ── Tabs ── */
        .page-tabs {
            display: flex;
            gap: 0;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 22px;
        }

        .page-tab-btn {
            padding: 10px 22px;
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            cursor: pointer;
            font-size: .95rem;
            font-weight: 600;
            color: #64748b;
            transition: all .2s;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .page-tab-btn.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }

        .page-tab-content {
            display: none;
        }

        .page-tab-content.active {
            display: block;
        }

        /* ── Stats cards ── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 22px;
        }

        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 18px 20px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .stat-card .ico {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        .stat-card.mine .ico {
            background: #ede9fe;
            color: #7c3aed;
        }

        .stat-card.glob .ico {
            background: #e0f2fe;
            color: #0284c7;
        }

        .stat-card .stat-info p {
            margin: 0;
            font-size: .8rem;
            color: #64748b;
        }

        .stat-card .stat-info strong {
            font-size: 1.4rem;
            color: #1e293b;
        }

        /* ── Alert banners ── */
        .alert {
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: .92rem;
        }

        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border-left: 4px solid #22c55e;
        }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        /* ── Search bar ── */
        .search-card {
            background: #fff;
            border-radius: 10px;
            padding: 12px 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
            margin-bottom: 18px;
        }

        .search-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .search-form input,
        .search-form select {
            padding: 9px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 7px;
            font-size: .92rem;
        }

        .search-form input {
            flex: 1;
            min-width: 160px;
        }

        /* ── Buttons ── */
        .btn {
            padding: 9px 16px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: .88rem;
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

        .btn-success {
            background: #22c55e;
            color: #fff;
        }

        .btn-success:hover {
            background: #16a34a;
            color: #fff;
        }

        .btn-danger {
            background: #ef4444;
            color: #fff;
        }

        .btn-danger:hover {
            background: #dc2626;
            color: #fff;
        }

        .btn-sm {
            padding: 6px 11px;
            font-size: .82rem;
        }

        .btn-outline {
            background: #fff;
            border: 1px solid #667eea;
            color: #667eea;
        }

        .btn-outline:hover {
            background: #667eea;
            color: #fff;
        }

        /* ── Data table ── */
        .data-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .data-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 20px;
            border-bottom: 1px solid #f1f5f9;
            flex-wrap: wrap;
            gap: 10px;
        }

        .data-card-header h3 {
            margin: 0;
            font-size: 1rem;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: .78rem;
            font-weight: 700;
        }

        .badge-purple {
            background: #ede9fe;
            color: #7c3aed;
        }

        .badge-blue {
            background: #e0f2fe;
            color: #0284c7;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            padding: 11px 14px;
            text-align: left;
            font-size: .82rem;
            font-weight: 700;
            letter-spacing: .04em;
            white-space: nowrap;
        }

        td {
            padding: 10px 14px;
            border-bottom: 1px solid #f8fafc;
            font-size: .9rem;
            color: #334155;
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: #fafafa;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 2.4rem;
            margin-bottom: 12px;
            display: block;
        }

        .empty-state p {
            margin: 0;
            font-size: .95rem;
        }

        /* ── Copy modal ── */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            z-index: 9000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-overlay.open {
            display: flex;
        }

        .modal-box {
            background: #fff;
            border-radius: 14px;
            padding: 28px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .18);
        }

        .modal-box h3 {
            margin: 0 0 6px;
            font-size: 1.1rem;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .modal-box p.modal-sub {
            margin: 0 0 18px;
            color: #64748b;
            font-size: .88rem;
        }

        .modal-label {
            display: block;
            font-size: .8rem;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
            color: #475569;
            margin-bottom: 6px;
        }

        .modal-input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: .92rem;
            box-sizing: border-box;
        }

        .modal-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, .15);
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            margin-top: 18px;
        }

        .modal-actions .btn {
            flex: 1;
            justify-content: center;
        }

        /* ── Add form card ── */
        .form-card {
            background: #fff;
            border-radius: 12px;
            padding: 26px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
            margin-bottom: 24px;
        }

        .form-card .section-title {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            border-left: 4px solid #667eea;
            padding-left: 12px;
            margin: 0 0 20px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
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
        .form-group select,
        .form-group textarea {
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: .92rem;
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
            margin-top: 20px;
        }

        .form-actions .btn {
            padding: 11px 22px;
            font-size: .92rem;
        }

        @media (max-width: 640px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .page-header-bar {
                flex-direction: column;
                align-items: flex-start;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container">

        <!-- Header -->
        <div class="page-header-bar">
            <div>
                <h1><i class="fas fa-box-open"></i> Daftar Barang Saya</h1>
                <p>Kelola daftar barang dan harga satuan untuk nota Anda</p>
            </div>
            <a href="{{ route('nota.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-error"><i class="fas fa-times-circle"></i> {{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    @foreach ($errors->all() as $err)
                        <div>{{ $err }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Stats -->
        <div class="stats-row">
            <div class="stat-card mine">
                <div class="ico"><i class="fas fa-box"></i></div>
                <div class="stat-info">
                    <p>Barang Saya</p>
                    <strong>{{ $my_barang->count() }}</strong>
                </div>
            </div>
            <div class="stat-card glob">
                <div class="ico"><i class="fas fa-database"></i></div>
                <div class="stat-info">
                    <p>Daftar Global</p>
                    <strong>{{ $global_barang->count() }}</strong>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="page-tabs">
            <button class="page-tab-btn active" data-tab="my-list"><i class="fas fa-box"></i> Barang Saya</button>
            <button class="page-tab-btn" data-tab="global-list"><i class="fas fa-database"></i> Salin dari Admin</button>
            <button class="page-tab-btn" data-tab="add-new"><i class="fas fa-plus"></i> Tambah Baru</button>
        </div>

        <!-- Search (shared) -->
        <div class="search-card">
            <form class="search-form" method="GET" action="{{ route('barang-saya.index') }}">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama barang...">
                <select name="kategori">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategori_list as $kat)
                        <option value="{{ $kat }}" {{ $kategori == $kat ? 'selected' : '' }}>{{ $kat }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Cari</button>
                @if ($search || $kategori)
                    <a href="{{ route('barang-saya.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i>
                        Reset</a>
                @endif
            </form>
        </div>

        {{-- ═══════════════════════════════════════════════════════ --}}
        {{-- TAB 1: My barang --}}
        {{-- ═══════════════════════════════════════════════════════ --}}
        <div class="page-tab-content active" id="my-list">
            <div class="data-card">
                <div class="data-card-header">
                    <h3>
                        <i class="fas fa-box" style="color:#7c3aed"></i>
                        Barang Saya
                        <span class="section-badge badge-purple">{{ $my_barang->count() }} item</span>
                    </h3>
                </div>

                @if ($my_barang->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-box-open"></i>
                        <p>Belum ada barang dalam daftar Anda.</p>
                        <p style="margin-top:8px; font-size:.85rem;">Tambah baru dari tab <strong>Tambah Baru</strong> atau
                            salin dari daftar admin.</p>
                    </div>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Satuan</th>
                                <th>Harga Satuan</th>
                                <th>Keuntungan/Unit</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($my_barang as $i => $b)
                                <tr>
                                    <td style="color:#94a3b8; font-size:.8rem;">{{ $i + 1 }}</td>
                                    <td><strong>{{ $b->uraian }}</strong></td>
                                    <td><span class="section-badge badge-purple">{{ $b->kategori }}</span></td>
                                    <td>{{ $b->satuan }}</td>
                                    <td>Rp {{ number_format($b->harga_satuan, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($b->profit_per_unit)
                                            <span style="color:#16a34a; font-weight:600;">Rp
                                                {{ number_format($b->profit_per_unit, 0, ',', '.') }}</span>
                                        @else
                                            <span style="color:#94a3b8;">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display:flex; gap:6px; flex-wrap:wrap;">
                                            <a href="{{ route('barang-saya.edit', $b->id) }}"
                                                class="btn btn-sm btn-secondary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('barang-saya.destroy', $b->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus barang ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i
                                                        class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════ --}}
        {{-- TAB 2: Copy from admin --}}
        {{-- ═══════════════════════════════════════════════════════ --}}
        <div class="page-tab-content" id="global-list">
            <div class="data-card">
                <div class="data-card-header">
                    <h3>
                        <i class="fas fa-database" style="color:#0284c7"></i>
                        Daftar Harga Global (Admin)
                        <span class="section-badge badge-blue">{{ $global_barang->count() }} item</span>
                    </h3>
                    <span style="font-size:.82rem; color:#64748b;">Klik <strong>Salin</strong> untuk menambah barang ke
                        daftar Anda dengan keuntungan kustom</span>
                </div>

                @if ($global_barang->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-database"></i>
                        <p>Belum ada daftar harga global dari admin.</p>
                    </div>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Satuan</th>
                                <th>Harga Satuan</th>
                                <th>Profit Default</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($global_barang as $i => $b)
                                @php $alreadyCopied = in_array(strtolower($b->uraian), $my_uraian); @endphp
                                <tr>
                                    <td style="color:#94a3b8; font-size:.8rem;">{{ $i + 1 }}</td>
                                    <td><strong>{{ $b->uraian }}</strong></td>
                                    <td><span class="section-badge badge-blue">{{ $b->kategori }}</span></td>
                                    <td>{{ $b->satuan }}</td>
                                    <td>Rp {{ number_format($b->harga_satuan, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($b->profit_per_unit)
                                            <span style="color:#64748b;">Rp
                                                {{ number_format($b->profit_per_unit, 0, ',', '.') }}</span>
                                        @else
                                            <span style="color:#94a3b8;">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($alreadyCopied)
                                            <span class="section-badge badge-purple"><i class="fas fa-check"></i> Sudah
                                                ada</span>
                                        @else
                                            <span style="color:#94a3b8; font-size:.82rem;">Belum disalin</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (!$alreadyCopied)
                                            <button class="btn btn-sm btn-outline"
                                                onclick="openCopyModal({{ $b->id }}, '{{ addslashes($b->uraian) }}', {{ $b->profit_per_unit ?? 0 }})">
                                                <i class="fas fa-copy"></i> Salin
                                            </button>
                                        @else
                                            <span class="btn btn-sm btn-secondary" style="opacity:.5; cursor:not-allowed;">
                                                <i class="fas fa-check"></i> Tersalin
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════ --}}
        {{-- TAB 3: Add new barang --}}
        {{-- ═══════════════════════════════════════════════════════ --}}
        <div class="page-tab-content" id="add-new">
            <div class="form-card">
                <div class="section-title"><i class="fas fa-plus-circle" style="color:#667eea; margin-right:8px;"></i>
                    Tambah Barang Baru</div>

                <form action="{{ route('barang-saya.store') }}" method="POST">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group full">
                            <label for="uraian">Nama Barang *</label>
                            <input type="text" id="uraian" name="uraian" value="{{ old('uraian') }}"
                                placeholder="Contoh: Beras Premium" required>
                        </div>

                        <div class="form-group">
                            <label for="kategori">Kategori *</label>
                            <select id="kategori" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori_list as $kat)
                                    <option value="{{ $kat }}" {{ old('kategori') == $kat ? 'selected' : '' }}>
                                        {{ $kat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="satuan">Satuan *</label>
                            <select id="satuan" name="satuan" required>
                                <option value="">Pilih Satuan</option>
                                @foreach (['Kg', 'Gram', 'Liter', 'Ml', 'Pcs', 'Pack', 'Dus', 'Karung', 'Ball'] as $s)
                                    <option value="{{ $s }}" {{ old('satuan') == $s ? 'selected' : '' }}>
                                        {{ $s }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="nilai_satuan">Nilai Satuan *</label>
                            <input type="number" step="0.01" id="nilai_satuan" name="nilai_satuan"
                                value="{{ old('nilai_satuan', 1) }}" required>
                            <span class="hint">Contoh: 1 Kg=1 · 500 Gram=0.5</span>
                        </div>

                        <div class="form-group">
                            <label for="harga_satuan">Harga Satuan (Rp) *</label>
                            <input type="number" id="harga_satuan" name="harga_satuan"
                                value="{{ old('harga_satuan') }}" required placeholder="0">
                        </div>

                        <div class="form-group">
                            <label for="profit_per_unit">Keuntungan per Satuan (Rp)</label>
                            <input type="number" id="profit_per_unit" name="profit_per_unit"
                                value="{{ old('profit_per_unit', 0) }}" min="0" placeholder="0">
                            <span class="hint">Opsional — keuntungan tetap per unit dalam nota</span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Barang</button>
                        <a href="{{ route('barang-saya.index') }}" class="btn btn-secondary"><i
                                class="fas fa-times"></i> Batal</a>
                    </div>
                </form>
            </div>
        </div>

    </div>

    {{-- Copy modal --}}
    <div class="modal-overlay" id="copyModal">
        <div class="modal-box">
            <h3><i class="fas fa-copy" style="color:#667eea"></i> Salin Barang ke Daftar Saya</h3>
            <p class="modal-sub" id="copyModalSub">Set keuntungan per satuan untuk barang ini</p>

            <form action="{{ route('barang-saya.copy') }}" method="POST" id="copyForm">
                @csrf
                <input type="hidden" name="barang_id" id="copyBarangId">

                <div class="form-group" style="margin-bottom:16px;">
                    <label class="modal-label">Nama Barang</label>
                    <input type="text" class="modal-input" id="copyBarangNama" readonly
                        style="background:#f8fafc; color:#64748b;">
                </div>

                <div class="form-group">
                    <label class="modal-label" for="copyProfit">Keuntungan per Satuan (Rp)</label>
                    <input type="number" class="modal-input" id="copyProfit" name="profit_per_unit" min="0"
                        placeholder="0">
                    <div style="font-size:.78rem; color:#94a3b8; margin-top:5px;">
                        Biarkan 0 jika ingin menggunakan profit default barang
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-copy"></i> Salin ke Daftar
                        Saya</button>
                    <button type="button" class="btn btn-secondary" onclick="closeCopyModal()"><i
                            class="fas fa-times"></i> Batal</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab switching
            const tabBtns = document.querySelectorAll('.page-tab-btn');
            const tabContents = document.querySelectorAll('.page-tab-content');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    tabBtns.forEach(b => b.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    document.getElementById(this.dataset.tab).classList.add('active');
                });
            });

            // If there were validation errors (old input), switch to add-new tab
            @if ($errors->any() || old('uraian'))
                document.querySelector('[data-tab="add-new"]').click();
            @endif
        });

        function openCopyModal(id, nama, defaultProfit) {
            document.getElementById('copyBarangId').value = id;
            document.getElementById('copyBarangNama').value = nama;
            document.getElementById('copyProfit').value = defaultProfit || 0;
            document.getElementById('copyModalSub').textContent = 'Set keuntungan per satuan untuk: ' + nama;
            document.getElementById('copyModal').classList.add('open');
        }

        function closeCopyModal() {
            document.getElementById('copyModal').classList.remove('open');
        }

        // Close modal on backdrop click
        document.getElementById('copyModal').addEventListener('click', function(e) {
            if (e.target === this) closeCopyModal();
        });
    </script>
@endpush

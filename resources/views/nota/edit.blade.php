@extends('layouts.app')

@section('title', 'Edit Nota')

@push('styles')
    <style>
        /* ===== PAGE LAYOUT ===== */
        .form-page {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* ===== CARD ===== */
        .form-card {
            background: #fff;
            padding: 24px 28px;
            border-radius: 14px;
            box-shadow: 0 4px 20px rgba(12, 33, 55, 0.07);
        }

        /* ===== HEADER ===== */
        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            padding-bottom: 16px;
            border-bottom: 2px solid #f1f5f9;
        }

        .form-header h2 {
            margin: 0;
            font-size: 1.15rem;
            font-weight: 800;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-header h2 i {
            color: #667eea;
        }

        .form-header-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* ===== SECTION TITLES ===== */
        .section-title {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #94a3b8;
            margin: 20px 0 12px;
            padding-left: 10px;
            border-left: 3px solid #667eea;
        }

        /* ===== FORM GRID ===== */
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 14px;
            margin-bottom: 14px;
        }

        .form-row.full {
            grid-template-columns: 1fr;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .form-group label {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.35px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px 13px;
            font-size: 0.9rem;
            border-radius: 8px;
            border: 1.5px solid #e2e8f0;
            background: #f9fafb;
            color: #1e293b;
            transition: border-color .15s, box-shadow .15s, background .15s;
            box-sizing: border-box;
            min-height: 42px;
        }

        .form-group textarea {
            min-height: 80px;
            resize: vertical;
        }

        .form-group input::placeholder,
        .search-box input::placeholder {
            color: #b0bec5;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus,
        .search-box input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.12);
            background: #fff;
        }

        .form-group input[readonly],
        .form-group textarea[readonly] {
            background: #f1f5f9;
            color: #64748b;
            cursor: default;
        }

        /* ===== ALERT ===== */
        .nota-alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .nota-alert.success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .nota-alert.danger {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .nota-alert ul {
            margin: 6px 0 0 16px;
            padding: 0;
            font-weight: 500;
        }

        /* ===== ITEMS SECTION ===== */
        .items-section {
            margin-top: 20px;
            padding: 18px 20px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1.5px solid #e9eef5;
        }

        .items-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }

        .items-toolbar-title {
            font-size: 13px;
            font-weight: 800;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .items-toolbar-title i {
            color: #667eea;
        }

        .items-toolbar-actions {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        /* Search box */
        .search-box {
            position: relative;
        }

        .search-box input {
            width: 280px;
            padding: 9px 13px;
            border-radius: 8px;
            border: 1.5px solid #e2e8f0;
            font-size: 0.88rem;
            background: #fff;
            transition: border-color .15s, box-shadow .15s;
        }

        .search-box input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.12);
        }

        .search-results {
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            right: 0;
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            box-shadow: 0 8px 30px rgba(2, 6, 23, 0.09);
            z-index: 1200;
            max-height: 260px;
            overflow: auto;
            display: none;
        }

        .search-item {
            padding: 9px 13px;
            cursor: pointer;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.88rem;
            font-weight: 600;
            color: #334155;
        }

        .search-item:hover,
        .search-item.active {
            background: #eef2ff;
            color: #4f46e5;
        }

        .search-item.add-new {
            color: #0284c7;
            font-weight: 700;
        }

        .search-item:last-child {
            border-bottom: none;
        }

        /* ===== TABLE ===== */
        .table-responsive {
            overflow-x: auto;
            border-radius: 10px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 520px;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
        }

        .items-table thead tr {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .items-table th {
            padding: 10px 12px;
            font-size: 0.78rem;
            font-weight: 800;
            color: #fff;
            text-align: left;
            white-space: nowrap;
        }

        .items-table td {
            padding: 8px 10px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        .items-table tbody tr:hover {
            background: #fafbff;
        }

        .items-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* item inputs */
        .items-table .barang-select {
            width: 100%;
            min-width: 200px;
            max-width: 480px;
            padding: 8px 10px;
            font-size: 0.88rem;
            border-radius: 7px;
            border: 1.5px solid #e2e8f0;
            background: #f9fafb;
            min-height: 38px;
        }

        .items-table .satuan-select {
            width: 90px;
            padding: 8px 10px;
            font-size: 0.88rem;
            border-radius: 7px;
            border: 1.5px solid #e2e8f0;
            background: #f9fafb;
            min-height: 38px;
        }

        .items-table .qty {
            width: 80px;
            padding: 8px 10px;
            font-size: 0.88rem;
            border-radius: 7px;
            border: 1.5px solid #e2e8f0;
            background: #f9fafb;
            min-height: 38px;
            text-align: right;
        }

        .items-table .profit-per-unit {
            width: 110px;
            padding: 8px 10px;
            font-size: 0.88rem;
            border-radius: 7px;
            border: 1.5px solid #e2e8f0;
            background: #f9fafb;
            min-height: 38px;
            text-align: right;
        }

        .items-table .harga {
            width: 130px;
            padding: 8px 10px;
            font-size: 0.88rem;
            border-radius: 7px;
            border: 1.5px solid #e2e8f0;
            background: #f9fafb;
            min-height: 38px;
            text-align: right;
        }

        .items-table .barang-select:focus,
        .items-table .satuan-select:focus,
        .items-table .qty:focus,
        .items-table .profit-per-unit:focus,
        .items-table .harga:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: #fff;
        }

        .items-table .subtotal {
            font-weight: 800;
            white-space: nowrap;
            text-align: right;
            color: #0f172a;
            font-size: 0.88rem;
        }

        .items-table .btn-remove {
            background: #fef2f2;
            color: #dc2626;
            border: 1.5px solid #fecaca;
            padding: 5px 8px;
            border-radius: 7px;
            cursor: pointer;
            font-size: 12px;
            transition: background .15s;
        }

        .items-table .btn-remove:hover {
            background: #fee2e2;
        }

        /* error row */
        tr.missing-uraian td {
            background: #fff5f5;
        }

        tr.missing-uraian .barang-select {
            border-color: #f87171;
            box-shadow: 0 0 0 2px rgba(248, 113, 113, 0.2);
        }

        /* ===== TOTAL BAR ===== */
        .items-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 14px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .update-harga-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #475569;
            cursor: pointer;
        }

        .update-harga-label input[type=checkbox] {
            width: 16px;
            height: 16px;
            accent-color: #667eea;
        }

        .total-section {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .total-label {
            font-size: 13px;
            font-weight: 700;
            color: #64748b;
        }

        .total-amount {
            font-size: 1.2rem;
            font-weight: 900;
            color: #1e293b;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* ===== FORM ACTIONS (sticky) ===== */
        .form-actions {
            position: sticky;
            bottom: 0;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(8px);
            padding: 12px 0;
            border-top: 1.5px solid #e2e8f0;
            margin-top: 16px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            z-index: 100;
        }

        /* ===== MODAL ===== */
        .modal-overlay {
            background: rgba(0, 0, 0, 0.45);
        }

        .modal-dialog {
            border-radius: 14px;
            padding: 22px 26px;
            box-shadow: 0 20px 60px rgba(2, 6, 23, 0.2);
        }

        .modal-input {
            width: 100%;
            padding: 10px 13px;
            border-radius: 8px;
            border: 1.5px solid #e2e8f0;
            font-size: 0.9rem;
            background: #f9fafb;
            box-sizing: border-box;
            transition: border-color .15s, box-shadow .15s;
        }

        .modal-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.12);
            background: #fff;
        }

        .modal-label {
            display: block;
            margin-bottom: 6px;
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.35px;
        }

        /* ===== DRAG HANDLE ===== */
        .drag-handle-item {
            color: #cbd5e1;
            cursor: grab;
            font-size: 14px;
        }

        .drag-handle-item:active {
            cursor: grabbing;
        }

        tr.dragging {
            opacity: .45;
        }

        tr.drag-over {
            outline: 2px dashed #667eea;
            background: #eef2ff;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 720px) {
            .form-card {
                padding: 16px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .search-box input {
                width: 100%;
            }

            .items-toolbar {
                flex-direction: column;
            }

            .items-toolbar-actions {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container">
        {{-- Success/Error Messages --}}
        @if (session('status'))
            <div class="nota-alert success">
                <i class="fas fa-check-circle"></i>
                @if (session('status') == 'updated')
                    Nota berhasil diperbarui!
                @elseif(session('status') == 'item_added')
                    Item berhasil ditambahkan!
                @elseif(session('status') == 'item_deleted')
                    Item berhasil dihapus!
                @endif
            </div>
        @endif

        @if ($errors->any())
            <div class="nota-alert danger">
                <div>
                    <i class="fas fa-exclamation-triangle"></i> <strong>Terdapat kesalahan:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="form-card">
            <div class="form-header">
                <h2><i class="fas fa-edit"></i> Edit Nota {{ $nota->no }}</h2>
                <div class="form-header-actions">
                    <a href="{{ route('nota.show', $nota->id) }}" class="btn btn-secondary"><i class="fas fa-eye"></i>
                        Lihat</a>
                    <a href="{{ route('nota.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i>
                        Kembali</a>
                </div>
            </div>

            <form action="{{ route('nota.update', $nota->id) }}" method="POST" id="notaForm">
                @csrf
                @method('PUT')

                <div class="section-title">Informasi Nota</div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="no">No Nota</label>
                        <input type="text" id="no" name="no" value="{{ old('no', $nota->no) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Tanggal <span style="color:#ef4444">*</span></label>
                        <input type="date" id="tanggal" name="tanggal"
                            value="{{ old('tanggal', $nota->tanggal ? $nota->tanggal->format('Y-m-d') : '') }}" required>
                    </div>
                </div>

                <div class="section-title">Informasi Toko / Pembeli</div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="toko_selection">Pilih Toko</label>
                        <select id="toko_selection" class="toko-select">
                            <option value="">-- Pilih Toko --</option>
                            @foreach ($toko_list as $toko)
                                <option value="{{ $toko->id }}" data-nama="{{ $toko->nama_toko }}"
                                    data-alamat="{{ $toko->alamat }}"
                                    {{ old('toko_id', $nota->toko_id) == $toko->id ? 'selected' : '' }}>
                                    {{ $toko->nama_toko }}
                                </option>
                            @endforeach
                            <option value="manual"
                                {{ !old('toko_id', $nota->toko_id) && old('nama_toko_manual', $nota->nama_toko_manual) ? 'selected' : '' }}>
                                + Tulis Manual</option>
                        </select>
                        <input type="hidden" id="toko_id" name="toko_id" value="{{ old('toko_id', $nota->toko_id) }}">
                    </div>
                    <div class="form-group">
                        <label for="nama_toko">Nama Toko <span style="color:#ef4444">*</span></label>
                        <input type="text" id="nama_toko" name="nama_toko" placeholder="Masukkan nama toko"
                            value="{{ old('nama_toko', $nota->nama_toko) }}" required>
                        <input type="hidden" id="nama_toko_manual" name="nama_toko_manual"
                            value="{{ old('nama_toko_manual', $nota->nama_toko_manual) }}">
                    </div>
                </div>

                <div class="form-row full">
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea id="alamat" name="alamat">{{ old('alamat', $nota->alamat) }}</textarea>
                        <input type="hidden" id="alamat_toko_manual" name="alamat_toko_manual"
                            value="{{ old('alamat_toko_manual', $nota->alamat_toko_manual) }}">
                    </div>
                </div>

                <div class="items-section">
                    <div class="items-toolbar">
                        <div class="items-toolbar-title"><i class="fas fa-list"></i> Daftar Barang</div>
                        <div class="items-toolbar-actions">
                            <div class="search-box">
                                <input type="search" id="itemSearch" placeholder="Cari barang dan tekan Enter…"
                                    autocomplete="off">
                                <div id="searchResults" class="search-results"></div>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="addRow()">
                                <i class="fas fa-plus"></i> Tambah Baris
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="items-table" id="itemsTable">
                            <thead>
                                <tr>
                                    <th style="width:36px">No</th>
                                    <th style="width:28px"></th>
                                    <th>Barang</th>
                                    <th>Satuan</th>
                                    <th>Qty</th>
                                    <th>Keuntungan/Sat.</th>
                                    <th>Harga Satuan</th>
                                    <th>Subtotal</th>
                                    <th style="width:36px"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody"></tbody>
                        </table>
                    </div>

                    <div class="items-footer">
                        <div>
                            <label class="update-harga-label">
                                <input type="checkbox" name="update_harga" value="1">
                                Perbarui harga master barang
                            </label>
                        </div>
                        <div class="total-section">
                            <span class="total-label">Total Nota</span>
                            <span class="total-amount" id="totalAmount">Rp 0</span>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('nota.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Nota</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Barang Modal -->
    <div id="addBarangModal" class="modal-overlay"
        style="display:none; position:fixed; inset:0;align-items:center;justify-content:center;z-index:1500;">
        <div class="modal-dialog" style="width:640px;max-width:96%;background:#fff;">
            <button class="modal-close" aria-label="Tutup"
                onclick="document.getElementById('addBarangModal').style.display='none'"
                style="float:right;border:none;background:transparent;font-size:22px;cursor:pointer;">&times;</button>
            <h3
                style="margin:0 0 16px;font-size:1rem;font-weight:800;color:#1e293b;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-plus-circle" style="color:#22c55e"></i> Tambah Barang ke Daftar Harga
            </h3>
            <form id="addBarangForm" onsubmit="event.preventDefault(); submitNewBarang();"
                style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div style="grid-column:1 / -1;">
                    <label class="modal-label">Uraian / Nama Barang</label>
                    <input id="newUraian" name="uraian" type="text" placeholder="Nama barang" class="modal-input"
                        required>
                    <div id="uraianError" style="color:#dc2626;display:none;margin-top:6px;font-size:0.85rem"></div>
                </div>
                <div>
                    <label class="modal-label">Satuan</label>
                    <select id="newSatuan" name="satuan" class="modal-input">
                        @foreach ($satuan_list as $s)
                            <option value="{{ $s->nama_satuan }}">{{ $s->nama_satuan }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="modal-label">Kategori</label>
                    <select id="newKategori" name="kategori" class="modal-input">
                        @foreach ($kategori_list as $kat)
                            <option value="{{ $kat }}">{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="modal-label">Harga Satuan (Rp)</label>
                    <input id="newHarga" name="harga_satuan" type="number" step="1" value="0"
                        class="modal-input" style="text-align:right;">
                </div>
                <div>
                    <label class="modal-label">Keuntungan / Satuan (Rp)</label>
                    <input id="newProfitPerUnit" name="profit_per_unit" type="number" step="1" value="0"
                        class="modal-input" style="text-align:right;" placeholder="0 = auto-hitung">
                </div>
                <div style="grid-column:1 / -1;display:flex;justify-content:flex-end;gap:10px;margin-top:4px;">
                    <button type="button" class="btn btn-secondary"
                        onclick="document.getElementById('addBarangModal').style.display='none'">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan &amp; Tambah ke Nota</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        @php
            $initialItems = $nota->items
                ->map(function ($it) {
                    return [
                        'uraian' => $it->uraian,
                        'qty' => $it->qty,
                        'harga' => $it->harga_satuan,
                        'satuan' => $it->satuan,
                        'profit_per_unit' => $it->profit_per_unit ?? 0,
                    ];
                })
                ->values()
                ->toArray();
        @endphp
        <script>
            const barangList = @json($barang_list);
            const satuanList = {!! json_encode($satuan_list->map->nama_satuan) !!};
            const initialItems = @json($initialItems);
            // All authenticated users can add to their own barang list
            window.canCreateMasterBarang = true;
            window.barangStoreUrl = '{{ route('nota.storeBarang') }}';

            // Toko selection handler
            document.addEventListener('DOMContentLoaded', function() {
                const tokoSelect = document.getElementById('toko_selection');

                if (tokoSelect) {
                    tokoSelect.addEventListener('change', function() {
                        const selectEl = this;
                        const tokoId = selectEl.value;
                        const tokoIdInput = document.getElementById('toko_id');
                        const namaToko = document.getElementById('nama_toko');
                        const alamat = document.getElementById('alamat');
                        const namaTokoManual = document.getElementById('nama_toko_manual');
                        const alamatTokoManual = document.getElementById('alamat_toko_manual');

                        if (tokoId === 'manual') {
                            // Manual entry
                            tokoIdInput.value = '';
                            namaToko.value = '';
                            alamat.value = '';
                            namaToko.readOnly = false;
                            alamat.readOnly = false;
                            namaToko.focus();
                        } else if (tokoId) {
                            // Selected from list
                            const selectedOption = selectEl.options[selectEl.selectedIndex];
                            const nama = selectedOption.getAttribute('data-nama') || '';
                            const alamatData = selectedOption.getAttribute('data-alamat') || '';

                            tokoIdInput.value = tokoId;
                            namaToko.value = nama;
                            alamat.value = alamatData;
                            namaToko.readOnly = true;
                            alamat.readOnly = true;
                            namaTokoManual.value = '';
                            alamatTokoManual.value = '';
                        } else {
                            // No selection
                            tokoIdInput.value = '';
                            namaToko.value = '';
                            alamat.value = '';
                            namaToko.readOnly = false;
                            alamat.readOnly = false;
                        }
                    });

                    // Set initial readonly state
                    const currentTokoId = document.getElementById('toko_id').value;
                    if (currentTokoId) {
                        document.getElementById('nama_toko').readOnly = true;
                        document.getElementById('alamat').readOnly = true;
                    }
                }

                // Capture manual entries before submit
                const notaForm = document.getElementById('notaForm');
                if (notaForm) {
                    notaForm.addEventListener('submit', function(e) {
                        const tokoId = document.getElementById('toko_id').value;
                        if (!tokoId) {
                            // Manual entry - save to manual fields
                            document.getElementById('nama_toko_manual').value = document.getElementById(
                                'nama_toko').value;
                            document.getElementById('alamat_toko_manual').value = document.getElementById(
                                'alamat').value;
                        }
                    });
                }
            });
        </script>
        <script src="/js/nota-items.js"></script>
    @endpush
@endsection

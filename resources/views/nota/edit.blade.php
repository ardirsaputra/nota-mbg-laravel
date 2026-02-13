@extends('layouts.app')

@section('title', 'Edit Nota')

@push('styles')
    <style>
        /* Enhanced input sizing & visual polish for Nota edit - matching create */
        .form-card {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(12, 33, 55, 0.06);
        }

        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        .form-row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .form-row.full {
            flex-direction: column;
        }

        .form-group {
            flex: 1;
            min-width: 180px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #374151;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 14px;
            font-size: 1rem;
            border-radius: 10px;
            border: 1px solid #e6eef6;
            background: #fbfdff;
            line-height: 1.2;
            transition: box-shadow .12s, border-color .12s;
            min-height: 44px;
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-group input::placeholder,
        .search-box input::placeholder {
            color: #9aa6b2;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus,
        .search-box input:focus {
            outline: none;
            border-color: #6b9cff;
            box-shadow: 0 8px 30px rgba(59, 130, 246, 0.08);
            background: #ffffff;
        }

        .items-section {
            padding: 14px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(2, 6, 23, 0.03);
            margin-top: 12px;
        }

        .search-box {
            position: relative;
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-box input {
            width: 100%;
            max-width: 480px;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid #e6eef6;
            font-size: 1rem;
        }

        /* search results */
        .search-results {
            position: absolute;
            top: 46px;
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #e6eef6;
            border-radius: 8px;
            box-shadow: 0 8px 30px rgba(2, 6, 23, 0.06);
            z-index: 1200;
            max-height: 260px;
            overflow: auto;
            display: none;
        }

        .search-item {
            padding: 8px 12px;
            cursor: pointer;
            border-bottom: 1px solid #f5f7fb;
            font-weight: 700;
        }

        .search-item:hover,
        .search-item.active {
            background: #f1f5ff;
        }

        .search-item.add-new {
            color: #0b76a8;
            font-weight: 800;
        }

        .item-form input,
        .item-form select {
            padding: 10px 12px;
            font-size: 0.95rem;
            border-radius: 8px;
            min-height: 44px;
        }

        .item-form select {
            min-width: 160px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 420px;
        }

        .items-table th,
        .items-table td {
            padding: 12px 14px;
            vertical-align: middle;
        }

        /* proportional sizing for item-row controls */
        .items-table .barang-select {
            width: 100%;
            min-width: 220px;
            max-width: 560px;
            padding: 10px 12px;
            font-size: 0.95rem;
            border-radius: 8px;
            border: 1px solid #e6eef6;
            background: #fbfdff;
            min-height: 42px;
        }

        .items-table .satuan-select {
            width: 96px;
            max-width: 120px;
            padding: 10px 12px;
            font-size: 0.95rem;
            border-radius: 8px;
            border: 1px solid #e6eef6;
            background: #fbfdff;
            min-height: 42px;
        }

        .items-table .barang-select:focus,
        .items-table .satuan-select:focus {
            outline: none;
            border-color: #6b9cff;
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.1);
            background: #ffffff;
        }

        .items-table .qty {
            width: 92px;
            padding: 10px 12px;
            font-size: 0.95rem;
            border-radius: 8px;
            border: 1px solid #e6eef6;
            background: #fbfdff;
            min-height: 42px;
        }

        .items-table .harga {
            width: 140px;
            padding: 10px 12px;
            font-size: 0.95rem;
            border-radius: 8px;
            border: 1px solid #e6eef6;
            background: #fbfdff;
            min-height: 42px;
        }

        .items-table .qty:focus,
        .items-table .harga:focus {
            outline: none;
            border-color: #6b9cff;
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.1);
            background: #ffffff;
        }

        .items-table .subtotal {
            font-weight: 700;
            white-space: nowrap;
            text-align: right;
        }

        .items-table .btn-remove {
            padding: 6px 8px;
            border-radius: 6px;
        }

        .items-table input[type=number] {
            text-align: right;
        }

        /* highlight missing uraian */
        tr.missing-uraian td {
            background: #fff5f5;
            border-left: 4px solid #f8d7da;
        }

        tr.missing-uraian .barang-select {
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.06);
        }

        .table-responsive {
            overflow: auto;
        }

        .total-section {
            text-align: right;
            display: flex;
            gap: 16px;
            align-items: center;
            justify-content: flex-end;
        }

        .total-label {
            color: #667;
            font-weight: 600;
        }

        .total-amount {
            font-weight: 800;
            font-size: 1.2rem;
        }

        .form-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            margin-top: 14px;
        }

        @media(max-width:720px) {
            .items-table {
                min-width: 320px;
            }

            .form-row {
                flex-direction: column;
            }

            .form-group {
                min-width: 100%;
            }

            .item-form {
                flex-direction: column;
                align-items: stretch
            }

            .search-box input {
                max-width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container">
        {{-- Success/Error Messages --}}
        @if (session('status'))
            <div class="alert alert-success"
                style="background: #d4edda; color: #155724; padding: 12px; border-radius: 8px; margin-bottom: 16px; border: 1px solid #c3e6cb;">
                @if (session('status') == 'updated')
                    <i class="fas fa-check-circle"></i> Nota berhasil diperbarui!
                @elseif(session('status') == 'item_added')
                    <i class="fas fa-plus-circle"></i> Item berhasil ditambahkan!
                @elseif(session('status') == 'item_deleted')
                    <i class="fas fa-trash"></i> Item berhasil dihapus!
                @endif
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger"
                style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 8px; margin-bottom: 16px; border: 1px solid #f5c6cb;">
                <i class="fas fa-exclamation-triangle"></i> <strong>Terdapat kesalahan:</strong>
                <ul style="margin: 8px 0 0 20px; padding: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-card">
            <div class="form-header">
                <h2><i class="fas fa-edit"></i> Edit Nota {{ $nota->no }}</h2>
                <div style="display:flex;gap:8px;">
                    <a href="{{ route('nota.show', $nota->id) }}" class="btn btn-secondary"><i class="fas fa-eye"></i>
                        Lihat</a>
                    <a href="{{ route('nota.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i>
                        Kembali</a>
                </div>
            </div>

            <form action="{{ route('nota.update', $nota->id) }}" method="POST" id="notaForm">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label for="no">No Nota</label>
                        <input type="text" id="no" name="no" value="{{ old('no', $nota->no) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Tanggal <span style="color: red">*</span></label>
                        <input type="date" id="tanggal" name="tanggal"
                            value="{{ old('tanggal', $nota->tanggal ? $nota->tanggal->format('Y-m-d') : '') }}" required>
                    </div>
                </div>

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
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nama_toko">Nama Toko <span style="color: red">*</span></label>
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
                    <h3>
                        <span>Daftar Barang</span>
                        <div style="display:flex;gap:10px;align-items:center">
                            <div style="position:relative;">
                                <div class="search-box">
                                    <input type="search" id="itemSearch"
                                        placeholder="Cari barang... ketik lalu tekan Enter atau klik hasil"
                                        autocomplete="off">
                                </div>
                                <div id="searchResults" class="search-results"></div>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="addRow()"><i class="fas fa-plus"></i>
                                Tambah Barang</button>
                        </div>
                    </h3>



                    <div class="table-responsive">
                        <table class="items-table" id="itemsTable">
                            <thead>
                                <tr>
                                    <th style="width:40px">No</th>
                                    <th style="width:36px"></th>
                                    <th>Barang</th>
                                    <th>Satuan</th>
                                    <th>Qty</th>
                                    <th style="width:140px">Keuntungan/Satuan (Rp)</th>
                                    <th>Harga Satuan</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody"></tbody>
                        </table>
                    </div>

                    <div
                        style="display:flex;justify-content:space-between;align-items:center;margin-top:12px;flex-wrap:wrap;gap:12px;">
                        <div>
                            <label style="font-weight:600;">
                                <input type="checkbox" name="update_harga" value="1"> Perbarui harga master barang
                            </label>
                        </div>
                        <div class="total-section">
                            <div class="total-label">Total Nota:</div>
                            <div class="total-amount" id="totalAmount">Rp 0</div>
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

    <!-- Add Barang Modal (moved out of nota form to prevent accidental parent-form submit) -->
    <div id="addBarangModal" class="modal-overlay"
        style="display:none; position:fixed; inset:0;align-items:center;justify-content:center;z-index:1500;">
        <div class="modal-dialog"
            style="width:720px;max-width:96%;background:#fff;border-radius:12px;padding:18px;box-shadow:0 20px 60px rgba(2,6,23,0.2);">
            <button class="modal-close" aria-label="Tutup"
                onclick="document.getElementById('addBarangModal').style.display='none'"
                style="float:right;border:none;background:transparent;font-size:22px;">&times;</button>
            <h3 style="margin:0 0 8px;"><i class="fas fa-plus-circle" style="color:#2ecc71;margin-right:8px"></i> Tambah
                Barang Baru</h3>
            <form id="addBarangForm" onsubmit="event.preventDefault(); submitNewBarang();"
                style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:12px;">
                <div style="grid-column:1 / -1;">
                    <label style="display:block;margin-bottom:6px;font-weight:700">Uraian</label>
                    <input id="newUraian" name="uraian" type="text" placeholder="Nama barang"
                        style="width:100%;padding:10px;border-radius:8px;border:1px solid #e6eef6;" required>
                    <div id="uraianError" style="color:#c0392b;display:none;margin-top:6px;font-size:0.9rem"></div>
                </div>

                <div>
                    <label style="display:block;margin-bottom:6px;font-weight:700">Satuan</label>
                    <select id="newSatuan" name="satuan"
                        style="width:100%;padding:10px;border-radius:8px;border:1px solid #e6eef6;">
                        @foreach ($satuan_list as $s)
                            <option value="{{ $s->nama_satuan }}">{{ $s->nama_satuan }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display:block;margin-bottom:6px;font-weight:700">Kategori</label>
                    <input id="newKategori" name="kategori" type="text" value="Umum"
                        placeholder="Kategori (mis. Umum)"
                        style="width:100%;padding:10px;border-radius:8px;border:1px solid #e6eef6;">
                </div>

                <div>
                    <label style="display:block;margin-bottom:6px;font-weight:700">Harga Satuan (Rp)</label>
                    <input id="newHarga" name="harga_satuan" type="number" step="1" value="0"
                        style="width:100%;padding:10px;border-radius:8px;border:1px solid #e6eef6;">
                </div>



                <div style="grid-column:1 / -1;text-align:right;margin-top:6px;">
                    <button type="button" class="btn btn-secondary"
                        onclick="document.getElementById('addBarangModal').style.display='none'">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan dan Tambah ke Nota</button>
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
            const satuanList = @json($satuan_list);
            const initialItems = @json($initialItems);

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

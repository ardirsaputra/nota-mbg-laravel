@extends('layouts.app')

@section('title', 'Daftar Harga Barang Pokok')

@push('styles')
    <style>
        /* Header / actions — match Nota UI */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 30px 0 12px;
            gap: 20px;
            flex-wrap: wrap;
        }

        .page-header h1 {
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.25rem;
        }

        .header-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        /* Small stats cards (same look as Nota) */
        .nota-stats {
            margin-bottom: 18px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 18px;
        }

        .nota-card {
            background: #fff;
            padding: 18px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04);
            display: flex;
            gap: 14px;
            align-items: center;
        }

        .nota-card .icon {
            width: 56px;
            height: 56px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .nota-card.total .icon {
            background: linear-gradient(135deg, #f3e5f5, #d6c0e9);
            color: #9c27b0
        }

        .nota-card.notes .icon {
            background: #e8f3ff;
            color: #2196f3
        }

        /* Search box kept but adapted */
        .search-box {
            margin-bottom: 20px;
            background: white;
            padding: 12px 16px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        }

        .search-form {
            display: flex;
            gap: 10px;
            align-items: center;
            width: 100%;
            flex-wrap: wrap;
        }

        .search-form input,
        .search-form select {
            padding: 10px 12px;
            border: 1px solid #e6eef6;
            border-radius: 6px;
            font-size: 0.98rem
        }

        .search-form input {
            flex: 1;
            min-width: 200px
        }

        /* Buttons (consistent with Nota) */
        .btn {
            padding: 8px 14px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            gap: 8px;
            align-items: center;
            border: none
        }

        .btn-primary {
            background: #3498db;
            color: #fff
        }

        .btn-primary:hover {
            background: #2980b9
        }

        .btn-secondary {
            background: #95a5a6;
            color: #fff
        }

        .btn-secondary:hover {
            background: #7f8c8d
        }

        .btn-success {
            background: #2ecc71;
            color: #fff
        }

        .btn-success:hover {
            background: #27ae60
        }

        /* Table — use same visual style as Nota */
        .table-responsive {
            background: transparent;
        }

        table.notas {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: visible;
            /* allow action-menu dropdowns to escape */
            box-shadow: 0 6px 20px rgba(2, 6, 23, 0.06);
            min-width: 720px
        }

        table.notas thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 16px;
            font-weight: 800;
            text-align: left;
            font-size: 0.9rem
        }

        table.notas tbody td {
            padding: 12px 16px;
            vertical-align: middle;
            color: #2c3e50
        }

        table.notas tbody tr:hover {
            background: #fbfdff
        }

        .actions .btn {
            padding: 8px 10px;
            font-size: 0.9rem
        }

        @media (max-width:640px) {
            .page-header {
                gap: 12px
            }

            .header-actions {
                width: 100%;
                flex-direction: column;
                gap: 8px
            }

            .header-actions .btn {
                width: 100%
            }

            table.notas {
                min-width: 0
            }
        }

        .last-update {
            text-align: center;
            margin-top: 20px;
            color: #7f8c8d;
            font-size: 0.9rem
        }
    </style>

    <style>
        /* three-dot action menu (shared) */
        .action-menu {
            display: inline-block;
            position: relative
        }

        .action-menu-button {
            background: transparent;
            border: none;
            padding: 6px 8px;
            font-size: 16px;
            cursor: pointer;
            color: #344;
            border-radius: 6px
        }

        .action-menu-button:hover {
            background: rgba(0, 0, 0, 0.04)
        }

        .action-menu-list {
            position: absolute;
            top: 34px;
            right: 0;
            min-width: 160px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 8px 26px rgba(2, 6, 23, 0.12);
            padding: 6px 0;
            display: none;
            z-index: 1200
        }

        .action-menu-list.show {
            display: block
        }

        .action-menu-item {
            display: block;
            padding: 8px 12px;
            color: #2c3e50;
            text-decoration: none;
            font-weight: 700;
            white-space: nowrap;
            border: none;
            background: transparent;
            text-align: left;
            width: 100%
        }

        .action-menu-item:hover {
            background: #f5f7fb
        }

        .action-menu-item-danger {
            color: #c0392b
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-boxes"></i> Daftar Barang Pokok</h1>
            <div class="header-actions">
                @if (Auth::check() && Auth::user()->isAdmin())
                    <a href="{{ route('harga-barang-pokok.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i>
                        Tambah
                        Barang</a>

                    <a href="{{ route('harga-barang-pokok.export') }}?search={{ urlencode($search) }}&kategori={{ urlencode($kategori) }}"
                        class="btn btn-secondary" title="Export CSV"><i class="fas fa-file-csv"></i> Export CSV</a>

                    <a href="{{ route('harga-barang-pokok.import') }}" class="btn btn-secondary" title="Import CSV"><i
                            class="fas fa-file-import"></i> Import</a>
                    <a href="{{ route('harga-barang-pokok.export-wa') }}?search={{ urlencode($search) }}&kategori={{ urlencode($kategori) }}&view=1"
                        class="btn btn-secondary" title="Tampilkan / Salin WA"><i class="fab fa-whatsapp"></i> View WA</a>
                @endif



                <button class="btn btn-success" onclick="printList()"><i class="fas fa-print"></i> Cetak / Simpan
                    PDF</button>
            </div>
        </div>

        <!-- Search Form -->
        <div class="search-box">
            <form action="{{ route('harga-barang-pokok.index') }}" method="GET" class="search-form">
                <i class="fas fa-search" style="color: #7f8c8d;"></i>
                <input type="text" name="search" placeholder="Cari barang..." value="{{ $search }}">
                <div style="display:flex;gap:8px;align-items:center;">
                    <select name="kategori">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategori_list as $kat)
                            <option value="{{ $kat }}" {{ $kategori == $kat ? 'selected' : '' }}>
                                {{ $kat }}</option>
                        @endforeach
                    </select>

                    <a href="{{ route('kategori.index') }}" class="btn btn-secondary" title="Kelola Kategori"
                        style="padding:8px 10px;font-size:0.9rem;">
                        <i class="fas fa-cog"></i>
                    </a>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Cari
                </button>
                @if ($search || $kategori)
                    <a href="{{ route('harga-barang-pokok.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table class="notas">
                <thead>
                    <tr>
                        <th style="width:6%">No</th>
                        <th>Uraian</th>
                        <th style="width:14%">Kategori</th>
                        <th style="width:12%">Satuan</th>
                        @if (Auth::check() && Auth::user()->isAdmin())
                            <th style="width:12%;text-align:right">Keuntungan/Satuan (Rp)</th>
                        @endif
                        <th style="width:14%;text-align:right">Harga Satuan</th>
                        {{-- <th class="col-harga-baru" style="width:14%;text-align:right">Harga Baru</th> --}}
                        <th style="width:8%;text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barang_pokok as $index => $barang)
                        <tr data-id="{{ $barang->id }}">
                            <td>{{ $index + 1 }}</td>
                            <td class="cell-uraian">{{ $barang->uraian }}<div
                                    style="font-size:0.75rem;color:#7f8c8d;margin-top:6px">
                                    {{ $barang->updated_at ? $barang->updated_at->format('d M Y, H:i') : '-' }}</div>
                            </td>
                            <td>{{ $barang->kategori }}</td>
                            <td>{{ $barang->satuan }}</td>
                            @if (Auth::check() && Auth::user()->isAdmin())
                                <td style="text-align:right">Rp
                                    {{ number_format($barang->profit_per_unit ?? 0, 0, ',', '.') }}
                                </td>
                            @endif
                            <td style="text-align:right">Rp {{ number_format($barang->harga_satuan, 0, ',', '.') }}</td>
                            {{-- <td class="col-harga-baru"></td> --}}
                            <td style="text-align:right">
                                <div class="actions" style="position:relative;justify-content:flex-end;">
                                    <div class="action-menu">
                                        <button type="button" class="action-menu-button" aria-expanded="false"
                                            aria-haspopup="true"><i class="fas fa-ellipsis-v"></i></button>
                                        <ul class="action-menu-list" role="menu">
                                            <li role="none"><a role="menuitem"
                                                    href="{{ route('harga-barang-pokok.edit', $barang->id) }}"
                                                    class="action-menu-item"><i class="fas fa-eye"></i> View</a></li>
                                            <li role="none"><a role="menuitem"
                                                    href="{{ route('harga-barang-pokok.edit', $barang->id) }}"
                                                    class="action-menu-item"><i class="fas fa-edit"></i> Edit</a></li>
                                            <li role="none">
                                                <form action="{{ route('harga-barang-pokok.destroy', $barang->id) }}"
                                                    method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-menu-item action-menu-item-danger"
                                                        role="menuitem"><i class="fas fa-trash"></i> Hapus</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;padding:36px;color:#95a5a6;">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($last_update)
            <div class="update-info">
                <i class="fas fa-clock"></i>
                <span>Terakhir diperbarui: <strong>{{ $last_update->format('d F Y, H:i') }}</strong></span>
            </div>
        @endif

        <!-- Edit Modal (AJAX) -->
        <div id="edit-modal" class="modal-overlay" style="display:none;" role="dialog" aria-modal="true"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" role="document">
                    <button class="modal-close" aria-label="Tutup">&times;</button>
                    <div id="edit-modal-header" class="modal-header">
                        <h3><i class="fas fa-edit" style="color:#2ecc71;margin-right:10px;"></i> Edit Harga Barang</h3>
                        <p class="modal-subtitle">Perbarui uraian, satuan, dan harga barang. Perubahan akan diterapkan
                            langsung ke daftar.</p>
                    </div>
                    <div id="edit-modal-body" class="modal-body"></div>
                    <div id="edit-modal-footer" class="modal-footer" style="display:none;"></div>
                </div>
            </div>
        </div>

        <!-- Toast container -->
        <div id="toast-container" aria-live="polite" aria-atomic="true"
            style="position: fixed; right: 20px; bottom: 20px; z-index: 10000;"></div>
    </div>

    @push('scripts')
        <script>
            (function() {
                var _lastActiveElement = null;

                function closeModal() {
                    var modal = document.getElementById('edit-modal');
                    if (modal) {
                        modal.style.display = 'none';
                        modal.setAttribute('aria-hidden', 'true');
                    }
                    var body = document.getElementById('edit-modal-body');
                    if (body) body.innerHTML = '';
                    try {
                        if (_lastActiveElement) _lastActiveElement.focus();
                    } catch (e) {}
                    _lastActiveElement = null;
                }

                function showToast(message, type) {
                    var container = document.getElementById('toast-container');
                    if (!container) return;
                    var el = document.createElement('div');
                    el.className = 'toast ' + (type === 'error' ? 'toast-error' : 'toast-success');
                    el.textContent = message;
                    container.appendChild(el);
                    setTimeout(function() {
                        el.style.animation = 'toast-out 300ms ease forwards';
                        setTimeout(function() {
                            try {
                                container.removeChild(el);
                            } catch (e) {}
                        }, 300);
                    }, 4200);
                }

                function setButtonLoading(btn, loading) {
                    if (!btn) return;
                    if (loading) {
                        btn.classList.add('loading');
                        if (!btn.querySelector('.spinner')) {
                            var sp = document.createElement('span');
                            sp.className = 'spinner';
                            btn.insertBefore(sp, btn.firstChild);
                        }
                    } else {
                        btn.classList.remove('loading');
                        var sp = btn.querySelector('.spinner');
                        if (sp) sp.parentNode.removeChild(sp);
                    }
                }

                document.addEventListener('click', function(e) {
                    var btn = e.target.closest('.btn-warning');
                    if (!btn) return;
                    e.preventDefault();

                    var href = btn.getAttribute('href');
                    var id = btn.getAttribute('data-id');
                    if (!href) return;

                    var modal = document.getElementById('edit-modal');
                    var body = document.getElementById('edit-modal-body');
                    _lastActiveElement = document.activeElement;
                    body.innerHTML = '<div class="modal-loading">Memuat form ...</div>';
                    modal.setAttribute('aria-hidden', 'false');
                    modal.style.display = 'flex';

                    fetch(href, {
                            credentials: 'same-origin',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(function(resp) {
                            return resp.text();
                        })
                        .then(function(html) {
                            body.innerHTML = html;

                            var form = document.getElementById('ajax-edit-form');
                            if (!form) return;

                            // set correct action (PUT route)
                            form.action = href.replace('/edit', '');

                            form.addEventListener('submit', function(ev) {
                                ev.preventDefault();
                                var fd = new FormData(form);
                                // include method override
                                fd.set('_method', 'PUT');

                                var submitBtn = form.querySelector('.btn-submit');
                                setButtonLoading(submitBtn, true);

                                fetch(form.action, {
                                        method: 'POST',
                                        credentials: 'same-origin',
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest'
                                        },
                                        body: fd
                                    }).then(function(r) {
                                        return r.json();
                                    })
                                    .then(function(json) {
                                        setButtonLoading(submitBtn, false);
                                        if (json.status === 'ok') {
                                            var data = json.data;
                                            var row = document.querySelector('tr[data-id="' + data
                                                .id + '"]');
                                            if (row) {
                                                var uraian = row.querySelector('.cell-uraian');
                                                var kategori = row.querySelector('.cell-kategori');
                                                var satuan = row.querySelector('.cell-satuan');
                                                var harga = row.querySelector('.cell-harga');
                                                var updated = row.querySelector('.cell-updated');
                                                if (uraian) uraian.textContent = data.uraian;
                                                if (kategori) kategori.textContent = data.kategori;
                                                if (satuan) satuan.innerHTML =
                                                    '<span class="satuan-badge"><strong>' + (data
                                                        .satuan || '-') + '</strong></span>';
                                                if (harga) harga.innerHTML =
                                                    '<span class="price-format"><strong>' + (data
                                                        .harga_formatted || '-') +
                                                    '</strong></span>';
                                                if (updated) updated.innerHTML =
                                                    '<small style="color: #7f8c8d;"><i class="fas fa-clock"></i> ' +
                                                    (data.updated_at || '-') + '</small>';
                                            }

                                            showToast('Data berhasil diperbarui', 'success');
                                            closeModal();
                                        } else {
                                            var errorDiv = document.getElementById(
                                                'ajax-form-errors');
                                            if (!errorDiv) {
                                                var bodyDiv = document.getElementById(
                                                    'edit-modal-body');
                                                var el = document.createElement('div');
                                                el.id = 'ajax-form-errors';
                                                bodyDiv.insertBefore(el, bodyDiv.firstChild);
                                                errorDiv = el;
                                            }
                                            var htmlErr = '';
                                            if (json.errors) {
                                                htmlErr =
                                                    '<div class="alert alert-danger"><ul style="margin:0;padding-left:18px;">';
                                                Object.keys(json.errors).forEach(function(k) {
                                                    json.errors[k].forEach(function(m) {
                                                        htmlErr += '<li>' + m +
                                                            '</li>';
                                                    });
                                                });
                                                htmlErr += '</ul></div>';
                                                showToast(Object.values(json.errors)[0][0],
                                                    'error');
                                            } else {
                                                htmlErr =
                                                    '<div class="alert alert-danger">Terjadi kesalahan</div>';
                                                showToast('Terjadi kesalahan', 'error');
                                            }
                                            errorDiv.innerHTML = htmlErr;
                                        }
                                    }).catch(function() {
                                        setButtonLoading(submitBtn, false);
                                        showToast('Gagal mengirim data. Coba lagi.', 'error');
                                    });
                            });

                            var cancel = document.getElementById('ajax-cancel');
                            if (cancel) cancel.addEventListener('click', function(ev) {
                                ev.preventDefault();
                                closeModal();
                            });
                        }).catch(function() {
                            showToast('Gagal memuat form edit', 'error');
                            modal.style.display = 'none';
                        });
                });

                var modalEl = document.getElementById('edit-modal');
                modalEl.addEventListener('click', function(e) {
                    if (e.target === this || e.target.classList.contains('modal-close')) closeModal();
                });
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') closeModal();
                });

            })();
        </script>

        <script>
            // Print / save as PDF helper
            function sanitizeForFilename(str) {
                return String(str || '')
                    .normalize('NFKD')
                    .replace(/[\u0300-\u036F]/g, '')
                    .replace(/[^a-zA-Z0-9\-_. ]+/g, '-')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .replace(/^[-_.]+|[-_.]+$/g, '')
                    .toLowerCase();
            }

            function formatDateForFilename() {
                const d = new Date();
                const y = d.getFullYear();
                const m = String(d.getMonth() + 1).padStart(2, '0');
                const dd = String(d.getDate()).padStart(2, '0');
                return y + m + dd;
            }

            function printList() {
                // Open server-rendered printable page (better layout for PDF)
                const search = encodeURIComponent("{{ $search }}");
                const kategori = encodeURIComponent("{{ $kategori }}");
                const url = `{{ route('harga-barang-pokok.print') }}?search=${search}&kategori=${kategori}`;

                // Try to open in a new tab; if blocked by popup blocker, fallback to same-tab navigation
                const w = window.open(url, '_blank');
                if (!w || w.closed || typeof w.closed === 'undefined') {
                    // popup blocked — navigate in current tab as fallback
                    window.location.href = url;
                } else {
                    try {
                        w.focus();
                    } catch (e) {
                        /* ignore */
                    }
                }
            }

            // Toggle Harga Baru column visibility
            let hargaBaruVisible = true;

            function toggleHargaBaru() {
                const cells = document.querySelectorAll('.col-harga-baru');
                const toggleBtnText = document.getElementById('harga-toggle-text');
                const icon = document.querySelector('.btn-toggle i');

                if (hargaBaruVisible) {
                    cells.forEach(c => c.classList.add('hidden'));
                    if (toggleBtnText) toggleBtnText.textContent = 'Tampilkan Harga Baru';
                    if (icon) {
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                    hargaBaruVisible = false;
                } else {
                    cells.forEach(c => c.classList.remove('hidden'));
                    if (toggleBtnText) toggleBtnText.textContent = 'Sembunyikan Harga Baru';
                    if (icon) {
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    }
                    hargaBaruVisible = true;
                }
            }

            // hide Harga Baru by default on page load
            document.addEventListener('DOMContentLoaded', function() {
                toggleHargaBaru();
            });
        </script>
    @endpush
@endsection

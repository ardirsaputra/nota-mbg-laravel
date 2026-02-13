@extends('layouts.app')

@section('title', 'Daftar Nota')

@push('styles')
    <style>
        /* Header / actions — aligned with other index pages */
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

        /* Small stats cards */
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

        /* Buttons (consistent) */
        .btn {
            padding: 8px 14px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            gap: 8px;
            align-items: center;
            border: none;
        }

        .btn-primary {
            background: #3498db;
            color: #fff;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .btn-secondary {
            background: #95a5a6;
            color: #fff;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        /* Table — consistent visual style */
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
            min-width: 720px;
        }

        table.notas thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 16px;
            font-weight: 800;
            text-align: left;
            font-size: 0.9rem;
        }

        table.notas tbody td {
            padding: 12px 16px;
            vertical-align: middle;
            color: #2c3e50;
        }

        table.notas tbody tr:hover {
            background: #fbfdff;
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
                min-width: 0;
            }
        }
    </style>

    <style>
        /* three-dot action menu (shared) — same as toko/harga pages */
        .action-menu {
            display: inline-block;
            position: relative;
        }

        .action-menu-button {
            background: transparent;
            border: none;
            padding: 6px 8px;
            font-size: 16px;
            cursor: pointer;
            color: #344;
            border-radius: 6px;
        }

        .action-menu-button:hover {
            background: rgba(0, 0, 0, 0.04);
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
            z-index: 1200;
        }

        .action-menu-list.show {
            display: block;
        }

        .action-menu-item {
            display: block;
            padding: 8px 12px;
            color: #2c3e50;
            text-decoration: none;
            font-weight: 700;
            background: transparent;
            text-align: left;
            width: 100%;
        }

        .action-menu-item:hover {
            background: #f5f7fb;
        }

        .action-menu-item-danger {
            color: #c0392b;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-receipt"></i> Daftar Nota</h1>
            <div class="header-actions">
                <a href="{{ route('nota.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Buat Nota</a>

                <button id="btnInsightProfit" type="button" class="btn btn-secondary" title="Tampilkan ringkasan profit">
                    <i class="fas fa-lightbulb"></i> Insight Profit
                </button>

                <form method="GET" action="{{ route('nota.index') }}"
                    style="display:inline-flex;gap:8px;flex-wrap:wrap;align-items:center">
                    <select name="profit_filter" onchange="this.form.submit()" class="btn btn-secondary">
                        <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="included" {{ $filter == 'included' ? 'selected' : '' }}>Hanya Profit</option>
                        <option value="excluded" {{ $filter == 'excluded' ? 'selected' : '' }}>Hanya Non-Profit</option>
                    </select>

                    <select name="toko_filter" onchange="this.form.submit()" class="btn btn-secondary">
                        <option value="all" {{ $toko_filter == 'all' ? 'selected' : '' }}>Semua Toko</option>
                        <option value="none" {{ $toko_filter == 'none' ? 'selected' : '' }}>Tanpa Toko</option>
                        <option value="manual" {{ $toko_filter == 'manual' ? 'selected' : '' }}>Input Manual</option>
                        @foreach ($toko_list as $toko)
                            <option value="{{ $toko->id }}" {{ $toko_filter == $toko->id ? 'selected' : '' }}>
                                {{ $toko->nama_toko }}
                            </option>
                        @endforeach
                    </select>
                    &ensp; &ensp;
                    <!-- Date range filter -->
                    <label style="display:flex;gap:6px;align-items:center">
                        <small style="color:#6b7280">Dari</small>
                        <input type="date" name="tanggal_from"
                            value="{{ old('tanggal_from', request('tanggal_from', isset($tanggalFrom) ? $tanggalFrom : '')) }}"
                            class="btn btn-secondary" onchange="this.form.submit()">
                    </label>

                    <label style="display:flex;gap:6px;align-items:center">
                        <small style="color:#6b7280">Sampai</small>
                        <input type="date" name="tanggal_to"
                            value="{{ old('tanggal_to', request('tanggal_to', isset($tanggalTo) ? $tanggalTo : '')) }}"
                            class="btn btn-secondary" onchange="this.form.submit()">
                    </label>

                    <div style="display:flex;gap:6px;align-items:center">
                        <button type="submit" class="btn btn-secondary">Terapkan</button>
                        <a href="{{ route('nota.index') }}" class="btn btn-outline">Reset</a>
                    </div>

                    <input type="hidden" name="profit_filter" value="{{ $filter }}">
                    <input type="hidden" name="toko_filter" value="{{ $toko_filter }}">
                </form>
            </div>
        </div>

        <div class="nota-stats">
            <div class="nota-card notes">
                <div class="icon"><i class="fas fa-file-invoice"></i></div>
                <div>
                    <div style="color:#95a5a6;font-weight:700;font-size:0.85rem">Total Nota</div>
                    <div style="font-weight:800;font-size:1.4rem">{{ $notas->count() }}</div>
                </div>
            </div>

            <div class="nota-card total">
                <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
                <div>
                    <div style="color:#95a5a6;font-weight:700;font-size:0.85rem">Total</div>
                    <div style="font-weight:800;font-size:1.2rem">Rp {{ number_format($notas->sum('total'), 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <div class="nota-card" id="profitStatCard" style="display:none;">
                <div class="icon" style="background:linear-gradient(135deg,#667eea,#764ba2);color:#fff"><i
                        class="fas fa-chart-line"></i></div>
                <div>
                    <div style="color:#95a5a6;font-weight:700;font-size:0.85rem">Estimasi Profit (terpilih)</div>
                    <div style="font-weight:800;font-size:1.2rem">Rp
                        {{ number_format($notas->sum('estimated_profit'), 0, ',', '.') }}</div>
                    <div style="margin-top:6px;color:#95a5a6;font-size:0.85rem">Nota yang dihitung:
                        {{ $notas->where('profit_insight', true)->count() }}</div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="notas">
                <thead>
                    <tr>
                        <th style="width:6%">No</th>
                        <th>Nomor Nota</th>
                        <th style="width:14%">Tanggal</th>
                        <th>Nama Toko</th>
                        <th style="width:14%;text-align:right">Total</th>
                        <th class="profit-column" style="width:14%;text-align:right">Est. Profit</th>
                        <th style="text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notas as $nota)
                        <tr>
                            <td>{{ $nota->id }}</td>
                            <td class="no-cell">{{ $nota->no }}</td>
                            <td class="date-cell">{{ $nota->tanggal ? $nota->tanggal->format('d M Y') : '-' }}</td>
                            <td class="toko-cell">{{ $nota->nama_toko ?? '-' }}</td>
                            <td class="total-cell" style="text-align:right">Rp
                                {{ number_format($nota->total, 0, ',', '.') }}</td>
                            <td class="profit-column" style="text-align:right">Rp
                                {{ number_format($nota->estimated_profit ?? 0, 0, ',', '.') }}</td>
                            <td style="text-align:right">
                                <div class="actions" style="position:relative;justify-content:flex-end;">
                                    <div class="action-menu">
                                        <button type="button" class="action-menu-button" aria-expanded="false"
                                            aria-haspopup="true">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="action-menu-list" role="menu">
                                            <li role="none"><a role="menuitem"
                                                    href="{{ route('nota.show', $nota->id) }}"
                                                    class="action-menu-item"><i class="fas fa-eye"></i> View</a></li>
                                            <li role="none"><a role="menuitem"
                                                    href="{{ route('nota.edit', $nota->id) }}"
                                                    class="action-menu-item"><i class="fas fa-edit"></i> Edit</a></li>
                                            <li role="none">
                                                <form action="{{ route('nota.destroy', $nota->id) }}" method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus nota ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="action-menu-item action-menu-item-danger"
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
                            <td colspan="7" style="text-align:center;padding:36px;color:#95a5a6;">Belum ada nota.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                const btn = document.getElementById('btnInsightProfit');
                const card = document.getElementById('profitStatCard');
                const profitCells = document.querySelectorAll('.profit-column');

                if (!btn || !card) return;

                btn.addEventListener('click', function() {
                    const shown = card.style.display !== 'none';
                    card.style.display = shown ? 'none' : 'flex';

                    // toggle button active look
                    btn.classList.toggle('active', !shown);

                    // highlight profit column when visible
                    profitCells.forEach(function(c) {
                        c.style.display = shown ? '' : 'table-cell';
                    });

                    if (!shown) {
                        card.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            })();
        </script>
    @endpush
@endsection

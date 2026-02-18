@extends('layouts.app')

@section('title', 'Daftar Nota')

@push('styles')
    <style>
        /* ── Header bar ── */
        .page-header-bar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 22px 28px;
            border-radius: 14px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 14px;
        }

        .page-header-bar h1 {
            margin: 0;
            font-size: 1.4rem;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-header-bar p {
            margin: 4px 0 0;
            opacity: .85;
            font-size: .88rem;
        }

        .page-header-bar .header-cta {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        /* ── Stats row ── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: #fff;
            border-radius: 13px;
            padding: 18px 20px;
            box-shadow: 0 2px 14px rgba(0, 0, 0, .06);
            display: flex;
            align-items: center;
            gap: 14px;
            transition: box-shadow .2s;
        }

        .stat-card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, .1);
        }

        .stat-card .sc-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
        }

        .sc-icon.blue {
            background: #e0f2fe;
            color: #0284c7;
        }

        .sc-icon.purple {
            background: #ede9fe;
            color: #7c3aed;
        }

        .sc-icon.green {
            background: #dcfce7;
            color: #16a34a;
        }

        .sc-icon.grad {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
        }

        .stat-card .sc-body p {
            margin: 0;
            font-size: .78rem;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .stat-card .sc-body strong {
            font-size: 1.35rem;
            color: #1e293b;
            display: block;
            line-height: 1.2;
        }

        .stat-card .sc-body small {
            font-size: .75rem;
            color: #94a3b8;
        }

        /* ── Filter card ── */
        .filter-card {
            background: #fff;
            border-radius: 12px;
            padding: 16px 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
            margin-bottom: 20px;
        }

        .filter-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            user-select: none;
        }

        .filter-card-header h3 {
            margin: 0;
            font-size: .92rem;
            font-weight: 700;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-card-body {
            margin-top: 14px;
        }

        .filter-row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .filter-group label {
            font-size: .75rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .filter-group select,
        .filter-group input[type="date"] {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: .88rem;
            color: #334155;
            background: #f8fafc;
            cursor: pointer;
            min-width: 130px;
        }

        .filter-group select:focus,
        .filter-group input[type="date"]:focus {
            outline: none;
            border-color: #667eea;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, .12);
        }

        .active-filters {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 12px;
        }

        .filter-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px 3px 10px;
            background: #ede9fe;
            color: #6d28d9;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 700;
        }

        .filter-pill a {
            color: inherit;
            text-decoration: none;
            margin-left: 4px;
            opacity: .7;
        }

        .filter-pill a:hover {
            opacity: 1;
        }

        /* ── Buttons ── */
        .btn {
            padding: 9px 16px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: .88rem;
            text-decoration: none;
            transition: all .2s;
            white-space: nowrap;
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

        .btn-outline {
            background: #fff;
            border: 1.5px solid #e2e8f0;
            color: #64748b;
        }

        .btn-outline:hover {
            border-color: #667eea;
            color: #667eea;
        }

        .btn-insight {
            background: #fff;
            border: 1.5px solid #e2e8f0;
            color: #64748b;
        }

        .btn-insight.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            border-color: transparent;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: .8rem;
        }

        /* ── Table wrapper ── */
        .table-wrap {
            background: #fff;
            border-radius: 13px;
            box-shadow: 0 2px 14px rgba(0, 0, 0, .06);
            overflow: hidden;
        }

        .table-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 18px;
            border-bottom: 1px solid #f1f5f9;
            flex-wrap: wrap;
            gap: 10px;
        }

        .table-toolbar h3 {
            margin: 0;
            font-size: .97rem;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .count-badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 700;
            background: #ede9fe;
            color: #6d28d9;
        }

        .table-scroll {
            overflow-x: auto;
        }

        table.notas {
            width: 100%;
            border-collapse: collapse;
            min-width: 660px;
        }

        table.notas thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 11px 16px;
            font-weight: 700;
            font-size: .82rem;
            letter-spacing: .04em;
            text-align: left;
            white-space: nowrap;
        }

        table.notas thead th.text-right {
            text-align: right;
        }

        table.notas tbody td {
            padding: 12px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f8fafc;
            color: #334155;
            font-size: .9rem;
        }

        table.notas tbody tr:last-child td {
            border-bottom: none;
        }

        table.notas tbody tr:hover td {
            background: #fafbff;
        }

        /* ── Cell styles ── */
        .nota-num {
            font-weight: 700;
            color: #667eea;
            text-decoration: none;
        }

        .nota-num:hover {
            text-decoration: underline;
        }

        .nota-date {
            color: #64748b;
            font-size: .85rem;
        }

        .nota-toko {
            font-weight: 600;
            color: #1e293b;
        }

        .nota-total {
            font-weight: 700;
            color: #1e293b;
            text-align: right;
        }

        .nota-profit {
            font-weight: 600;
            color: #16a34a;
            text-align: right;
        }

        /* ── Badges ── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-locked {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-profit {
            background: #dcfce7;
            color: #166534;
        }

        .badge-admin {
            background: #dbeafe;
            color: #1d4ed8;
        }

        /* ── Action menu (three-dot) ── */
        .action-menu {
            display: inline-block;
            position: relative;
        }

        .action-menu-button {
            background: transparent;
            border: 1.5px solid #e2e8f0;
            padding: 5px 9px;
            font-size: 14px;
            cursor: pointer;
            color: #64748b;
            border-radius: 7px;
            transition: all .2s;
        }

        .action-menu-button:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #334155;
        }

        .action-menu-list {
            position: absolute;
            top: calc(100% + 6px);
            right: 0;
            min-width: 150px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 28px rgba(0, 0, 0, .13);
            padding: 6px 0;
            display: none;
            z-index: 1200;
            border: 1px solid #f1f5f9;
        }

        .action-menu-list.show {
            display: block;
        }

        .action-menu-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 14px;
            color: #334155;
            text-decoration: none;
            font-weight: 600;
            font-size: .88rem;
            background: transparent;
            border: none;
            text-align: left;
            width: 100%;
            cursor: pointer;
            transition: background .15s;
        }

        .action-menu-item:hover {
            background: #f8fafc;
        }

        .action-menu-item-danger {
            color: #dc2626;
        }

        .action-menu-item-danger:hover {
            background: #fef2f2;
        }

        .action-menu-divider {
            height: 1px;
            background: #f1f5f9;
            margin: 4px 0;
        }

        /* ── Empty state ── */
        .empty-state {
            text-align: center;
            padding: 52px 20px;
            color: #94a3b8;
        }

        .empty-state .es-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: #f1f5f9;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: #cbd5e1;
            margin-bottom: 16px;
        }

        .empty-state p {
            margin: 0 0 16px;
            font-size: .95rem;
        }

        @media (max-width: 640px) {
            .page-header-bar {
                flex-direction: column;
                align-items: flex-start;
            }

            .stats-row {
                grid-template-columns: 1fr 1fr;
            }

            .filter-row {
                flex-direction: column;
            }

            .filter-group {
                width: 100%;
            }

            .filter-group select,
            .filter-group input[type="date"] {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container">

        {{-- ── Header ── --}}
        <div class="page-header-bar">
            <div>
                <h1><i class="fas fa-receipt"></i> Daftar Nota</h1>
                <p>Kelola semua nota transaksi Anda</p>
            </div>
            <div class="header-cta">
                <button id="btnInsightProfit" type="button" class="btn btn-insight" title="Insight Profit">
                    <i class="fas fa-chart-line"></i> Insight Profit
                </button>
                <a href="{{ route('nota.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Buat Nota
                </a>
            </div>
        </div>

        {{-- ── Stats ── --}}
        <div class="stats-row">
            <div class="stat-card">
                <div class="sc-icon blue"><i class="fas fa-file-invoice"></i></div>
                <div class="sc-body">
                    <p>Total Nota</p>
                    <strong>{{ $notas->total() }}</strong>
                    <small>hasil pencarian</small>
                </div>
            </div>
            <div class="stat-card">
                <div class="sc-icon purple"><i class="fas fa-money-bill-wave"></i></div>
                <div class="sc-body">
                    <p>Total Nilai</p>
                    <strong>Rp {{ number_format($totalSum ?? 0, 0, ',', '.') }}</strong>
                    <small>dari semua hasil filter</small>
                </div>
            </div>
            <div class="stat-card" id="profitStatCard" style="display:none;">
                <div class="sc-icon grad"><i class="fas fa-chart-line"></i></div>
                <div class="sc-body">
                    <p>Estimasi Profit</p>
                    <strong>Rp {{ number_format($includedEstimatedProfit ?? 0, 0, ',', '.') }}</strong>
                    <small>{{ $includedCount ?? 0 }} nota dengan profit insight</small>
                </div>
            </div>
        </div>

        {{-- ── Filter card ── --}}
        <div class="filter-card">
            <div class="filter-card-header" id="filterToggle">
                <h3>
                    <i class="fas fa-filter" style="color:#667eea;"></i>
                    Filter &amp; Pencarian
                    @php
                        $activeFilterCount = 0;
                        if ($filter !== 'all') {
                            $activeFilterCount++;
                        }
                        if ($toko_filter !== 'all') {
                            $activeFilterCount++;
                        }
                        if (!empty($tanggalFrom)) {
                            $activeFilterCount++;
                        }
                        if (!empty($tanggalTo)) {
                            $activeFilterCount++;
                        }
                    @endphp
                    @if ($activeFilterCount > 0)
                        <span class="count-badge">{{ $activeFilterCount }} aktif</span>
                    @endif
                </h3>
                <i class="fas fa-chevron-down" id="filterChevron" style="color:#94a3b8; transition:transform .2s;"></i>
            </div>

            <div class="filter-card-body" id="filterBody">
                <form method="GET" action="{{ route('nota.index') }}" id="filterForm">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label><i class="fas fa-lightbulb"></i> Profit</label>
                            <select name="profit_filter" onchange="this.form.submit()">
                                <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>Semua</option>
                                <option value="included" {{ $filter == 'included' ? 'selected' : '' }}>Insight Aktif
                                </option>
                                <option value="excluded" {{ $filter == 'excluded' ? 'selected' : '' }}>Tanpa Insight
                                </option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label><i class="fas fa-store"></i> Toko</label>
                            <select name="toko_filter" onchange="this.form.submit()">
                                <option value="all" {{ $toko_filter == 'all' ? 'selected' : '' }}>Semua Toko</option>
                                <option value="none" {{ $toko_filter == 'none' ? 'selected' : '' }}>Tanpa Toko</option>
                                <option value="manual" {{ $toko_filter == 'manual' ? 'selected' : '' }}>Input Manual
                                </option>
                                @foreach ($toko_list as $toko)
                                    <option value="{{ $toko->id }}" {{ $toko_filter == $toko->id ? 'selected' : '' }}>
                                        {{ $toko->nama_toko }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-group">
                            <label><i class="fas fa-calendar-alt"></i> Dari Tanggal</label>
                            <input type="date" name="tanggal_from" value="{{ $tanggalFrom ?? '' }}"
                                onchange="this.form.submit()">
                        </div>

                        <div class="filter-group">
                            <label><i class="fas fa-calendar-alt"></i> Sampai Tanggal</label>
                            <input type="date" name="tanggal_to" value="{{ $tanggalTo ?? '' }}"
                                onchange="this.form.submit()">
                        </div>

                        <div class="filter-group" style="justify-content:flex-end; padding-bottom:1px;">
                            @if ($activeFilterCount > 0)
                                <a href="{{ route('nota.index') }}" class="btn btn-outline btn-sm"
                                    style="height:fit-content;">
                                    <i class="fas fa-times"></i> Reset
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- preserve hidden inputs to not lose other filter state --}}
                    <input type="hidden" name="profit_filter" value="{{ $filter }}" id="hProfit" disabled>
                </form>

                @if ($activeFilterCount > 0)
                    <div class="active-filters">
                        @if ($filter !== 'all')
                            <span class="filter-pill"><i class="fas fa-lightbulb"></i>
                                Profit: {{ $filter === 'included' ? 'Insight Aktif' : 'Tanpa Insight' }}
                            </span>
                        @endif
                        @if ($toko_filter !== 'all')
                            <span class="filter-pill"><i class="fas fa-store"></i>
                                Toko:
                                {{ $toko_filter === 'none' ? 'Tanpa Toko' : ($toko_filter === 'manual' ? 'Manual' : optional($toko_list->firstWhere('id', $toko_filter))->nama_toko) }}
                            </span>
                        @endif
                        @if (!empty($tanggalFrom))
                            <span class="filter-pill"><i class="fas fa-calendar"></i>
                                Dari: {{ \Carbon\Carbon::parse($tanggalFrom)->format('d M Y') }}
                            </span>
                        @endif
                        @if (!empty($tanggalTo))
                            <span class="filter-pill"><i class="fas fa-calendar"></i>
                                Sampai: {{ \Carbon\Carbon::parse($tanggalTo)->format('d M Y') }}
                            </span>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- ── Table ── --}}
        <div class="table-wrap">
            <div class="table-toolbar">
                <h3>
                    <i class="fas fa-list" style="color:#667eea;"></i>
                    Nota
                    <span class="count-badge">{{ $notas->total() }} item</span>
                </h3>
                <div style="display:flex; gap:8px; align-items:center;">
                    <a href="{{ route('nota.export-month') }}" class="btn btn-outline btn-sm">
                        <i class="fas fa-download"></i> Export
                    </a>
                </div>
            </div>

            <div class="table-scroll">
                <table class="notas">
                    <thead>
                        <tr>
                            <th style="width:6%;">No</th>
                            <th>Nomor Nota</th>
                            <th style="width:13%;">Tanggal</th>
                            <th>Toko</th>
                            <th style="width:5%;">Status</th>
                            <th class="text-right" style="width:15%;">Total</th>
                            <th class="profit-column text-right" style="width:14%;">Est. Profit</th>
                            <th class="text-right" style="width:7%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notas as $i => $nota)
                            <tr>
                                <td style="color:#94a3b8; font-size:.8rem;">{{ ($notas->firstItem() ?? 0) + $i }}</td>

                                <td>
                                    <a href="{{ route('nota.show', $nota->id) }}" class="nota-num">
                                        {{ $nota->no ?? '—' }}
                                    </a>
                                </td>

                                <td class="nota-date">
                                    @if ($nota->tanggal)
                                        <div>{{ $nota->tanggal->format('d M Y') }}</div>
                                        <div style="font-size:.75rem; color:#94a3b8;">{{ $nota->tanggal->format('H:i') }}
                                        </div>
                                    @else
                                        <span style="color:#cbd5e1;">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($nota->nama_toko)
                                        <span class="nota-toko">{{ $nota->nama_toko }}</span>
                                    @else
                                        <span style="color:#cbd5e1; font-size:.85rem;">Tanpa toko</span>
                                    @endif
                                </td>

                                <td>
                                    <div style="display:flex; flex-direction:column; gap:3px; align-items:flex-start;">
                                        @if ($nota->is_locked)
                                            <span class="badge badge-locked"><i class="fas fa-lock"></i> Kunci</span>
                                        @endif
                                        @if ($nota->profit_insight)
                                            <span class="badge badge-profit"><i class="fas fa-chart-line"></i>
                                                Profit</span>
                                        @endif
                                        @if (isset($nota->is_admin_nota) && $nota->is_admin_nota)
                                            <span class="badge badge-admin"><i class="fas fa-user-shield"></i>
                                                Admin</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="nota-total">
                                    Rp {{ number_format($nota->total, 0, ',', '.') }}
                                </td>

                                <td class="profit-column nota-profit">
                                    @if (($nota->estimated_profit ?? 0) > 0)
                                        Rp {{ number_format($nota->estimated_profit, 0, ',', '.') }}
                                    @else
                                        <span style="color:#cbd5e1;">—</span>
                                    @endif
                                </td>

                                <td style="text-align:right;">
                                    <div class="action-menu">
                                        <button type="button" class="action-menu-button" aria-expanded="false"
                                            aria-haspopup="true" title="Aksi">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="action-menu-list" role="menu">
                                            <li><a href="{{ route('nota.show', $nota->id) }}" class="action-menu-item"
                                                    role="menuitem">
                                                    <i class="fas fa-eye" style="color:#667eea; width:16px;"></i> Lihat
                                                </a></li>
                                            <li><a href="{{ route('nota.edit', $nota->id) }}" class="action-menu-item"
                                                    role="menuitem">
                                                    <i class="fas fa-edit" style="color:#f59e0b; width:16px;"></i> Edit
                                                </a></li>
                                            <li><a href="{{ route('nota.show', $nota->id) }}" target="_blank"
                                                    class="action-menu-item" role="menuitem">
                                                    <i class="fas fa-print" style="color:#0284c7; width:16px;"></i> Print
                                                </a></li>
                                            @if (Auth::user()->isAdmin())
                                                <li>
                                                    <form action="{{ route('nota.clone', $nota->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="action-menu-item" role="menuitem">
                                                            <i class="fas fa-copy" style="color:#7c3aed; width:16px;"></i>
                                                            Duplikat
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                            <li>
                                                <div class="action-menu-divider"></div>
                                            </li>
                                            <li>
                                                <form action="{{ route('nota.destroy', $nota->id) }}" method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus nota {{ $nota->no }}?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="action-menu-item action-menu-item-danger" role="menuitem">
                                                        <i class="fas fa-trash" style="width:16px;"></i> Hapus
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <div class="es-icon"><i class="fas fa-receipt"></i></div>
                                        <p>Belum ada nota yang ditemukan.</p>
                                        <a href="{{ route('nota.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Buat Nota Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Table footer: pagination + range info --}}
            <div
                style="display:flex;justify-content:space-between;align-items:center;padding:12px 18px;border-top:1px solid #f1f5f9;background:#fff;">
                <div style="color:#64748b;font-size:.9rem;">
                    Menampilkan {{ $notas->firstItem() ?? 0 }} - {{ $notas->lastItem() ?? 0 }} dari {{ $notas->total() }}
                    nota
                </div>
                <div>
                    {{ $notas->onEachSide(1)->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            // ── Profit insight toggle ──
            const btn = document.getElementById('btnInsightProfit');
            const card = document.getElementById('profitStatCard');
            const cols = document.querySelectorAll('.profit-column');

            function setInsight(show) {
                if (card) card.style.display = show ? 'flex' : 'none';
                btn.classList.toggle('active', show);
                cols.forEach(c => c.style.display = show ? '' : 'none');
                localStorage.setItem('nota_insight_open', show ? '1' : '0');
            }

            // restore persisted state
            const saved = localStorage.getItem('nota_insight_open');
            setInsight(saved === '1');

            if (btn) {
                btn.addEventListener('click', function() {
                    setInsight(card.style.display === 'none');
                });
            }

            // ── Filter collapse ──
            const filterToggle = document.getElementById('filterToggle');
            const filterBody = document.getElementById('filterBody');
            const filterChevron = document.getElementById('filterChevron');

            function setFilter(open) {
                filterBody.style.display = open ? 'block' : 'none';
                filterChevron.style.transform = open ? 'rotate(180deg)' : '';
                localStorage.setItem('nota_filter_open', open ? '1' : '0');
            }

            const savedFilter = localStorage.getItem('nota_filter_open');
            // Default open if filters are active
            const hasActiveFilter = {{ $activeFilterCount }} > 0;
            setFilter(savedFilter === null ? hasActiveFilter : savedFilter === '1');

            filterToggle.addEventListener('click', function() {
                setFilter(filterBody.style.display === 'none');
            });

            // ── Action menu dropdowns ──
            document.querySelectorAll('.action-menu-button').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const menu = btn.nextElementSibling;
                    const isOpen = menu.classList.contains('show');
                    document.querySelectorAll('.action-menu-list.show').forEach(m => m.classList.remove(
                        'show'));
                    if (!isOpen) {
                        menu.classList.add('show');
                        btn.setAttribute('aria-expanded', 'true');
                    } else {
                        btn.setAttribute('aria-expanded', 'false');
                    }
                });
            });

            document.addEventListener('click', function() {
                document.querySelectorAll('.action-menu-list.show').forEach(m => m.classList.remove('show'));
                document.querySelectorAll('.action-menu-button[aria-expanded="true"]').forEach(b => b
                    .setAttribute('aria-expanded', 'false'));
            });
        })();
    </script>
@endpush

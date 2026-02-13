@extends('layouts.app')

@section('title', 'Satuan — CV Mia Jaya Abadi')

@push('styles')
    <style>
        /* Match Nota UI: header, stats, and table.notas */
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
            align-items: center
        }

        .nota-stats {
            margin-bottom: 18px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 18px
        }

        .nota-card {
            background: #fff;
            padding: 18px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04);
            display: flex;
            gap: 14px;
            align-items: center
        }

        .nota-card .icon {
            width: 56px;
            height: 56px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem
        }

        .nota-card.total .icon {
            background: linear-gradient(135deg, #f3e5f5, #d6c0e9);
            color: #9c27b0
        }

        .nota-card.notes .icon {
            background: #e8f3ff;
            color: #2196f3
        }

        .table-responsive {
            background: transparent
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
            color: #fff;
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

        .chip {
            display: inline-flex;
            gap: 8px;
            align-items: center;
            padding: 6px 10px;
            border-radius: 999px;
            background: #f1f5ff;
            color: #334155;
            font-weight: 600
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

        /* shared action-menu */
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
            <h1><i class="fas fa-ruler"></i> Satuan</h1>
            <div class="header-actions">
                <a href="{{ route('satuan.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Satuan</a>
                <a href="{{ route('admin') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>

        <div class="nota-stats">
            <div class="nota-card notes">
                <div class="icon"><i class="fas fa-balance-scale"></i></div>
                <div>
                    <div style="color:#95a5a6;font-weight:700;font-size:0.85rem">Total Satuan</div>
                    <div style="font-weight:800;font-size:1.4rem">{{ $satuans->count() }}</div>
                </div>
            </div>

            <div class="nota-card total">
                <div class="icon"><i class="fas fa-clock"></i></div>
                <div>
                    <div style="color:#95a5a6;font-weight:700;font-size:0.85rem">Terakhir diperbarui</div>
                    <div style="font-weight:800;font-size:1.2rem">
                        {{ $satuans->max('updated_at') ? $satuans->max('updated_at')->format('d M Y, H:i') : '-' }}</div>
                </div>
            </div>
        </div>

        <div class="satuan-card">
            @if (session('status'))
                <div class="alert alert-success">✓ {{ session('status') }}</div>
            @endif

            <div class="table-responsive">
                <table class="notas">
                    <thead>
                        <tr>
                            <th style="width:64px">No</th>
                            <th>Nama Satuan</th>
                            <th>Keterangan</th>
                            <th style="width:160px;text-align:right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($satuans as $i => $satuan)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><span class="chip">{{ $satuan->nama_satuan }}</span></td>
                                <td>{{ $satuan->keterangan }}</td>
                                <td style="text-align:right">
                                    <div class="actions" style="position:relative;justify-content:flex-end;">
                                        <div class="action-menu">
                                            <button type="button" class="action-menu-button" aria-expanded="false"
                                                aria-haspopup="true"><i class="fas fa-ellipsis-v"></i></button>
                                            <ul class="action-menu-list" role="menu">
                                                <li role="none"><a role="menuitem"
                                                        href="{{ route('satuan.edit', $satuan->id) }}"
                                                        class="action-menu-item"><i class="fas fa-eye"></i> View</a></li>
                                                <li role="none"><a role="menuitem"
                                                        href="{{ route('satuan.edit', $satuan->id) }}"
                                                        class="action-menu-item"><i class="fas fa-edit"></i> Edit</a></li>
                                                <li role="none">
                                                    <form action="{{ route('satuan.destroy', $satuan->id) }}"
                                                        method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="action-menu-item action-menu-item-danger"
                                                            type="submit" role="menuitem"><i class="fas fa-trash"></i>
                                                            Hapus</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center;padding:36px;color:#95a5a6;">Tidak ada data.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Kelola Kategori')

@section('content')
    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-tags"></i> Kelola Kategori</h1>
            <div class="header-actions">
                <a href="{{ route('kategori.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah
                    Kategori</a>
                <a href="{{ route('harga-barang-pokok.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>

        <div class="nota-card" style="margin-bottom:18px;">
            <div style="display:flex;gap:12px;align-items:center;">
                <div class="icon" style="background:#e8f3ff;color:#2196f3;padding:10px;border-radius:8px"><i
                        class="fas fa-tags"></i></div>
                <div>
                    <div style="color:#95a5a6;font-weight:700;font-size:0.85rem">Total Kategori</div>
                    <div style="font-weight:800;font-size:1.2rem">{{ count($categories) }}</div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            @if (count($categories) === 0)
                <div style="text-align:center;padding:40px;color:#95a5a6;">Belum ada kategori.</div>
            @else
                <table class="notas">
                    <thead>
                        <tr>
                            <th style="width:6%">No</th>
                            <th>Kategori</th>
                            <th style="width:14%;text-align:right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $idx => $cat)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>{{ $cat }}</td>
                                <td style="text-align:right">
                                    <div class="actions" style="position:relative;justify-content:flex-end;">
                                        <div class="action-menu">
                                            <button type="button" class="action-menu-button" aria-expanded="false"
                                                aria-haspopup="true"><i class="fas fa-ellipsis-v"></i></button>
                                            <ul class="action-menu-list" role="menu" style="right:0;left:auto;">
                                                <li role="none"><a role="menuitem"
                                                        href="{{ route('kategori.edit', $idx) }}"
                                                        class="action-menu-item"><i class="fas fa-eye"></i> View</a></li>
                                                <li role="none"><a role="menuitem"
                                                        href="{{ route('kategori.edit', $idx) }}"
                                                        class="action-menu-item"><i class="fas fa-edit"></i> Edit</a></li>
                                                <li role="none">
                                                    <form action="{{ route('kategori.destroy', $idx) }}" method="POST"
                                                        onsubmit="return confirm('Hapus kategori ini?');">
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
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </div>

    @push('styles')
        <style>
            /* Header / actions — same as Nota */
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

            /* Small stats cards */
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

            /* Buttons */
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

            /* Table — same visual as Nota */
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
@endsection

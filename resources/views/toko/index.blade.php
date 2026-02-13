@extends('layouts.app')

@section('title', 'Daftar Toko')

@push('styles')
    <style>
        /* Page header / actions (consistent with other index pages) */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 18px 0;
            gap: 12px;
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
            gap: 8px;
            align-items: center;
        }

        .btn {
            padding: 8px 14px;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all .12s;
            font-size: 0.95rem;
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

        /* Table — match visual style used across app */
        .table-responsive {
            background: transparent;
        }

        table.table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: visible;
            /* allow action-menu dropdowns to escape */
            box-shadow: 0 6px 20px rgba(2, 6, 23, 0.06);
        }

        table.table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 16px;
            font-weight: 800;
            text-align: left;
            font-size: 0.9rem;
        }

        table.table tbody td {
            padding: 12px 16px;
            vertical-align: middle;
            color: #2c3e50;
        }

        table.table tbody tr:hover {
            background: #fbfdff;
        }

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
            <h1><i class="fas fa-store"></i> Daftar Toko</h1>
            <div class="header-actions">
                <a href="{{ route('toko.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Toko</a>
            </div>
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                @if (session('status') == 'created')
                    <i class="fas fa-check-circle"></i> Toko berhasil ditambahkan!
                @elseif(session('status') == 'updated')
                    <i class="fas fa-check-circle"></i> Toko berhasil diperbarui!
                @elseif(session('status') == 'deleted')
                    <i class="fas fa-check-circle"></i> Toko berhasil dihapus!
                @endif
            </div>
        @endif

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width:6%">No</th>
                        <th>Nama Toko</th>
                        <th>Alamat</th>
                        <th style="width:14%;text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($toko_list as $index => $toko)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $toko->nama_toko }}</strong></td>
                            <td>{{ $toko->alamat ?? '-' }}</td>
                            <td style="text-align:right">
                                <div class="action-menu">
                                    <button type="button" class="action-menu-button">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="action-menu-list">
                                        <li>
                                            <a href="{{ route('toko.show', $toko->id) }}" class="action-menu-item">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('toko.edit', $toko->id) }}" class="action-menu-item">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('toko.destroy', $toko->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus toko ini?')"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-menu-item action-menu-item-danger">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center;padding:24px;color:#95a5a6">
                                <i class="fas fa-inbox"></i> Belum ada data toko
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

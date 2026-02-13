@extends('layouts.app')

@section('title', 'Detail Toko')

@push('styles')
    <style>
        .form-card {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(12, 33, 55, 0.06);
        }

        .detail-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #e6eef6;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            width: 200px;
            font-weight: 700;
            color: #667;
        }

        .detail-value {
            flex: 1;
            color: #2c3e50;
        }

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

        .btn-secondary {
            background: #95a5a6;
            color: #fff;
        }

        /* small table style for related notas */
        table.table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        table.table thead th {
            background: transparent;
            color: #667;
            padding: 10px 12px;
            font-weight: 700;
            text-align: left;
        }

        table.table tbody td {
            padding: 10px 12px;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-store"></i> Detail Toko</h1>
            <div class="header-actions">
                <a href="{{ route('toko.edit', $toko->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i>
                    Edit</a>
                <a href="{{ route('toko.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </div>

        <div class="form-card">
            <div class="detail-row">
                <div class="detail-label">Nama Toko:</div>
                <div class="detail-value"><strong>{{ $toko->nama_toko }}</strong></div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Alamat:</div>
                <div class="detail-value">{{ $toko->alamat ?? '-' }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Ditambahkan:</div>
                <div class="detail-value">{{ $toko->created_at->format('d M Y H:i') }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Terakhir Diubah:</div>
                <div class="detail-value">{{ $toko->updated_at->format('d M Y H:i') }}</div>
            </div>
        </div>

        <div class="form-card" style="margin-top:20px;">
            <h3>Nota Terkait</h3>
            @if ($toko->notas->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>No Nota</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($toko->notas as $nota)
                            <tr>
                                <td>{{ $nota->no }}</td>
                                <td>{{ $nota->tanggal ? $nota->tanggal->format('d M Y') : '-' }}</td>
                                <td>Rp {{ number_format($nota->total, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('nota.show', $nota->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="color:#95a5a6;text-align:center;padding:20px;">
                    <i class="fas fa-inbox"></i> Belum ada nota untuk toko ini
                </p>
            @endif
        </div>
    </div>

    @push('styles')
        <style>
            .detail-row {
                display: flex;
                padding: 12px 0;
                border-bottom: 1px solid #e6eef6;
            }

            .detail-row:last-child {
                border-bottom: none;
            }

            .detail-label {
                width: 200px;
                font-weight: 700;
                color: #667;
            }

            .detail-value {
                flex: 1;
                color: #2c3e50;
            }
        </style>
    @endpush
@endsection

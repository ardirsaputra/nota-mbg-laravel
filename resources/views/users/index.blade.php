@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
    <div class="container">

        <div class="page-header">
            <h1><i class="fas fa-users"></i> Manajemen Pengguna</h1>
            <div class="header-actions">
                <a href="{{ route('admin') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>

        <div class="nota-stats">
            <div class="nota-card">
                <div class="card-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%)">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-info">
                    <div class="card-value">{{ $users->count() }}</div>
                    <div class="card-label">Total Pengguna Terdaftar</div>
                </div>
            </div>
            <div class="nota-card">
                <div class="card-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%)">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div class="card-info">
                    <div class="card-value">{{ $users->sum('total_notas') }}</div>
                    <div class="card-label">Total Nota Pengguna</div>
                </div>
            </div>
            <div class="nota-card">
                <div class="card-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="card-info">
                    <div class="card-value">Rp {{ number_format($users->sum('total_value'), 0, ',', '.') }}</div>
                    <div class="card-label">Total Nilai Nota</div>
                </div>
            </div>
        </div>

        <div class="users-container">
            @forelse($users as $user)
                <div class="user-card">
                    <div class="user-header">
                        <div class="user-info">
                            <div class="user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="user-details">
                                <h3>{{ $user->name }}</h3>
                                <p><i class="fas fa-envelope"></i> {{ $user->email }}</p>
                                <p><i class="fas fa-calendar"></i> Terdaftar: {{ $user->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="user-stats">
                            <div class="stat-item">
                                <span class="stat-value">{{ $user->total_notas }}</span>
                                <span class="stat-label">Nota</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-value">Rp {{ number_format($user->total_value, 0, ',', '.') }}</span>
                                <span class="stat-label">Total Nilai</span>
                            </div>
                        </div>
                    </div>

                    @if ($user->notas->count() > 0)
                        <div class="user-notas">
                            <h4><i class="fas fa-file-invoice"></i> Daftar Nota</h4>
                            <table class="notas">
                                <thead>
                                    <tr>
                                        <th>No Nota</th>
                                        <th>Tanggal</th>
                                        <th>Toko</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->notas as $nota)
                                        <tr>
                                            <td><strong>{{ $nota->no }}</strong></td>
                                            <td>{{ $nota->tanggal->format('d/m/Y') }}</td>
                                            <td>{{ $nota->nama_toko ?? '-' }}</td>
                                            <td>Rp {{ number_format($nota->total, 0, ',', '.') }}</td>
                                            <td>
                                                @if ($nota->clones->count() > 0)
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check-circle"></i> Sudah di-clone
                                                    </span>
                                                @else
                                                    <span class="badge badge-warning">
                                                        <i class="fas fa-clock"></i> Belum di-clone
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="{{ route('nota.show', $nota->id) }}" class="btn-sm btn-view"
                                                        title="Lihat Nota">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if ($nota->clones->count() == 0)
                                                        <form action="{{ route('nota.clone', $nota->id) }}" method="POST"
                                                            style="display: inline;"
                                                            onsubmit="return confirm('Clone nota ini menjadi nota admin?')">
                                                            @csrf
                                                            <button type="submit" class="btn-sm btn-clone"
                                                                title="Clone ke Admin">
                                                                <i class="fas fa-copy"></i> Clone
                                                            </button>
                                                        </form>
                                                    @else
                                                        <a href="{{ route('nota.show', $nota->clones->first()->id) }}"
                                                            class="btn-sm btn-success" title="Lihat Nota Clone">
                                                            <i class="fas fa-check"></i> Lihat Clone
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="no-notas">
                            <i class="fas fa-inbox"></i>
                            <p>Belum ada nota dibuat oleh pengguna ini</p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-users-slash"></i>
                    <h3>Belum Ada Pengguna Terdaftar</h3>
                    <p>Belum ada pengguna umum yang mendaftar di sistem</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 30px 0 12px;
        }

        .page-header h1 {
            margin: 0;
            font-size: 28px;
            color: #2d3748;
        }

        .page-header h1 i {
            margin-right: 12px;
            color: #667eea;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #f7fafc;
            color: #4a5568;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-secondary:hover {
            background: #edf2f7;
            border-color: #cbd5e0;
        }

        .nota-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .nota-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            gap: 16px;
        }

        /* Table — same visual as Nota */
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
            color: #fff;
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

        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .card-info {
            flex: 1;
        }

        .card-value {
            font-size: 24px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 4px;
        }

        .card-label {
            font-size: 14px;
            color: #718096;
        }

        .users-container {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .user-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        /* Header for each user card — aligned with Nota style (light, non-gradient) */
        .user-header {
            padding: 16px 20px;
            background: #ffffff;
            color: #2d3748;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #edf2f7;
        }

        .user-info {
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .user-avatar {
            width: 56px;
            height: 56px;
            border-radius: 10px;
            background: #f1f5ff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #334155;
        }

        .user-details h3 {
            margin: 0 0 6px 0;
            font-size: 16px;
            color: #2d3748;
        }

        .user-details p {
            margin: 2px 0;
            opacity: 0.85;
            font-size: 13px;
            color: #718096;
        }

        .user-details p i {
            margin-right: 6px;
            opacity: 0.7;
        }

        .user-stats {
            display: flex;
            gap: 32px;
            text-align: center;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 12px;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .user-notas {
            padding: 24px;
        }

        .user-notas h4 {
            margin: 0 0 16px 0;
            color: #2d3748;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* table.notas styles are defined globally (same as Nota). Keep alias for backward compatibility */
        .notas-table {
            width: 100%;
            border-collapse: collapse;
        }

        .notas-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .notas-table th,
        .notas-table td {
            padding: 12px 16px;
        }

        /* Prefer using `table.notas` for full Nota styling */

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-sm {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-view {
            background: #4299e1;
            color: white;
        }

        .btn-view:hover {
            background: #3182ce;
        }

        .btn-clone {
            background: #667eea;
            color: white;
        }

        .btn-clone:hover {
            background: #5a67d8;
        }

        .btn-success {
            background: #48bb78;
            color: white;
        }

        .btn-success:hover {
            background: #38a169;
        }

        .no-notas {
            padding: 40px;
            text-align: center;
            color: #a0aec0;
        }

        .no-notas i {
            font-size: 48px;
            margin-bottom: 12px;
            opacity: 0.5;
        }

        .no-notas p {
            margin: 0;
            font-size: 14px;
        }

        .empty-state {
            padding: 80px 20px;
            text-align: center;
            color: #a0aec0;
        }

        .empty-state i {
            font-size: 64px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .empty-state h3 {
            margin: 0 0 8px 0;
            color: #718096;
        }

        .empty-state p {
            margin: 0;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .user-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .user-stats {
                width: 100%;
                justify-content: space-around;
            }

            .action-buttons {
                flex-direction: column;
            }

            .notas-table {
                font-size: 12px;
            }

            .notas-table th,
            .notas-table td {
                padding: 8px;
            }
        }
    </style>
@endpush

@extends('layouts.app')

@section('title', 'Daftar Harga Barang Pokok')

@push('styles')
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 30px 0;
            flex-wrap: wrap;
            gap: 20px;
        }

        .page-header h1 {
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-header h1 i {
            color: #2ecc71;
            font-size: 2rem;
        }

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
            font-size: 0.98rem;
        }

        .search-form input {
            flex: 1;
            min-width: 200px;
        }

        .search-form select {
            min-width: 150px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .btn-success {
            background: #2ecc71;
            color: white;
        }

        .btn-success:hover {
            background: #27ae60;
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        .btn-warning {
            background: #f39c12;
            color: white;
        }

        table {
            width: 100%;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
        }

        tbody tr:hover {
            background: #f8f9fa;
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .badge {
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .badge-primary {
            background: #3498db;
            color: white;
        }

        .last-update {
            text-align: center;
            margin-top: 20px;
            color: #7f8c8d;
            font-size: 0.9rem;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="page-header">
            <h1>
                <i class="fas fa-dollar-sign"></i>
                Harga Barang Pokok
            </h1>
            <a href="{{ route('harga-barang-pokok.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Tambah Barang
            </a>
        </div>

        <!-- Search Form -->
        <div class="search-box">
            <form action="{{ route('harga-barang-pokok.index') }}" method="GET" class="search-form">
                <i class="fas fa-search" style="color: #7f8c8d;"></i>
                <input type="text" name="search" placeholder="Cari barang..." value="{{ $search }}">
                <select name="kategori">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategori_list as $kat)
                        <option value="{{ $kat }}" {{ $kategori == $kat ? 'selected' : '' }}>{{ $kat }}
                        </option>
                    @endforeach
                </select>
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

        <!-- Table -->
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Uraian</th>
                    <th>Kategori</th>
                    <th>Satuan</th>
                    <th>Nilai Satuan</th>
                    <th>Harga Satuan</th>
                    <th>Harga per Kg</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barang_pokok as $index => $barang)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $barang->uraian }}</td>
                        <td><span class="badge badge-primary">{{ $barang->kategori }}</span></td>
                        <td>{{ $barang->satuan }}</td>
                        <td>{{ $barang->nilai_satuan }}</td>
                        <td>Rp {{ number_format($barang->harga_satuan, 0, ',', '.') }}</td>
                        <td>
                            @if ($barang->satuan == 'Kg')
                                Rp {{ number_format($barang->harga_satuan, 0, ',', '.') }}
                            @else
                                Rp {{ number_format($barang->harga_satuan / $barang->nilai_satuan, 0, ',', '.') }}
                            @endif
                        </td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('harga-barang-pokok.edit', $barang->id) }}" class="btn btn-warning"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('harga-barang-pokok.destroy', $barang->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px;">
                            <i class="fas fa-inbox"
                                style="font-size: 3rem; color: #ddd; margin-bottom: 10px; display: block;"></i>
                            Tidak ada data
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($last_update)
            <div class="last-update">
                Terakhir diupdate: {{ $last_update->format('d M Y H:i') }}
            </div>
        @endif
    </div>
@endsection

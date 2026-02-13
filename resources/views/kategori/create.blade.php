@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
    <div class="container" style="max-width:640px;">
        <h1><i class="fas fa-plus" style="color:#2ecc71;margin-right:8px;"></i> Tambah Kategori</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin:0;padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('kategori.store') }}" method="POST"
            style="background:#fff;padding:16px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.04);">
            @csrf
            <div style="display:flex;flex-direction:column;gap:8px;">
                <label>Nama Kategori</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    style="padding:10px;border:1px solid #e6eef6;border-radius:6px;">
                <div style="display:flex;gap:8px;margin-top:8px;">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Simpan</button>
                    <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </div>
        </form>
    </div>
@endsection

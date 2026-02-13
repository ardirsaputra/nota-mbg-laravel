<form id="ajax-edit-form">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="uraian">Uraian / Nama Barang *</label>
        <input type="text" id="uraian" name="uraian" value="{{ old('uraian', $barang->uraian) }}" required>
    </div>

    <div class="form-group">
        <label for="kategori">Kategori *</label>
        <select id="kategori" name="kategori" required>
            <option value="">Pilih Kategori</option>
            @foreach ($kategori_list as $kat)
                <option value="{{ $kat }}" {{ old('kategori', $barang->kategori) == $kat ? 'selected' : '' }}>
                    {{ $kat }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="satuan">Satuan *</label>
        <select id="satuan" name="satuan" required>
            <option value="">Pilih Satuan</option>
            @foreach (['Kg', 'Gram', 'Liter', 'Ml', 'Pcs', 'Pack', 'Dus', 'Karung', 'Ball'] as $s)
                <option value="{{ $s }}" {{ old('satuan', $barang->satuan) == $s ? 'selected' : '' }}>
                    {{ $s }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="nilai_satuan">Nilai Satuan (untuk konversi ke Kg) *</label>
        <input type="number" step="0.01" id="nilai_satuan" name="nilai_satuan"
            value="{{ old('nilai_satuan', $barang->nilai_satuan) }}" required>
        <small style="color: #7f8c8d;">Contoh: 1 Kg = 1, 500 Gram = 0.5</small>
    </div>

    <div class="form-group">
        <label for="harga_satuan">Harga Satuan (Rp) *</label>
        <input type="number" id="harga_satuan" name="harga_satuan"
            value="{{ old('harga_satuan', $barang->harga_satuan) }}" required>
    </div>

    <div class="form-group">
        <label for="profit_per_unit">Keuntungan per Satuan (Rp)</label>
        <input type="number" id="profit_per_unit" name="profit_per_unit"
            value="{{ old('profit_per_unit', $barang->profit_per_unit ?? 0) }}" min="0">
        <small style="color:#7f8c8d;">Opsional — diisi jika ingin menetapkan keuntungan per unit untuk
            perhitungan.</small>
    </div>

    <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:12px">
        <button type="submit" class="btn btn-success btn-submit"><i class="fas fa-save"></i> Simpan</button>
        <button id="ajax-cancel" class="btn btn-secondary btn-cancel">Batal</button>
    </div>
</form>

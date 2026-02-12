<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HargaBarangPokok extends Model
{
    protected $table = 'harga_barang_pokok';

    protected $fillable = [
        'uraian',
        'kategori',
        'satuan',
        'nilai_satuan',
        'harga_satuan',
    ];

    protected $casts = [
        'nilai_satuan' => 'float',
        'harga_satuan' => 'integer',
    ];
}

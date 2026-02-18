<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HargaBarangPokok extends Model
{
    protected $table = 'harga_barang_pokok';

    protected $fillable = [
        'user_id',
        'uraian',
        'kategori',
        'satuan',
        'nilai_satuan',
        'harga_satuan',
        'profit_per_unit',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'nilai_satuan' => 'float',
        'harga_satuan' => 'integer',
        'profit_per_unit' => 'integer',
    ];
}

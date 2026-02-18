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

    /**
     * Scope a query to the given user id. If the `user_id` column does not exist
     * (older DB schema), the query is returned unchanged.
     *
     * Usage:
     * - HargaBarangPokok::forUser(null) => whereNull('user_id') (admin/global)
     * - HargaBarangPokok::forUser($id) => where('user_id', $id)
     */
    public function scopeForUser($query, $userId)
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasColumn($this->getTable(), 'user_id')) {
                return $query;
            }
        } catch (\Throwable $e) {
            return $query;
        }

        if ($userId === null) {
            return $query->whereNull('user_id');
        }

        return $query->where('user_id', $userId);
    }
}

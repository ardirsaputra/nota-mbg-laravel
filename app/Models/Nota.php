<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    protected $table = 'nota';

    protected $fillable = [
        'no',
        'tanggal',
        'toko_id',
        'nama_toko_manual',
        'alamat_toko_manual',
        'nama_toko',
        'alamat',
        'total',
        'is_locked',
        'profit_insight',
        'user_id',
        'is_admin_nota',
        'cloned_from_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total' => 'integer',
        'is_locked' => 'boolean',
        'profit_insight' => 'boolean',
        'is_admin_nota' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clonedFrom()
    {
        return $this->belongsTo(Nota::class, 'cloned_from_id');
    }

    public function clones()
    {
        return $this->hasMany(Nota::class, 'cloned_from_id');
    }

    public function items()
    {
        return $this->hasMany(NotaItem::class, 'nota_id');
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'toko_id');
    }

    /**
     * Calculate total from items
     */
    public function calculateTotal()
    {
        $this->total = $this->items()->sum('subtotal');
        $this->save();
        return $this->total;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    protected $table = 'nota';

    protected $fillable = [
        'no',
        'tanggal',
        'nama_toko',
        'alamat',
        'total',
        'is_locked',
        'profit_insight',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total' => 'integer',
        'is_locked' => 'boolean',
        'profit_insight' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(NotaItem::class, 'nota_id');
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

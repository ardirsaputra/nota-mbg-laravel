<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaItem extends Model
{
    protected $table = 'nota_items';

    protected $fillable = [
        'nota_id',
        'uraian',
        'satuan',
        'qty',
        'harga_satuan',
        'subtotal',
    ];

    protected $casts = [
        'qty' => 'integer',
        'harga_satuan' => 'integer',
        'subtotal' => 'integer',
    ];

    public function nota()
    {
        return $this->belongsTo(Nota::class, 'nota_id');
    }

    /**
     * Calculate subtotal automatically
     */
    public function calculateSubtotal()
    {
        $this->subtotal = $this->qty * $this->harga_satuan;
        return $this->subtotal;
    }

    /**
     * Boot function to auto-calculate subtotal
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->subtotal = $item->qty * $item->harga_satuan;
        });
    }
}

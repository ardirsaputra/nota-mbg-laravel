<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaItem extends Model
{
    protected $table = 'nota_items';

    /**
     * Dynamically disable timestamps when the table doesn't have created_at/updated_at
     * (shared/old hosts may have an older schema). This keeps the model compatible
     * with databases that haven't run all migrations.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        try {
            if (!\Illuminate\Support\Facades\Schema::hasColumn($this->getTable(), 'created_at') ||
                !\Illuminate\Support\Facades\Schema::hasColumn($this->getTable(), 'updated_at')) {
                $this->timestamps = false;
            }
        } catch (\Throwable $e) {
            // If Schema check fails for any reason, be conservative and disable timestamps
            $this->timestamps = false;
        }
    }

    protected $fillable = [
        'nota_id',
        'uraian',
        'satuan',
        'qty',
        'harga_satuan',
        'subtotal',
        'profit_per_unit',
    ];

    protected $casts = [
        'qty' => 'integer',
        'harga_satuan' => 'integer',
        'subtotal' => 'integer',
        'profit_per_unit' => 'integer',
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

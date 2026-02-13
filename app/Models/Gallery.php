<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = ['title', 'image_path', 'order'];

    protected $casts = [
        'order' => 'integer',
    ];

    // Scope untuk urutan
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}

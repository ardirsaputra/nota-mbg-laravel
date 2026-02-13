<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    protected $table = 'toko';

    protected $fillable = [
        'nama_toko',
        'alamat',
        'user_id',
    ];

    public function notas()
    {
        return $this->hasMany(Nota::class, 'toko_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}

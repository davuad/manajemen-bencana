<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriBencana extends Model
{
    protected $table = 'kategori_bencana';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    public function wargaTerdampak(): HasMany
    {
        return $this->hasMany(WargaTerdampak::class, 'kategori_id', 'id');
    }
}

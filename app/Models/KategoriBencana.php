<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriBencana extends Model
{
    use HasFactory;

    protected $table = 'kategori_bencana';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    public function wargaTerdampak(): HasMany
    {
        return $this->hasMany(WargaTerdampak::class, 'kategori_id', 'id');
    }

        public function bencana()
    {
        return $this->hasMany(Bencana::class, 'kategori_id');
    }
}
    // Relasi ke tabel bencana (1 kategori punya banyak bencana)
    // public function bencana()
    // {
    //     return $this->hasMany(Bencana::class, 'kategori_id');
    // }


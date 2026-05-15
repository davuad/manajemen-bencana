<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBencana extends Model
{
    use HasFactory;

    protected $table = 'kategori_bencana';

    protected $fillable = [
        'nama_kategori',
        'deskripsi'
    ];

    // Relasi ke tabel bencana (1 kategori punya banyak bencana)
    // public function bencana()
    // {
    //     return $this->hasMany(Bencana::class, 'kategori_id');
    // }
}
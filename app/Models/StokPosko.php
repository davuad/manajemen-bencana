<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokPosko extends Model
{
    use HasFactory;

    protected $table = 'stok_posko';

    protected $fillable = [
        'posko_id',
        'barang_id',
        'kategori_distribusi',
        'jumlah_barang'
    ];

    
    // Relasi ke Posko
    public function posko()
    {
        return $this->belongsTo(Posko::class, 'posko_id');
    }

    // Relasi ke Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id_barang');
    }
}

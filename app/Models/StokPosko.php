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
        'jumlah_barang',
        'satuan',
        'tanggal_masuk',
        'keterangan'
    ];

    public function posko()
    {
        return $this->belongsTo(Posko::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
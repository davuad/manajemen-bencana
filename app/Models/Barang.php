<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'nama_barang',
        'id_jenis_barang',
        'stok',
        'satuan',
        'keterangan'
    ];

    /**
     * Relasi ke tabel pengambilan
     * Satu barang bisa banyak transaksi pengambilan
     */
    public function pengambilan()
    {
        return $this->hasMany(Pengambilan::class, 'barang_id');
    }

    /**
     * Relasi ke tabel stok_posko
     */
    public function stokPosko()
    {
        return $this->hasMany(StokPosko::class, 'barang_id');
    }
}
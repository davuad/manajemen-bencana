<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';

    protected $primaryKey = 'id_barang';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_barang',
        'nama_barang',
        'id_jenis_barang',
        'stok',
        'satuan',
        'keterangan'
    ];

    public function stokGudang()
    {
        return $this->hasMany(StokGudang::class, 'barang_id', 'id_barang');
    }
}
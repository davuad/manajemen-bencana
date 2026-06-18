<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BarangMasuk;
use App\Models\Barang;

class DetailBarangMasuk extends Model
{
    protected $table = 'detail_barang_masuk';
    protected $primaryKey = 'id_detail_barang_masuk';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_detail_barang_masuk',
        'id_barang_masuk',
        'id_barang',
        'jumlah',
        'satuan',
        'kondisi_barang'
    ];

    public function barangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class, 'id_barang_masuk');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
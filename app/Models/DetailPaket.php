<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPaket extends Model
{
    protected $table = 'detail_paket';

    protected $fillable = [
        'paket_bantuan_id',
        'barang_id',
        'jumlah'
    ];

    public function paketBantuan()
    {
        return $this->belongsTo(PaketBantuan::class, 'paket_bantuan_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id_barang');
    }
}

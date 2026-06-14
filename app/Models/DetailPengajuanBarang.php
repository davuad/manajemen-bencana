<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PengajuanBarang;
use App\Models\Barang;

class DetailPengajuanBarang extends Model
{
    protected $table = 'detail_pengajuan_barang';

    protected $fillable = [
        'pengajuan_barang_id',
        'barang_id',
        'kategori_penerima',
        'jumlah',
    ];

    /**
     * Relasi ke pengajuan barang
     */
    public function pengajuanBarang()
    {
        return $this->belongsTo(PengajuanBarang::class);
    }

    /**
     * Relasi ke barang
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}

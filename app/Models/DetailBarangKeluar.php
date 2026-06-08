<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BarangKeluar;
use App\Models\Barang;

class DetailBarangKeluar extends Model
{
    protected $table = 'detail_barang_keluar';

    protected $fillable = [
        'barang_keluar_id',
        'barang_id',
        'jumlah',
        'jumlah_keluar',
        'catatan'
    ];

    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function pengajuanDetail()
    {
        return $this->belongsTo(DetailPengajuanBarang::class, 'barang_id', 'barang_id')
                    ->where('pengajuan_barang_id', $this->barangKeluar->pengajuan_barang_id);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailDistribusi extends Model
{
    protected $table = 'detail_distribusi';

    protected $fillable = [
        'distribusi_id',
        'barang_keluar_id',
        'jumlah_kirim',
        'satuan',
    ];

    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class, 'barang_keluar_id');
    }

    public function distribusi()
    {
        return $this->belongsTo(Distribusi::class);
    }
}
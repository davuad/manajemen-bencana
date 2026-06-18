<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailDistribusi extends Model
{
    protected $table = 'detail_distribusi';

    protected $fillable = [
        'distribusi_id',
        'detail_barang_keluar_id',
        'jumlah_kirim',
        'satuan',
    ];

    // Relasi ke Distribusi
    public function distribusi()
    {
        return $this->belongsTo(Distribusi::class, 'distribusi_id');
    }

    // Relasi ke Detail Barang Keluar
    public function detailBarangKeluar()
    {
        return $this->belongsTo(
            DetailBarangKeluar::class,
            'detail_barang_keluar_id'
        )->withDefault();
    }

    // Relasi ke Penerima Distribusi
    public function penerimaDistribusi()
    {
        return $this->hasMany(
            PenerimaDistribusi::class,
            'detail_distribusi_id'
        );
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Gudang;
use App\Models\Pegawai;
use App\Models\SumberBarangMasuk;
use App\Models\DetailBarangMasuk;

class BarangMasuk extends Model
{
    protected $table = 'barang_masuk';
    protected $primaryKey = 'id_barang_masuk';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_barang_masuk',
        'tgl_masuk',
        'id_sumber',
        'id_gudang',
        'id_pegawai',
        'status',
        'no_dokumen',
        'keterangan'
    ];

    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'id_gudang');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }

    public function sumber()
    {
        return $this->belongsTo(SumberBarangMasuk::class, 'id_sumber');
    }

    public function detail()
    {
    return $this->hasMany(DetailBarangMasuk::class, 'id_barang_masuk');
    }
}
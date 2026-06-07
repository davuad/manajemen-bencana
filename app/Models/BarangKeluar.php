<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Gudang;
use App\Models\PengajuanBarang;
use App\Models\Pegawai;

class BarangKeluar extends Model
{
    protected $table = 'barang_keluar';

    protected $fillable = [
        'gudang_id',
        'pengajuan_barang_id',
        'petugas_gudang_id',
        'updated_by',
        'tgl_keluar',
        'status_proses',
        'catatan'
    ];

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    public function pengajuanBarang()
    {
        return $this->belongsTo(PengajuanBarang::class);
    }

    public function petugasGudang()
    {
        return $this->belongsTo(Pegawai::class, 'petugas_gudang_id');
    }

    public function detailBarangKeluar()
    {
        return $this->hasMany(DetailBarangKeluar::class, 'barang_keluar_id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

}

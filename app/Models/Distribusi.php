<?php

namespace App\Models;

// ⬇️ TAMBAHKAN DI SINI
use Illuminate\Database\Eloquent\Model;
use App\Models\BarangKeluar;
use App\Models\Bencana;
use App\Models\Posko;
use App\Models\DetailDistribusi;

class Distribusi extends Model
{
    protected $table = 'distribusi';

    protected $fillable = [
    'barang_keluar_id',
    'bencana_id',
    'posko_id',
    'tanggal_distribusi',
    'lokasi_distribusi',
    'kendaraan',
    'nama_supir',
    'nomor_kendaraan',
    'keterangan',
    'kategori_distribusi',
    'status',
];

    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class);
    }

    public function bencana()
    {
        return $this->belongsTo(Bencana::class);
    }

    public function posko()
    {
        return $this->belongsTo(Posko::class);
    }

    public function detailDistribusis()
    {
        return $this->hasMany(DetailDistribusi::class);
    }
    
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posko extends Model
{
    protected $table = 'posko';

    protected $fillable = [
        'nama_posko',
        'tanggal_dibuat',
        'desa_id',
        'pengaduan_bencana_id',
        'lokasi',
        'status'
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }

    public function pengaduan()
    {
        return $this->belongsTo(PengaduanBencana::class, 'pengaduan_bencana_id');
    }

    public function petugas()
    {
        return $this->hasMany(Petugas::class, 'posko_id');
    }

    public function pengambilan()
    {
        return $this->hasMany(Pengambilan::class, 'posko_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $table = 'pengembalian';

    protected $fillable = [
        'pengambilan_id',
        'petugas_id',
        'posko_id',
        'tanggal_pengembalian',
        'jumlah_kembali',
        'keterangan',
        'status',
    ];

    public function pengambilan()
    {
        return $this->belongsTo(Pengambilan::class, 'pengambilan_id');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'petugas_id');
    }

    public function posko()
    {
        return $this->belongsTo(Posko::class, 'posko_id');
    }
}
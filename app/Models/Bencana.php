<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bencana extends Model
{
    protected $table = 'bencana';

    protected $fillable = [
        'kategori_id',
        'pengaduan_bencana_id',
        'desa_id',
        'tanggal',
        'jumlah_korban',
        'tingkat_kerusakan'
    ];

    // RELASI
    public function pengaduan()
    {
        return $this->belongsTo(PengaduanBencana::class, 'pengaduan_bencana_id');
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function distribusis()
    {
        return $this->hasMany(Distribusi::class, 'bencana_id');
    }
}